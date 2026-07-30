@extends('layouts.dashboard')

@section('title', 'Kelola Pengguna')
@section('header_title', 'Pengguna')

@section('dashboard_content')
<div class="space-y-6" x-data="{ createModalOpen: false, editModalOpen: false, editUser: { id: '', name: '', email: '', role: '' } }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-200">Manajemen Pengguna</h3>
            <p class="text-xs text-slate-500">Kelola contributor, editor, jurnalis, dan hak akses platform ContentImpact.</p>
        </div>
        
        <button @click="createModalOpen = true" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition flex items-center space-x-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 bg-slate-900/40">
            <h4 class="text-sm font-bold text-slate-200">Daftar Pengguna</h4>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-950 text-slate-300 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Peran (Role)</th>

                        <th class="px-6 py-4">Tanggal Bergabung</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-850/40 transition">
                        <td class="px-6 py-4 font-semibold text-slate-200 flex items-center space-x-3">
                            <div class="w-8 h-8 bg-slate-850 text-slate-300 rounded-full flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span>{{ $user->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs uppercase font-bold tracking-wider px-2.5 py-0.5 rounded-md @if($user->isAdmin()) bg-indigo-500/10 text-indigo-400 @elseif($user->isEditor()) bg-pink-500/10 text-pink-400 @else bg-cyan-500/10 text-cyan-400 @endif">
                                {{ $user->role }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-xs">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-3">
                                <!-- Trigger Edit Modal -->
                                <button 
                                    @click="editUser = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email) }}', role: '{{ $user->role }}' }; editModalOpen = true;"
                                    class="text-indigo-400 hover:text-indigo-300 text-xs transition" 
                                    title="Edit Pengguna"
                                >
                                    <i class="fa-solid fa-user-pen"></i>
                                </button>

                                <!-- Delete User -->
                                @if(auth()->id() !== $user->id)
                                <button 
                                    @click="$dispatch('open-delete-modal', { id: 'globalDeleteModal', actionUrl: '{{ route('dashboard.users.destroy', $user->id) }}', title: 'Hapus Pengguna', message: 'Apakah Anda yakin ingin menghapus pengguna {{ addslashes($user->name) }}?' })"
                                    class="text-red-400 hover:text-red-300 text-xs transition" 
                                    title="Hapus Pengguna"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-600">Belum ada pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Modal -->
    <div 
        x-show="createModalOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
        x-transition
        style="display: none;"
    >
        <div @click.away="createModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-slate-850">
                <h4 class="text-base font-bold text-white">Tambah Pengguna Baru</h4>
                <button @click="createModalOpen = false" class="text-slate-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('dashboard.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition" placeholder="Contoh: Reyhan Aditama">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" id="email" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition" placeholder="Contoh: reyhan@mail.com">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" id="password" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition" placeholder="Minimal 6 karakter">
                </div>

                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Peran (Role)</label>
                    <select name="role" id="role" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-350 text-xs focus:outline-none focus:border-indigo-500 transition">
                        <option value="journalist" selected>Jurnalis</option>
                        <option value="editor">Editor</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <div class="flex items-center space-x-3 justify-end pt-4 border-t border-slate-850">
                    <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-white text-xs font-bold px-4 py-2 rounded-xl border border-slate-800 transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2 rounded-xl transition">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div 
        x-show="editModalOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
        x-transition
        style="display: none;"
    >
        <div @click.away="editModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-slate-850">
                <h4 class="text-base font-bold text-white">Edit Pengguna</h4>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'{{ route('dashboard.users.index') }}/' + editUser.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="edit_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name" x-model="editUser.name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div>
                    <label for="edit_email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" id="edit_email" x-model="editUser.email" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div>
                    <label for="edit_password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password Baru (Opsional)</label>
                    <input type="password" name="password" id="edit_password" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition" placeholder="Biarkan kosong jika tidak diganti">
                </div>

                <div>
                    <label for="edit_role" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Peran (Role)</label>
                    <select name="role" id="edit_role" x-model="editUser.role" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-360 text-xs focus:outline-none focus:border-indigo-500 transition">
                        <option value="journalist">Jurnalis</option>
                        <option value="editor">Editor</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <div class="flex items-center space-x-3 justify-end pt-4 border-t border-slate-850">
                    <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-white text-xs font-bold px-4 py-2 rounded-xl border border-slate-800 transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2 rounded-xl transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
