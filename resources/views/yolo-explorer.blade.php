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
        activeTab: new URLSearchParams(window.location.search).get('tab') || localStorage.getItem('activeTab') || 'home',
        carouselIndex: 0,
        videoFile: null,
        cameraActive: false,
        modelVersion: 'YOLOv8s',
        confidence: 0.5,
        outputFormat: 'video',
        init() {
            this.$watch('activeTab', value => localStorage.setItem('activeTab', value));
            
            // If session has activeTab, use it and clear it from future reloads
            @if(session('activeTab'))
                this.activeTab = '{{ session("activeTab") }}';
            @endif
        },
        nextSlide() { this.carouselIndex = this.carouselIndex === 1 ? 0 : 1 },
        prevSlide() { this.carouselIndex = this.carouselIndex === 0 ? 1 : 0 },
        resetUpload() { this.videoFile = null; this.$refs.fileInput.value = '' },
        toggleCamera() { this.cameraActive = !this.cameraActive },
    }" x-init="init()" class="max-w-7xl mx-auto px-4 py-8">

    @include('partials.header')
    @include('partials.nav-tabs')

    {{-- Session Messages --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

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
