# Deteksi Olahraga (Laravel + MediaPipe)

Aplikasi web untuk mengunggah dan memantau deteksi gerakan olahraga (push-up, squat, jumping jack) berbasis Laravel + Alpine/Tailwind, dengan pemrosesan pose dilakukan di layanan Flask menggunakan MediaPipe Pose.

## Model dan Arsitektur
- Model: MediaPipe Pose (varian BlazePose) 33 keypoint, berjalan di CPU; dipakai untuk menghitung sudut siku/lutut serta jarak pergelangan kaki.
- Layanan pemrosesan: aplikasi Flask (endpoint `/process_video`, `/api/realtime_frame`) yang menerima video/frame, memproses, lalu mengirim hasil dan jumlah repetisi lewat webhook.
- Integrasi: Laravel mengirim video beserta `video_id` dan `webhook_url`; webhook diterima di `/api/videos/webhook` untuk memperbarui status, nama file hasil, dan detail hitungan.

## Fitur Utama
- Autentikasi (login Google atau email/password) sebelum akses dashboard.
- Unggah video latihan (validasi tipe + batas 100MB), kirim ke Flask, dan pantau status (pending/processing/completed/failed) di tab riwayat.
- Riwayat video per pengguna: lihat detail hitungan push-up/squat/jumping jack, unduh video hasil dengan overlay pose, hapus, atau batalkan job yang masih berjalan.
- Deteksi real-time lewat kamera: pilih perangkat kamera, atur FPS/resolusi, kirim frame ke Flask, dan lihat overlay serta counter langsung di browser.
- Pengaturan: ganti URL backend Flask secara dinamis, simpan preferensi tema (light/dark), kamera, FPS, dan resolusi di localStorage.

## Kelebihan
- Pemrosesan asinkron dengan webhook sehingga upload cepat dan status tetap sinkron meski proses di backend berbeda server.
- Model MediaPipe Pose ringan dan dapat berjalan di CPU, cocok untuk server dengan resource terbatas.
- Video hasil sudah teranotasi (landmark + counter) dan siap diunduh dari layanan Flask.
- Proteksi dasar: validasi file, batas ukuran, timeout permintaan ke Flask, serta opsi pembatalan job.

## Alur Kerja
- Upload video: pengguna login -> unggah video -> file disimpan di `storage/app/public/videos/originals` -> Laravel mengirim ke Flask `/process_video` dengan `video_id` dan `webhook_url` -> status berubah ke `processing` -> Flask memproses dan memanggil webhook `/api/videos/webhook` membawa status akhir, nama file hasil, dan hitungan -> pengguna melihat hasil di riwayat dan dapat mengunduh video terproses.
- Real-time: buka tab Realtime -> pilih kamera/FPS/resolusi -> browser mengirim frame Base64 ke Flask `/api/realtime_frame` dengan `stream_id` -> Flask mengembalikan frame teranotasi dan counter yang ditampilkan langsung; reset counter ketika kamera dimatikan.
