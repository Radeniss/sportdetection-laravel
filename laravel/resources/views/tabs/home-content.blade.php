<h2 class="text-3xl font-bold text-slate-800 mb-6 border-b-4 border-blue-600 inline-block pb-2">
    Apa itu YOLO?
</h2>

<div class="space-y-8">
    <section>
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-6 border-l-4 border-blue-600">
            <h3 class="text-2xl font-semibold text-slate-800 mb-4">Definisi</h3>
            <p class="text-slate-700 leading-relaxed text-lg">
                <strong>YOLO (You Only Look Once)</strong> adalah algoritma deteksi objek yang revolusioner dalam dunia
                computer vision.
                Berbeda dengan metode deteksi tradisional yang memproses gambar dalam beberapa tahap terpisah (seperti
                Region-based CNN yang
                menggunakan pendekatan proposal region), YOLO memproses seluruh gambar dalam <strong>satu kali forward
                    pass</strong> melalui
                neural network. Pendekatan ini memungkinkan YOLO untuk mendeteksi ribuan objek dalam gambar secara
                bersamaan dengan kecepatan
                yang sangat tinggi, menjadikannya ideal untuk aplikasi real-time seperti autonomous driving,
                surveillance, dan augmented reality.
            </p>
        </div>
    </section>

    <section>
        <h3 class="text-2xl font-semibold text-slate-800 mb-4">Manfaat Utama</h3>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-green-50 rounded-lg p-6 border-t-4 border-green-500 hover:shadow-lg transition-shadow">
                <div class="text-green-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-slate-800 mb-2">Kecepatan Tinggi</h4>
                <p class="text-slate-600">
                    Mampu memproses hingga <strong>60+ FPS</strong> pada hardware modern. Versi ringan seperti
                    YOLOv8-nano dapat
                    mencapai 100+ FPS, sempurna untuk aplikasi real-time seperti video streaming dan robotika.
                </p>
            </div>

            <div class="bg-blue-50 rounded-lg p-6 border-t-4 border-blue-500 hover:shadow-lg transition-shadow">
                <div class="text-blue-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-slate-800 mb-2">Akurasi Tinggi</h4>
                <p class="text-slate-600">
                    Model terbaru seperti YOLOv8 dan YOLOv9 mencapai <strong>mAP hingga 55%+</strong> pada COCO dataset
                    dengan
                    balance optimal antara speed dan accuracy. Mendukung deteksi multi-class dengan presisi tinggi.
                </p>
            </div>

            <div class="bg-purple-50 rounded-lg p-6 border-t-4 border-purple-500 hover:shadow-lg transition-shadow">
                <div class="text-purple-600 mb-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-slate-800 mb-2">Generalisasi Kuat</h4>
                <p class="text-slate-600">
                    Belajar dari konteks global gambar sehingga lebih robust terhadap variasi pose, lighting, dan
                    background.
                    Dapat di-fine-tune dengan mudah untuk domain spesifik menggunakan transfer learning.
                </p>
            </div>
        </div>
    </section>
</div>