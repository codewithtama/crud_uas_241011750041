@extends('layouts.app')

@section('title', 'Tambah Film Baru')

@section('content')
<div class="max-w-2xl mx-auto px-4 pt-24 sm:pt-28 space-y-6">
    <!-- Breadcrumb & Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-400 hover:text-netflix-red transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-zinc-900 border border-zinc-800 p-6 sm:p-8 rounded-lg relative overflow-hidden shadow-2xl">
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-netflix-red/5 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Title -->
        <div class="border-b border-zinc-800 pb-5 mb-6">
            <h2 class="text-2xl font-bold font-bebas tracking-wide text-white flex items-center gap-2.5">
                <i class="fa-solid fa-square-plus text-netflix-red"></i> Tambah Film Baru
            </h2>
            <p class="text-xs text-slate-400 mt-1">
                Masukkan detail informasi film bioskop secara lengkap. Seluruh kolom wajib diisi.
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Judul Film -->
                <div class="sm:col-span-2">
                    <label for="judul" class="block text-xs font-semibold text-slate-400 mb-1.5">Judul Film</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required placeholder="Contoh: Interstellar"
                        @class([
                            'block w-full px-3.5 py-2.5 bg-[#141414] border rounded text-slate-100 placeholder-zinc-600 focus:outline-none focus:ring-2 transition-all text-sm',
                            'border-netflix-red focus:ring-netflix-red/30' => $errors->has('judul'),
                            'border-zinc-800 focus:ring-netflix-red/30' => !$errors->has('judul'),
                        ])>
                    @error('judul')
                        <p class="mt-1.5 text-xs text-netflix-red flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Genre -->
                <div>
                    <label for="genre" class="block text-xs font-semibold text-slate-400 mb-1.5">Genre</label>
                    <div class="relative">
                        <select name="genre" id="genre" required
                            @class([
                                'block w-full px-3.5 py-2.5 bg-[#141414] border rounded text-slate-100 placeholder-zinc-600 focus:outline-none focus:ring-2 transition-all text-sm appearance-none cursor-pointer pr-10',
                                'border-netflix-red focus:ring-netflix-red/30' => $errors->has('genre'),
                                'border-zinc-800 focus:ring-netflix-red/30' => !$errors->has('genre'),
                            ])>
                            <option value="" disabled {{ empty(old('genre')) ? 'selected' : '' }} class="bg-zinc-900 text-slate-400">Pilih Genre</option>
                            @foreach(['Action', 'Sci-Fi', 'Drama', 'Comedy', 'Horror', 'Romance', 'Thriller', 'Anime', 'Adventure', 'Fantasy'] as $g)
                                <option value="{{ $g }}" {{ old('genre') === $g ? 'selected' : '' }} class="bg-zinc-900 text-slate-100">{{ $g }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('genre')
                        <p class="mt-1.5 text-xs text-netflix-red flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Sutradara -->
                <div>
                    <label for="sutradara" class="block text-xs font-semibold text-slate-400 mb-1.5">Sutradara</label>
                    <input type="text" name="sutradara" id="sutradara" value="{{ old('sutradara') }}" required placeholder="Contoh: Christopher Nolan"
                        @class([
                            'block w-full px-3.5 py-2.5 bg-[#141414] border rounded text-slate-100 placeholder-zinc-600 focus:outline-none focus:ring-2 transition-all text-sm',
                            'border-netflix-red focus:ring-netflix-red/30' => $errors->has('sutradara'),
                            'border-zinc-800 focus:ring-netflix-red/30' => !$errors->has('sutradara'),
                        ])>
                    @error('sutradara')
                        <p class="mt-1.5 text-xs text-netflix-red flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Tahun Rilis -->
                <div>
                    <label for="tahun_rilis" class="block text-xs font-semibold text-slate-400 mb-1.5">Tahun Rilis</label>
                    <input type="number" name="tahun_rilis" id="tahun_rilis" value="{{ old('tahun_rilis', date('Y')) }}" required min="1800" max="2100"
                        @class([
                            'block w-full px-3.5 py-2.5 bg-[#141414] border rounded text-slate-100 placeholder-zinc-600 focus:outline-none focus:ring-2 transition-all text-sm',
                            'border-netflix-red focus:ring-netflix-red/30' => $errors->has('tahun_rilis'),
                            'border-zinc-800 focus:ring-netflix-red/30' => !$errors->has('tahun_rilis'),
                        ])>
                    @error('tahun_rilis')
                        <p class="mt-1.5 text-xs text-netflix-red flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Gambar/Poster Film -->
                <div>
                    <label for="gambar" class="block text-xs font-semibold text-slate-400 mb-1.5">Gambar Poster</label>
                    <input type="file" name="gambar" id="gambar" required accept="image/*"
                        @class([
                            'block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-zinc-800 file:text-slate-200 hover:file:bg-zinc-700 cursor-pointer focus:outline-none bg-[#141414] border p-1 rounded transition-all',
                            'border-netflix-red' => $errors->has('gambar'),
                            'border-zinc-800' => !$errors->has('gambar'),
                        ])>
                    @error('gambar')
                        <p class="mt-1.5 text-xs text-netflix-red flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-800">
                <a href="{{ route('admin.index') }}" class="px-5 py-2.5 bg-[#141414] border border-zinc-800 text-slate-300 hover:bg-zinc-800 text-xs font-semibold rounded transition-colors cursor-pointer">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-netflix-red hover:bg-[#b81d24] text-white text-xs font-bold rounded transition-all shadow-md shadow-netflix-red/10 cursor-pointer">
                    Simpan Film <i class="fa-solid fa-floppy-disk ml-1.5"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

