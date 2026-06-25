@extends('layouts.app')

@section('title', 'Tambah Data Master - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('pakar.dashboard') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Tambah Data Baru</h1>
        </div>
        <p class="text-white/80 text-xs font-light">
            Tambahkan penyakit, hama, atau gejala baru ke dalam sistem pakar.
        </p>
    </div>

    <div class="px-6 -mt-4 relative z-10 flex flex-col mb-8">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-xs font-medium p-4 rounded-xl mb-4">
                <ul class="list-disc list-inside flex flex-col gap-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pakar.master.store') }}" method="POST" class="bg-white p-6 rounded-2xl border border-neutral-100 shadow-md flex flex-col gap-5">
            @csrf

            <!-- Tipe Data Selector -->
            <div>
                <label class="text-xs font-bold text-neutral-600 mb-2 block">Pilih Tipe Data</label>
                <div class="flex gap-2">
                    <button type="button" id="type-penyakit" onclick="setType('penyakit')"
                            class="flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-[#0E4E37] text-white border-[#0E4E37]">
                        Penyakit
                    </button>
                    <button type="button" id="type-hama" onclick="setType('hama')"
                            class="flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-white text-neutral-500 border-neutral-200">
                        Hama
                    </button>
                    <button type="button" id="type-gejala" onclick="setType('gejala')"
                            class="flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-white text-neutral-500 border-neutral-200">
                        Gejala
                    </button>
                </div>
                <input type="hidden" name="dataType" id="dataType" value="penyakit">
            </div>

            <!-- Kode (Auto/Custom) -->
            <div>
                <label class="text-xs font-bold text-neutral-600 mb-1.5 block">Kode Data</label>
                <input type="text" name="kode" id="input-kode" value="{{ $suggestedPenyakit }}" required
                       class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] uppercase">
            </div>

            <!-- Nama -->
            <div>
                <label class="text-xs font-bold text-neutral-600 mb-1.5 block" id="label-nama">Nama Penyakit</label>
                <input type="text" name="nama" id="input-nama" required placeholder="Contoh: Hawar Daun Bakteri"
                       class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
            </div>

            <!-- Kategori Gejala (Only visible for Gejala) -->
            <div id="kategori-wrapper" class="hidden">
                <label class="text-xs font-bold text-neutral-600 mb-1.5 block">Bagian / Kategori Gejala</label>
                <select name="kategori"
                        class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                    <option value="Daun">Daun</option>
                    <option value="Batang">Batang</option>
                    <option value="Akar">Akar</option>
                    <option value="Bulir / Malai">Bulir / Malai</option>
                    <option value="Seluruh Tanaman">Seluruh Tanaman</option>
                </select>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="text-xs font-bold text-neutral-600 mb-1.5 block">Deskripsi Singkat (Opsional)</label>
                <textarea name="deskripsi" id="input-deskripsi" rows="3" placeholder="Masukkan deskripsi detail..."
                          class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 rounded-full shadow-lg transition flex items-center justify-center gap-2 mt-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Simpan Data
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const suggested = {
        penyakit: @json($suggestedPenyakit),
        hama: @json($suggestedHama),
        gejala: @json($suggestedGejala)
    };

    function setType(type) {
        document.getElementById('dataType').value = type;
        
        // Update toggles
        ['penyakit', 'hama', 'gejala'].forEach(t => {
            const btn = document.getElementById(`type-${t}`);
            if (t === type) {
                btn.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-[#0E4E37] text-white border-[#0E4E37]';
            } else {
                btn.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-white text-neutral-500 border-neutral-200';
            }
        });

        // Update Labels & Placeholders & Values
        const labelNama = document.getElementById('label-nama');
        const inputNama = document.getElementById('input-nama');
        const inputKode = document.getElementById('input-kode');
        const kategoriWrapper = document.getElementById('kategori-wrapper');

        inputKode.value = suggested[type];

        if (type === 'penyakit') {
            labelNama.textContent = 'Nama Penyakit';
            inputNama.placeholder = 'Contoh: Hawar Daun Bakteri';
            kategoriWrapper.classList.add('hidden');
        } else if (type === 'hama') {
            labelNama.textContent = 'Nama Hama';
            inputNama.placeholder = 'Contoh: Wereng Coklat';
            kategoriWrapper.classList.add('hidden');
        } else if (type === 'gejala') {
            labelNama.textContent = 'Nama Gejala';
            inputNama.placeholder = 'Contoh: Daun terlihat kekuningan dan menggulung';
            kategoriWrapper.classList.remove('hidden');
        }
    }

    // Initialize once
    window.addEventListener('load', () => {
        setType('{{ $defaultType ?? "penyakit" }}');
    });
</script>
@endsection
