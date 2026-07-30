@extends('layouts.dashboard')

@section('title', 'Moderasi Komentar')
@section('header_title', 'Komentar')

@section('dashboard_content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h3 class="text-base font-bold text-slate-200">Moderasi Komentar Publik</h3>
        <p class="text-xs text-slate-500">Tinjau dan moderasilah komentar pembaca agar platform ContentImpact tetap bersih dari spam.</p>
    </div>



    <!-- Approved / All Comments History -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 bg-slate-900/40">
            <h4 class="text-sm font-bold text-slate-200">Riwayat Semua Komentar</h4>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-950 text-slate-300 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Komentator</th>
                        <th class="px-6 py-4">Komentar</th>
                        <th class="px-6 py-4">Artikel</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($allComments as $com)
                    <tr class="hover:bg-slate-850/40 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-200">{{ $com->name }}</div>
                            <div class="text-[10px] text-slate-500 font-mono">{{ $com->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <p class="max-w-xs truncate" title="{{ $com->comment }}">{{ $com->comment }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-indigo-400">
                            <a href="{{ route('articles.show', $com->article->slug) }}" target="_blank" class="hover:underline line-clamp-1">
                                {{ $com->article->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded @if($com->status === 'approved') bg-emerald-500/10 text-emerald-400 @elseif($com->status === 'pending') bg-amber-500/10 text-amber-400 @else bg-red-500/10 text-red-400 @endif">
                                {{ $com->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center">
                                <button 
                                    @click="$dispatch('open-delete-modal', { id: 'globalDeleteModal', actionUrl: '{{ route('dashboard.comments.destroy', $com->id) }}', title: 'Hapus Komentar', message: 'Apakah Anda yakin ingin menghapus komentar ini dari artikel {{ addslashes($com->article->title) }}?' })"
                                    class="text-red-400 hover:text-red-300 text-xs transition" 
                                    title="Hapus Komentar"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-600">Belum ada riwayat komentar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
