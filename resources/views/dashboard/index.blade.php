@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard Overview')

@section('dashboard_content')
<div class="space-y-8">
    <!-- Quick Statistics Widgets Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Total Users Widget -->
        @if(auth()->user()->isAdmin())
        <a href="{{ route('dashboard.users.index') }}" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex items-center justify-between shadow hover:border-indigo-500 hover:scale-[1.01] transition-all duration-200 group">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1 group-hover:text-slate-400 transition">Total Users</span>
                <span class="text-2xl font-extrabold text-white">{{ number_format($metrics['total_users']) }}</span>
                <div class="flex items-center space-x-3 mt-2 text-xs">
                    <span class="text-emerald-400 font-medium"><i class="fa-solid fa-user-check mr-1"></i>{{ $metrics['active_users'] }} Aktif</span>
                    <span class="text-red-400 font-medium"><i class="fa-solid fa-user-slash mr-1"></i>{{ $metrics['suspended_users'] }} Suspended</span>
                </div>
            </div>
            <div class="bg-indigo-600/10 p-4 rounded-xl text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition">
                <i class="fa-solid fa-users text-2xl"></i>
            </div>
        </a>
        @else
        <!-- For non-admins, show a personalized welcome widget -->
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex items-center justify-between col-span-1 shadow group">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">Kontributor</span>
                <h4 class="text-lg font-bold text-white mb-2">{{ auth()->user()->name }}</h4>
                <div class="flex items-center space-x-2">
                    <span class="bg-indigo-600/20 text-indigo-400 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded">
                        {{ auth()->user()->role }}
                    </span>
                    @if(auth()->user()->isJournalist())
                    <a href="{{ route('dashboard.articles.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded transition flex items-center space-x-1">
                        <i class="fa-solid fa-pen-nib"></i>
                        <span>Tulis Baru</span>
                    </a>
                    @endif
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="bg-indigo-600/10 p-4 rounded-xl text-indigo-400 hover:bg-indigo-600 hover:text-white transition" title="Edit Profil">
                <i class="fa-solid fa-id-card text-2xl"></i>
            </a>
        </div>
        @endif

        <!-- Total Articles Widget -->
        <a href="{{ route('dashboard.articles.index') }}" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex items-center justify-between shadow hover:border-indigo-500 hover:scale-[1.01] transition-all duration-200 group">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1 group-hover:text-slate-400 transition">Total Articles</span>
                <span class="text-2xl font-extrabold text-white">{{ number_format($metrics['total_articles']) }}</span>
                <div class="flex items-center space-x-3 mt-2 text-xs">
                    <span class="text-indigo-400 font-medium"><i class="fa-solid fa-check-circle mr-1"></i>{{ $metrics['published_articles'] }} Publish</span>
                    <span class="text-amber-400 font-medium"><i class="fa-solid fa-clock mr-1"></i>{{ $metrics['pending_articles'] }} Review</span>
                </div>
            </div>
            <div class="bg-indigo-600/10 p-4 rounded-xl text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition">
                <i class="fa-solid fa-newspaper text-2xl"></i>
            </div>
        </a>

        <!-- Role specific context widget -->
        @if(auth()->user()->isJournalist())
        <a href="{{ route('dashboard.articles.index') }}" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex items-center justify-between shadow hover:border-indigo-500 hover:scale-[1.01] transition-all duration-200 group">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1 group-hover:text-slate-400 transition">Artikel Saya</span>
                <span class="text-2xl font-extrabold text-white">{{ number_format($myArticlesCount) }}</span>
                <p class="text-xs text-slate-400 mt-2">Daftar tulisan yang telah Anda buat.</p>
            </div>
            <div class="bg-indigo-600/10 p-4 rounded-xl text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition">
                <i class="fa-solid fa-folder-open text-2xl"></i>
            </div>
        </a>
        @else
        <a href="{{ route('dashboard.articles.index') }}" class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex items-center justify-between shadow hover:border-indigo-500 hover:scale-[1.01] transition-all duration-200 group">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1 group-hover:text-slate-400 transition">Artikel Pending Review</span>
                <span class="text-2xl font-extrabold text-white">{{ number_format($metrics['pending_articles']) }}</span>
                <p class="text-xs text-slate-400 mt-2">Butuh persetujuan penerbitan.</p>
            </div>
            <div class="bg-indigo-600/10 p-4 rounded-xl text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition">
                <i class="fa-solid fa-folder-open text-2xl"></i>
            </div>
        </a>
        @endif

    </div>

    <!-- Charts and Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Views history (Line Chart) -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow">
            <h3 class="text-base font-bold text-white mb-6">Statistik Kunjungan Berita (7 Hari Terakhir)</h3>
            <div class="h-80">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>

        <!-- Categories Distribution (Doughnut Chart) -->
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow">
            <h3 class="text-base font-bold text-white mb-6">Artikel per Kategori</h3>
            <div class="h-80 flex items-center justify-center">
                <canvas id="categoriesChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Editorial Pending Queue Section (For Editors/Admins only if there are pending articles) -->
    @if((auth()->user()->isEditor() || auth()->user()->isAdmin()) && !empty($pendingArticles) && $pendingArticles->isNotEmpty())
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow">
        <h4 class="text-sm font-bold text-amber-400 mb-4 flex items-center space-x-2">
            <i class="fa-solid fa-hourglass-half"></i>
            <span>Butuh Review Segera ({{ $pendingArticles->count() }})</span>
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($pendingArticles as $pArt)
            <div class="bg-slate-950 border border-slate-850 p-4 rounded-xl flex items-center justify-between group hover:border-slate-700 transition">
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

    <!-- Popular Articles Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow overflow-hidden">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center">
            <h3 class="text-base font-bold text-white">5 Berita Paling Banyak Dilihat</h3>
            <span class="text-xs text-slate-400">Diurutkan berdasarkan total views</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-950 text-slate-300 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Judul</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Penulis</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Views</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($popularArticles as $art)
                    <tr class="hover:bg-slate-850 transition">
                        <td class="px-6 py-4 font-medium text-slate-200">
                            <a href="{{ route('dashboard.articles.show', $art->id) }}" class="hover:underline hover:text-indigo-450 transition line-clamp-1">
                                {{ $art->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-xs">{{ $art->category->name }}</td>
                        <td class="px-6 py-4 text-xs">{{ $art->author->name }}</td>
                        <td class="px-6 py-4">
                            @if($art->isPublished())
                                <span class="bg-emerald-500/10 text-emerald-400 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">Published</span>
                            @elseif($art->isPendingReview())
                                <span class="bg-amber-500/10 text-amber-400 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">Pending Review</span>
                            @elseif($art->isRevisionRequired())
                                <span class="bg-red-500/10 text-red-400 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">Revision</span>
                            @else
                                <span class="bg-slate-500/10 text-slate-400 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-200">{{ number_format($art->views) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-600">Belum ada artikel.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Line Chart (Views over time)
        const viewsCtx = document.getElementById('viewsChart').getContext('2d');
        new Chart(viewsCtx, {
            type: 'line',
            data: {
                labels: @json($viewsChart['labels']),
                datasets: [{
                    label: 'Total Views',
                    data: @json($viewsChart['data']),
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(51, 65, 85, 0.2)' },
                        ticks: { color: 'rgb(148, 163, 184)', font: { size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(51, 65, 85, 0.2)' },
                        ticks: { color: 'rgb(148, 163, 184)', font: { size: 10 } }
                    }
                }
            }
        });

        // Doughnut Chart (Categories Distribution)
        const catCtx = document.getElementById('categoriesChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoriesChart['labels']),
                datasets: [{
                    data: @json($categoriesChart['data']),
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(99, 102, 241, 0.4)',
                    ],
                    borderWidth: 1,
                    borderColor: 'rgb(15, 23, 42)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: 'rgb(148, 163, 184)', font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endsection
