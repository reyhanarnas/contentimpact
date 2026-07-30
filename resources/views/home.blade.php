@extends('layouts.portal')

@section('title', 'ContentImpact - Berita Terkini & Analisis Mendalam')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Hero & Trending Layout -->
    @if($hero)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
        <!-- Hero Section -->
        <div class="lg:col-span-2 group relative bg-slate-900 rounded-3xl overflow-hidden border border-slate-800 shadow-xl transition-all duration-300 hover:border-slate-700 flex flex-col h-[500px]">
            <!-- Cover image container -->
            <div class="absolute inset-0 bg-cover bg-center transition-all duration-500 group-hover:scale-105" style="background-image: url('{{ $hero->cover_image_url }}')"></div>
            <!-- Overlay gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

            <!-- Hero content pinned at bottom -->
            <div class="relative mt-auto p-8 sm:p-10 flex flex-col items-start">
                <span class="bg-indigo-600/90 text-white text-xs font-semibold tracking-wider uppercase px-3 py-1.5 rounded-full mb-4">
                    {{ $hero->category->name }}
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight mb-4 group-hover:text-indigo-200 transition duration-200">
                    <a href="{{ route('articles.show', $hero->slug) }}">{{ $hero->title }}</a>
                </h1>
                <p class="text-slate-300 text-sm sm:text-base line-clamp-2 mb-6 max-w-2xl">
                    {{ $hero->excerpt }}
                </p>
                <div class="flex items-center space-x-6 text-xs text-slate-400">
                    <span class="flex items-center space-x-1">
                        <i class="fa-regular fa-user"></i>
                        <span>{{ $hero->author->name }}</span>
                    </span>
                    <span class="flex items-center space-x-1">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ $hero->published_at ? $hero->published_at->translatedFormat('d M Y') : $hero->created_at->translatedFormat('d M Y') }}</span>
                    </span>
                    <span class="flex items-center space-x-1">
                        <i class="fa-regular fa-eye"></i>
                        <span>{{ number_format($hero->views) }} views</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Trending Sidebar -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 flex flex-col h-[500px]">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center space-x-2">
                <i class="fa-solid fa-fire text-amber-500"></i>
                <span>Trending News</span>
            </h2>
            <div class="space-y-6 overflow-y-auto pr-2 flex-grow scrollbar-thin scrollbar-thumb-slate-800 scrollbar-track-transparent">
                @forelse($popular as $index => $art)
                <div class="flex items-start space-x-4 group">
                    <div class="text-3xl font-extrabold text-slate-700 group-hover:text-indigo-500 transition-colors duration-200 w-8">
                        0{{ $index + 1 }}
                    </div>
                    <div class="flex-grow">
                        <a href="{{ route('articles.show', $art->slug) }}" class="text-sm font-semibold text-slate-200 hover:text-white line-clamp-2 leading-snug mb-1 transition-colors duration-150">
                            {{ $art->title }}
                        </a>
                        <div class="flex items-center space-x-3 text-[10px] text-slate-500">
                            <span>{{ $art->category->name }}</span>
                            <span>&bull;</span>
                            <span class="flex items-center space-x-1">
                                <i class="fa-regular fa-eye text-[9px]"></i>
                                <span>{{ number_format($art->views) }} views</span>
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-slate-500 text-sm">Tidak ada berita populer saat ini.</p>
                @endforelse
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-20 bg-slate-900 border border-slate-800 rounded-3xl mb-16">
        <i class="fa-solid fa-newspaper text-slate-600 text-5xl mb-4"></i>
        <h2 class="text-xl font-semibold text-slate-400">Belum ada berita yang diterbitkan.</h2>
    </div>
    @endif

    <!-- Category Filter Pill Section -->
    <div class="mb-12">
        <h3 class="text-lg font-bold text-slate-300 mb-6 uppercase tracking-wider">Pilih Kategori</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('search') }}" class="bg-indigo-600/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-600 hover:text-white px-5 py-2 rounded-full text-sm font-medium transition duration-200">
                Semua Kategori
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('search', ['category' => $cat->id]) }}" class="bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800 hover:text-slate-200 px-5 py-2 rounded-full text-sm font-medium transition duration-200 flex items-center space-x-2">
                <span>{{ $cat->name }}</span>
                <span class="bg-slate-800 text-slate-500 text-xs px-2 py-0.5 rounded-full group-hover:bg-slate-700">{{ $cat->articles_count }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Latest Articles Section -->
    <div>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-extrabold text-white flex items-center space-x-3">
                <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                <span>Berita Terbaru</span>
            </h2>
            <a href="{{ route('search') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-semibold transition flex items-center space-x-1">
                <span>Lihat Semua</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($latest as $art)
            <article class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden group shadow-lg flex flex-col h-[420px] hover:border-slate-700 transition duration-300">
                <!-- Cover Image -->
                <div class="h-48 overflow-hidden relative">
                    <img src="{{ $art->cover_image_url }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <a href="{{ route('search', ['category' => $art->category_id]) }}" class="absolute top-4 left-4 bg-indigo-600/90 backdrop-blur-md text-white text-[10px] font-bold tracking-wider uppercase px-2.5 py-1.5 rounded-md">
                        {{ $art->category->name }}
                    </a>
                </div>
                <!-- Content -->
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex items-center space-x-3 text-xs text-slate-500 mb-3">
                        <span class="flex items-center space-x-1">
                            <i class="fa-regular fa-calendar"></i>
                            <span>{{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : $art->created_at->translatedFormat('d M Y') }}</span>
                        </span>
                        <span>&bull;</span>
                        <span class="flex items-center space-x-1">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ $art->reading_time }} mnt baca</span>
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-200 line-clamp-2 group-hover:text-indigo-400 transition mb-3">
                        <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                    </h3>
                    
                    <p class="text-slate-400 text-sm line-clamp-3 mb-4 flex-grow">
                        {{ $art->excerpt }}
                    </p>

                    <div class="border-t border-slate-800/80 pt-4 flex items-center justify-between mt-auto">
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 bg-slate-800 rounded-full flex items-center justify-center text-xs font-semibold text-slate-300">
                                {{ strtoupper(substr($art->author->name, 0, 1)) }}
                            </div>
                            <span class="text-xs text-slate-300 font-medium">{{ $art->author->name }}</span>
                        </div>
                        <div class="text-[10px] text-slate-500 flex items-center space-x-1">
                            <i class="fa-regular fa-eye"></i>
                            <span>{{ number_format($art->views) }} views</span>
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-10 bg-slate-900 border border-slate-800 rounded-3xl text-slate-500">
                Belum ada berita terbaru.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
