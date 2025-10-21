<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-6">Login</h2>
        <form method="POST" action="/login">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-slate-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" required autofocus>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password" class="block text-slate-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Login
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="/register">
                    Belum punya akun?
                </a>
            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('google.login') }}" 
                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 533.5 544.3">
                        <path fill="#4285F4" d="M533.5 278.4c0-17.4-1.6-34.1-4.7-50.2H272v95h147.5c-6.4 34.7-25 64-53.4 83.6v68h86.3c50.6-46.6 80.1-115.4 80.1-196.4z"/>
                        <path fill="#34A853" d="M272 544.3c72.6 0 133.6-24 178.1-65.5l-86.3-68c-24 16-54.7 25.4-91.8 25.4-70.7 0-130.5-47.7-152-111.4h-89v69.9C75.7 484 167.2 544.3 272 544.3z"/>
                        <path fill="#FBBC05" d="M120 324.8c-10.4-30.7-10.4-63.9 0-94.6V160.3H31v69.9c-21.5 63.7-21.5 133.8 0 197.5l89-69.9z"/>
                        <path fill="#EA4335" d="M272 107.8c39.5-.6 77.8 14 106.9 40.5l80-80C418.5 24.8 346.5-0.1 272 0 167.2 0 75.7 60.3 31 160.3l89 69.9c21.4-63.7 81.2-111.4 152-111.4z"/>
                    </svg>
                    Login dengan Google
                </a>
            </div>

        </form>
    </div>
</body>
</html>
