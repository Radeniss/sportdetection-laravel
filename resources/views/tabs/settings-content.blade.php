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
                <label class="block text-sm font-medium text-slate-700">FPS</label>
                <div class="flex items-center gap-3 mt-1">
                    <button type="button" @click="decFps()"
                        class="w-10 h-10 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-xl">-</button>
                    <div class="flex-1 bg-slate-100 rounded-full h-3 relative">
                        <div class="absolute top-0 left-0 h-3 bg-green-500 rounded-full transition-all"
                            :style="`width: ${((fpsIndex)/(fpsOptions.length-1))*100}%`"></div>
                    </div>
                    <button type="button" @click="incFps()"
                        class="w-10 h-10 rounded-full bg-green-500 hover:bg-green-600 text-white font-bold text-xl">+</button>
                    <span class="w-12 text-right font-semibold text-slate-800" x-text="fps + ' fps'"></span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Opsi: 10 / 15 / 20 fps</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Resolusi</label>
                <div class="flex items-center gap-3 mt-1">
                    <button type="button" @click="decRes()"
                        class="w-10 h-10 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-xl">-</button>
                    <div class="flex-1 bg-slate-100 rounded-full h-3 relative">
                        <div class="absolute top-0 left-0 h-3 bg-blue-500 rounded-full transition-all"
                            :style="`width: ${((resIndex)/(resOptions.length-1))*100}%`"></div>
                    </div>
                    <button type="button" @click="incRes()"
                        class="w-10 h-10 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold text-xl">+</button>
                    <span class="w-20 text-right font-semibold text-slate-800" x-text="resLabel"></span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Preset: 640x480 / 800x600 / 1280x720</p>
            </div>
        </div>
        <div class="mt-4">
            <label for="backend-api" class="block text-sm font-medium text-slate-700">API Backend</label>
            <input type="text" name="backend-api" id="backend-api"
                x-model="backendApi"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                placeholder="http://192.168.x.x:7000 (kosongkan untuk pakai default)">
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
