<h2 class="text-3xl font-bold text-slate-800 mb-6 border-b-4 border-blue-600 inline-block pb-2 dark:text-slate-100">
    Pengaturan Model Deteksi
</h2>

<div class="max-w-3xl mx-auto space-y-8">
    <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
        <h3 class="text-xl font-semibold text-slate-800 mb-4 ">Pengaturan Kamera</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="camera-source" class="block text-sm font-medium text-slate-700">Sumber Kamera</label>
                <select id="camera-source" name="camera-source" x-model="selectedCamera" @change="startCamera()" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <template x-for="camera in cameras" :key="camera.deviceId">
                        <option :value="camera.deviceId" x-text="camera.label"></option>
                    </template>
                </select>
            </div>
            <div>
                <label for="fps-slider" class="block text-sm font-medium text-slate-700">FPS: <span x-text="fps"></span></label>
                <input type="range" id="fps-slider" min="5" max="60" x-model="fps" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
            </div>
        </div>
        <div class="mt-4">
            <label for="backend-api" class="block text-sm font-medium text-slate-700">API Backend</label>
            <input type="text" name="backend-api" id="backend-api" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="http://localhost:5000/detect">
        </div>
    </div>

    <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
        <label class="block mb-4">
            <span class="text-lg font-semibold text-slate-800 mb-2 block flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
                Versi Model YOLO
            </span>
            <select x-model="modelVersion"
                class="w-full px-4 py-3 rounded-lg border-2 border-slate-300 focus:border-blue-500 focus:outline-none text-slate-700 font-medium bg-white cursor-pointer">
                <option value="YOLOv8n">YOLOv8n (Nano - Tercepat)</option>
                <option value="YOLOv8s">YOLOv8s (Small - Balance)</option>
                <option value="YOLOv8m">YOLOv8m (Medium)</option>
                <option value="YOLOv8l">YOLOv8l (Large)</option>
                <option value="YOLOv8x">YOLOv8x (Extra Large - Paling Akurat)</option>
                <option value="YOLOv7-tiny">YOLOv7-tiny (Ringan)</option>
                <option value="YOLOv9-c">YOLOv9-c (Compact)</option>
                <option value="YOLOv9-e">YOLOv9-e (Extended)</option>
            </select>
        </label>
        <p class="text-sm text-slate-600 mt-2">
            Model terpilih: <strong class="text-blue-600" x-text="modelVersion"></strong>
        </p>
    </div>

    <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
        <label class="block mb-4">
            <span class="text-lg font-semibold text-slate-800 mb-2 block flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Confidence Threshold (Ambang Batas Kepercayaan)
            </span>
            <div class="flex items-center gap-4">
                <input type="range" x-model.number="confidence" min="0.1" max="0.9" step="0.05"
                    class="flex-1 h-3 bg-blue-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                <span class="text-2xl font-bold text-blue-600 min-w-[60px] text-right"
                    x-text="confidence.toFixed(2)"></span>
            </div>
        </label>
        <p class="text-sm text-slate-600 mt-2">
            Hanya objek dengan confidence ≥ <strong x-text="(confidence * 100).toFixed(0) + '%'"></strong> yang akan
            ditampilkan.
            <br>
            <span class="text-slate-500">Nilai rendah (0.1-0.3): Lebih banyak deteksi, kemungkinan false positive. Nilai
                tinggi (0.7-0.9): Lebih sedikit deteksi, lebih akurat.</span>
        </p>
    </div>

    <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
        <span class="text-lg font-semibold text-slate-800 mb-4 block flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Format Output
        </span>

        <div class="space-y-3">
            <label
                class="flex items-start p-4 bg-white rounded-lg border-2 cursor-pointer transition-all hover:shadow-md"
                :class="outputFormat === 'video' ? 'border-blue-500 bg-blue-50' : 'border-slate-200'">
                <input type="radio" x-model="outputFormat" value="video"
                    class="mt-1 w-5 h-5 text-blue-600 cursor-pointer">
                <div class="ml-3">
                    <span class="font-semibold text-slate-800">Video dengan Bounding Box</span>
                    <span class="text-sm text-slate-600">Output berupa video dengan kotak deteksi dan label objek yang
                        terdetksi</span>
                </div>
            </label>

            <label
                class="flex items-start p-4 bg-white rounded-lg border-2 cursor-pointer transition-all hover:shadow-md"
                :class="outputFormat === 'json' ? 'border-blue-500 bg-blue-50' : 'border-slate-200'">
                <input type="radio" x-model="outputFormat" value="json"
                    class="mt-1 w-5 h-5 text-blue-600 cursor-pointer">
                <div class="ml-3">
                    <span class="font-semibold text-slate-800">Data Hasil JSON</span>
                    <span class="text-sm text-slate-600">Output berupa file JSON dengan koordinat, kelas, dan confidence
                        score setiap objek</span>
                </div>
            </label>
        </div>
    </div>

    <div class="flex justify-end pt-4">
        <button
            @click="alert('Pengaturan disimpan: Model=' + modelVersion + ', Confidence=' + confidence + ', Output=' + outputFormat)"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-10 rounded-lg transition-colors shadow-lg text-lg">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Simpan Pengaturan
        </button>
    </div>
</div>
