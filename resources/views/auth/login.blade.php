<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Asian Agri</title>

    {{-- Tailwind CDN (untuk testing cepat)
         Untuk production sebaiknya pakai Vite --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-amber-400 via-green-400 to-green-700">

    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto w-16 mb-2">
            <h1 class="text-xl font-semibold text-gray-700">LOGIN</h1>
        </div>

        {{-- Error Global --}}
        @if(session('error'))
            <div class="mb-4 text-sm text-red-600 bg-red-100 p-2 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Username --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Username</label>
                <input 
                    type="text" 
                    name="username"
                    value="{{ old('username') }}"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                >
                @error('username')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Password</label>
                <input 
                    type="password" 
                    name="password"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                >
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Button --}}
            <button 
                type="submit"
                class="w-full py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-full transition duration-300 shadow-md"
            >
                Masuk
            </button>
        </form>

    </div>

</body>
</html>
