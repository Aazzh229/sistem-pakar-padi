@extends('layouts.app')

@section('title', 'Kelola Pengguna - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Kelola Pengguna</h1>
        </div>
        <div class="flex justify-between items-center">
            <p class="text-white/80 text-xs font-light">Daftar lengkap admin, pakar, dan user.</p>
            <a href="{{ route('admin.users.create') }}" class="bg-[#3CD070] text-[#0A3D2A] text-xs font-bold px-3.5 py-1.5 rounded-full transition shadow-sm">
                + Tambah Akun
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mx-6 mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Users List Cards -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col gap-4 mt-6">
        @foreach($users as $user)
            <div class="bg-white p-4 rounded-xl border border-neutral-100 shadow-sm flex items-center justify-between">
                <div class="min-w-0 flex-grow pr-3">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <h3 class="font-bold text-neutral-800 text-sm truncate leading-none">{{ $user->name }}</h3>
                        
                        <!-- Role Badge -->
                        @php
                            $roleColor = match($user->role) {
                                'admin' => 'bg-red-50 text-red-700 border-red-200',
                                'pakar' => 'bg-amber-50 text-amber-700 border-amber-200',
                                default => 'bg-emerald-50 text-emerald-700 border-emerald-200'
                            };
                        @endphp
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded border uppercase tracking-wider {{ $roleColor }}">
                            {{ $user->role }}
                        </span>

                        <!-- Status Badge -->
                        @if($user->status === 'inactive')
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded border border-neutral-200 bg-neutral-50 text-neutral-400 uppercase tracking-wider">Nonaktif</span>
                        @endif
                    </div>
                    <p class="text-xs text-neutral-400 font-light truncate">{{ $user->email }}</p>
                </div>
                
                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-neutral-400 hover:text-[#0E4E37] p-2 flex-shrink-0 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
