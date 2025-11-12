<h2 class="text-3xl font-bold text-slate-800 mb-6 border-b-4 border-blue-600 inline-block pb-2 dark:text-slate-100">
    Riwayat Pemrosesan
</h2>

<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <th class="py-4 px-6 text-left font-semibold">Nama File</th>
                <th class="py-4 px-6 text-left font-semibold">Tanggal Unggah</th>
                <th class="py-4 px-6 text-center font-semibold">Status</th>
                <th class="py-4 px-6 text-center font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($videos as $video)
                <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium text-slate-800 dark:text-slate-100">{{ $video->filename }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-slate-600 dark:text-slate-300">{{ $video->created_at->format('d M Y, H:i') }}</td>
                    <td class="py-4 px-6 text-center">
                        @if($video->status == 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Selesai
                            </span>
                        @elseif($video->status == 'processing')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">
                                <svg class="w-4 h-4 mr-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memproses
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                Gagal
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($video->status == 'completed')
                            <a href="#" {{-- TODO: Link to processed video --}}
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors mr-2">Lihat Hasil</a>
                        @else
                            <button class="bg-gray-400 text-white font-semibold py-2 px-4 rounded-lg cursor-not-allowed" disabled>Lihat Hasil</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-12">
                        <svg class="w-20 h-20 mx-auto text-slate-300 dark:text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-slate-500 dark:text-slate-400 text-lg font-semibold">
                            Belum ada riwayat pemrosesan.
                        </p>
                        <p class="text-slate-400 dark:text-slate-500 text-sm mt-2">
                            Unggah video pertama Anda di tab "Upload Video".
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>