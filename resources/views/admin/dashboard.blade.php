@extends('layouts.app')

@section('title', 'Dashboard Admin - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Dashboard Admin</h1>
            
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
        <span class="text-xs font-medium text-[#3CD070] tracking-wider uppercase mb-1">Selamat Datang, {{ Auth::user()->name }}</span>
        <p class="text-white/80 text-xs font-light">
            Pengendali penuh aplikasi. Kelola akun pengguna, bobot pakar CF, edukasi library, dan pantau riwayat diagnosa.
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-4 px-6 -mt-4 relative z-10">
        <div class="bg-white p-4 rounded-xl border border-neutral-100 shadow-md text-center">
            <span class="text-2xl font-black text-[#0E4E37] block">{{ $usersCount }}</span>
            <span class="text-[10px] text-neutral-400 font-semibold uppercase">Total Akun</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-neutral-100 shadow-md text-center">
            <span class="text-2xl font-black text-[#0E4E37] block">{{ $rulesCount }}</span>
            <span class="text-[10px] text-neutral-400 font-semibold uppercase">Rule CF</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-neutral-100 shadow-md text-center">
            <span class="text-2xl font-black text-[#0E4E37] block">{{ $libraryCount }}</span>
            <span class="text-[10px] text-neutral-400 font-semibold uppercase">Artikel Lib</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-neutral-100 shadow-md text-center">
            <span class="text-2xl font-black text-[#0E4E37] block">{{ $historyCount }}</span>
            <span class="text-[10px] text-neutral-400 font-semibold uppercase">Riwayat Diag</span>
        </div>
    </div>

    <!-- Management Modules -->
    <div class="px-6 flex flex-col gap-4 mt-6">
        
        <!-- Module 1: Kelola User -->
        <a href="{{ route('admin.users.index') }}" class="bg-white p-4 rounded-xl border border-neutral-100 shadow-sm flex items-center justify-between hover:bg-neutral-50 transition">
            <div class="flex items-center gap-3">
                <div class="bg-[#E2F2EB] text-[#0A3D2A] p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-neutral-700">Kelola User & Pakar</span>
            </div>
            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <!-- Module 2: Kelola Basis Pengetahuan -->
        <a href="{{ route('admin.rules.index') }}" class="bg-white p-4 rounded-xl border border-neutral-100 shadow-sm flex items-center justify-between hover:bg-neutral-50 transition">
            <div class="flex items-center gap-3">
                <div class="bg-[#FDECE8] text-[#D85C30] p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-neutral-700">Kelola Basis Pengetahuan</span>
            </div>
            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <!-- Module 3: Kelola Library -->
        <a href="{{ route('admin.library.index') }}" class="bg-white p-4 rounded-xl border border-neutral-100 shadow-sm flex items-center justify-between hover:bg-neutral-50 transition">
            <div class="flex items-center gap-3">
                <div class="bg-[#EAF6F0] text-[#0E4E37] p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-neutral-700">Kelola Pengetahuan Library</span>
            </div>
            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <!-- Module 4: Monitoring Diagnosa -->
        <a href="{{ route('deteksi.history') }}" class="bg-white p-4 rounded-xl border border-neutral-100 shadow-sm flex items-center justify-between hover:bg-neutral-50 transition">
            <div class="flex items-center gap-3">
                <div class="bg-neutral-100 text-neutral-600 p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-neutral-700">Monitoring Riwayat Diagnosa</span>
            </div>
            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <!-- Client View Redirect -->
        <div class="border-t border-neutral-200 pt-5 mt-2 flex flex-col gap-3">
            <a href="{{ route('beranda') }}" class="w-full text-center bg-[#0E4E37] hover:bg-[#12583F] text-white text-xs font-bold py-3.5 rounded-full transition shadow-md">
                Buka Deteksi & Ensiklopedia (Tampilan Petani)
            </a>
        </div>

    </div>
</div>
@endsection
