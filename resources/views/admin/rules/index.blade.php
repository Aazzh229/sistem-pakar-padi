@extends('layouts.app')

@section('title', 'Basis Pengetahuan Rules - SiPakar Padi')

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
            <h1 class="text-xl font-bold">Kelola Aturan CF</h1>
        </div>
        <div class="flex justify-between items-center">
            <p class="text-white/80 text-xs font-light">Pemetaan gejala ke hama & penyakit.</p>
            <a href="{{ route('admin.rules.create') }}" class="bg-[#3CD070] text-[#0A3D2A] text-xs font-bold px-3.5 py-1.5 rounded-full transition shadow-sm">
                + Tambah Rule
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mx-6 mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Rules List Cards -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col gap-4 mt-6">
        @foreach($rules as $rule)
            @php
                $target = $rule->target;
                $isPenyakit = $rule->target_type === 'penyakit';
                $badgeBg = $isPenyakit ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-orange-50 text-orange-700 border-orange-200';
            @endphp
            <div class="bg-white p-4 rounded-xl border border-neutral-100 shadow-sm flex flex-col gap-2.5">
                <div class="flex justify-between items-start">
                    <div class="min-w-0 flex-grow pr-2">
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded border uppercase tracking-wider {{ $badgeBg }}">
                            {{ $isPenyakit ? 'Penyakit' : 'Hama' }}
                        </span>
                        <h3 class="font-bold text-neutral-800 text-sm mt-1 truncate leading-tight">
                            {{ $isPenyakit ? $target->nama_penyakit : $target->nama_hama }}
                        </h3>
                    </div>
                    <!-- CF Badge -->
                    <div class="text-right">
                        <span class="text-xs font-black text-[#0E4E37] block bg-[#E2F2EB] px-2 py-0.5 rounded-full">CF: {{ $rule->cf_pakar }}</span>
                    </div>
                </div>

                <!-- Gejala -->
                <div class="bg-neutral-50 p-2.5 rounded-lg border border-neutral-100 flex items-start gap-2">
                    <span class="bg-[#E2F2EB] text-[#0A3D2A] text-[9px] font-bold px-1 py-0.5 rounded flex-shrink-0 mt-0.5">
                        {{ $rule->gejala->kode_gejala }}
                    </span>
                    <p class="text-xs text-neutral-500 font-light leading-normal">{{ $rule->gejala->nama_gejala }}</p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 border-t pt-2 mt-0.5">
                    <a href="{{ route('admin.rules.edit', $rule->id) }}" class="text-neutral-400 hover:text-[#0E4E37] text-xs font-bold flex items-center gap-1 transition">
                        Edit
                    </a>
                    <form action="{{ route('admin.rules.delete', $rule->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rule ini?')">
                        @csrf
                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs font-bold transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
