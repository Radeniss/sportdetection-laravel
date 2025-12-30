<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaPipe Pose Explorer - Deteksi Gerak</title>
    <style>
        /* Transition for theme switching */
        body {
            transition: background 0.6s ease, color 0.4s ease;
        }

        /* Dark mode refresh: lembut di mata, garis terlihat tapi tidak tajam */
        .dark body {
            background: radial-gradient(circle at 20% 20%, #154232 0%, #123a2c 35%, #0f3326 70%, #0d2f23 100%) !important;
            color: #e4edf5 !important;
            transition: background 0.6s ease, color 0.6s ease 0.6s;
        }

        .dark .card-surface {
            background: #12382a;
            border: 1px solid #2f5142;
        }

        .dark .muted-border {
            border-color: #2f5142 !important;
        }

        .dark .muted-bg {
            background-color: #16422f !important;
        }
    </style>

    <!-- ✅ Tailwind dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi custom Tailwind (warna, dark mode aktif)
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1E40AF',
                    },
                },
            },
        };
    </script>

    <!-- ✅ Deteksi tema sebelum render (hindari flicker) -->
    <script>
        (function() {
            try {
                const userPref = localStorage.getItem('theme');
                const systemPrefDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (userPref === 'dark' || (!userPref && systemPrefDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {
                console.error(e);
            }
        })();
    </script>

    <!-- ✅ Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-white text-slate-900 dark:bg-none dark:text-slate-100 min-h-screen transition-colors duration-500">

    <!--  Tombol toggle tema -->
    <div class="flex justify-end p-4">
        <button id="theme-toggle"
            class="p-3 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 transition text-xl">
            🌞 / 🌜
        </button>
    </div>

    <!-- Script toggle tema -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('theme-toggle');
            btn.addEventListener('click', () => {
                const html = document.documentElement;
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        });
    </script>

    <!--  Konten utama -->
    <div x-data="{
        activeTab: new URLSearchParams(window.location.search).get('tab') || localStorage.getItem('activeTab') || 'home',
        carouselIndex: 0,
        videoFile: null,
        cameraActive: false,
        videoStream: null,
        realtimeTimer: null,
        overlayFrame: null,
        counts: { pushup: 0, squat: 0, jj: 0 },
        sending: false,
        streamId: crypto.randomUUID ? crypto.randomUUID() : Math.random().toString(36).slice(2),
        flaskDefault: '{{ rtrim(config('services.flask.url'), '/') }}',
        backendApi: localStorage.getItem('customFlaskUrl') || '',
        cameras: [],
        selectedCamera: localStorage.getItem('selectedCameraId') || 'default',
        fpsOptions: [10, 15, 20],
        fpsIndex: 1,
        fps: parseInt(localStorage.getItem('realtimeFps') || '15', 10),
        resOptions: [
            { label: '640x480', w: 640, h: 480 },
            { label: '800x600', w: 800, h: 600 },
            { label: '1280x720', w: 1280, h: 720 },
        ],
        resIndex: 0,
        resLabel: localStorage.getItem('realtimeRes') || '640x480',
        init() {
            this.$watch('activeTab', value => {
                localStorage.setItem('activeTab', value);
                if (value !== 'realtime' && this.cameraActive) {
                    this.stopRealtime();
                }
            });
            this.$watch('backendApi', v => localStorage.setItem('customFlaskUrl', v || ''));
            this.$watch('selectedCamera', v => localStorage.setItem('selectedCameraId', v || 'default'));
            this.$watch('fpsIndex', v => {
                const clamped = Math.max(0, Math.min(this.fpsOptions.length - 1, v));
                this.fpsIndex = clamped;
                this.fps = this.fpsOptions[clamped];
                localStorage.setItem('realtimeFps', this.fps);
            });
            this.$watch('resIndex', v => {
                const clamped = Math.max(0, Math.min(this.resOptions.length - 1, v));
                this.resIndex = clamped;
                this.resLabel = this.resOptions[clamped].label;
                localStorage.setItem('realtimeRes', this.resLabel);
            });
    
            // If session has activeTab, use it and clear it from future reloads
            @if(session('activeTab'))
            this.activeTab = '{{ session('activeTab') }}';
            @endif
    
            this.initCameras();
            const idx = this.fpsOptions.indexOf(this.fps);
            this.fpsIndex = idx >= 0 ? idx : 1;
            const resIdx = this.resOptions.findIndex(r => r.label === this.resLabel);
            this.resIndex = resIdx >= 0 ? resIdx : 0;
        },
        flaskUrl() {
            const base = (this.backendApi && this.backendApi.trim()) ? this.backendApi.trim() : this.flaskDefault;
            return base.replace(/\/+$/, '');
        },
        nextSlide() { this.carouselIndex = this.carouselIndex === 1 ? 0 : 1 },
        prevSlide() { this.carouselIndex = this.carouselIndex === 0 ? 1 : 0 },
        resetUpload() {
            this.videoFile = null;
            this.$refs.fileInput.value = ''
        },
        incFps() {
            if (this.fpsIndex < this.fpsOptions.length - 1) this.fpsIndex++;
        },
        decFps() {
            if (this.fpsIndex > 0) this.fpsIndex--;
        },
        incRes() {
            if (this.resIndex < this.resOptions.length - 1) this.resIndex++;
        },
        decRes() {
            if (this.resIndex > 0) this.resIndex--;
        },
        async initCameras() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                this.cameras = devices.filter(d => d.kind === 'videoinput');
            } catch (e) {
                console.error('Enumerate cameras failed', e);
            }
        },
        async toggleCamera() { this.cameraActive ? this.stopRealtime() : await this.startRealtime(); },
        async startRealtime() {
            try {
                const currentRes = this.resOptions[this.resIndex] || this.resOptions[0];
                const constraints = {
                    video: {
                        width: { ideal: currentRes.w },
                        height: { ideal: currentRes.h },
                        frameRate: { ideal: this.fps },
                        deviceId: this.selectedCamera !== 'default' ? { exact: this.selectedCamera } : undefined,
                    }
                };
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                this.videoStream = stream;
                this.cameraActive = true;
                this.$refs.videoPlayer.srcObject = stream;
                // Kirim frame berkala
                this.realtimeTimer = setInterval(() => { this.sendFrame(); }, 800);
            } catch (e) {
                console.error('Tidak bisa akses kamera', e);
                alert('Tidak bisa akses kamera. Pastikan izin kamera diaktifkan.');
            }
        },
        stopRealtime() {
            this.cameraActive = false;
            if (this.realtimeTimer) {
                clearInterval(this.realtimeTimer);
                this.realtimeTimer = null;
            }
            if (this.videoStream) {
                this.videoStream.getTracks().forEach(t => t.stop());
                this.videoStream = null;
            }
            this.overlayFrame = null;
            fetch(this.flaskUrl() + '/api/realtime_reset', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ stream_id: this.streamId })
            }).catch(() => {});
        },
        async sendFrame() {
            if (!this.cameraActive || this.sending) return;
            const video = this.$refs.videoPlayer;
            if (!video.videoWidth || !video.videoHeight) return;
            const canvas = this.$refs.realtimeCanvas;
            const ctx = canvas.getContext('2d');
            const targetW = 640;
            const ratio = video.videoWidth / video.videoHeight;
            const targetH = Math.round(targetW / ratio);
            canvas.width = targetW;
            canvas.height = targetH;
            ctx.drawImage(video, 0, 0, targetW, targetH);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.7);
            this.sending = true;
            try {
                const resp = await fetch(this.flaskUrl() + '/api/realtime_frame', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ stream_id: this.streamId, frame: dataUrl })
                });
                if (resp.ok) {
                    const data = await resp.json();
                    this.counts = { pushup: data.pushup, squat: data.squat, jj: data.jj };
                    this.overlayFrame = 'data:image/jpeg;base64,' + data.frame;
                }
            } catch (err) {
                console.error('Gagal kirim frame realtime', err);
            } finally {
                this.sending = false;
            }
        },
    }" x-init="init()" class="max-w-7xl mx-auto px-4 py-8">

        @include('partials.header')
        @include('partials.nav-tabs')

        {{-- Session Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!--  Card utama -->
        <div
            class="bg-white/95 dark:bg-[#0f1c2a] dark:border dark:border-slate-700 rounded-xl shadow-xl p-8 transition-colors duration-500 card-surface">
            <div x-show="activeTab === 'home'" x-transition>
                @include('tabs.home-content')
            </div>

            <div x-show="activeTab === 'upload'" x-transition>
                @include('tabs.upload-video')
            </div>

            <div x-show="activeTab === 'realtime'" x-transition>
                @include('tabs.real-time-detection')
            </div>

            <div x-show="activeTab === 'settings'" x-transition>
                @include('tabs.settings-content')
            </div>

            <div x-show="activeTab === 'history'" x-transition>
                @include('tabs.history-table')
            </div>
        </div>

        <!--  Footer -->
        <footer class="text-center mt-12 text-slate-600 dark:text-slate-400 transition-colors duration-500">
            <p>© 2025 YOLO Explorer | Dibuat dengan Alpine.js & Tailwind CSS</p>
        </footer>
    </div>
</body>

</html>
