@extends('layouts.portal')

@section('title', $article->title . ' - ContentImpact')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ copied: false }">
    <!-- Breadcrumb -->
    <nav class="flex space-x-2 text-xs text-slate-500 mb-8 items-center">
        <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
        <i class="fa-solid fa-angle-right text-[8px]"></i>
        <a href="{{ route('search', ['category' => $article->category_id]) }}" class="hover:text-white transition">{{ $article->category->name }}</a>
        <i class="fa-solid fa-angle-right text-[8px]"></i>
        <span class="text-slate-400 line-clamp-1">{{ $article->title }}</span>
    </nav>

    <!-- Cover Image -->
    <div class="w-full h-[320px] sm:h-[450px] rounded-3xl overflow-hidden mb-10 border border-slate-800 shadow-lg">
        <img src="{{ $article->cover_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
    </div>

    <!-- Article Header Info -->
    <div class="mb-10">
        <a href="{{ route('search', ['category' => $article->category_id]) }}" class="bg-indigo-600/90 text-white text-xs font-semibold uppercase tracking-wider px-3.5 py-1.5 rounded-full inline-block mb-6 shadow-md shadow-indigo-600/10">
            {{ $article->category->name }}
        </a>
        
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight mb-6">
            {{ $article->title }}
        </h1>

        <div class="flex flex-wrap items-center justify-between gap-6 border-y border-slate-800/80 py-5">
            <!-- Author and Date -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-600 text-white font-bold rounded-full flex items-center justify-center shadow-lg shadow-indigo-600/20">
                    {{ strtoupper(substr($article->author->name, 0, 1)) }}
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-slate-200">{{ $article->author->name }}</h5>
                    <p class="text-xs text-slate-500">
                        {{ $article->published_at ? $article->published_at->translatedFormat('d M Y, H:i') : $article->created_at->translatedFormat('d M Y, H:i') }} WIB
                    </p>
                </div>
            </div>

            <!-- Reading Time, Views & Social Sharing -->
            <div class="flex items-center space-x-6 text-sm text-slate-400">
                <span class="flex items-center space-x-1.5">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ $article->reading_time }} mnt baca</span>
                </span>
                <span class="flex items-center space-x-1.5">
                    <i class="fa-regular fa-eye"></i>
                    <span>{{ number_format($article->views) }} views</span>
                </span>
                
                <!-- Like Button -->
                <button 
                    id="like-btn" 
                    data-slug="{{ $article->slug }}"
                    data-liked="{{ session()->has('liked_article_' . $article->id) ? 'true' : 'false' }}"
                    class="flex items-center space-x-1.5 transition {{ session()->has('liked_article_' . $article->id) ? 'text-red-500 hover:text-red-400' : 'text-slate-400 hover:text-red-500' }}"
                >
                    <i class="{{ session()->has('liked_article_' . $article->id) ? 'fa-solid' : 'fa-regular' }} fa-heart text-base"></i>
                    <span id="likes-count">{{ number_format($article->likes) }}</span>
                </button>

                <!-- Share Buttons -->
                <div class="flex items-center space-x-3 border-l border-slate-800 pl-6">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($article->title) }}" target="_blank" class="hover:text-indigo-400 transition" title="Bagikan ke X">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="hover:text-indigo-400 transition" title="Bagikan ke Facebook">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . request()->fullUrl()) }}" target="_blank" class="hover:text-indigo-400 transition" title="Bagikan ke WhatsApp">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    <button 
                        @click="navigator.clipboard.writeText('{{ request()->fullUrl() }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                        class="hover:text-indigo-400 transition relative" 
                        title="Salin Link"
                    >
                        <i class="fa-regular fa-copy"></i>
                        <span x-show="copied" x-transition class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-[10px] bg-slate-950 text-white rounded border border-slate-800 whitespace-nowrap">Link disalin!</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <article class="prose prose-invert prose-indigo max-w-none text-slate-300 leading-relaxed text-base sm:text-lg mb-16 space-y-6">
        {!! nl2br(e($article->content)) !!}
    </article>

    <!-- Public Comments Section -->
    <section class="border-t border-slate-800/80 pt-12 mb-16">
        <h3 class="text-2xl font-bold text-white mb-8 flex items-center space-x-2">
            <i class="fa-regular fa-comments text-indigo-500"></i>
            <span>Komentar ({{ $comments->count() }})</span>
        </h3>

        <!-- Comment Flash Alert -->
        @if(session('success'))
            <div class="bg-indigo-600/10 border border-indigo-500/20 text-indigo-300 p-4 rounded-2xl mb-8 flex items-start space-x-3 text-sm">
                <i class="fa-solid fa-circle-info mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Add Comment Form -->
        <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-3xl mb-12 shadow-lg">
            <h4 class="text-lg font-bold text-slate-200 mb-6">Tinggalkan Komentar</h4>
            <form action="{{ route('articles.comment.store', $article->slug) }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Anda</label>
                        <input type="text" name="name" id="name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-700 transition" placeholder="Contoh: Reyhan">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email</label>
                        <input type="email" name="email" id="email" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-700 transition" placeholder="Contoh: reyhan@mail.com">
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label for="comment" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Komentar</label>
                    <textarea name="comment" id="comment" rows="4" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-700 transition" placeholder="Tulis pendapat Anda tentang berita ini..."></textarea>
                    @error('comment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm px-6 py-3 rounded-full transition shadow-md shadow-indigo-600/10">
                    Kirim Komentar
                </button>
            </form>
        </div>

        <!-- Comments List -->
        <div class="space-y-6">
            @forelse($comments as $com)
            <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-2xl">
                <div class="flex items-center justify-between mb-3 text-xs">
                    <span class="font-bold text-slate-200">{{ $com->name }}</span>
                    <span class="text-slate-500">{{ $com->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">
                    {{ $com->comment }}
                </p>
            </div>
            @empty
            <div class="text-center py-8 border border-dashed border-slate-800 rounded-2xl text-slate-500 text-sm">
                Belum ada komentar untuk artikel ini.
            </div>
            @endforelse
        </div>
    </section>

    <!-- Related Articles -->
    @if($related->isNotEmpty())
    <section class="border-t border-slate-800/80 pt-12">
        <h3 class="text-xl font-bold text-white mb-8 flex items-center space-x-2">
            <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
            <span>Artikel Terkait</span>
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach($related as $art)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden group shadow flex flex-col hover:border-slate-700 transition">
                <div class="h-32 overflow-hidden relative">
                    <img src="{{ $art->cover_image_url }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                </div>
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <h4 class="text-sm font-semibold text-slate-200 line-clamp-2 hover:text-indigo-400 transition mb-3">
                        <a href="{{ route('articles.show', $art->slug) }}">{{ $art->title }}</a>
                    </h4>
                    <span class="text-[10px] text-slate-500">{{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : $art->created_at->translatedFormat('d M Y') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const likeBtn = document.getElementById('like-btn');
    if (likeBtn) {
        likeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const slug = this.getAttribute('data-slug');
            
            fetch(`/articles/${slug}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likesCount = document.getElementById('likes-count');
                    const heartIcon = likeBtn.querySelector('i');
                    
                    likesCount.textContent = data.likes;
                    
                    if (data.liked) {
                        likeBtn.setAttribute('data-liked', 'true');
                        likeBtn.className = 'flex items-center space-x-1.5 transition text-red-500 hover:text-red-400';
                        heartIcon.className = 'fa-solid fa-heart text-base';
                    } else {
                        likeBtn.setAttribute('data-liked', 'false');
                        likeBtn.className = 'flex items-center space-x-1.5 transition text-slate-400 hover:text-red-500';
                        heartIcon.className = 'fa-regular fa-heart text-base';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
</script>
@endsection
