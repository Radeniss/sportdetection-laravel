from google.colab import drive
drive.mount('/content/drive')

!pip -q install ultralytics opencv-python numpy

from ultralytics import YOLO
import cv2
import numpy as np
import os
import platform
import torch

print(
    "PyTorch:", torch.__version__,
    "| CUDA:", torch.cuda.is_available(),
    "| Python:", platform.python_version()
)

from google.colab import files

uploaded = files.upload()
assert len(uploaded) > 0, "Tidak ada file yang di-upload."
in_path = list(uploaded.keys())[0]
print("Video input:", in_path)

model = YOLO('yolov8n-pose.pt')

# Indeks keypoint (COCO):
# 0 nose, 1 l_eye, 2 r_eye, 3 l_ear, 4 r_ear,
# 5 l_shoulder, 6 r_shoulder, 7 l_elbow, 8 r_elbow,
# 9 l_wrist, 10 r_wrist, 11 l_hip, 12 r_hip,
# 13 l_knee, 14 r_knee, 15 l_ankle, 16 r_ankle

def angle(a, b, c):

    a, b, c = np.array(a), np.array(b), np.array(c)
    ba, bc = a - b, c - b
    cosang = np.dot(ba, bc) / (np.linalg.norm(ba) * np.linalg.norm(bc) + 1e-6)
    cosang = np.clip(cosang, -1.0, 1.0)
    return float(np.degrees(np.arccos(cosang)))

def euclid(p1, p2):
    p1, p2 = np.array(p1), np.array(p2)
    return float(np.linalg.norm(p1 - p2))

def get_kpt_xy(kpts, idx):
    return (float(kpts[idx, 0]), float(kpts[idx, 1]))

def is_visible(kpts, idx, thr=0.25):
    return float(kpts[idx, 2]) >= thr

class RepetitionCounter:
    def __init__(self):
        # Push-up
        self.pushup_cnt = 0
        self.pushup_state = "up"
        # Squat
        self.squat_cnt = 0
        self.squat_state = "top"
        # Jumping Jack
        self.jj_cnt = 0
        self.jj_state = "closed"

    def update_pushup(self, k):
        needed = [5,6,7,8,9,10,11,12,15,16]
        if not all(is_visible(k, i) for i in needed):
            return

        # Sudut siku kiri/kanan → rata-rata
        L = angle(get_kpt_xy(k, 5), get_kpt_xy(k, 7), get_kpt_xy(k, 9))
        R = angle(get_kpt_xy(k, 6), get_kpt_xy(k, 8), get_kpt_xy(k, 10))
        elbow = (L + R) / 2.0

        # Plank check: bahu-hip-ankle relatif lurus
        hipL = angle(get_kpt_xy(k, 5), get_kpt_xy(k, 11), get_kpt_xy(k, 15))
        hipR = angle(get_kpt_xy(k, 6), get_kpt_xy(k, 12), get_kpt_xy(k, 16))
        plank = (hipL > 155 and hipR > 155)

        # Threshold bisa dikalibrasi
        down_thr, up_thr = 90, 150

        if plank:
            if self.pushup_state == "up" and elbow < down_thr:
                self.pushup_state = "down"
            elif self.pushup_state == "down" and elbow > up_thr:
                self.pushup_cnt += 1
                self.pushup_state = "up"

    def update_squat(self, k):
        needed = [11,12,13,14,15,16]
        if not all(is_visible(k, i) for i in needed):
            return

        L = angle(get_kpt_xy(k, 11), get_kpt_xy(k, 13), get_kpt_xy(k, 15))
        R = angle(get_kpt_xy(k, 12), get_kpt_xy(k, 14), get_kpt_xy(k, 16))
        knee = (L + R) / 2.0

        # pinggul turun cukup dalam (di bawah lutut)
        hip_y  = (get_kpt_xy(k, 11)[1] + get_kpt_xy(k, 12)[1]) / 2
        knee_y = (get_kpt_xy(k, 13)[1] + get_kpt_xy(k, 14)[1]) / 2
        depth_ok = hip_y > knee_y - 5  # toleransi kecil

        bottom_thr, top_thr = 90, 160
        if self.squat_state == "top" and knee < bottom_thr and depth_ok:
            self.squat_state = "bottom"
        elif self.squat_state == "bottom" and knee > top_thr:
            self.squat_cnt += 1
            self.squat_state = "top"

    def update_jj(self, k, frame_w):
        needed = [5,6,9,10,15,16,0]
        if not all(is_visible(k, i) for i in needed):
            return

        # Tangan di atas kepala
        wristY = (get_kpt_xy(k, 9)[1] + get_kpt_xy(k, 10)[1]) / 2
        headY  = get_kpt_xy(k, 0)[1]
        hands_up = wristY < headY

        # Kaki lebar
        ankles_dist = euclid(get_kpt_xy(k, 15), get_kpt_xy(k, 16))
        feet_apart = ankles_dist > 0.25 * frame_w 

        if self.jj_state == "closed" and hands_up and feet_apart:
            self.jj_state = "open"
        elif self.jj_state == "open" and (not hands_up or not feet_apart):
            self.jj_cnt += 1
            self.jj_state = "closed"

def draw_text(img, text, x, y, scale=0.9, thickness=2):
    # Outline gelap + tulisan putih agar terbaca jelas
    cv2.putText(img, text, (x, y), cv2.FONT_HERSHEY_SIMPLEX,
                scale, (0, 0, 0), thickness + 3, cv2.LINE_AA)
    cv2.putText(img, text, (x, y), cv2.FONT_HERSHEY_SIMPLEX,
                scale, (255, 255, 255), thickness, cv2.LINE_AA)

def process_video(in_path, out_path="output.mp4", img_size=640, conf_thr=0.25):
    cap = cv2.VideoCapture(in_path)
    assert cap.isOpened(), f"Gagal membuka video: {in_path}"

    # FPS fallback → 25 jika tidak terbaca
    fps = cap.get(cv2.CAP_PROP_FPS)
    fps = fps if fps and fps > 1 else 25

    W = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
    H = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))

    fourcc = cv2.VideoWriter_fourcc(*'mp4v')
    writer = cv2.VideoWriter(out_path, fourcc, fps, (W, H))

    counter = RepetitionCounter()

    while True:
        ok, frame = cap.read()
        if not ok:
            break

        res = model.predict(source=frame, imgsz=img_size, conf=conf_thr, verbose=False)[0]

        if len(res.keypoints) > 0:
            scores = res.boxes.conf.cpu().numpy() if res.boxes is not None else np.array([1.0]*len(res.keypoints))
            best_i = int(np.argmax(scores))

            kpts = res.keypoints[best_i].data[0].cpu().numpy().reshape(-1, 3)

            # Update penghitung gerakan
            counter.update_pushup(kpts)
            counter.update_squat(kpts)
            counter.update_jj(kpts, frame_w=W)

            # Render skeleton ke frame
            frame = res.plot()

        # Overlay teks hasil
        draw_text(frame, f"Push-up: {counter.pushup_cnt}", 20, 40)
        draw_text(frame, f"Squat  : {counter.squat_cnt}", 20, 80)
        draw_text(frame, f"J. Jack: {counter.jj_cnt}",   20, 120)

        writer.write(frame)

    cap.release()
    writer.release()
    print("Selesai:", out_path)
    return out_path

out_path = process_video(in_path, out_path="output.mp4", img_size=640, conf_thr=0.25)

from google.colab import files
files.download('output.mp4')