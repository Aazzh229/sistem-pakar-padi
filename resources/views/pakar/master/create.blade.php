@extends('layouts.app')

@section('title', 'Tambah Data Master - Padiku')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('pakar.dashboard') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Tambah Data Master</h1>
        </div>
        <p class="text-white/80 text-xs font-light">Tambah gejala, penyakit, atau hama untuk basis pengetahuan.</p>
    </div>

    @if ($errors->any())
        <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="px-6 -mt-4 relative z-10 flex flex-col mb-8">
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-lg">
            <form action="{{ route('pakar.master.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf

                <div class="flex flex-col gap-1.5">
                    <label for="dataType" class="text-xs font-bold text-neutral-700">Jenis Data</label>
                    <select id="dataType" name="dataType" required onchange="syncMasterForm()"
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                        <option value="penyakit" {{ $defaultType === 'penyakit' ? 'selected' : '' }}>Penyakit</option>
                        <option value="hama" {{ $defaultType === 'hama' ? 'selected' : '' }}>Hama</option>
                        <option value="gejala" {{ $defaultType === 'gejala' ? 'selected' : '' }}>Gejala</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="kode" class="text-xs font-bold text-neutral-700">Kode</label>
                    <input type="text" id="kode" name="kode"
                           data-penyakit="{{ $suggestedPenyakit }}"
                           data-hama="{{ $suggestedHama }}"
                           data-gejala="{{ $suggestedGejala }}"
                           value="{{ $defaultType === 'hama' ? $suggestedHama : ($defaultType === 'gejala' ? $suggestedGejala : $suggestedPenyakit) }}"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="nama" id="namaLabel" class="text-xs font-bold text-neutral-700">Nama Penyakit</label>
                    <input type="text" id="nama" name="nama" required value="{{ old('nama') }}"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                </div>

                <div id="kategoriWrapper" class="hidden flex flex-col gap-1.5">
                    <label for="kategori" class="text-xs font-bold text-neutral-700">Kategori Gejala</label>
                    <input type="text" id="kategori" name="kategori" value="{{ old('kategori', 'Seluruh Tanaman') }}"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="deskripsi" class="text-xs font-bold text-neutral-700">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                              class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">{{ old('deskripsi') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 rounded-full shadow-md transition mt-2">
                    Simpan Data
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function syncMasterForm() {
    const type = document.getElementById('dataType').value;
    const kode = document.getElementById('kode');
    const label = document.getElementById('namaLabel');
    const kategori = document.getElementById('kategoriWrapper');

    kode.value = kode.dataset[type];
    label.textContent = type === 'gejala' ? 'Nama Gejala' : (type === 'hama' ? 'Nama Hama' : 'Nama Penyakit');
    kategori.classList.toggle('hidden', type !== 'gejala');
}

window.addEventListener('load', syncMasterForm);
</script>
@endsection
