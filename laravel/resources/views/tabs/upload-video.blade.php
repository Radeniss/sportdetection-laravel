<div class="max-w-2xl mx-auto bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 border-2 border-blue-200">
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
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700 font-medium">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </p>
            </div>
        @endif
        <form action="{{ route('submit-video') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="block mb-4">
                <span class="text-slate-700 font-semibold mb-2 block">Pilih File Video:</span>
                <input type="file" name="video" accept="video/*"
                    class="block w-full text-sm text-slate-600
                                        file:mr-4 file:py-3 file:px-6
                                        file:rounded-lg file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-600 file:text-white
                                        hover:file:bg-blue-700 file:cursor-pointer
                                        cursor-pointer">
            </label>

            <div class="flex gap-3">
                <button type="reset"
                    class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-3 px-6 rounded-lg transition-colors">
                    Hapus
                </button>
                <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                    Kirim
                </button>
            </div>
        </form>
    </div>
</div>
