@extends('layouts.dashboard')

@section('title', 'Kelola Kategori')
@section('header_title', 'Kategori')

@section('dashboard_content')
<div class="space-y-6" x-data="{ createModalOpen: false, editModalOpen: false, editCategory: { id: '', name: '', slug: '', description: '' } }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-200">Manajemen Kategori</h3>
            <p class="text-xs text-slate-500">Kelola kategori publikasi berita platform ContentImpact.</p>
        </div>
        
        <button @click="createModalOpen = true" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Kategori</span>
        </button>
    </div>

    <!-- Categories List Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 bg-slate-900/40">
            <h4 class="text-sm font-bold text-slate-200">Daftar Kategori</h4>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-950 text-slate-300 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-slate-850/40 transition">
                        <td class="px-6 py-4 font-semibold text-slate-200">{{ $cat->name }}</td>
                        <td class="px-6 py-4 text-xs text-indigo-400 font-mono">{{ $cat->slug }}</td>
                        <td class="px-6 py-4 text-xs max-w-sm truncate">{{ $cat->description ?: '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-3">
                                <!-- Trigger Edit Modal -->
                                <button 
                                    @click="editCategory = { id: '{{ $cat->id }}', name: '{{ addslashes($cat->name) }}', slug: '{{ addslashes($cat->slug) }}', description: '{{ addslashes($cat->description) }}' }; editModalOpen = true;"
                                    class="text-indigo-400 hover:text-indigo-300 text-xs transition" 
                                    title="Edit Kategori"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <!-- Delete category form -->
                                <button 
                                    @click="$dispatch('open-delete-modal', { id: 'globalDeleteModal', actionUrl: '{{ route('dashboard.categories.destroy', $cat->id) }}', title: 'Hapus Kategori', message: 'Apakah Anda yakin ingin menghapus kategori {{ addslashes($cat->name) }}? Semua artikel di dalamnya akan ikut terhapus.' })"
                                    class="text-red-400 hover:text-red-300 text-xs transition" 
                                    title="Hapus Kategori"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-600">Belum ada kategori yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div 
        x-show="createModalOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
        x-transition
        style="display: none;"
    >
        <div @click.away="createModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-slate-850">
                <h4 class="text-base font-bold text-white">Tambah Kategori Baru</h4>
                <button @click="createModalOpen = false" class="text-slate-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('dashboard.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition" placeholder="Contoh: Otomotif">
                </div>

                <div>
                    <label for="slug" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Slug (Opsional)</label>
                    <input type="text" name="slug" id="slug" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition" placeholder="Contoh: otomotif (dibuat otomatis jika kosong)">
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 placeholder-slate-700 transition" placeholder="Tulis deskripsi kategori ringkas..."></textarea>
                </div>

                <div class="flex items-center space-x-3 justify-end pt-4 border-t border-slate-850">
                    <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-white text-xs font-bold px-4 py-2 rounded-xl border border-slate-800 transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2 rounded-xl transition">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div 
        x-show="editModalOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
        x-transition
        style="display: none;"
    >
        <div @click.away="editModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-slate-850">
                <h4 class="text-base font-bold text-white">Edit Kategori</h4>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="'{{ route('dashboard.categories.index') }}/' + editCategory.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="edit_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="edit_name" x-model="editCategory.name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div>
                    <label for="edit_slug" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Slug</label>
                    <input type="text" name="slug" id="edit_slug" x-model="editCategory.slug" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div>
                    <label for="edit_description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Deskripsi</label>
                    <textarea name="description" id="edit_description" rows="3" x-model="editCategory.description" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition"></textarea>
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
