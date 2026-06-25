@extends('layouts.admin')

@section('title', 'Kelola Basis Pengetahuan - Padiku')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-lg border border-neutral-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-[#0E4E37] hover:underline">Dashboard</a>
            <h1 class="mt-2 text-3xl font-black text-neutral-900">Kelola Basis Pengetahuan</h1>
            <p class="mt-2 text-sm text-neutral-500">Kelola gejala, hama dan penyakit.</p>
        </div>
        <a href="{{ route('admin.rules.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#0E4E37] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#12583F]">
            + Tambah Data
        </a>
    </section>

    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <section class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm">
        <div class="flex gap-2 border-b border-neutral-200 pb-4">
            <button id="btn-gejala" class="rounded-lg bg-[#0E4E37] px-4 py-2 text-sm font-bold text-white">
                Gejala
            </button>
            <button id="btn-target" class="rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm font-bold text-neutral-600">
                Penyakit / Hama
            </button>
        </div>

        <div id="section-gejala" class="mt-5">
            <h2 class="mb-4 text-base font-black text-neutral-900">Gejala</h2>
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach($gejala as $g)
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4">
                        <span class="text-xs font-black text-[#0E4E37]">{{ $g->kode_gejala }}</span>
                        <p class="mt-1 text-sm font-bold text-neutral-700">{{ $g->nama_gejala }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="section-target" class="hidden mt-5">
            <h2 class="mb-4 text-base font-black text-neutral-900">Penyakit / Hama</h2>
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach($penyakit as $p)
                    <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4">
                        <span class="text-xs font-black text-emerald-700">{{ $p->kode_penyakit }}</span>
                        <p class="mt-1 text-sm font-bold text-neutral-700">{{ $p->nama_penyakit }}</p>
                    </div>
                @endforeach

                @foreach($hama as $h)
                    <div class="rounded-lg border border-orange-100 bg-orange-50 p-4">
                        <span class="text-xs font-black text-orange-700">{{ $h->kode_hama }}</span>
                        <p class="mt-1 text-sm font-bold text-neutral-700">{{ $h->nama_hama }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </section>
</div>
@endsection

@section('scripts')
<script>
const btnGejala = document.getElementById('btn-gejala');
const btnTarget = document.getElementById('btn-target');

const sectionGejala = document.getElementById('section-gejala');
const sectionTarget = document.getElementById('section-target');

function aktifkan(button){
    [btnGejala, btnTarget].forEach(btn=>{
        btn.style.backgroundColor="white";
        btn.style.color="#525252";
        btn.style.border="1px solid #e5e5e5";
    });

    button.style.backgroundColor="#0E4E37";
    button.style.color="white";
    button.style.border="1px solid #0E4E37";
}

function showSection(section,button){
    sectionGejala.classList.add("hidden");
    sectionTarget.classList.add("hidden");

    section.classList.remove("hidden");
    aktifkan(button);
}

btnGejala.addEventListener("click",()=>{
    showSection(sectionGejala,btnGejala);
});

btnTarget.addEventListener("click",()=>{
    showSection(sectionTarget,btnTarget);
});

showSection(sectionGejala,btnGejala);
</script>
@endsection
