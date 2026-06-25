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
            Hubungkan penyakit atau hama target dengan gejala-gejalanya beserta Certainty Factor Pakar.
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
            <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-md flex flex-col gap-4">
                <h2 class="text-sm font-bold text-neutral-800 flex items-center gap-2">
                    <span class="w-6 h-6 bg-[#E2F2EB] text-[#0A3D2A] rounded-full flex items-center justify-center text-xs font-black">1</span>
                    Pilih Target Penyakit atau Hama
                </h2>

                <!-- Jenis Toggle (Penyakit vs Hama) -->
                <div class="flex gap-2" id="jenis-toggle">
                    <button type="button" id="btn-penyakit"
                            onclick="setJenis('penyakit')"
                            class="flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-[#0E4E37] text-white border-[#0E4E37]">
                        Penyakit
                    </button>
                    <button type="button" id="btn-hama"
                            onclick="setJenis('hama')"
                            class="flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-white text-[#8E9A94] border-neutral-200">
                        Hama
                    </button>
                </div>
                <input type="hidden" name="target_type" id="target_type" value="penyakit">

                <!-- Toggle Input Baru -->
                <div class="flex items-center gap-2 mt-1 py-1 border-t border-neutral-100 pt-3">
                    <input type="checkbox" id="is_new_target" name="is_new_target" value="1" onchange="toggleNewTarget(this)"
                           class="rounded text-[#0E4E37] focus:ring-[#0E4E37] h-4 w-4">
                    <label for="is_new_target" class="text-xs font-semibold text-neutral-600 cursor-pointer select-none">
                        Tulis Nama Target Baru (Jika tidak ada di daftar)
                    </label>
                </div>

                <!-- Dropdown Penyakit (Existing) -->
                <div id="penyakit-wrapper">
                    <label class="text-xs font-semibold text-neutral-500 block mb-1.5">Pilih Penyakit</label>
                    <select name="target_id_penyakit" id="target_penyakit"
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                        @foreach($penyakit as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_penyakit }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Hama (Existing) -->
                <div id="hama-wrapper" class="hidden">
                    <label class="text-xs font-semibold text-neutral-500 block mb-1.5">Pilih Hama</label>
                    <select name="target_id_hama" id="target_hama"
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                        @foreach($hama as $h)
                            <option value="{{ $h->id }}">{{ $h->nama_hama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Text Input for New Target (Hidden by default) -->
                <div id="new-target-wrapper" class="hidden">
                    <label class="text-xs font-semibold text-neutral-500 block mb-1.5" id="new-target-label">Nama Penyakit Baru</label>
                    <input type="text" name="new_target_name" placeholder="Masukkan nama target baru..."
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                </div>

                <input type="hidden" id="real_target_id" name="target_id" value="">
            </div>

            <!-- BAGIAN 2: Tambah Gejala -->
            <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-md flex flex-col gap-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-sm font-bold text-neutral-800 flex items-center gap-2">
                        <span class="w-6 h-6 bg-[#E2F2EB] text-[#0A3D2A] rounded-full flex items-center justify-center text-xs font-black">2</span>
                        Tambahkan Gejala-Gejala
                    </h2>
                </div>
                <p class="text-[10px] text-neutral-400 font-light ml-8 -mt-2">Minimal 1, maksimal 3 gejala. Nilai CF Pakar: 0.1 – 1.0</p>

                <!-- Gejala Rows Container -->
                <div id="gejala-container" class="flex flex-col gap-4">
                    <!-- Row 1 (always visible initially) -->
                    <div class="gejala-row bg-neutral-50 border border-neutral-100 p-4 rounded-xl flex flex-col gap-3" data-index="0">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-[#0A3D2A]">Gejala 1</span>
                            <div class="flex items-center gap-1.5">
                                <input type="checkbox" id="is_new_gejala_0" name="symptom_rows[0][is_new]" value="1" onchange="toggleGejalaRowInput(0, this)"
                                       class="rounded text-[#0E4E37] focus:ring-[#0E4E37] h-3.5 w-3.5">
                                <label for="is_new_gejala_0" class="text-[11px] font-bold text-neutral-500 cursor-pointer select-none">Tulis Gejala Baru</label>
                            </div>
                        </div>

                        <!-- Dropdown select existing -->
                        <div id="gejala-select-wrapper-0">
                            <label class="text-[10px] font-bold text-neutral-500 block mb-1">Pilih Gejala</label>
                            <select name="symptom_rows[0][gejala_id]"
                                    class="w-full bg-white border border-neutral-200 text-xs rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                                @foreach($gejala as $g)
                                    <option value="{{ $g->id }}">{{ $g->kode_gejala }}: {{ $g->nama_gejala }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Text input for new symptom -->
                        <div id="gejala-input-wrapper-0" class="hidden">
                            <label class="text-[10px] font-bold text-neutral-500 block mb-1">Nama Gejala Baru</label>
                            <input type="text" name="symptom_rows[0][new_name]" placeholder="Tulis nama gejala baru..."
                                   class="w-full bg-white border border-neutral-200 text-xs rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                        </div>

                        <!-- CF Pakar -->
                        <div class="flex items-center gap-2">
                            <label class="text-[10px] font-bold text-neutral-500 w-20 flex-shrink-0">CF Pakar:</label>
                            <select name="symptom_rows[0][cf]"
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

                <!-- Add Gejala Button -->
                <button type="button" id="btn-add-gejala" onclick="addGejalaRow()"
                        class="w-full border-2 border-dashed border-[#0E4E37]/30 text-[#0E4E37] text-xs font-bold py-3.5 rounded-xl hover:bg-[#0E4E37]/5 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Gejala Lain
                </button>
                <p class="text-[10px] text-neutral-400 text-center" id="gejala-count-info">1 dari 3 gejala ditambahkan</p>
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
    const gejalaOptions = @json($gejala->map(fn($g) => ['id' => $g->id, 'kode' => $g->kode_gejala, 'nama' => $g->nama_gejala]));
    let rowIndices = [0];
    let nextIndex = 1;

    function setJenis(type) {
        document.getElementById('target_type').value = type;
        const penyakitWrapper = document.getElementById('penyakit-wrapper');
        const hamaWrapper = document.getElementById('hama-wrapper');
        const btnPenyakit = document.getElementById('btn-penyakit');
        const btnHama = document.getElementById('btn-hama');
        const newTargetLabel = document.getElementById('new-target-label');

        if (type === 'penyakit') {
            btnPenyakit.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-[#0E4E37] text-white border-[#0E4E37]';
            btnHama.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-white text-[#8E9A94] border-neutral-200';
            newTargetLabel.textContent = 'Nama Penyakit Baru';
            
            if (!document.getElementById('is_new_target').checked) {
                penyakitWrapper.classList.remove('hidden');
                hamaWrapper.classList.add('hidden');
            }
        } else {
            btnPenyakit.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-white text-[#8E9A94] border-neutral-200';
            btnHama.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl border transition bg-[#0E4E37] text-white border-[#0E4E37]';
            newTargetLabel.textContent = 'Nama Hama Baru';

            if (!document.getElementById('is_new_target').checked) {
                penyakitWrapper.classList.add('hidden');
                hamaWrapper.classList.remove('hidden');
            }
        }
        syncTargetId();
    }

    function toggleNewTarget(checkbox) {
        const type = document.getElementById('target_type').value;
        const penyakitWrapper = document.getElementById('penyakit-wrapper');
        const hamaWrapper = document.getElementById('hama-wrapper');
        const newTargetWrapper = document.getElementById('new-target-wrapper');

        if (checkbox.checked) {
            penyakitWrapper.classList.add('hidden');
            hamaWrapper.classList.add('hidden');
            newTargetWrapper.classList.remove('hidden');
        } else {
            newTargetWrapper.classList.add('hidden');
            if (type === 'penyakit') {
                penyakitWrapper.classList.remove('hidden');
            } else {
                hamaWrapper.classList.remove('hidden');
            }
        }
        syncTargetId();
    }

    function toggleGejalaRowInput(index, checkbox) {
        const selectWrapper = document.getElementById(`gejala-select-wrapper-${index}`);
        const inputWrapper = document.getElementById(`gejala-input-wrapper-${index}`);

        if (checkbox.checked) {
            selectWrapper.classList.add('hidden');
            inputWrapper.classList.remove('hidden');
        } else {
            inputWrapper.classList.add('hidden');
            selectWrapper.classList.remove('hidden');
        }
    }

    function syncTargetId() {
        const isNew = document.getElementById('is_new_target').checked;
        if (isNew) {
            document.getElementById('real_target_id').value = '';
            return;
        }

        const type = document.getElementById('target_type').value;
        const id = type === 'penyakit'
            ? document.getElementById('target_penyakit').value
            : document.getElementById('target_hama').value;
        document.getElementById('real_target_id').value = id;
    }

    function buildGejalaSelectOptions() {
        return gejalaOptions.map(g => `<option value="${g.id}">${g.kode}: ${g.nama}</option>`).join('');
    }

    function addGejalaRow() {
        if (rowIndices.length >= 3) return;

        const currentIdx = nextIndex;
        rowIndices.push(currentIdx);
        nextIndex++;

        const container = document.getElementById('gejala-container');
        const row = document.createElement('div');
        row.className = 'gejala-row bg-neutral-50 border border-neutral-100 p-4 rounded-xl flex flex-col gap-3';
        row.setAttribute('data-index', currentIdx);
        row.id = `gejala-row-${currentIdx}`;

        row.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#0A3D2A]">Gejala ${rowIndices.length}</span>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1">
                        <input type="checkbox" id="is_new_gejala_${currentIdx}" name="symptom_rows[${currentIdx}][is_new]" value="1" onchange="toggleGejalaRowInput(${currentIdx}, this)"
                               class="rounded text-[#0E4E37] focus:ring-[#0E4E37] h-3.5 w-3.5">
                        <label for="is_new_gejala_${currentIdx}" class="text-[11px] font-bold text-neutral-500 cursor-pointer select-none">Tulis Baru</label>
                    </div>
                    <button type="button" onclick="removeGejalaRow(${currentIdx})" class="text-red-500 hover:text-red-700 text-[10px] font-bold flex items-center gap-0.5 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>

            <!-- Dropdown select existing -->
            <div id="gejala-select-wrapper-${currentIdx}">
                <label class="text-[10px] font-bold text-neutral-500 block mb-1">Pilih Gejala</label>
                <select name="symptom_rows[${currentIdx}][gejala_id]"
                        class="w-full bg-white border border-neutral-200 text-xs rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                    ${buildGejalaSelectOptions()}
                </select>
            </div>

            <!-- Text input for new symptom -->
            <div id="gejala-input-wrapper-${currentIdx}" class="hidden">
                <label class="text-[10px] font-bold text-neutral-500 block mb-1">Nama Gejala Baru</label>
                <input type="text" name="symptom_rows[${currentIdx}][new_name]" placeholder="Tulis nama gejala baru..."
                       class="w-full bg-white border border-neutral-200 text-xs rounded-lg py-2.5 px-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
            </div>

            <!-- CF Pakar -->
            <div class="flex items-center gap-2">
                <label class="text-[10px] font-bold text-neutral-500 w-20 flex-shrink-0">CF Pakar:</label>
                <select name="symptom_rows[${currentIdx}][cf]"
                        class="flex-1 bg-white border border-neutral-200 text-xs rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
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

    function removeGejalaRow(index) {
        const row = document.getElementById(`gejala-row-${index}`);
        if (row) {
            row.remove();
            rowIndices = rowIndices.filter(i => i !== index);
            
            // Re-label remaining rows
            document.querySelectorAll('#gejala-container .gejala-row').forEach((r, i) => {
                const label = r.querySelector('span');
                if (label) label.textContent = `Gejala ${i + 1}`;
            });
            
            updateUI();
        }
    }

    function updateUI() {
        const addBtn = document.getElementById('btn-add-gejala');
        const countInfo = document.getElementById('gejala-count-info');
        const count = rowIndices.length;

        addBtn.disabled = count >= 3;
        addBtn.classList.toggle('opacity-40', count >= 3);
        addBtn.classList.toggle('cursor-not-allowed', count >= 3);
        countInfo.textContent = `${count} dari 3 gejala ditambahkan`;
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
