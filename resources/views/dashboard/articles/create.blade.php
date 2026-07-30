@extends('layouts.dashboard')

@section('title', 'Tulis Artikel Baru')
@section('header_title', 'Tulis Artikel')

@section('dashboard_content')
<div class="max-w-4xl mx-auto bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-lg">
    <div class="mb-8 border-b border-slate-800 pb-5">
        <h3 class="text-lg font-bold text-white">Buat Artikel Baru</h3>
        <p class="text-xs text-slate-500">Tulis gagasan Anda dan terbitkan karya jurnalistik berkualitas.</p>
    </div>

    <form action="{{ route('dashboard.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <label for="title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Judul Artikel</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition" placeholder="Tulis judul berita yang menarik...">
            @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                <select name="category_id" id="category_id" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 text-sm focus:outline-none focus:border-indigo-500 transition">
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Cover Image -->
            <div>
                <label for="cover_image" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Cover Image (Max 2MB)</label>
                <input type="file" name="cover_image" id="cover_image" accept="image/*" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-slate-400 text-xs focus:outline-none focus:border-indigo-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition">
                @error('cover_image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Excerpt -->
        <div>
            <label for="excerpt" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kutipan Ringkas (Excerpt)</label>
            <textarea name="excerpt" id="excerpt" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition" placeholder="Ringkasan singkat berita untuk card halaman depan. Jika kosong, sistem akan mengambil beberapa paragraf dari konten..."></textarea>
            @error('excerpt') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Content -->
        <div>
            <label for="content" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Konten Berita</label>
            <textarea name="content" id="content" rows="12" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition font-mono leading-relaxed" placeholder="Tulis berita lengkap di sini..."></textarea>
            @error('content') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Options -->
        <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <label for="status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pilihan Penyimpanan</label>
                <select name="status" id="status" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition">
                    <option value="draft">Simpan Sebagai Draft</option>
                    <option value="pending_review">Kirim untuk Direview Editor</option>
                </select>
            </div>

            <div class="flex items-center space-x-3 w-full sm:w-auto justify-end">
                <a href="{{ route('dashboard.articles.index') }}" class="text-slate-400 hover:text-white text-xs font-bold px-5 py-3 rounded-xl border border-slate-800 transition">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition shadow-lg shadow-indigo-600/10 w-full sm:w-auto text-center">
                    Simpan Artikel
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
