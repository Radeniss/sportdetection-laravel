<header class="text-center mb-12 relative">
    <h1 class="text-5xl font-bold text-slate-800 mb-3 dark:text-slate-100">
        <span class="text-green-600">MediaPipe</span> Pose Explorer
    </h1>
    <p class="text-slate-600 text-lg dark:text-slate-100">Deteksi dan hitung gerakan olahraga berbasis MediaPipe Pose</p>
    <div class="absolute top-0 right-0">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Logout
            </button>
        </form>
    </div>
</header>
