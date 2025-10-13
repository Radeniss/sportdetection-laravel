!pip -q install ultralytics opencv-python numpy gradio==4.44.0

from ultralytics import YOLO
import cv2, numpy as np, time, platform, torch, gradio as gr

print("PyTorch:", torch.__version__,
      "| CUDA:", torch.cuda.is_available(),
      "| Python:", platform.python_version())

# 4) Helper (geometri & overlay)
# COCO keypoints index:
# 0 nose, 1 l_eye, 2 r_eye, 3 l_ear, 4 r_ear,
# 5 l_shoulder, 6 r_shoulder, 7 l_elbow, 8 r_elbow,
# 9 l_wrist, 10 r_wrist, 11 l_hip, 12 r_hip,
# 13 l_knee, 14 r_knee, 15 l_ankle, 16 r_ankle

def angle(a, b, c):
    a, b, c = np.array(a), np.array(b), np.array(c)
    ba, bc = a - b, c - b
    cosang = np.dot(ba, bc) / (np.linalg.norm(ba)*np.linalg.norm(bc) + 1e-6)
    cosang = np.clip(cosang, -1.0, 1.0)
    return float(np.degrees(np.arccos(cosang)))

def euclid(p1, p2):
    p1, p2 = np.array(p1), np.array(p2)
    return float(np.linalg.norm(p1 - p2))

def get_kpt_xy(k, i):
    return (float(k[i, 0]), float(k[i, 1]))

def is_visible(k, i, thr=0.25):
    return float(k[i, 2]) >= thr

def draw_text(img, text, x, y, scale=0.9, thickness=2):
    cv2.putText(img, text, (x, y), cv2.FONT_HERSHEY_SIMPLEX, scale, (0, 0, 0), thickness+3, cv2.LINE_AA)
    cv2.putText(img, text, (x, y), cv2.FONT_HERSHEY_SIMPLEX, scale, (255, 255, 255), thickness, cv2.LINE_AA)

def draw_progress_bar(img, p, H, x=20, y=None, w=220, h=16):
    if y is None:
        y = H - 40
    cv2.rectangle(img, (x, y), (x+w, y+h), (255, 255, 255), 2)
    cv2.rectangle(img, (x, y), (x+int(w*p), y+h), (255, 255, 255), -1)

def norm_progress(elbow, down_thr=95, up_thr=155):
    if elbow is None:
        return 0.0
    x = (up_thr - elbow) / (up_thr - down_thr + 1e-6)
    return float(np.clip(x, 0.0, 1.0))

# 5) Smoothing & Counter
class KptSmoother:
    def __init__(self, alpha=0.25):
        self.alpha = alpha
        self.buf = None
    def step(self, k):
        if self.buf is None:
            self.buf = k.copy()
        self.buf = self.alpha * k + (1 - self.alpha) * self.buf
        return self.buf

class RepetitionCounter:
    def __init__(self, min_down_ms=120, min_interval_ms=500):
        self.min_down_ms = min_down_ms
        self.min_interval_ms = min_interval_ms
        self.reset()

    def reset(self):
        self.pushup_cnt = 0
        self.pushup_state = "up"
        self.last_rep_time = 0.0
        self.down_enter_time = None

    def update_pushup(self, k):
        need = [5, 6, 7, 8, 9, 10, 11, 12, 15, 16]
        if not all(is_visible(k, i) for i in need):
            return False, None

        L = angle(get_kpt_xy(k, 5), get_kpt_xy(k, 7), get_kpt_xy(k, 9))
        R = angle(get_kpt_xy(k, 6), get_kpt_xy(k, 8), get_kpt_xy(k, 10))
        elbow = (L + R) / 2.0

        hipL = angle(get_kpt_xy(k, 5), get_kpt_xy(k, 11), get_kpt_xy(k, 15))
        hipR = angle(get_kpt_xy(k, 6), get_kpt_xy(k, 12), get_kpt_xy(k, 16))
        plank = (hipL > 155 and hipR > 155)

        t = time.time() * 1000.0
        down_thr, up_thr = 95, 155
        rep = False

        if plank:
            if self.pushup_state == "up" and elbow < down_thr:
                self.pushup_state = "down"
                self.down_enter_time = t
            elif self.pushup_state == "down" and elbow > up_thr:
                if self.down_enter_time and (t - self.down_enter_time) >= self.min_down_ms:
                    if (t - self.last_rep_time) >= self.min_interval_ms:
                        self.pushup_cnt += 1
                        self.last_rep_time = t
                        rep = True
                self.pushup_state = "up"
                self.down_enter_time = None

        return rep, elbow

# 6) Load model & state
model = YOLO('yolov8n-pose.pt')
counter = RepetitionCounter(min_down_ms=120, min_interval_ms=500)
smoother = KptSmoother(alpha=0.25)

# 7) Inference function (Gradio webcam)
def infer(rgb_frame):
    # Guard kamera
    if rgb_frame is None:
        return None

    bgr = cv2.cvtColor(rgb_frame, cv2.COLOR_RGB2BGR)
    H, W = bgr.shape[:2]

    res = model.predict(source=bgr, imgsz=640, conf=0.25, verbose=False)[0]
    elbow = None
    out = bgr

    if len(res.keypoints) > 0:
        scores = res.boxes.conf.cpu().numpy() if res.boxes is not None else np.array([1.0]*len(res.keypoints))
        best_i = int(np.argmax(scores))
        kpts = res.keypoints[best_i].data[0].cpu().numpy().reshape(-1, 3)

        kpts = smoother.step(kpts)
        rep_added, elbow = counter.update_pushup(kpts)

        out = res.plot()

    # overlay info
    draw_text(out, f"Push-up: {counter.pushup_cnt}", 20, 40)
    p = norm_progress(elbow)
    draw_progress_bar(out, p, H)

    return cv2.cvtColor(out, cv2.COLOR_BGR2RGB)

def reset_counter():
    counter.reset()
    return gr.update(value="Counter di-reset ✅")

# 8) UI Gradio
with gr.Blocks() as demo:
    gr.Markdown("## Realtime Pose Counter (Push-up) — YOLOv8-Pose + Smoothing (Colab)")
    with gr.Row():
        cam = gr.Image(sources=["webcam"], streaming=True, label="Webcam")
        out = gr.Image(label="Output", interactive=False)
    with gr.Row():
        reset_btn = gr.Button("Reset Counter")
        status = gr.Markdown("")
    cam.stream(infer, inputs=cam, outputs=out)
    reset_btn.click(fn=reset_counter, outputs=status)

demo.queue().launch(share=True).