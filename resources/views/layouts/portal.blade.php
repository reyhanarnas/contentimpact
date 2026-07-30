<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'ContentImpact - Digital News Portal')</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="bg-[#0f172a] text-slate-100 min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white">
        <!-- Navigation Header -->
        <header class="sticky top-0 z-50 backdrop-blur-md bg-[#0f172a]/80 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div class="bg-indigo-600 p-2 rounded-xl text-white shadow-lg shadow-indigo-600/30">
                                <i class="fa-solid fa-bolt text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold tracking-tight text-white">Content<span class="text-indigo-500">Impact</span></span>
                        </a>
                    </div>

                    <!-- Navigation Links & Categories (Desktop) -->
                    <nav class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-slate-300 hover:text-white font-medium transition duration-200">Home</a>
                        <a href="{{ route('search') }}" class="text-slate-300 hover:text-white font-medium transition duration-200">Semua Berita</a>
                        
                        <!-- Simple category quick links -->
                        @php
                            $navCategories = \App\Models\Category::all();
                        @endphp
                        @foreach($navCategories->take(4) as $cat)
                            <a href="{{ route('search', ['category' => $cat->id]) }}" class="text-slate-400 hover:text-white transition duration-200 text-sm font-medium">{{ $cat->name }}</a>
                        @endforeach
                    </nav>

                    <!-- Search Bar & Actions -->
                    <div class="flex items-center space-x-6">
                        <!-- Portal Search Form -->
                        <form action="{{ route('search') }}" method="GET" class="hidden sm:block relative">
                            <input type="text" name="query" placeholder="Cari berita..." class="bg-slate-800 border border-slate-700 text-slate-200 text-sm rounded-full pl-10 pr-4 py-2 w-52 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 transition-all duration-300 focus:w-64">
                            <span class="absolute left-3.5 top-2.5 text-slate-500">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                        </form>

                        @if (Route::has('login'))
                            <div class="flex items-center space-x-4">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-full font-medium transition duration-200 shadow-md shadow-indigo-600/20 text-sm flex items-center space-x-2">
                                        <i class="fa-solid fa-gauge"></i>
                                        <span>Dashboard</span>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-slate-300 hover:text-white font-semibold transition duration-200 text-sm">Masuk</a>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-slate-950 border-t border-slate-900 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2 mb-4">
                            <div class="bg-indigo-600 p-2 rounded-xl text-white">
                                <i class="fa-solid fa-bolt text-lg"></i>
                            </div>
                            <span class="text-xl font-bold tracking-tight text-white">Content<span class="text-indigo-500">Impact</span></span>
                        </a>
                        <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                            Digital publishing & news management platform profesional yang menghadirkan konten berkualitas dan analisis mendalam.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold text-sm tracking-wider uppercase mb-4">Kategori Populer</h4>
                        <ul class="space-y-2 text-sm">
                            @foreach($navCategories->take(5) as $cat)
                                <li>
                                    <a href="{{ route('search', ['category' => $cat->id]) }}" class="text-slate-400 hover:text-white transition duration-150">{{ $cat->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold text-sm tracking-wider uppercase mb-4">Navigasi</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('home') }}" class="text-slate-400 hover:text-white transition duration-150">Beranda</a></li>
                            <li><a href="{{ route('search') }}" class="text-slate-400 hover:text-white transition duration-150">Cari Berita</a></li>
                            <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition duration-150">Login Kontributor</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-900 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center text-sm text-slate-500">
                    <p>&copy; {{ date('Y') }} ContentImpact. All rights reserved.</p>
                    <p class="mt-4 sm:mt-0">Dibuat untuk db_reyhan</p>
                </div>
            </div>
        </footer>
    </body>
</html>
