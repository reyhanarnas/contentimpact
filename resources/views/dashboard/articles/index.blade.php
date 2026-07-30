@extends('layouts.dashboard')

@section('title', 'Kelola Artikel')
@section('header_title', 'Artikel')

@section('dashboard_content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-200">
                @if(auth()->user()->isAdmin()) Daftar Semua Artikel @elseif(auth()->user()->isEditor()) Antrean Editorial @else Artikel Saya @endif
            </h3>
            <p class="text-xs text-slate-500">Kelola publikasi berita, tinjauan, dan status draf tulisan Anda.</p>
        </div>
        
        @can('create', App\Models\Article::class)
        <a href="{{ route('dashboard.articles.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tulis Artikel</span>
        </a>
        @endcan
    </div>

    <!-- Editorial Pending Queue Section (For Editors/Admins only if there are pending articles) -->
    @if((auth()->user()->isEditor() || auth()->user()->isAdmin()) && !empty($pendingArticles) && $pendingArticles->isNotEmpty())
    <div class="bg-slate-900/50 border border-slate-800 p-6 rounded-2xl shadow">
        <h4 class="text-sm font-bold text-amber-400 mb-4 flex items-center space-x-2">
            <i class="fa-solid fa-hourglass-half"></i>
            <span>Butuh Review Segera ({{ $pendingArticles->count() }})</span>
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($pendingArticles as $pArt)
            <div class="bg-slate-900 border border-slate-850 p-4 rounded-xl flex items-center justify-between group hover:border-slate-750 transition">
                <div class="overflow-hidden pr-4">
                    <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">{{ $pArt->category->name }}</span>
                    <h5 class="text-xs font-bold text-slate-200 line-clamp-1 group-hover:text-indigo-400 transition">
                        <a href="{{ route('dashboard.articles.show', $pArt->id) }}">{{ $pArt->title }}</a>
                    </h5>
                    <p class="text-[10px] text-slate-500 mt-1">Oleh: {{ $pArt->author->name }} &bull; {{ $pArt->updated_at->diffForHumans() }}</p>
                </div>
                <a href="{{ route('dashboard.articles.show', $pArt->id) }}" class="bg-indigo-600/10 hover:bg-indigo-600 hover:text-white text-indigo-400 text-xs px-3.5 py-2 rounded-lg font-semibold transition shrink-0">
                    Review
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- All Articles Table Container -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 bg-slate-900/40">
            <h4 class="text-sm font-bold text-slate-200">Daftar Artikel</h4>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-950 text-slate-300 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Judul</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Penulis</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal Diperbarui</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($allArticles as $art)
                    <tr class="hover:bg-slate-850/40 transition">
                        <td class="px-6 py-4 font-semibold text-slate-200">
                            <div class="max-w-md">
                                <a href="{{ route('dashboard.articles.show', $art->id) }}" class="hover:text-indigo-400 transition block truncate">
                                    {{ $art->title }}
                                </a>
                                @if($art->isRevisionRequired() && $art->revision_note)
                                <div class="text-[10px] text-red-400 mt-1 flex items-start space-x-1">
                                    <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                                    <span class="line-clamp-1">Catatan: "{{ $art->revision_note }}"</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs">{{ $art->category->name }}</td>
                        <td class="px-6 py-4 text-xs">{{ $art->author->name }}</td>
                        <td class="px-6 py-4">
                            @if($art->isPublished())
                                <span class="bg-emerald-500/10 text-emerald-400 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md">Published</span>
                            @elseif($art->isPendingReview())
                                <span class="bg-amber-500/10 text-amber-400 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md">Pending Review</span>
                            @elseif($art->isRevisionRequired())
                                <span class="bg-red-500/10 text-red-400 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md">Revision Required</span>
                            @else
                                <span class="bg-slate-700/20 text-slate-400 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">{{ $art->updated_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-3">
                                <!-- Details / Preview -->
                                <a href="{{ route('dashboard.articles.show', $art->id) }}" class="text-slate-400 hover:text-white text-xs transition" title="Preview / Review Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <!-- Edit -->
                                @can('update', $art)
                                <a href="{{ route('dashboard.articles.edit', $art->id) }}" class="text-indigo-400 hover:text-indigo-300 text-xs transition" title="Edit Artikel">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @endcan

                                <!-- Submit Review (For journalist draft/revisions) -->
                                @can('submit', $art)
                                <form action="{{ route('dashboard.articles.submit', $art->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-xs transition" title="Ajukan Review">
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </button>
                                </form>
                                @endcan

                                <!-- Delete -->
                                @can('delete', $art)
                                <button 
                                    @click="$dispatch('open-delete-modal', { id: 'globalDeleteModal', actionUrl: '{{ route('dashboard.articles.destroy', $art->id) }}', title: 'Hapus Artikel', message: 'Apakah Anda yakin ingin menghapus artikel {{ addslashes($art->title) }}?' })"
                                    class="text-red-400 hover:text-red-300 text-xs transition" 
                                    title="Hapus Artikel"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-600">Belum ada artikel yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
