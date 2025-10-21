<h2 class="text-3xl font-bold text-slate-800 mb-6 border-b-4 border-blue-600 inline-block pb-2">
    Riwayat Pemrosesan
</h2>

<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <th class="py-4 px-6 text-left font-semibold">Nama File/Sumber</th>
                <th class="py-4 px-6 text-left font-semibold">Tanggal Kirim</th>
                <th class="py-4 px-6 text-center font-semibold">Status</th>
                <th class="py-4 px-6 text-center font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if ($videos->count() > 0)
                @foreach ($videos as $video)
                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium text-slate-800">{{ $video->filename }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">{{ $video->created_at->format('d-m-Y H:i') }}</td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $video->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ asset('storage/videos/' . $video->filename) }}" target="_blank"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors mr-2">Lihat
                                Hasil</a>
                            <a href="{{ asset('storage/videos/' . $video->filename) }}" download
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">Unduh</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center py-12">
                        <svg class="w-20 h-20 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-slate-500 text-lg">
                            **Belum ada riwayat pemrosesan.**
                        </p>
                        <p class="text-slate-400 text-sm mt-2">
                            Ayo, unggah atau rekam video pertama Anda di tab **Input Deteksi**!
                        </p>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>