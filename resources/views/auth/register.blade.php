<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-6">Register</h2>
        <form method="POST" action="/register">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-slate-700 text-sm font-bold mb-2">Name</label>
                                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" required autofocus>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-slate-700 text-sm font-bold mb-2">Email</label>
                                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-slate-700 text-sm font-bold mb-2">Password</label>
                                <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @error('password')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-slate-700 text-sm font-bold mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Register
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="/login">
                    Sudah punya akun?
                </a>
            </div>
        </form>
    </div>
</body>
</html>
