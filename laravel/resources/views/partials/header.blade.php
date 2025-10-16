<header class="text-center mb-12 relative">
    <h1 class="text-5xl font-bold text-slate-800 mb-3">
        <span class="text-blue-600">YOLO</span> Explorer
    </h1>
    <p class="text-slate-600 text-lg">Eksplorasi Deteksi Objek dengan You Only Look Once</p>
    <div class="absolute top-0 right-0">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Logout
            </button>
        </form>
    </div>
</header>
