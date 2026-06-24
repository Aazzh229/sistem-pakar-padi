@extends('layouts.app')

@section('title', 'Daftar Akun - SiPakar Padi')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] flex flex-col justify-center px-6 py-12 text-white">
    
    <!-- Logo & Title -->
    <div class="flex flex-col items-center mb-8 text-center">
        <!-- Leaf SVG -->
        <svg class="w-14 h-14 text-[#3CD070] mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 3.5 1 8.8C19 16 15 20 11 20z"></path>
            <path d="M19 2c-2.26 3.19-5.27 6.42-8 8"></path>
        </svg>
        <h1 class="text-3xl font-extrabold tracking-tight">SiPakar Padi</h1>
        <p class="text-xs text-white/70 mt-1 font-light max-w-[250px]">Sistem Pakar Identifikasi Hama & Penyakit Padi</p>
    </div>

    <!-- Register Card -->
    <div class="bg-white/10 backdrop-blur-md border border-white/10 p-6 rounded-2xl shadow-xl flex flex-col">
        <h2 class="text-lg font-bold mb-4 text-center">Daftar Akun Petani Baru</h2>
        
        <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-3.5">
            @csrf

            <!-- Name -->
            <div class="flex flex-col gap-1.5">
                <label for="name" class="text-xs font-semibold text-white/80">Nama Lengkap</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       placeholder="Pak Tani"
                       class="bg-white/15 border border-white/15 text-white placeholder-white/50 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/20 transition-all"
                >
                @error('name')
                    <span class="text-[11px] text-red-400 mt-1 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-xs font-semibold text-white/80">Alamat Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       placeholder="nama@email.com"
                       class="bg-white/15 border border-white/15 text-white placeholder-white/50 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/20 transition-all"
                >
                @error('email')
                    <span class="text-[11px] text-red-400 mt-1 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-1.5">
                <label for="password" class="text-xs font-semibold text-white/80">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       placeholder="Minimal 6 karakter"
                       class="bg-white/15 border border-white/15 text-white placeholder-white/50 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/20 transition-all"
                >
                @error('password')
                    <span class="text-[11px] text-red-400 mt-1 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="flex flex-col gap-1.5">
                <label for="password_confirmation" class="text-xs font-semibold text-white/80">Konfirmasi Password</label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required 
                       placeholder="Ulangi password"
                       class="bg-white/15 border border-white/15 text-white placeholder-white/50 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/20 transition-all"
                >
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="bg-[#3CD070] hover:bg-[#32b05f] text-[#0A3D2A] text-sm font-extrabold py-3.5 px-6 rounded-xl transition-all shadow-md mt-2 flex justify-center items-center gap-2"
            >
                Daftar Akun
            </button>
        </form>

        <div class="text-center mt-6 text-xs text-white/60">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-[#3CD070] hover:underline font-bold">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
