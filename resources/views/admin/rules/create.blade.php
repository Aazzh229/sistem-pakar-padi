@extends('layouts.app')

@section('title', 'Tambah Rule CF - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('admin.rules.index') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Tambah Aturan</h1>
        </div>
        <p class="text-white/80 text-xs font-light">
            Petakan satu gejala tertentu ke hama/penyakit dengan mengisi nilai CF pakar.
        </p>
    </div>

    <!-- Rule Form -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col mb-8">
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-lg">
            
            <form action="{{ route('admin.rules.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf

                <!-- Gejala -->
                <div class="flex flex-col gap-1.5 relative">
                    <label class="text-xs font-bold text-neutral-700">Gejala</label>

                    <input
                        type="text"
                        id="nama_gejala"
                        name="nama_gejala"
                        autocomplete="off"
                        placeholder="Tambah gejala..."
                        class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]"
                    >

                    <input type="hidden" id="gejala_id" name="gejala_id">

                    <div id="gejala-suggestion"
                        class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-48 overflow-y-auto z-50">
                    </div>
                </div>

                <!-- Target Type -->
                <!-- Target Type -->
                <div class="flex flex-col gap-1.5">
                    <label for="target_type" class="text-xs font-bold text-neutral-700">
                        Jenis Target
                    </label>

                    <select
                        id="target_type"
                        name="target_type"
                        class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]"
                        onchange="toggleTargets()"
                    >
                        <option value="penyakit">Penyakit</option>
                        <option value="hama">Hama</option>
                    </select>
                </div>

                <!-- input penyakit -->
                <div class="flex flex-col gap-1.5 relative" id="penyakit-wrapper">
                    <label class="text-xs font-bold text-neutral-700">Penyakit</label>

                    <input
                        type="text"
                        id="nama_penyakit"
                        name="nama_penyakit"
                        autocomplete="off"
                        placeholder="Masukkan nama penyakit"
                        class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4"
                    >

                    <!-- Suggestion -->
                    <div id="penyakit-suggestion"
                        class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-48 overflow-y-auto z-50">
                    </div>
                </div>

                <!-- Target ID (Pest) -->
                <div class="flex flex-col gap-1.5 hidden" id="hama-wrapper">
                    <label class="text-xs font-bold text-neutral-700">Hama</label>

                    <input
                        type="text"
                        id="nama_hama"
                        name="nama_hama"
                        autocomplete="off"
                        placeholder="Masukkan nama hama"
                        class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4"
                    >

                    <div id="hama-suggestion"
                        class="hidden bg-white border border-neutral-200 rounded-xl shadow-lg mt-1 max-h-48 overflow-y-auto">
                    </div>
                </div>

                <!-- Hidden sync field -->
                <input type="hidden" id="real_target_id" name="target_id" value="">

                <!-- CF Pakar -->
                <div class="flex flex-col gap-1.5">
                    <label for="cf_pakar" class="text-xs font-bold text-neutral-700">Bobot CF Pakar (0.1 s/d 1.0)</label>
                    <input type="number" step="0.1" min="0.1" max="1.0" id="cf_pakar" name="cf_pakar" required value="0.8"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        onclick="syncTargetId()"
                        class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 rounded-full shadow-md transition mt-2 flex justify-center items-center"
                >
                    Simpan Rule CF
                </button>
            </form>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleTargets() {

    const type = document.getElementById("target_type").value;

    const penyakit = document.getElementById("penyakit-wrapper");
    const hama = document.getElementById("hama-wrapper");

    if(type === "penyakit"){
        penyakit.classList.remove("hidden");
        hama.classList.add("hidden");
    }else{
        penyakit.classList.add("hidden");
        hama.classList.remove("hidden");
    }
}

window.onload = function(){
    toggleTargets();
};

// ==================== GEJALA ====================

const gejalaInput = document.getElementById("nama_gejala");
const gejalaBox = document.getElementById("gejala-suggestion");

gejalaInput.addEventListener("keyup", function(){

    const keyword = this.value;

    if(keyword.length < 2){
        gejalaBox.classList.add("hidden");
        return;
    }

    fetch(`/admin/search/gejala?q=${keyword}`)
    .then(res => res.json())
    .then(data => {

        gejalaBox.innerHTML = "";

        if(data.length == 0){
            gejalaBox.innerHTML = `
                <div class="p-3 text-neutral-400 text-sm">
                    Gejala belum ada
                </div>
            `;
            gejalaBox.classList.remove("hidden");
            return;
        }

        gejalaBox.classList.remove("hidden");

        data.forEach(item => {

            gejalaBox.innerHTML += `
                <div class="p-3 hover:bg-neutral-100 cursor-pointer"
                    onclick="pilihGejala('${item.id}','${item.nama_gejala}')">

                    ${item.nama_gejala}

                </div>
            `;

        });

    });

});

function pilihGejala(id,nama){

    document.getElementById("gejala_id").value = id;
    gejalaInput.value = nama;
    gejalaBox.classList.add("hidden");

}

// ==================== PENYAKIT ====================

const penyakitInput = document.getElementById("nama_penyakit");
const penyakitBox = document.getElementById("penyakit-suggestion");

penyakitInput.addEventListener("keyup",function(){

    fetch(`/admin/search/penyakit?q=${this.value}`)
    .then(res=>res.json())
    .then(data=>{

        penyakitBox.innerHTML = "";

        if(data.length==0){

            penyakitBox.innerHTML = `
                <div class="p-3 text-neutral-400 text-sm">
                    Tidak ada penyakit yang cocok
                </div>
            `;

            penyakitBox.classList.remove("hidden");
            return;
        }

        penyakitBox.classList.remove("hidden");

        data.forEach(item=>{

            penyakitBox.innerHTML += `
                <div class="p-3 hover:bg-neutral-100 cursor-pointer"
                    onclick="pilihPenyakit('${item.nama_penyakit}')">

                    ${item.nama_penyakit}

                </div>
            `;

        });

    });

});

function pilihPenyakit(nama){

    penyakitInput.value = nama;
    penyakitBox.classList.add("hidden");

}

// ==================== HAMA ====================

const hamaInput = document.getElementById("nama_hama");
const hamaBox = document.getElementById("hama-suggestion");

hamaInput.addEventListener("keyup",function(){

    fetch(`/admin/search/hama?q=${this.value}`)
    .then(res=>res.json())
    .then(data=>{

        hamaBox.innerHTML = "";

        if(data.length==0){

            hamaBox.innerHTML = `
                <div class="p-3 text-neutral-400 text-sm">
                    Tidak ada hama yang cocok
                </div>
            `;

            hamaBox.classList.remove("hidden");
            return;
        }

        hamaBox.classList.remove("hidden");

        data.forEach(item=>{

            hamaBox.innerHTML += `
                <div class="p-3 hover:bg-neutral-100 cursor-pointer"
                    onclick="pilihHama('${item.nama_hama}')">

                    ${item.nama_hama}

                </div>
            `;

        });

    });

});

function pilihHama(nama){

    hamaInput.value = nama;
    hamaBox.classList.add("hidden");

}
</script>

@endsection
