@extends('layouts.portal')

@section('title', 'Cari Berita - ContentImpact')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header -->
    <div class="mb-10 text-center sm:text-left">
        <h1 class="text-3xl font-extrabold text-white mb-2 flex items-center justify-center sm:justify-start space-x-3">
            <i class="fa-solid fa-magnifying-glass text-indigo-500"></i>
            <span>Pencarian Berita</span>
        </h1>
        <p class="text-slate-400 text-sm">Cari artikel dengan kata kunci dan filter canggih di bawah ini.</p>
    </div>

    <!-- Search Form & Filters -->
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-3xl mb-12 shadow-lg">
        <form action="{{ route('search') }}" method="GET" class="space-y-6">
            <!-- Search bar -->
            <div class="relative">
                <input type="text" name="query" value="{{ $filters['query'] ?? '' }}" placeholder="Ketik kata kunci judul atau isi artikel..." class="w-full bg-slate-950 border border-slate-800 rounded-2xl pl-12 pr-4 py-4 text-slate-200 text-sm sm:text-base focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-700 transition-all">
                <span class="absolute left-4 top-4 text-slate-500 text-lg sm:text-xl">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
            </div>

            <!-- Advanced Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Category Select -->
                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                    <select name="category" id="category" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 text-sm focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (isset($filters['category']) && $filters['category'] == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Author Select -->
                <div>
                    <label for="author" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Penulis</label>
                    <select name="author" id="author" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 text-sm focus:outline-none focus:border-indigo-500 transition">
                        <option value="">Semua Penulis</option>
                        @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ (isset($filters['author']) && $filters['author'] == $author->id) ? 'selected' : '' }}>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label for="date" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal Terbit</label>
                    <input type="date" name="date" id="date" value="{{ $filters['date'] ?? '' }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>
            </div>

            <!-- Submit Button & Clear -->
            <div class="flex items-center space-x-4 justify-end">
                @if(!empty($filters))
                <a href="{{ route('search') }}" class="text-slate-400 hover:text-white text-sm font-semibold transition py-2.5 px-4 rounded-full border border-slate-800 hover:bg-slate-850">
                    Hapus Filter
                </a>
                @endif
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-6 py-3 rounded-full transition shadow-md shadow-indigo-600/10">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Search Results Grid -->
    <div>
        <h2 class="text-lg font-bold text-slate-300 mb-6 uppercase tracking-wider">Hasil Pencarian</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
            @forelse($articles as $art)
            <article class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden group shadow flex flex-col h-[400px] hover:border-slate-700 transition">
                <div class="h-44 overflow-hidden relative">
                    <img src="{{ $art->cover_image_url }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-4 left-4 bg-indigo-600/90 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded">
                        {{ $art->category->name }}
                    </span>
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <div class="flex items-center space-x-2 text-[10px] text-slate-500 mb-2">
                            <span>{{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : $art->created_at->translatedFormat('d M Y') }}</span>
                            <span>&bull;</span>
                            <span>{{ $art->reading_time }} mnt baca</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-200 line-clamp-2 hover:text-indigo-400 transition mb-2">
                            <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                        </h3>
                        <p class="text-xs text-slate-400 line-clamp-3">
                            {{ $art->excerpt }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-850">
                        <span class="text-xs text-slate-300 font-medium">{{ $art->author->name }}</span>
                        <span class="text-[10px] text-slate-500">{{ number_format($art->views) }} views</span>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-20 bg-slate-900 border border-slate-850 rounded-3xl">
                <i class="fa-solid fa-face-frown text-slate-600 text-5xl mb-4"></i>
                <h3 class="text-lg font-semibold text-slate-400 mb-1">Berita tidak ditemukan.</h3>
                <p class="text-slate-500 text-sm">Coba kata kunci lain atau bersihkan filter pencarian.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
