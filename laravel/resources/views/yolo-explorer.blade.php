<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOLO Explorer - Aplikasi Deteksi Objek</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <div x-data="{
        activeTab: 'home',
        carouselIndex: 0,
        videoFile: null,
        cameraActive: false,
        modelVersion: 'YOLOv8s',
        confidence: 0.5,
        outputFormat: 'video',
        historyData: [],
        nextSlide() {
            this.carouselIndex = this.carouselIndex === 1 ? 0 : 1;
        },
        prevSlide() {
            this.carouselIndex = this.carouselIndex === 0 ? 1 : 0;
        },
        resetUpload() {
            this.videoFile = null;
            this.$refs.fileInput.value = '';
        },
        toggleCamera() {
            this.cameraActive = !this.cameraActive;
        },
        submitVideo() {
            if (this.videoFile) {
                alert('Video berhasil dikirim: ' + this.videoFile);
            } else {
                alert('Silakan pilih video terlebih dahulu');
            }
        }
    }" class="max-w-7xl mx-auto px-4 py-8">

        @include('partials.header')

        @include('partials.nav-tabs')

        <div class="bg-white rounded-xl shadow-xl p-8">
        </div>

        <footer class="text-center mt-12 text-slate-600">
            <p>© 2025 YOLO Explorer | Dibuat dengan Alpine.js & Tailwind CSS</p>
        </footer>

    </div>
</body>

</html>
