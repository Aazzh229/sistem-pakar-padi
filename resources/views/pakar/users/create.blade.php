@extends('layouts.app')

@section('title', 'Tambah Petani - SiPakar Padi')

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
            <h1 class="text-xl font-bold">Daftarkan Petani</h1>
        </div>
        <p class="text-white/80 text-xs font-light">
            Daftarkan akun petani baru di bawah pengawasan dan binaan Anda sebagai Pakar.
        </p>
    </div>

    <!-- User Form -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col">
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-lg">
            
            <form action="{{ route('pakar.users.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf

                <!-- Nama -->
                <div class="flex flex-col gap-1.5">
                    <label for="name" class="text-xs font-bold text-neutral-700">Nama Petani</label>
                    <input type="text" id="name" name="name" required placeholder="Pak Tani"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                    <label for="email" class="text-xs font-bold text-neutral-700">Alamat Email</label>
                    <input type="email" id="email" name="email" required placeholder="tani@email.com"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                    @error('email')
                        <span class="text-[11px] text-red-500 font-medium mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-1.5">
                    <label for="password" class="text-xs font-bold text-neutral-700">Password Sementara</label>
                    <input type="text" id="password" name="password" required value="tani123"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                    <span class="text-[9px] text-neutral-400 font-light mt-0.5 leading-snug">Berikan password sementara ini kepada petani bersangkutan.</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 rounded-full shadow-md transition mt-2 flex justify-center items-center"
                >
                    Daftar Akun Petani
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
