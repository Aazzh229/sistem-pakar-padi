@extends('layouts.app')

@section('title', 'Kelola Library - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('pakar.dashboard') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Kelola Library</h1>
        </div>
        <div class="flex justify-between items-center">
            <p class="text-white/80 text-xs font-light">Artikel edukasi dan deskripsi hama penyakit.</p>
            <a href="{{ route('pakar.library.create') }}" class="bg-[#3CD070] text-[#0A3D2A] text-xs font-bold px-3.5 py-1.5 rounded-full transition shadow-sm">
                + Tambah Artikel
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mx-6 mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Library List Cards -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col gap-4 mt-6">
        @foreach($libraries as $lib)
            @php
                $isPenyakit = $lib->jenis === 'penyakit';
                $badgeBg = $isPenyakit ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-orange-50 text-orange-700 border-orange-200';
            @endphp
            <div class="bg-white p-4 rounded-xl border border-neutral-100 shadow-sm flex items-center justify-between gap-4">
                
                <!-- Info content -->
                <div class="min-w-0 flex-grow">
                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded border uppercase tracking-wider {{ $badgeBg }}">
                        {{ $lib->jenis }}
                    </span>
                    <h3 class="font-bold text-neutral-800 text-sm mt-1 truncate leading-tight">{{ $lib->nama }}</h3>
                    <p class="text-[10px] text-neutral-400 italic mt-0.5 truncate">{{ $lib->nama_latin }}</p>
                </div>

                <!-- Action Button links -->
                <div class="flex items-center gap-2.5 flex-shrink-0">
                    <a href="{{ route('pakar.library.edit', $lib->id) }}" class="text-neutral-400 hover:text-[#0E4E37] p-1.5 transition">
                        <!-- Edit Icon -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    
                    <form action="{{ route('pakar.library.delete', $lib->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel library ini?')">
                        @csrf
                        <button type="submit" class="text-red-400 hover:text-red-600 p-1.5 transition">
                            <!-- Trash Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>
        @endforeach
    </div>
</div>
@endsection
