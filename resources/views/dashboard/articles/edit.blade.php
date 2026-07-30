@extends('layouts.dashboard')

@section('title', 'Edit Artikel')
@section('header_title', 'Edit Artikel')

@section('dashboard_content')
<div class="max-w-4xl mx-auto bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-lg">
    <!-- Revision Alert Panel -->
    @if($article->isRevisionRequired() && $article->revision_note)
    <div class="bg-red-500/10 border border-red-500/20 text-red-300 p-5 rounded-2xl mb-8 flex items-start space-x-3 text-sm">
        <i class="fa-solid fa-triangle-exclamation mt-0.5 text-lg text-red-400"></i>
        <div>
            <h4 class="font-bold text-red-400 mb-1">Catatan Revisi dari Editor:</h4>
            <p class="leading-relaxed text-slate-300 italic">"{{ $article->revision_note }}"</p>
            <p class="text-[10px] text-slate-500 mt-2">Silakan perbaiki artikel sesuai catatan di atas. Artikel akan otomatis dikirim kembali ke Antrean Editorial setelah Anda menekan tombol simpan.</p>
        </div>
    </div>
    @endif

    <div class="mb-8 border-b border-slate-800 pb-5 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Edit Artikel</h3>
            <p class="text-xs text-slate-500">Sesuaikan data berita Anda di bawah ini.</p>
        </div>
        <span class="text-xs uppercase font-bold px-3 py-1 rounded bg-slate-850 text-slate-400">
            Status: {{ $article->status }}
        </span>
    </div>

    <form action="{{ route('dashboard.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label for="title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Judul Artikel</label>
            <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition" placeholder="Tulis judul berita yang menarik...">
            @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                <select name="category_id" id="category_id" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 text-sm focus:outline-none focus:border-indigo-500 transition">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Cover Image -->
            <div>
                <label for="cover_image" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Cover Image (Max 2MB)</label>
                @if($article->cover_image)
                <div class="mb-3 flex items-center space-x-3">
                    <img src="{{ $article->cover_image_url }}" alt="Preview" class="w-16 h-10 object-cover rounded border border-slate-800">
                    <span class="text-[10px] text-slate-500">Gambar saat ini. Unggah baru untuk mengganti.</span>
                </div>
                @endif
                <input type="file" name="cover_image" id="cover_image" accept="image/*" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-slate-400 text-xs focus:outline-none focus:border-indigo-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition">
                @error('cover_image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Excerpt -->
        <div>
            <label for="excerpt" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Kutipan Ringkas (Excerpt)</label>
            <textarea name="excerpt" id="excerpt" rows="2" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition" placeholder="Ringkasan singkat berita... (opsional)">{{ old('excerpt', $article->excerpt) }}</textarea>
            @error('excerpt') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Content -->
        <div>
            <label for="content" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Konten Berita</label>
            <textarea name="content" id="content" rows="12" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 text-sm focus:outline-none focus:border-indigo-500 transition font-mono leading-relaxed" placeholder="Tulis berita lengkap di sini...">{{ old('content', $article->content) }}</textarea>
            @error('content') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Actions -->
        <div class="border-t border-slate-800 pt-6 flex justify-end items-center space-x-3">
            <a href="{{ route('dashboard.articles.index') }}" class="text-slate-400 hover:text-white text-xs font-bold px-5 py-3 rounded-xl border border-slate-800 transition">
                Batal
            </a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition shadow-lg shadow-indigo-600/10">
                Simpan & Perbarui
            </button>
        </div>
    </form>
</div>
@endsection
