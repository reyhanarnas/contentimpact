<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Daftar - ContentImpact CMS</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;850&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Outfit', sans-serif;
                background-color: #121829;
            }
        </style>
    </head>
    <body class="min-h-screen flex items-center justify-center p-4">
        
        <div class="w-full max-w-md bg-[#192239]/60 border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl backdrop-blur-md">
            
            <!-- Logo Header -->
            <div class="text-center mb-8 flex flex-col items-center">
                <!-- Hexagon Logo C -->
                <div class="relative w-16 h-16 flex items-center justify-center text-white mb-4">
                    <!-- Custom SVG Hexagon -->
                    <svg class="absolute inset-0 w-full h-full text-indigo-650" viewBox="0 0 100 100" fill="currentColor">
                        <polygon points="50,5 95,25 95,75 50,95 5,75 5,25" />
                    </svg>
                    <span class="relative text-2xl font-black z-10 font-mono tracking-tighter">C</span>
                </div>
                
                <h1 class="text-2xl font-extrabold text-white tracking-tight mb-1">Content<span class="text-indigo-500">Impact</span></h1>
                <p class="text-xs text-slate-400 font-medium">Registrasi Akun Jurnalis Baru</p>
            </div>

            <!-- Errors display -->
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/25 p-4 rounded-2xl mb-6 text-xs text-red-400 space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-start space-x-1.5">
                            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-400 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500">
                            <i class="fa-regular fa-user text-sm"></i>
                        </span>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            class="w-full bg-[#12192a] border border-slate-800 rounded-2xl pl-12 pr-4 py-3.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-700 transition" 
                            placeholder="Nama Lengkap Anda"
                        >
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 mb-2">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}" 
                            required 
                            class="w-full bg-[#12192a] border border-slate-800 rounded-2xl pl-12 pr-4 py-3.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-700 transition" 
                            placeholder="nama@email.com"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required 
                            class="w-full bg-[#12192a] border border-slate-800 rounded-2xl pl-12 pr-4 py-3.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-700 transition" 
                            placeholder="Minimal 8 karakter"
                        >
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-slate-500">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            required 
                            class="w-full bg-[#12192a] border border-slate-800 rounded-2xl pl-12 pr-4 py-3.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-700 transition" 
                            placeholder="Ketik ulang password"
                        >
                    </div>
                </div>

                <!-- Sign Up Button -->
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-750 text-white font-bold text-sm py-4 rounded-2xl transition shadow-lg shadow-indigo-600/10 mt-2">
                    Daftar Akun
                </button>

                <!-- Divider -->
                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-slate-800/80"></div>
                    <span class="flex-shrink mx-4 text-[10px] text-slate-600 uppercase font-bold tracking-wider">Atau</span>
                    <div class="flex-grow border-t border-slate-800/80"></div>
                </div>

                <!-- Login CTA -->
                <a href="{{ route('login') }}" class="w-full bg-[#27324c] hover:bg-[#2c3957] text-slate-200 hover:text-white font-semibold text-sm py-4 rounded-2xl transition flex items-center justify-center space-x-2 text-center">
                    <span>Sudah punya akun? Masuk di sini</span>
                </a>
            </form>
        </div>

    </body>
</html>
