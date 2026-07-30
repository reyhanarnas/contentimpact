<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard') - ContentImpact CMS</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="bg-[#0b0f19] text-slate-100 min-h-screen flex selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false }">
        <!-- Sidebar Navigation -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0d1321] border-r border-slate-800 transition-transform duration-300 transform lg:translate-x-0 flex flex-col justify-between"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div>
                <!-- Sidebar Header Logo -->
                <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="bg-indigo-600 p-1.5 rounded-lg text-white">
                            <i class="fa-solid fa-bolt text-sm"></i>
                        </div>
                        <span class="text-lg font-bold tracking-tight text-white">Content<span class="text-indigo-500">Impact</span></span>
                    </a>
                    <!-- Close button for mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Nav Links -->
                <nav class="p-6 space-y-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block px-3 mb-2">Main Menu</span>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-850 hover:text-slate-200' }}">
                        <i class="fa-solid fa-chart-line w-5"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Article Manager (All roles but conditional views) -->
                    <a href="{{ route('dashboard.articles.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard.articles.index') || request()->routeIs('dashboard.articles.show') || request()->routeIs('dashboard.articles.edit') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-850 hover:text-slate-200' }}">
                        <i class="fa-solid fa-newspaper w-5"></i>
                        <span>
                            @if(auth()->user()->isAdmin()) Kelola Artikel @elseif(auth()->user()->isEditor()) Antrean Artikel @else Artikel Saya @endif
                        </span>
                    </a>

                    @can('create', App\Models\Article::class)
                    <a href="{{ route('dashboard.articles.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard.articles.create') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-850 hover:text-slate-200' }}">
                        <i class="fa-solid fa-pen-nib w-5"></i>
                        <span>Tulis Artikel Baru</span>
                    </a>
                    @endcan

                    <!-- Moderator Menu (Admins) -->
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard.comments.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard.comments.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-850 hover:text-slate-200' }}">
                        <i class="fa-solid fa-comments w-5"></i>
                        <span>Komentar</span>
                    </a>
                    @endif

                    <!-- Administrator Menu -->
                    @if(auth()->user()->isAdmin())
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block px-3 pt-6 mb-2">Admin Tools</span>
                    
                    <a href="{{ route('dashboard.categories.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard.categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-850 hover:text-slate-200' }}">
                        <i class="fa-solid fa-folder w-5"></i>
                        <span>Kategori</span>
                    </a>

                    <a href="{{ route('dashboard.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-850 hover:text-slate-200' }}">
                        <i class="fa-solid fa-users w-5"></i>
                        <span>Pengguna</span>
                    </a>
                    @endif
                </nav>
            </div>

            <!-- Profile Widget bottom -->
            <div class="p-6 border-t border-slate-800">
                <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 mb-4 group">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-700 group-hover:border-indigo-500 transition">
                    @else
                        <div class="w-9 h-9 bg-indigo-600 rounded-full flex items-center justify-center font-bold text-white text-sm group-hover:bg-indigo-500 transition">
                            {{ auth()->user()->initial }}
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <h6 class="text-xs font-bold text-slate-200 truncate group-hover:text-indigo-400 transition">{{ auth()->user()->name }}</h6>
                        <span class="text-[10px] font-semibold text-indigo-400 uppercase">{{ auth()->user()->role }}</span>
                    </div>
                </a>
                
                <a href="{{ route('logout.get') }}" class="w-full bg-slate-900 border border-slate-850 hover:bg-slate-850 hover:text-red-400 text-slate-400 text-xs font-bold py-2 rounded-xl transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Sign Out</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-grow lg:pl-64 flex flex-col min-h-screen">
            <!-- Top Navbar Header -->
            <header class="h-20 bg-[#0d1321]/60 border-b border-slate-850 flex items-center justify-between px-6 sm:px-8">
                <!-- Hamburger menu button -->
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-300 hover:text-white">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                
                <!-- Page Title -->
                <h2 class="text-lg font-bold text-white hidden sm:block">
                    @yield('header_title', 'CMS Portal')
                </h2>

                <div class="flex items-center space-x-4 ml-auto">
                    @if(auth()->user()->isJournalist())
                    <!-- Notification Bell (Journalist only) -->
                    <div id="notif-bell" class="relative text-slate-400 hover:text-amber-400 transition cursor-pointer" title="Notifikasi Revisi">
                        <i class="fa-solid fa-bell text-lg"></i>
                        <span id="notif-badge" class="hidden absolute -top-1.5 -right-1.5 w-4 h-4 bg-red-500 rounded-full text-[9px] font-bold text-white flex items-center justify-center">!</span>
                    </div>
                    @endif
                    <!-- Guest link -->
                    <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-200 text-sm font-semibold transition flex items-center space-x-1" target="_blank">
                        <span>Lihat Portal</span>
                        <i class="fa-solid fa-up-right-from-square text-xs"></i>
                    </a>
                </div>
            </header>

            <!-- Main Body -->
            <main class="flex-grow p-6 sm:p-8">
                <!-- Success Alert -->
                @if(session('success'))
                    <div class="bg-indigo-600/10 border border-indigo-500/20 text-indigo-300 p-4 rounded-xl mb-6 flex items-start space-x-3 text-sm">
                        <i class="fa-solid fa-circle-check mt-0.5 text-indigo-400"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Errors Alert -->
                @if($errors->any())
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl mb-6 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-start space-x-2">
                                <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-400"></i>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @yield('dashboard_content')
            </main>
        </div>

        {{-- ── Toast Notification Container (Real-time Broadcasting) ── --}}
        <div id="toast-container" class="fixed bottom-6 right-6 z-[999] flex flex-col gap-3 pointer-events-none"></div>

        <!-- Global Delete Confirmation Modal -->
        <x-delete-modal id="globalDeleteModal" />

        {{-- ── Broadcasting Listener Script (Journalist Only) ── --}}
        @if(auth()->user()->isJournalist())
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            /**
             * Menampilkan toast notifikasi di pojok kanan bawah layar.
             * @param {string} message  - Pesan teks notifikasi
             * @param {string} type     - 'warning' | 'success' | 'error'
             */
            function showToast(message, type = 'warning') {
                const container = document.getElementById('toast-container');
                const badge = document.getElementById('notif-badge');

                // Tampilkan badge di bell icon
                if (badge) badge.classList.remove('hidden');

                const colors = {
                    warning : 'bg-amber-500/20 border-amber-500/40 text-amber-300',
                    success : 'bg-emerald-500/20 border-emerald-500/40 text-emerald-300',
                    error   : 'bg-red-500/20 border-red-500/40 text-red-300',
                };
                const icons = {
                    warning : 'fa-triangle-exclamation',
                    success : 'fa-circle-check',
                    error   : 'fa-circle-xmark',
                };

                const toast = document.createElement('div');
                toast.className = `pointer-events-auto flex items-start gap-3 border rounded-xl px-4 py-3 shadow-2xl text-sm max-w-sm backdrop-blur-sm transition-all duration-500 translate-y-4 opacity-0 ${colors[type] || colors.warning}`;
                toast.innerHTML = `
                    <i class="fa-solid ${icons[type] || icons.warning} mt-0.5 text-base shrink-0"></i>
                    <div>
                        <p class="font-bold mb-0.5">Notifikasi Redaksi</p>
                        <p class="leading-snug opacity-90">${message}</p>
                    </div>
                    <button onclick="this.closest('[class*=pointer]').remove()" class="ml-auto opacity-60 hover:opacity-100 shrink-0"><i class="fa-solid fa-xmark"></i></button>
                `;
                container.appendChild(toast);

                // Animasi masuk
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        toast.classList.remove('translate-y-4', 'opacity-0');
                    });
                });

                // Auto-remove setelah 8 detik
                setTimeout(() => {
                    toast.classList.add('translate-y-4', 'opacity-0');
                    setTimeout(() => toast.remove(), 500);
                }, 8000);
            }

            // ── Hubungkan ke Laravel Echo & Channel Privat Jurnalis ──
            // Broadcasting via Laravel Reverb/Pusher
            // Pastikan BROADCAST_CONNECTION=reverb di .env dan sudah install:broadcasting
            if (typeof window.Echo !== 'undefined') {
                window.Echo.private('journalist.{{ auth()->id() }}')
                    .listen('.revision.requested', (data) => {
                        showToast(data.message || 'Artikel Anda dikembalikan untuk revisi.', 'warning');
                    });
            } else {
                // Fallback: polling sederhana jika Echo belum terkonfigurasi
                // Notifikasi dari session jika ada
                console.info('[ContentImpact] Laravel Echo belum terkonfigurasi. Aktifkan broadcasting untuk real-time notifications.');
            }
        });
        </script>
        @endif
    </body>
</html>
