@extends('layouts.app')

@section('title', 'Input Basis Pengetahuan - SiPakar Padi')

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
            <h1 class="text-xl font-bold">Input Basis Pengetahuan</h1>
        </div>
        <p class="text-white/80 text-xs font-light">
            Pilih penyakit/hama target, lalu tambahkan gejala beserta nilai CF Pakar.
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

        <form action="{{ route('pakar.rules.store') }}" method="POST" id="rulesForm" class="flex flex-col gap-5">
            @csrf

            <!-- BAGIAN 1: Pilih Target -->
            <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-md">
                <h2 class="text-sm font-bold text-neutral-800 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#E2F2EB] text-[#0A3D2A] rounded-full flex items-center justify-center text-xs font-black">1</span>
                    Pilih Penyakit atau Hama
                </h2>

                <!-- Jenis Toggle -->
                <div class="flex gap-2 mb-4" id="jenis-toggle">
                    <button type="button" id="btn-penyakit"
                            onclick="setJenis('penyakit')"
                            class="flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-[#0E4E37] text-white border-[#0E4E37]">
                        Penyakit
                    </button>
                    <button type="button" id="btn-hama"
                            onclick="setJenis('hama')"
                            class="flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-white text-neutral-500 border-neutral-200">
                        Hama
                    </button>
                </div>
                <input type="hidden" name="target_type" id="target_type" value="penyakit">

                <!-- Dropdown Penyakit -->
                <div id="penyakit-wrapper">
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="text-xs font-semibold text-neutral-600 block">Nama Penyakit</label>
                        <a href="{{ route('pakar.penyakit.create') }}" class="text-[10px] text-[#0E4E37] font-bold hover:underline flex items-center gap-0.5">
                            + Tambah Penyakit Baru
                        </a>
                    </div>
                    <select name="target_id_penyakit" id="target_penyakit"
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                        @foreach($penyakit as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_penyakit }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Hama (hidden by default) -->
                <div id="hama-wrapper" class="hidden">
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="text-xs font-semibold text-neutral-600 block">Nama Hama</label>
                        <a href="{{ route('pakar.hama.create') }}" class="text-[10px] text-[#0E4E37] font-bold hover:underline flex items-center gap-0.5">
                            + Tambah Hama Baru
                        </a>
                    </div>
                    <select name="target_id_hama" id="target_hama"
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                        @foreach($hama as $h)
                            <option value="{{ $h->id }}">{{ $h->nama_hama }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" id="real_target_id" name="target_id" value="">
            </div>

            <!-- BAGIAN 2: Tambah Gejala -->
            <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-md">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-sm font-bold text-neutral-800 flex items-center gap-2">
                        <span class="w-6 h-6 bg-[#E2F2EB] text-[#0A3D2A] rounded-full flex items-center justify-center text-xs font-black">2</span>
                        Tambahkan Gejala
                    </h2>
                    <a href="{{ route('pakar.gejala.create') }}" class="text-[10px] text-[#0E4E37] font-bold hover:underline flex items-center gap-0.5">
                        + Tambah Gejala Baru
                    </a>
                </div>
                <p class="text-[10px] text-neutral-400 font-light mb-4 ml-8">Minimal 1, maksimal 3 gejala. Nilai CF Pakar: 0.1 – 1.0</p>

                <!-- Gejala Rows Container -->
                <div id="gejala-container" class="flex flex-col gap-3">

                    <!-- Row 1 (always visible) -->
                    <div class="gejala-row bg-neutral-50 border border-neutral-100 p-3 rounded-xl flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-[#0A3D2A] uppercase tracking-wider">Gejala 1</span>
                        </div>
                        <select name="gejala_ids[]"
                                class="w-full bg-white border border-neutral-200 text-xs rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                            @foreach($gejala as $g)
                                <option value="{{ $g->id }}">{{ $g->kode_gejala }}: {{ $g->nama_gejala }}</option>
                            @endforeach
                        </select>
                        <div class="flex items-center gap-2">
                            <label class="text-[10px] font-bold text-neutral-600 w-20 flex-shrink-0">CF Pakar:</label>
                            <select name="cf_pakar_list[]"
                                    class="flex-1 bg-white border border-neutral-200 text-xs rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                                <option value="0.2">0.2 – Tidak Yakin</option>
                                <option value="0.4">0.4 – Sedikit Yakin</option>
                                <option value="0.6">0.6 – Cukup Yakin</option>
                                <option value="0.8" selected>0.8 – Yakin</option>
                                <option value="1.0">1.0 – Sangat Yakin</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Tombol + Tambah Gejala -->
                <button type="button" id="btn-add-gejala"
                        onclick="addGejalaRow()"
                        class="mt-3 w-full border-2 border-dashed border-[#0E4E37]/30 text-[#0E4E37] text-xs font-bold py-3 rounded-xl hover:bg-[#0E4E37]/5 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Gejala
                </button>
                <p class="text-[10px] text-neutral-400 text-center mt-1.5" id="gejala-count-info">1 dari 3 gejala ditambahkan</p>
            </div>

            <!-- Submit -->
            <button type="submit" onclick="syncForm()"
                    class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-4 rounded-full shadow-lg transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Basis Pengetahuan
            </button>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Gejala data from server
    const gejalaOptions = @json($gejala->map(fn($g) => ['id' => $g->id, 'kode' => $g->kode_gejala, 'nama' => $g->nama_gejala]));
    let gejalaCount = 1;

    function setJenis(type) {
        document.getElementById('target_type').value = type;
        const penyakitWrapper = document.getElementById('penyakit-wrapper');
        const hamaWrapper = document.getElementById('hama-wrapper');
        const btnPenyakit = document.getElementById('btn-penyakit');
        const btnHama = document.getElementById('btn-hama');

        if (type === 'penyakit') {
            penyakitWrapper.classList.remove('hidden');
            hamaWrapper.classList.add('hidden');
            btnPenyakit.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-[#0E4E37] text-white border-[#0E4E37]';
            btnHama.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-white text-neutral-500 border-neutral-200';
        } else {
            penyakitWrapper.classList.add('hidden');
            hamaWrapper.classList.remove('hidden');
            btnPenyakit.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-white text-neutral-500 border-neutral-200';
            btnHama.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-[#0E4E37] text-white border-[#0E4E37]';
        }
        syncTargetId();
    }

    function syncTargetId() {
        const type = document.getElementById('target_type').value;
        const id = type === 'penyakit'
            ? document.getElementById('target_penyakit').value
            : document.getElementById('target_hama').value;
        document.getElementById('real_target_id').value = id;
    }

    function buildSelectOptions(excludeIndex) {
        return gejalaOptions.map(g =>
            `<option value="${g.id}">${g.kode}: ${g.nama}</option>`
        ).join('');
    }

    function addGejalaRow() {
        if (gejalaCount >= 3) return;
        gejalaCount++;

        const container = document.getElementById('gejala-container');
        const row = document.createElement('div');
        row.className = 'gejala-row bg-neutral-50 border border-neutral-100 p-3 rounded-xl flex flex-col gap-2';
        row.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-[#0A3D2A] uppercase tracking-wider">Gejala ${gejalaCount}</span>
                <button type="button" onclick="removeGejalaRow(this)" class="text-red-400 hover:text-red-600 text-[10px] font-bold flex items-center gap-0.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Hapus
                </button>
            </div>
            <select name="gejala_ids[]" class="w-full bg-white border border-neutral-200 text-xs rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                ${buildSelectOptions()}
            </select>
            <div class="flex items-center gap-2">
                <label class="text-[10px] font-bold text-neutral-600 w-20 flex-shrink-0">CF Pakar:</label>
                <select name="cf_pakar_list[]" class="flex-1 bg-white border border-neutral-200 text-xs rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                    <option value="0.2">0.2 – Tidak Yakin</option>
                    <option value="0.4">0.4 – Sedikit Yakin</option>
                    <option value="0.6">0.6 – Cukup Yakin</option>
                    <option value="0.8" selected>0.8 – Yakin</option>
                    <option value="1.0">1.0 – Sangat Yakin</option>
                </select>
            </div>
        `;
        container.appendChild(row);
        updateUI();
    }

    function removeGejalaRow(btn) {
        const row = btn.closest('.gejala-row');
        row.remove();
        gejalaCount--;
        // Re-label remaining rows
        document.querySelectorAll('.gejala-row').forEach((r, i) => {
            const label = r.querySelector('span');
            if (label) label.textContent = `Gejala ${i + 1}`;
        });
        updateUI();
    }

    function updateUI() {
        const addBtn = document.getElementById('btn-add-gejala');
        const countInfo = document.getElementById('gejala-count-info');
        addBtn.disabled = gejalaCount >= 3;
        addBtn.classList.toggle('opacity-40', gejalaCount >= 3);
        addBtn.classList.toggle('cursor-not-allowed', gejalaCount >= 3);
        countInfo.textContent = `${gejalaCount} dari 3 gejala ditambahkan`;
    }

    function syncForm() {
        syncTargetId();
    }

    // Init on load
    document.getElementById('target_penyakit').addEventListener('change', syncTargetId);
    document.getElementById('target_hama').addEventListener('change', syncTargetId);
    window.addEventListener('load', () => {
        syncTargetId();
        updateUI();
    });
</script>
@endsection
