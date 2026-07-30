@extends('layouts.dashboard')

@section('title', $article->title)
@section('header_title', 'Detail Artikel')

@section('dashboard_content')
<div class="max-w-4xl mx-auto space-y-8" x-data="{ revisionModalOpen: false }">
    <!-- Back Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('dashboard.articles.index') }}" class="text-slate-400 hover:text-white text-xs font-semibold flex items-center space-x-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>

        <div class="flex items-center space-x-3">
            @can('update', $article)
            <a href="{{ route('dashboard.articles.edit', $article->id) }}" class="bg-indigo-600/10 hover:bg-indigo-600 hover:text-white text-indigo-400 text-xs px-4 py-2 rounded-xl transition font-semibold">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
            </a>
            @endcan

            @if($article->isPublished())
            <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="bg-slate-800 hover:bg-slate-750 text-slate-200 text-xs px-4 py-2 rounded-xl transition font-semibold">
                <i class="fa-solid fa-up-right-from-square mr-1"></i> Buka di Portal
            </a>
            @endif
        </div>
    </div>

    <!-- Editorial Actions Widget (For Editors/Admins on Pending Articles) -->
    @if($article->isPendingReview() && Gate::allows('review', App\Models\Article::class))
    <div class="bg-indigo-950/20 border border-indigo-500/20 p-6 rounded-3xl flex flex-col sm:flex-row justify-between items-center gap-6 shadow">
        <div>
            <h4 class="font-bold text-indigo-400 text-sm mb-1 flex items-center space-x-2">
                <i class="fa-solid fa-circle-check"></i>
                <span>Tinjauan Redaksi Diperlukan</span>
            </h4>
            <p class="text-xs text-slate-400 leading-relaxed max-w-lg">Artikel ini sedang menunggu keputusan penerbitan Anda. Setujui untuk menerbitkan atau kembalikan kepada jurnalis dengan catatan revisi.</p>
        </div>
        <div class="flex items-center space-x-3 w-full sm:w-auto shrink-0 justify-end">
            <!-- Reject / Request Revision trigger -->
            <button @click="revisionModalOpen = true" class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow shadow-red-600/10">
                <i class="fa-solid fa-rotate-left mr-1"></i> Minta Revisi
            </button>

            <!-- Approve -->
            <form action="{{ route('dashboard.articles.approve', $article->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow shadow-emerald-600/10">
                    <i class="fa-solid fa-circle-check mr-1"></i> Setujui & Terbitkan
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Preview Container -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-lg">
        <div class="flex flex-wrap items-center space-x-3 text-xs text-slate-500 mb-6">
            <span class="bg-indigo-600/10 text-indigo-400 font-bold px-2.5 py-1 rounded">
                {{ $article->category->name }}
            </span>
            <span>&bull;</span>
            <span>Oleh: <strong class="text-slate-300">{{ $article->author->name }}</strong></span>
            <span>&bull;</span>
            <span>Status: 
                <strong class="@if($article->isPublished()) text-emerald-400 @elseif($article->isPendingReview()) text-amber-400 @elseif($article->isRevisionRequired()) text-red-400 @else text-slate-400 @endif uppercase">
                    {{ $article->status }}
                </strong>
            </span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-white mb-6 leading-tight">
            {{ $article->title }}
        </h1>

        @if($article->cover_image)
        <div class="w-full h-80 rounded-2xl overflow-hidden mb-8 border border-slate-850">
            <img src="{{ $article->cover_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
        </div>
        @endif

        @if($article->excerpt)
        <div class="bg-slate-950 border border-slate-850 p-4 rounded-xl text-slate-400 text-sm mb-6 leading-relaxed italic">
            <strong>Ringkasan:</strong> {{ $article->excerpt }}
        </div>
        @endif

        <div class="prose prose-invert max-w-none text-slate-300 text-sm sm:text-base leading-relaxed border-t border-slate-850 pt-6">
            {!! nl2br(e($article->content)) !!}
        </div>
    </div>

    <!-- Revision logs section -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-lg">
        <h3 class="text-base font-bold text-white mb-6 flex items-center space-x-2">
            <i class="fa-solid fa-clock-rotate-left text-slate-500"></i>
            <span>Riwayat Catatan Revisi & Feedback</span>
        </h3>

        <div class="space-y-6">
            @forelse($revisions as $rev)
            <div class="border-l-2 border-slate-800 pl-4 py-1">
                <div class="flex items-center space-x-2 text-[10px] text-slate-500 mb-2">
                    <span class="font-bold text-slate-300">{{ $rev->editor->name }}</span>
                    <span>&bull;</span>
                    <span>{{ $rev->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>
                <p class="text-slate-400 text-xs italic leading-relaxed">
                    "{{ $rev->note }}"
                </p>
            </div>
            @empty
            <div class="text-center py-6 text-slate-600 text-xs border border-dashed border-slate-850 rounded-2xl">
                Tidak ada riwayat revisi editorial.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Alpine.js Revision Modal -->
    <div 
        x-show="revisionModalOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
        x-transition
        style="display: none;"
    >
        <div 
            @click.away="revisionModalOpen = false" 
            class="bg-slate-900 border border-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6"
        >
            <div class="flex justify-between items-center pb-4 border-b border-slate-850">
                <h4 class="text-base font-bold text-white">Minta Revisi Artikel</h4>
                <button @click="revisionModalOpen = false" class="text-slate-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('dashboard.articles.revision', $article->id) }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="note" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Catatan Editorial / Koreksi</label>
                    <textarea name="note" id="note" rows="5" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition" placeholder="Tulis catatan detail apa saja bagian artikel yang perlu diperbaiki oleh jurnalis..."></textarea>
                </div>
                
                <div class="flex items-center space-x-3 justify-end">
                    <button type="button" @click="revisionModalOpen = false" class="text-slate-400 hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-800 transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-650 hover:bg-red-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition shadow shadow-red-600/10">
                        Kirim ke Jurnalis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
