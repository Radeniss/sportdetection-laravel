<h2 class="dark:text-slate-100 text-3xl font-bold text-slate-800 mb-6 border-b-4 border-green-600 inline-block pb-2">
    Apa itu MediaPipe Pose?
</h2>

<div class="space-y-8">
    <section>
        <div class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-lg p-6 border-l-4 border-green-600">
            <h3 class="text-2xl font-semibold text-slate-800 mb-4">Definisi</h3>
            <p class="text-slate-700 leading-relaxed text-lg">
                <strong>MediaPipe Pose</strong> adalah model deteksi dan pelacakan pose ringan dari Google yang
                menghasilkan <strong>33 titik kunci tubuh</strong> (x, y, visibility) dalam satu inferensi. Model ini
                dirancang untuk berjalan real-time di CPU maupun GPU, sehingga cocok untuk aplikasi olahraga, kebugaran,
                AR, dan antarmuka gestur.
            </p>
        </div>
    </section>

    <section>
        <h3 class="text-2xl font-semibold text-slate-800 dark:text-slate-100 mb-4">Manfaat Utama</h3>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-green-50 rounded-lg p-6 border-t-4 border-green-500 hover:shadow-lg transition-shadow">
                <div class="text-green-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-slate-800 mb-2">Ringan & Real-time</h4>
                <p class="text-slate-600">
                    Optimasi untuk perangkat konsumen; berjalan lancar di CPU dengan latensi rendah, ideal untuk webcam
                    atau mobile tanpa GPU kuat.
                </p>
            </div>

            <div class="bg-blue-50 rounded-lg p-6 border-t-4 border-blue-500 hover:shadow-lg transition-shadow">
                <div class="text-blue-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-slate-800 mb-2">Stabil & Akurat</h4>
                <p class="text-slate-600">
                    33 keypoints lengkap (kepala, bahu, siku, pergelangan, pinggul, lutut, pergelangan kaki) dengan nilai
                    visibility untuk memfilter deteksi yang tidak pasti.
                </p>
            </div>

            <div class="bg-emerald-50 rounded-lg p-6 border-t-4 border-emerald-500 hover:shadow-lg transition-shadow">
                <div class="text-emerald-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-slate-800 mb-2">Siap Dipakai</h4>
                <p class="text-slate-600">
                    Tidak perlu training ulang untuk hitung gerakan dasar seperti push-up, squat, dan jumping jack; cukup
                    pakai koordinat pose + logika perhitungan di aplikasi.
                </p>
            </div>
        </div>
    </section>

    <section>
        <h3 class="text-2xl font-semibold text-slate-800 dark:text-slate-100 mb-4">Cara Kerja Singkat</h3>
        <div class="space-y-4">
            <div class="flex gap-4 items-start bg-slate-50 rounded-lg p-5 hover:bg-slate-100 transition-colors">
                <div
                    class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold flex-shrink-0">
                    1</div>
                <div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Input Frame</h4>
                    <p class="text-slate-600">
                        Frame video diresize dan dinormalisasi, lalu masuk ke model pose.
                    </p>
                </div>
            </div>

            <div class="flex gap-4 items-start bg-slate-50 rounded-lg p-5 hover:bg-slate-100 transition-colors">
                <div
                    class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold flex-shrink-0">
                    2</div>
                <div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Inferensi Pose</h4>
                    <p class="text-slate-600">
                        Model menghasilkan 33 keypoints tubuh beserta visibility. Koordinat diproyeksikan ke piksel.
                    </p>
                </div>
            </div>

            <div class="flex gap-4 items-start bg-slate-50 rounded-lg p-5 hover:bg-slate-100 transition-colors">
                <div
                    class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold flex-shrink-0">
                    3</div>
                <div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Logika Perhitungan</h4>
                    <p class="text-slate-600">
                        Sudut siku/lutut/pinggul dan jarak antar pergelangan kaki dipakai untuk deteksi push-up, squat,
                        dan jumping jack.
                    </p>
                </div>
            </div>

            <div class="flex gap-4 items-start bg-slate-50 rounded-lg p-5 hover:bg-slate-100 transition-colors">
                <div
                    class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold flex-shrink-0">
                    4</div>
                <div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Output & Overlay</h4>
                    <p class="text-slate-600">
                        Titik pose digambar di frame, dan hitungan gerakan ditampilkan langsung pada video maupun riwayat
                        upload.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg p-6 border border-slate-200">
        <h3 class="text-xl font-semibold text-slate-800 mb-3">Kenapa Beralih ke MediaPipe?</h3>
        <ul class="list-disc list-inside space-y-2 text-slate-600 ml-4">
            <li>Performa tinggi di CPU tanpa dependency model besar.</li>
            <li>Stabil untuk pose manusia (33 keypoints) dengan nilai visibility.</li>
            <li>Mudah diintegrasikan untuk hitung rep olahraga dan deteksi real-time.</li>
        </ul>
    </section>
</div>
