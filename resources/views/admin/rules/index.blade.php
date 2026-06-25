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
            <h1 class="text-xl font-bold">Kelola Basis Pengetahuan CF</h1>
        </div>
        <div class="flex justify-between items-center">
            <p class="text-white/80 text-xs font-light">Kelola gejala, hama dan penyakit</p>
            <a href="{{ route('admin.rules.create') }}" class="bg-[#3CD070] text-[#0A3D2A] text-xs font-bold px-3.5 py-1.5 rounded-full transition shadow-sm">
                + Tambah Data
            </a>
        </div>
    </div>
    
    <!-- Kategori -->
    <div class="px-6 -mt-3 relative z-10">
        <div class="flex gap-2 overflow-x-auto pb-1">

            <button id="btn-gejala" class="px-4 py-2 rounded-full bg-[#0E4E37] text-white text-xs font-bold whitespace-nowrap">
                Gejala
            </button>

            <button id="btn-target" class="px-4 py-2 rounded-full bg-white border border-neutral-200 text-neutral-600 text-xs font-bold whitespace-nowrap">
                Penyakit / Hama
            </button>

            <button id="btn-rule" class="px-4 py-2 rounded-full bg-white border border-neutral-200 text-neutral-600 text-xs font-bold whitespace-nowrap">
                Rule
            </button>

        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mx-6 mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Gejala -->
    <div id="section-gejala" class="px-6 mt-6">
        <h2 class="text-sm font-bold text-neutral-700 mb-3">Gejala</h2>
                    <div class="flex flex-col gap-3">
                        @foreach($gejala as $g)
                            <div class="bg-white border border-neutral-100 rounded-xl p-3 shadow-sm flex justify-between items-center">
                                <div>
                                    <span class="text-[10px] font-bold text-[#0E4E37]">{{ $g->kode_gejala }}</span>
                                    <p class="text-sm text-neutral-700">{{ $g->nama_gejala }}</p>
                                </div>

                                <div class="flex gap-3">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Penyakit & Hama -->
                <div id="section-target" class="hidden px-6 mt-6">
                    <h2 class="text-sm font-bold text-neutral-700 mb-3">
                        Penyakit / Hama
                    </h2>

                    <div class="flex flex-col gap-3">

                        <div class="flex flex-col gap-3">

                <!-- Penyakit -->
                @foreach($penyakit as $p)
                <div class="bg-white border border-neutral-100 rounded-xl p-3 shadow-sm flex justify-between items-center">
                    <div>
                        <span class="text-[10px] font-bold text-emerald-600">
                            {{ $p->kode_penyakit }}
                        </span>
                        <p class="text-sm text-neutral-700">
                            {{ $p->nama_penyakit }}
                        </p>
                    </div>
                </div>
                @endforeach

                <!-- Hama -->
                @foreach($hama as $h)
                <div class="bg-white border border-neutral-100 rounded-xl p-3 shadow-sm flex justify-between items-center">
                    <div>
                        <span class="text-[10px] font-bold text-orange-600">
                            {{ $h->kode_hama }}
                        </span>
                        <p class="text-sm text-neutral-700">
                            {{ $h->nama_hama }}
                        </p>
                    </div>
                </div>
                @endforeach         

    </div>

                    </div>
                </div>

    <!-- Rules List Cards -->
    <div id="section-rule" class="hidden px-6 mt-6">
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
            </div>
        @endforeach
    </div>
</div>
<script>
const btnGejala = document.getElementById('btn-gejala');
const btnTarget = document.getElementById('btn-target');
const btnRule = document.getElementById('btn-rule');

const sectionGejala = document.getElementById('section-gejala');
const sectionTarget = document.getElementById('section-target');
const sectionRule = document.getElementById('section-rule');

function aktifkan(button){
    [btnGejala, btnTarget, btnRule].forEach(btn=>{
        btn.style.backgroundColor="white";
        btn.style.color="#525252";
        btn.style.border="1px solid #e5e5e5";
    });

    button.style.backgroundColor="#0E4E37";
    button.style.color="white";
    button.style.border="none";
}

function showSection(section,button){

    sectionGejala.classList.add("hidden");
    sectionTarget.classList.add("hidden");
    sectionRule.classList.add("hidden");

    section.classList.remove("hidden");

    aktifkan(button);
}

btnGejala.addEventListener("click",()=>{
    showSection(sectionGejala,btnGejala);
});

btnTarget.addEventListener("click",()=>{
    showSection(sectionTarget,btnTarget);
});

btnRule.addEventListener("click",()=>{
    showSection(sectionRule,btnRule);
});

showSection(sectionGejala,btnGejala);
</script>
@endsection
