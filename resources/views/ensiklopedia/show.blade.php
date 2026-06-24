@extends('layouts.app')

@section('title', $item->nama . ' - Ensiklopedia SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header with Back Button -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('ensiklopedia.index') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Detail Informasi</h1>
        </div>
        
        <span class="inline-block {{ $item->jenis === 'penyakit' ? 'bg-white/20' : 'bg-orange-500/20' }} text-white text-[10px] font-bold px-3 py-1 rounded-full mb-2">
            {{ $item->jenis === 'penyakit' ? 'Penyakit Padi' : 'Hama Padi' }}
        </span>
        <h2 class="text-2xl font-bold mt-1">{{ $item->nama }}</h2>
        @if($item->nama_latin)
            <span class="text-xs text-white/60 italic block mt-1">{{ $item->nama_latin }}</span>
        @endif
    </div>

    <!-- Image Container -->
    <div class="px-6 -mt-6 relative z-10">
        <div class="w-full h-48 rounded-2xl overflow-hidden shadow-lg border border-white/50 bg-neutral-100">
            @if(!$item->gambar || $item->gambar === 'placeholder')
                <div class="w-full h-full bg-[#E8F3ED] flex flex-col items-center justify-center p-4 text-center">
                    <svg class="w-8 h-8 text-[#A3B3AB] mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                    </svg>
                    <span class="text-xs text-[#A3B3AB] font-bold">{{ $item->nama }}</span>
                </div>
            @else
                <img src="{{ $item->gambar }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
            @endif
        </div>
    </div>

    <!-- Details Content -->
    <div class="px-6 mt-6 flex flex-col gap-6">
        
        <!-- Deskripsi -->
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-sm">
            <h3 class="font-bold text-neutral-800 text-sm mb-2 border-b pb-2 flex items-center gap-1.5">
                <span class="w-1 h-4 bg-[#0E4E37] rounded-full"></span>
                Deskripsi
            </h3>
            <p class="text-xs text-neutral-600 leading-relaxed font-light">{{ $item->deskripsi ?: 'Tidak ada deskripsi tersedia.' }}</p>
        </div>

        <!-- Penyebab -->
        @if($item->penyebab)
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-sm">
            <h3 class="font-bold text-neutral-800 text-sm mb-2 border-b pb-2 flex items-center gap-1.5">
                <span class="w-1 h-4 bg-[#0E4E37] rounded-full"></span>
                Penyebab Utama
            </h3>
            <p class="text-xs text-neutral-600 leading-relaxed font-light">{{ $item->penyebab }}</p>
        </div>
        @endif

        <!-- Gejala Terkait -->
        @if($symptoms->count() > 0)
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-sm">
            <h3 class="font-bold text-neutral-800 text-sm mb-3 border-b pb-2 flex items-center gap-1.5">
                <span class="w-1 h-4 bg-[#0E4E37] rounded-full"></span>
                Gejala yang Sering Muncul
            </h3>
            <ul class="flex flex-col gap-2">
                @foreach($symptoms as $g)
                    <li class="flex items-start gap-2.5">
                        <span class="bg-[#E2F2EB] text-[#0A3D2A] text-[10px] font-bold px-1.5 py-0.5 rounded mt-0.5 flex-shrink-0">
                            {{ $g->kode_gejala }}
                        </span>
                        <span class="text-xs text-neutral-600 leading-relaxed font-light">{{ $g->nama_gejala }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Cara Penanganan / Solusi -->
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-sm">
            <h3 class="font-bold text-[#0E4E37] text-sm mb-2 border-b pb-2 flex items-center gap-1.5">
                <span class="w-1 h-4 bg-[#0E4E37] rounded-full"></span>
                Solusi Penanganan (Rekomendasi Pakar)
            </h3>
            <p class="text-xs text-neutral-600 leading-relaxed font-light">{{ $item->solusi ?: 'Silakan konsultasikan ke petugas penyuluh pertanian setempat.' }}</p>
        </div>

        <!-- Pencegahan -->
        @if($item->pencegahan)
        <div class="bg-white p-5 rounded-2xl border border-[#D5EDE1] bg-[#EAF6F0]/30 shadow-sm">
            <h3 class="font-bold text-[#0A3D2A] text-sm mb-2 border-b border-[#D5EDE1] pb-2 flex items-center gap-1.5">
                <!-- Shield Icon -->
                <svg class="w-4 h-4 text-[#0A3D2A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Tindakan Pencegahan
            </h3>
            <p class="text-xs text-[#1D4733] leading-relaxed font-light">{{ $item->pencegahan }}</p>
        </div>
        @endif

    </div>
</div>
@endsection

@section('navigation')
    <x-bottom-nav active="ensiklopedia" />
@endsection
