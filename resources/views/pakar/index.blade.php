@extends('layouts.app')

@section('title', 'Dashboard Pakar - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('beranda') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Dashboard Pakar</h1>
        </div>
        <p class="text-white/80 text-xs font-light">
            Kelola data dasar gejala, diagnosa (hama & penyakit), basis aturan (rules) Certainty Factor, dan artikel ensiklopedia.
        </p>
    </div>

    <!-- Management Sections -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col gap-4">
        
        <!-- Section 1: Basis Pengetahuan -->
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-md">
            <h3 class="font-bold text-neutral-800 text-sm mb-3 border-b pb-2 flex items-center gap-1.5">
                <span class="w-1.5 h-4 bg-[#0E4E37] rounded-full"></span>
                Basis Pengetahuan
            </h3>
            
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-150 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-neutral-700 block">Penyakit Padi</span>
                        <span class="text-[10px] text-neutral-400 font-light mt-0.5 leading-snug">Kelola deskripsi, solusi, penyebab & gambar</span>
                    </div>
                    <button class="mt-4 text-left text-xs font-bold text-[#0E4E37] hover:underline flex items-center gap-1">
                        Kelola data &rarr;
                    </button>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-150 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-neutral-700 block">Hama Padi</span>
                        <span class="text-[10px] text-neutral-400 font-light mt-0.5 leading-snug">Kelola deskripsi, solusi, penyebab & gambar</span>
                    </div>
                    <button class="mt-4 text-left text-xs font-bold text-[#0E4E37] hover:underline flex items-center gap-1">
                        Kelola data &rarr;
                    </button>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-150 flex flex-col justify-between col-span-2">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-xs font-bold text-neutral-700 block">Daftar Gejala</span>
                            <span class="text-[10px] text-neutral-400 font-light leading-snug">Tambah, ubah, dan hapus kode serta nama gejala</span>
                        </div>
                        <button class="text-xs font-bold text-[#0E4E37] hover:underline">Kelola &rarr;</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Rule Certainty Factor -->
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-md">
            <h3 class="font-bold text-neutral-800 text-sm mb-3 border-b pb-2 flex items-center gap-1.5">
                <span class="w-1.5 h-4 bg-[#D85C30] rounded-full"></span>
                Basis Aturan (Rules CF)
            </h3>
            <p class="text-xs text-neutral-500 font-light leading-relaxed mb-4">
                Petakan hubungan gejala dengan penyakit/hama dan atur bobot keyakinan pakar (CF Pakar) antara 0.0 s/d 1.0.
            </p>
            <div class="bg-neutral-50 p-3 rounded-xl border border-neutral-150 flex justify-between items-center">
                <div>
                    <span class="text-xs font-bold text-neutral-700 block">Tabel Rule CF</span>
                    <span class="text-[10px] text-neutral-400 font-light leading-snug">Kelola keterkaitan gejala dengan diagnosa</span>
                </div>
                <button class="text-xs font-bold text-[#D85C30] hover:underline">Buka Rule &rarr;</button>
            </div>
        </div>

        <!-- Section 3: Artikel & Tips -->
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-md">
            <h3 class="font-bold text-neutral-800 text-sm mb-3 border-b pb-2 flex items-center gap-1.5">
                <span class="w-1.5 h-4 bg-[#2F8966] rounded-full"></span>
                Ensiklopedia & Tips
            </h3>
            <div class="flex flex-col gap-3">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-neutral-700">Daftar Tips Deteksi Dini</span>
                    <button class="font-bold text-[#0E4E37] hover:underline">Edit Tips &rarr;</button>
                </div>
                <div class="flex justify-between items-center text-xs border-t pt-2.5">
                    <span class="text-neutral-700">Artikel Edukasi Padi</span>
                    <button class="font-bold text-[#0E4E37] hover:underline">Kelola &rarr;</button>
                </div>
            </div>
        </div>

        <a href="{{ route('beranda') }}" class="w-full text-center bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-xs font-bold py-3.5 rounded-full transition my-4">
            Kembali ke Beranda
        </a>

    </div>
</div>
@endsection

@section('navigation')
    <x-bottom-nav active="beranda" />
@endsection
