@extends('layouts.app')

@section('title', 'Deteksi Hama & Penyakit Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex justify-between items-center mb-2">
            <h1 class="text-2xl font-bold">Deteksi Sistem Pakar</h1>
            @if(Auth::check() && (Auth::user()->role === 'pakar' || Auth::user()->role === 'admin'))
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('pakar.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white text-[10px] font-bold px-3 py-1.5 rounded-full transition">
                    Dashboard
                </a>
            @endif
        </div>
        <p class="text-white/80 text-xs mt-1.5 font-light">
            Identifikasi hama dan penyakit tanaman padi menggunakan metode Certainty Factor.
        </p>
    </div>

    <!-- Instruction Card -->
    <div class="px-6 -mt-4 relative z-10">
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-neutral-100 flex flex-col items-center text-center">
            <!-- Icon -->
            <div class="bg-[#E2F2EB] text-[#0A3D2A] p-4 rounded-full mb-4">
                <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18h8" />
                    <path d="M3 22h18" />
                    <path d="M14 22a7 7 0 1 0-14 0" />
                    <path d="M9 14h2" />
                    <path d="M9 12a3 3 0 0 1-3-3V6a3 3 0 0 1 6 0v3a3 3 0 0 1-3 3Z" />
                    <path d="M12 6h4" />
                    <path d="M14 6v8a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3a2 2 0 0 0-2 2Z" />
                </svg>
            </div>
            
            <h2 class="text-base font-bold text-neutral-800 mb-2">Petunjuk Diagnosa</h2>
            <p class="text-xs text-neutral-500 leading-relaxed font-light mb-6 max-w-xs">
                Pilih 1 sampai 3 gejala yang paling tampak pada tanaman padi Anda, kemudian tentukan tingkat keyakinan Anda.
            </p>

            <div class="w-full flex flex-col gap-3 text-left mb-6">
                <div class="flex gap-2.5 items-start">
                    <span class="w-5 h-5 bg-[#0E4E37] text-white rounded-full flex items-center justify-center text-[10px] font-bold mt-0.5 flex-shrink-0">1</span>
                    <p class="text-xs text-neutral-600 leading-normal font-light">Cari dan pilih 1 sampai 3 gejala pada daftar (bisa dicari atau difilter berdasarkan kategori).</p>
                </div>
                <div class="flex gap-2.5 items-start">
                    <span class="w-5 h-5 bg-[#0E4E37] text-white rounded-full flex items-center justify-center text-[10px] font-bold mt-0.5 flex-shrink-0">2</span>
                    <p class="text-xs text-neutral-600 leading-normal font-light">Tentukan tingkat keyakinan (0.1 s/d 1.0) untuk masing-masing gejala yang dipilih.</p>
                </div>
                <div class="flex gap-2.5 items-start">
                    <span class="w-5 h-5 bg-[#0E4E37] text-white rounded-full flex items-center justify-center text-[10px] font-bold mt-0.5 flex-shrink-0">3</span>
                    <p class="text-xs text-neutral-600 leading-normal font-light">Tekan tombol "Mulai Analisa Diagnosa" untuk melihat hasil analisis Certainty Factor.</p>
                </div>
            </div>

            <!-- Start Button -->
            <form action="{{ route('deteksi.start') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 px-6 rounded-full transition shadow-md flex items-center justify-center gap-2">
                    Mulai Diagnosa
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('navigation')
    <x-bottom-nav active="deteksi" />
@endsection
