@extends('layouts.app')

@section('title', 'SiPakar Padi - Sistem Pakar Hama & Penyakit Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    
    <!-- HERO SECTION -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-14 rounded-b-[2rem] flex flex-col relative text-white">
        
        <!-- App Identity & Logout -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <!-- Leaf SVG -->
                <svg class="w-6 h-6 text-[#3CD070] mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 3.5 1 8.8C19 16 15 20 11 20z"></path>
                    <path d="M19 2c-2.26 3.19-5.27 6.42-8 8"></path>
                </svg>
                <span class="text-base font-semibold tracking-wide text-white/95">SiPakar Padi</span>
            </div>
            
            <div class="flex items-center gap-3">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white text-[10px] font-bold px-3 py-1 rounded-full transition">Admin</a>
                @elseif(Auth::user()->role === 'pakar')
                    <a href="{{ route('pakar.dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white text-[10px] font-bold px-3 py-1 rounded-full transition">Pakar</a>
                @endif
                
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-white/80 hover:text-white text-xs font-semibold flex items-center gap-1.5">
                        Keluar
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Greeting -->
        <span class="text-xs font-medium text-[#3CD070] tracking-wider uppercase mb-1">Halo, {{ Auth::user()->name }}!</span>

        <!-- Heading Titles -->
        <h1 class="text-3xl font-bold leading-tight tracking-tight">Sistem Pakar</h1>
        <h2 class="text-3xl font-bold leading-tight tracking-tight mt-1 mb-4">Hama & Penyakit Padi</h2>

        <!-- Description -->
        <p class="text-white/80 text-sm leading-relaxed mb-6 font-light max-w-sm">
            Identifikasi hama dan penyakit tanaman padi secara akurat menggunakan sistem pakar berbasis gejala.
        </p>

        <!-- Search Bar -->
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                <!-- Search Icon -->
                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" 
                   placeholder="Cari penyakit atau hama..." 
                   class="w-full bg-white/15 border border-white/10 text-white placeholder-white/60 text-sm rounded-full py-3.5 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-white/20 focus:bg-white/20 transition-all duration-200 shadow-sm"
            >
        </div>
    </div>

    <!-- STATS CARD (Floating Overlap) -->
    <div class="relative -mt-8 mx-6 bg-white rounded-2xl py-4 px-2 shadow-lg border border-neutral-100 flex justify-around items-center">
        <!-- Penyakit -->
        <div class="flex-1 text-center border-r border-neutral-100">
            <span class="block text-2xl font-bold text-[#0E4E37] tracking-tight">{{ $penyakitCount }}</span>
            <span class="text-[11px] font-semibold text-[#8E9A94]">Penyakit</span>
        </div>
        <!-- Hama -->
        <div class="flex-1 text-center border-r border-neutral-100">
            <span class="block text-2xl font-bold text-[#0E4E37] tracking-tight">{{ $hamaCount }}</span>
            <span class="text-[11px] font-semibold text-[#8E9A94]">Hama</span>
        </div>
        <!-- Gejala -->
        <div class="flex-1 text-center">
            <span class="block text-2xl font-bold text-[#0E4E37] tracking-tight">{{ $gejalaCount }}</span>
            <span class="text-[11px] font-semibold text-[#8E9A94]">Gejala</span>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="grid grid-cols-2 gap-4 px-6 mt-6">
        <!-- Quick Action 1: Deteksi -->
        <a href="{{ route('deteksi.start') }}" class="bg-gradient-to-br from-[#0F4E37] to-[#12583F] text-white p-5 rounded-2xl shadow-md hover:brightness-105 transition-all relative flex flex-col justify-between h-[155px]">
            <div class="flex justify-between items-start w-full">
                <!-- Microscope Icon SVG -->
                <svg class="w-8 h-8 text-white/90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18h8" />
                    <path d="M3 22h18" />
                    <path d="M14 22a7 7 0 1 0-14 0" />
                    <path d="M9 14h2" />
                    <path d="M9 12a3 3 0 0 1-3-3V6a3 3 0 0 1 6 0v3a3 3 0 0 1-3 3Z" />
                    <path d="M12 6h4" />
                    <path d="M14 6v8a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3a2 2 0 0 0-2 2Z" />
                </svg>
                <!-- Chevron -->
                <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-lg leading-tight">Deteksi</h3>
                <p class="text-[10px] text-white/70 mt-1 leading-snug font-light">Diagnosa berdasarkan gejala</p>
            </div>
        </a>

        <!-- Quick Action 2: Ensiklopedia -->
        <a href="{{ route('ensiklopedia.index') }}" class="bg-[#2F8966] text-white p-5 rounded-2xl shadow-md hover:brightness-105 transition-all relative flex flex-col justify-between h-[155px]">
            <div class="flex justify-between items-start w-full">
                <!-- Book Icon SVG -->
                <svg class="w-7 h-7 text-white/90" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <!-- Chevron -->
                <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-lg leading-tight">Ensiklopedia</h3>
                <p class="text-[10px] text-white/70 mt-1 leading-snug font-light">Info lengkap hama & penyakit</p>
            </div>
        </a>
    </div>

    <!-- VIEW HISTORY LINK -->
    <div class="px-6 mt-4">
        <a href="{{ route('deteksi.history') }}" class="w-full flex items-center justify-between bg-white border border-neutral-100 p-4 rounded-2xl shadow-sm hover:bg-neutral-50 transition">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#0E4E37]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-bold text-neutral-800">Lihat Riwayat Diagnosa Anda</span>
            </div>
            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>

    <!-- SECTION PENYAKIT PADI -->
    <div class="mt-8 flex flex-col">
        <div class="px-6 flex justify-between items-center mb-4">
            <div class="flex items-center">
                <!-- Vertical Line -->
                <div class="w-1.5 h-6 bg-[#0E4E37] rounded-full mr-2"></div>
                <h2 class="text-xl font-bold text-neutral-800">Penyakit Padi</h2>
            </div>
            <a href="{{ route('ensiklopedia.index', ['type' => 'penyakit']) }}" class="text-[#2F8966] text-xs font-semibold flex items-center hover:underline">
                Lihat semua
                <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <!-- Horizontal Scroll List -->
        <div class="flex overflow-x-auto gap-4 px-6 pb-2 no-scrollbar">
            @foreach($penyakitList as $p)
            @php $slug = \Illuminate\Support\Str::slug($p->nama); @endphp
            <div class="w-[200px] flex-shrink-0 bg-white border border-neutral-100 rounded-2xl overflow-hidden shadow-sm flex flex-col">
                <div class="h-[105px] w-full overflow-hidden bg-neutral-100">
                    <img src="{{ $p->gambar }}" alt="{{ $p->nama }}" class="w-full h-full object-cover">
                </div>
                <div class="p-3.5 flex flex-col flex-grow justify-between min-h-[105px]">
                    <div>
                        <!-- Category Badge -->
                        <span class="inline-block bg-[#E2F2EB] text-[#0A3D2A] text-[9px] font-bold px-2 py-0.5 rounded-full mb-1">Penyakit</span>
                        <h4 class="font-bold text-neutral-800 text-sm leading-tight">
                            <a href="{{ route('ensiklopedia.show', $slug) }}" class="hover:text-[#0E4E37] hover:underline">{{ $p->nama }}</a>
                        </h4>
                    </div>
                    <p class="text-[10px] text-neutral-500 line-clamp-2 mt-1 leading-normal">
                        {{ $p->deskripsi }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- SECTION HAMA PADI -->
    <div class="mt-8 flex flex-col">
        <div class="px-6 flex justify-between items-center mb-4">
            <div class="flex items-center">
                <!-- Vertical Line (Orange-red) -->
                <div class="w-1.5 h-6 bg-[#D85C30] rounded-full mr-2"></div>
                <h2 class="text-xl font-bold text-neutral-800">Hama Padi</h2>
            </div>
            <a href="{{ route('ensiklopedia.index', ['type' => 'hama']) }}" class="text-[#2F8966] text-xs font-semibold flex items-center hover:underline">
                Lihat semua
                <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <!-- Horizontal Scroll List -->
        <div class="flex overflow-x-auto gap-4 px-6 pb-2 no-scrollbar">
            @foreach($hamaList as $h)
            @php $slug = \Illuminate\Support\Str::slug($h->nama); @endphp
            <div class="w-[200px] flex-shrink-0 bg-white border border-neutral-100 rounded-2xl overflow-hidden shadow-sm flex flex-col">
                <div class="h-[105px] w-full overflow-hidden bg-neutral-100">
                    @if($h->gambar === 'placeholder')
                        <div class="w-full h-full bg-[#E8F3ED] flex flex-col items-center justify-center p-4 text-center">
                            <svg class="w-6 h-6 text-[#A3B3AB] mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                            </svg>
                            <span class="text-[10px] text-[#A3B3AB] leading-none font-medium">{{ $h->nama }}</span>
                        </div>
                    @else
                        <img src="{{ $h->gambar }}" alt="{{ $h->nama }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="p-3.5 flex flex-col flex-grow justify-between min-h-[105px]">
                    <div>
                        <!-- Category Badge -->
                        <span class="inline-block bg-[#FDECE8] text-[#D85C30] text-[9px] font-bold px-2 py-0.5 rounded-full mb-1">Hama</span>
                        <h4 class="font-bold text-neutral-800 text-sm leading-tight">
                            <a href="{{ route('ensiklopedia.show', $slug) }}" class="hover:text-[#0E4E37] hover:underline">{{ $h->nama }}</a>
                        </h4>
                    </div>
                    <p class="text-[10px] text-neutral-500 line-clamp-2 mt-1 leading-normal">
                        {{ $h->deskripsi }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- SECTION TIPS -->
    <div class="bg-[#EAF6F0] border border-[#D5EDE1] p-4 rounded-2xl mx-6 my-8 flex items-start gap-3 shadow-[0_2px_8px_rgba(0,0,0,0.01)]">
        <!-- Shield Icon SVG -->
        <svg class="w-6 h-6 text-[#0E4E37] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
        <div>
            <h4 class="font-bold text-[#0E4E37] text-sm mb-0.5">Tips Deteksi Dini</h4>
            <p class="text-xs text-[#1D4733] leading-relaxed font-light">
                Periksa tanaman secara rutin setiap minggu. Deteksi dini dapat mencegah kerugian panen hingga 80%.
            </p>
        </div>
    </div>

</div>
@endsection

@section('navigation')
    <x-bottom-nav active="beranda" />
@endsection
