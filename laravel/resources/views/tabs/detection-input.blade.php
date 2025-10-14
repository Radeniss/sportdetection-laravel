<h2 class="text-3xl font-bold text-slate-800 mb-6 border-b-4 border-blue-600 inline-block pb-2">
    Input Deteksi Objek
</h2>

<div class="relative overflow-hidden">
    <div class="flex transition-transform duration-500 ease-in-out"
        :style="'transform: translateX(-' + (carouselIndex * 100) + '%)'">

        <div class="w-full flex-shrink-0 px-4">
            <div
                class="max-w-2xl mx-auto bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 border-2 border-blue-200">
                <div class="text-center mb-6">
                    <svg class="w-20 h-20 mx-auto text-blue-600 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Upload Video</h3>
                    <p class="text-slate-600">Unggah file video dari perangkat Anda untuk diproses</p>
                </div>

                <div class="bg-white rounded-lg p-6">
                    <label class="block mb-4">
                        <span class="text-slate-700 font-semibold mb-2 block">Pilih File Video:</span>
                        <input type="file" accept="video/*" x-ref="fileInput"
                            @change="videoFile = $event.target.files[0]?.name"
                            class="block w-full text-sm text-slate-600
                                        file:mr-4 file:py-3 file:px-6
                                        file:rounded-lg file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-600 file:text-white
                                        hover:file:bg-blue-700 file:cursor-pointer
                                        cursor-pointer">
                    </label>

                    <div x-show="videoFile" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-700 font-medium">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            File dipilih: <span x-text="videoFile"></span>
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button @click="resetUpload()"
                            class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                            Hapus
                        </button>
                        <button @click="submitVideo()"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                            Kirim
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full flex-shrink-0 px-4">
            <div
                class="max-w-2xl mx-auto bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-8 border-2 border-green-200">
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
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-green-500/20 to-blue-500/20 animate-pulse">
                            </div>
                            <div class="text-center z-10">
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
        </div>     
    </div>
</div>

<div class="flex justify-center items-center gap-6 mt-8">
    <button @click="prevSlide()"
        class="bg-slate-700 hover:bg-slate-800 text-white font-semibold py-3 px-8 rounded-lg transition-colors shadow-lg">
        ← Sebelumnya
    </button>

    <div class="flex gap-2">
        <div @click="carouselIndex = 0" :class="carouselIndex === 0 ? 'bg-blue-600 w-8' : 'bg-slate-300 w-3'"
            class="h-3 rounded-full cursor-pointer transition-all"></div>
        <div @click="carouselIndex = 1" :class="carouselIndex === 1 ? 'bg-blue-600 w-8' : 'bg-slate-300 w-3'"
            class="h-3 rounded-full cursor-pointer transition-all"></div>
    </div>

    <button @click="nextSlide()"
        class="bg-slate-700 hover:bg-slate-800 text-white font-semibold py-3 px-8 rounded-lg transition-colors shadow-lg">
        Lanjut →
    </button>
</div>
