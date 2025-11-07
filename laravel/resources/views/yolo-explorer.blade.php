<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>YOLO Explorer - Aplikasi Deteksi Objek</title>

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
    (function () {
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

<body
  class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 dark:text-slate-100 min-h-screen transition-colors duration-500">

  <!--  Tombol toggle tema -->
  <div class="flex justify-end p-4">
    <button id="theme-toggle"
      class="p-3 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 transition text-xl">
      🌞 / 🌜
    </button>
  </div>

  <!-- Script toggle tema -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
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
        activeTab: localStorage.getItem('activeTab') || 'home',
        carouselIndex: 0,
        videoFile: null,
        cameraActive: false,
        modelVersion: 'YOLOv8s',
        confidence: 0.5,
        outputFormat: 'video',
        historyData: [
            { name: 'video_traffic.mp4', date: '2025-10-10 14:30', status: 'Selesai' },
            { name: 'camera_live_01.mp4', date: '2025-10-09 10:15', status: 'Selesai' },
            { name: 'test_upload.mp4', date: '2025-10-08 16:45', status: 'Gagal' }
        ],
        init() {
            this.$watch('activeTab', value => localStorage.setItem('activeTab', value));
        },
        nextSlide() { this.carouselIndex = this.carouselIndex === 1 ? 0 : 1 },
        prevSlide() { this.carouselIndex = this.carouselIndex === 0 ? 1 : 0 },
        resetUpload() { this.videoFile = null; this.$refs.fileInput.value = '' },
        toggleCamera() { this.cameraActive = !this.cameraActive },
        submitVideo() {
            if (this.videoFile) alert('Video berhasil dikirim: ' + this.videoFile);
            else alert('Silakan pilih video terlebih dahulu');
        }
    }" x-init="init()" class="max-w-7xl mx-auto px-4 py-8">

    @include('partials.header')
    @include('partials.nav-tabs')

    <!--  Card utama -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-8 transition-colors duration-500">
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
