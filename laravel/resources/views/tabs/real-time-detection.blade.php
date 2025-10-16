<div class="max-w-2xl mx-auto bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-8 border-2 border-green-200">
    <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-slate-800 mb-2">Ambil Secara Real Time</h3>
        <p class="text-slate-600">Gunakan kamera perangkat untuk deteksi langsung</p>
    </div>

    <div class="bg-white rounded-lg p-6">
        <div x-show="!cameraActive" class="text-center py-12">
            <button @click="toggleCamera()"
                class="inline-flex flex-col items-center justify-center w-40 h-40 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-full text-white transition-all transform hover:scale-105 shadow-xl">
                <svg class="w-20 h-20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-lg font-semibold">Aktifkan Kamera</span>
            </button>
        </div>

        <div x-show="cameraActive" class="space-y-4">
            <div
                class="bg-slate-900 rounded-lg aspect-video flex items-center justify-center relative overflow-hidden">
                <video x-ref="videoPlayer" autoplay playsinline class="w-full h-full object-cover"></video>
                <div x-show="!videoStream"
                    class="absolute inset-0 bg-gradient-to-br from-green-500/20 to-blue-500/20 animate-pulse">
                </div>
                <div x-show="!videoStream" class="text-center z-10">
                    <svg class="w-16 h-16 mx-auto text-green-400 mb-3 animate-bounce" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p class="text-white font-semibold text-lg">Live Video Feed</p>
                    <p class="text-green-300 text-sm">Kamera Aktif</p>
                </div>
            </div>

            <button @click="toggleCamera()"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                Matikan Kamera
            </button>
        </div>
    </div>
</div>
