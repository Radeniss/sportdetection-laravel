<div class="max-w-2xl mx-auto">
    <div class="text-center mb-6">
        <svg class="w-20 h-20 mx-auto text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        <h3 class="text-2xl font-bold text-slate-800 mb-2 dark:text-slate-100">Upload Video</h3>
        <p class="text-slate-600 dark:text-slate-300">Unggah file video dari perangkat Anda untuk diproses</p>
    </div>

    <form action="{{ route('video.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white dark:bg-slate-700 rounded-lg p-6">
            <label class="block mb-4">
                <span class="text-slate-700 dark:text-slate-200 font-semibold mb-2 block">Pilih File Video:</span>
                <input type="file" name="video" accept="video/*" required
                    class="block w-full text-sm text-slate-600 dark:text-slate-300
                           file:mr-4 file:py-3 file:px-6
                           file:rounded-lg file:border-0
                           file:text-sm file:font-semibold
                           file:bg-blue-600 file:text-white
                           hover:file:bg-blue-700 file:cursor-pointer
                           cursor-pointer">
            </label>
            @error('video')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror

            <div class="flex gap-3 mt-4">
                <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                    Kirim
                </button>
            </div>
        </div>
    </form>
</div>
