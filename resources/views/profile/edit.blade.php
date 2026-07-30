@extends('layouts.dashboard')

@section('title', 'Edit Profil')
@section('header_title', 'Edit Profil')

@section('dashboard_content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Edit Profile Section --}}
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-3xl shadow-lg">
        <div class="mb-6">
            <h3 class="text-base font-bold text-slate-200 flex items-center space-x-2">
                <i class="fa-solid fa-user-pen text-indigo-400"></i>
                <span>Edit Profil</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">Ubah nama dan foto profil Anda. Email dan role tidak dapat diubah.</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- Current Avatar Preview --}}
            <div class="flex items-center space-x-6" x-data="{ previewUrl: null }">
                <div class="shrink-0">
                    @if($user->avatar_url)
                        <img x-show="!previewUrl" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-slate-700">
                    @else
                        <div x-show="!previewUrl" class="w-20 h-20 rounded-full bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold border-2 border-slate-700">
                            {{ $user->initial }}
                        </div>
                    @endif
                    <img x-show="previewUrl" :src="previewUrl" class="w-20 h-20 rounded-full object-cover border-2 border-indigo-500" style="display: none;">
                </div>
                <div>
                    <label for="profile_photo" class="cursor-pointer bg-slate-800 hover:bg-slate-750 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl transition inline-flex items-center space-x-2 border border-slate-700 hover:border-indigo-500">
                        <i class="fa-solid fa-camera"></i>
                        <span>Pilih Foto Baru</span>
                    </label>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="hidden"
                        @change="if ($event.target.files[0]) { previewUrl = URL.createObjectURL($event.target.files[0]) }">
                    <p class="text-[10px] text-slate-600 mt-2">JPG, PNG, GIF, atau WebP. Maksimal 2MB.</p>
                    @error('profile_photo')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition">
                @error('name')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email (Read-only) --}}
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email <span class="text-slate-600">(Tidak dapat diubah)</span></label>
                <input type="email" id="email" value="{{ $user->email }}" disabled
                    class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-500 text-sm cursor-not-allowed">
            </div>

            {{-- Role (Read-only) --}}
            <div>
                <label for="role" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Role <span class="text-slate-600">(Tidak dapat diubah)</span></label>
                <input type="text" id="role" value="{{ ucfirst($user->role) }}" disabled
                    class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-500 text-sm cursor-not-allowed capitalize">
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t border-slate-850">
                <a href="{{ route('profile.show') }}" class="text-slate-400 hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-800 transition">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password Section --}}
    <div id="change-password" class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-3xl shadow-lg">
        <div class="mb-6">
            <h3 class="text-base font-bold text-slate-200 flex items-center space-x-2">
                <i class="fa-solid fa-lock text-amber-400"></i>
                <span>Ganti Password</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">Pastikan akun Anda menggunakan password yang kuat dan aman.</p>
        </div>

        <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password Lama</label>
                <input type="password" name="current_password" id="current_password" required
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition"
                    placeholder="Masukkan password lama">
                @error('current_password')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password Baru</label>
                <input type="password" name="password" id="password" required
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition"
                    placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition"
                    placeholder="Ulangi password baru">
            </div>

            <div class="flex items-center space-x-3 pt-4 border-t border-slate-850">
                <a href="{{ route('profile.show') }}" class="text-slate-400 hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-800 transition">
                    Batal
                </a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
