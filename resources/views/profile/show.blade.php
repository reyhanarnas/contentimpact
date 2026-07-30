@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('header_title', 'Profil')

@section('dashboard_content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Profile Card --}}
    <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-lg overflow-hidden">
        {{-- Banner --}}
        <div class="h-32 bg-gradient-to-br from-indigo-600/30 via-slate-900 to-slate-900 relative">
            <div class="absolute -bottom-12 left-8">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full border-4 border-slate-900 object-cover shadow-lg">
                @else
                    <div class="w-24 h-24 rounded-full border-4 border-slate-900 bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ $user->initial }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Profile Info --}}
        <div class="pt-16 pb-8 px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-400 font-mono mt-1">{{ $user->email }}</p>
                    <div class="flex items-center space-x-3 mt-3">
                        <span class="text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-md
                            @if($user->isAdmin()) bg-indigo-500/10 text-indigo-400
                            @elseif($user->isEditor()) bg-pink-500/10 text-pink-400
                            @else bg-cyan-500/10 text-cyan-400 @endif">
                            {{ $user->role }}
                        </span>
                        <span class="text-xs text-slate-500">
                            <i class="fa-solid fa-calendar-days mr-1"></i>
                            Bergabung {{ $user->created_at->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-3 mt-4 sm:mt-0">
                    <a href="{{ route('profile.edit') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition flex items-center space-x-2">
                        <i class="fa-solid fa-user-pen"></i>
                        <span>Edit Profil</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Account Info --}}
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow">
            <h4 class="text-sm font-bold text-slate-200 mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-circle-info text-indigo-400"></i>
                <span>Informasi Akun</span>
            </h4>
            <div class="space-y-4">
                <div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Nama Lengkap</span>
                    <span class="text-sm text-slate-200 font-semibold">{{ $user->name }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Email</span>
                    <span class="text-sm text-slate-200 font-mono">{{ $user->email }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Role</span>
                    <span class="text-sm text-slate-200 font-semibold capitalize">{{ $user->role }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Tanggal Bergabung</span>
                    <span class="text-sm text-slate-200">{{ $user->created_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow">
            <h4 class="text-sm font-bold text-slate-200 mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-bolt text-indigo-400"></i>
                <span>Pengaturan Akun</span>
            </h4>
            <div class="space-y-3">
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 bg-slate-950 rounded-xl border border-slate-800 hover:border-indigo-500 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-600/10 p-2.5 rounded-lg text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition">
                            <i class="fa-solid fa-user-pen text-sm"></i>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-200 block">Edit Profil</span>
                            <span class="text-[10px] text-slate-500">Ubah nama dan foto profil</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-600 group-hover:text-indigo-400 transition"></i>
                </a>

                <a href="{{ route('profile.edit') }}#change-password" class="flex items-center justify-between p-4 bg-slate-950 rounded-xl border border-slate-800 hover:border-indigo-500 transition group">
                    <div class="flex items-center space-x-3">
                        <div class="bg-amber-500/10 p-2.5 rounded-lg text-amber-400 group-hover:bg-amber-600 group-hover:text-white transition">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-200 block">Change Password</span>
                            <span class="text-[10px] text-slate-500">Perbarui password akun Anda</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-600 group-hover:text-indigo-400 transition"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
