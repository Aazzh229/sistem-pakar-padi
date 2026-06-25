@extends('layouts.app')

@section('title', 'Tambah Artikel Library - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('pakar.library.index') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Tambah Artikel Library</h1>
        </div>
        <p class="text-white/80 text-xs font-light">
            Tambahkan materi edukasi hama & penyakit baru untuk membantu petani dalam bertani secara produktif.
        </p>
    </div>

    <!-- Library Form -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col mb-8">
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-lg">
            
            <form action="{{ route('pakar.library.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf

                <!-- Jenis -->
                <div class="flex flex-col gap-1.5">
                    <label for="jenis" class="text-xs font-bold text-neutral-700">Kategori Artikel</label>
                    <select id="jenis" name="jenis" required 
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                        <option value="penyakit">Penyakit</option>
                        <option value="hama">Hama</option>
                    </select>
                </div>

                <!-- Nama -->
                <div class="flex flex-col gap-1.5">
                    <label for="nama" class="text-xs font-bold text-neutral-700">Nama Penyakit / Hama</label>
                    <input type="text" id="nama" name="nama" required placeholder="Contoh: Blast Padi"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                </div>

                <!-- Nama Latin -->
                <div class="flex flex-col gap-1.5">
                    <label for="nama_latin" class="text-xs font-bold text-neutral-700">Nama Latin (Scientific Name)</label>
                    <input type="text" id="nama_latin" name="nama_latin" placeholder="Contoh: Pyricularia oryzae"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                </div>

                <!-- Deskripsi -->
                <div class="flex flex-col gap-1.5">
                    <label for="deskripsi" class="text-xs font-bold text-neutral-700">Deskripsi Singkat</label>
                    <textarea id="deskripsi" name="deskripsi" required rows="3" placeholder="Tuliskan gambaran umum penyakit/hama..."
                              class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    ></textarea>
                </div>

                <!-- Penyebab -->
                <div class="flex flex-col gap-1.5">
                    <label for="penyebab" class="text-xs font-bold text-neutral-700">Penyebab Utama</label>
                    <input type="text" id="penyebab" name="penyebab" placeholder="Contoh: Infeksi Jamur"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                </div>

                <!-- Solusi -->
                <div class="flex flex-col gap-1.5">
                    <label for="solusi" class="text-xs font-bold text-neutral-700">Solusi Penanganan</label>
                    <textarea id="solusi" name="solusi" required rows="3" placeholder="Cara mengatasi serangan..."
                              class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    ></textarea>
                </div>

                <!-- Pencegahan -->
                <div class="flex flex-col gap-1.5">
                    <label for="pencegahan" class="text-xs font-bold text-neutral-700">Tindakan Pencegahan</label>
                    <textarea id="pencegahan" name="pencegahan" rows="3" placeholder="Cara mencegah serangan terjadi..."
                              class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    ></textarea>
                </div>

                <!-- Gambar -->
                <div class="flex flex-col gap-1.5">
                    <label for="gambar" class="text-xs font-bold text-neutral-700">URL Gambar Ilustrasi</label>
                    <input type="url" id="gambar" name="gambar" placeholder="Contoh: https://unsplash.com/..."
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 rounded-full shadow-md transition mt-2 flex justify-center items-center"
                >
                    Simpan Artikel Library
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
