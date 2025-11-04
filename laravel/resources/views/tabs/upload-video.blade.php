<div 
    class="max-w-2xl mx-auto bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 border-2 border-blue-200"
    x-data="{
        videoFile: null,
        resetUpload() {
            this.videoFile = null;
            this.$refs.fileInput.value = null;
            document.getElementById('hasil').innerHTML = '';
        },
        async submitVideo() {
            if (!this.$refs.fileInput.files[0]) {
                alert('Pilih video terlebih dahulu!');
                return;
            }
            const formData = new FormData();
            formData.append('video', this.$refs.fileInput.files[0]);

            try {
                const res = await fetch('http://localhost:5000/api/process', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.output_url) {
                    document.getElementById('hasil').innerHTML = `
                        <video controls class='w-full rounded-lg mt-4 shadow-md'>
                            <source src='${data.output_url}' type='video/mp4'>
                        </video>
                        <div class='mt-4 text-left text-slate-700'>
                            <p><b>Push-up:</b> ${data.pushup}</p>
                            <p><b>Squat:</b> ${data.squat}</p>
                            <p><b>Jumping Jack:</b> ${data.jj}</p>
                            <a href='http://localhost:5000/api/download'
                               class='inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded mt-3'>
                               ⬇️ Download Hasil
                            </a>
                        </div>
                    `;
                } else {
                    alert('Gagal memproses video!');
                }
            } catch (err) {
                console.error(err);
                alert('Gagal mengunggah video');
            }
        }
    }"
>
    <div class="text-center mb-6">
        <svg class="w-20 h-20 mx-auto text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <div id="hasil" class="mt-6"></div>
</div>
