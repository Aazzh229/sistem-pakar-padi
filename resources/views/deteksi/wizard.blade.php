@extends('layouts.app')

@section('title', 'Pilih Gejala - Padiku')

@section('content')
<div class="flex flex-col w-full text-neutral-800 pb-24">
    <div id="limit-popup" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 px-6">
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl border border-neutral-100 p-5 text-center">
            <div class="mx-auto mb-3 w-10 h-10 rounded-full bg-[#FDECE8] text-[#D85C30] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                </svg>
            </div>
            <h2 class="text-sm font-bold text-neutral-800 mb-1">Batas Gejala Terpilih</h2>
            <p class="text-xs text-neutral-500 leading-relaxed mb-4">Diagnosis hanya dapat menggunakan maksimal 3 gejala tanaman.</p>
            <button type="button" id="limit-popup-close" class="w-full bg-[#0E4E37] text-white text-xs font-bold py-3 rounded-full">
                Mengerti
            </button>
        </div>
    </div>

    <div id="exit-popup" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 px-6">
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl border border-neutral-100 p-5 text-center">
            <div class="mx-auto mb-3 w-10 h-10 rounded-full bg-[#FDECE8] text-[#D85C30] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                </svg>
            </div>
            <h2 class="text-sm font-bold text-neutral-800 mb-1">Keluar dari Diagnosis?</h2>
            <p class="text-xs text-neutral-500 leading-relaxed mb-4">Gejala yang sudah dipilih akan dibatalkan jika Anda kembali.</p>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" id="exit-popup-cancel" class="w-full bg-neutral-100 text-neutral-600 text-xs font-bold py-3 rounded-full">
                    Lanjut Pilih
                </button>
                <a href="{{ route('deteksi.index') }}" class="w-full bg-[#D85C30] text-white text-xs font-bold py-3 rounded-full">
                    Keluar
                </a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center gap-2 min-w-0">
                <a href="{{ route('deteksi.index') }}" id="diagnosis-back" class="text-white/80 hover:text-white transition flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-bold truncate">Pilih Gejala Tanaman</h1>
            </div>
            <span id="selected-badge" class="bg-white/20 text-white text-[10px] font-bold px-3 py-1 rounded-full">
                0 Terpilih
            </span>
        </div>
        <p class="text-white/80 text-xs font-light">
            Pilih 1 sampai 3 gejala yang paling terlihat pada tanaman padi Anda, lalu tentukan tingkat keyakinannya.
        </p>

        @if(Auth::check() && (Auth::user()->role === 'pakar' || Auth::user()->role === 'admin'))
            <div class="mt-4 flex gap-2">
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('pakar.dashboard') }}" 
                   class="inline-flex items-center gap-1 text-[11px] font-bold text-white bg-white/25 hover:bg-white/35 px-3 py-1.5 rounded-full transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        @endif
    </div>

    <!-- Main Container -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col gap-4">
        
        <!-- Search and Category Filters -->
        <div class="bg-white p-4 rounded-2xl border border-neutral-100 shadow-md flex flex-col gap-3">
            <!-- Search Bar -->
            <div class="flex items-center gap-2">
                <div class="relative flex-1 min-w-0">
                    <svg class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="search-symptoms" placeholder="Cari gejala..."
                           class="w-full bg-neutral-50 border border-neutral-200 text-neutral-800 text-xs rounded-xl py-2.5 pl-9 pr-3 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition-all">
                </div>
                <button type="submit" form="wizard-form" id="btn-diagnose" disabled
                        class="shrink-0 bg-neutral-300 text-neutral-500 cursor-not-allowed text-xs font-bold px-3.5 py-2.5 rounded-xl shadow-sm transition-all flex items-center justify-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="btn-diagnose-text">Diagnosa</span>
                </button>
            </div>
            
            <!-- Category Filter Pills -->
            <div class="flex overflow-x-auto gap-2 no-scrollbar pb-1">
                <button type="button" data-category="semua" class="filter-pill bg-[#0E4E37] text-white text-[10px] font-bold px-3.5 py-1.5 rounded-full whitespace-nowrap transition-all shadow-sm">Semua</button>
                <button type="button" data-category="Daun" class="filter-pill bg-neutral-100 text-neutral-600 text-[10px] font-bold px-3.5 py-1.5 rounded-full whitespace-nowrap transition-all hover:bg-neutral-200">Daun</button>
                <button type="button" data-category="Batang" class="filter-pill bg-neutral-100 text-neutral-600 text-[10px] font-bold px-3.5 py-1.5 rounded-full whitespace-nowrap transition-all hover:bg-neutral-200">Batang</button>
                <button type="button" data-category="Malai" class="filter-pill bg-neutral-100 text-neutral-600 text-[10px] font-bold px-3.5 py-1.5 rounded-full whitespace-nowrap transition-all hover:bg-neutral-200">Malai</button>
                <button type="button" data-category="Tanaman" class="filter-pill bg-neutral-100 text-neutral-600 text-[10px] font-bold px-3.5 py-1.5 rounded-full whitespace-nowrap transition-all hover:bg-neutral-200">Tanaman</button>
            </div>
        </div>

        <!-- Form Diagnosa -->
        <form action="{{ route('deteksi.wizard.process') }}" method="POST" id="wizard-form" class="flex flex-col gap-4">
            @csrf

            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Symptoms List Wrapper -->
            <div class="flex flex-col gap-3.5" id="symptoms-list">
                @forelse($symptoms as $g)
                    <div class="symptom-card bg-white p-5 rounded-2xl border border-neutral-100 shadow-md flex flex-col gap-3.5 transition-all duration-200 hover:border-neutral-200" 
                         data-id="{{ $g->id }}" 
                         data-code="{{ strtolower($g->kode_gejala) }}" 
                         data-name="{{ strtolower($g->nama_gejala) }}" 
                         data-category="{{ $g->kategori }}">
                        
                        <label class="flex items-start gap-3.5 cursor-pointer select-none">
                            <!-- Custom Checkbox -->
                            <div class="relative flex items-center justify-center mt-0.5 flex-shrink-0">
                                <input type="checkbox" id="check-{{ $g->id }}" class="sr-only peer symptom-checkbox" data-target="input-{{ $g->id }}" data-id="{{ $g->id }}">
                                <div class="w-5.5 h-5.5 border-2 border-neutral-300 rounded-lg peer-checked:border-[#0E4E37] peer-checked:bg-[#0E4E37] transition-all flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white scale-0 peer-checked:scale-100 transition-all" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex flex-col gap-1 flex-grow">
                                <div class="flex gap-2 items-center">
                                    <span class="bg-[#E2F2EB] text-[#0A3D2A] text-[9px] font-bold px-2 py-0.5 rounded-md">
                                        {{ $g->kode_gejala }}
                                    </span>
                                    <span class="text-[9px] text-[#2F8966] font-bold bg-[#E8F3ED] px-2 py-0.5 rounded-md uppercase tracking-wider">{{ $g->kategori }}</span>
                                </div>
                                <h3 class="font-bold text-neutral-800 text-sm leading-snug mt-0.5">{{ $g->nama_gejala }}</h3>
                                @if($g->deskripsi)
                                    <p class="text-[11px] text-neutral-400 font-light mt-0.5 leading-relaxed">{{ $g->deskripsi }}</p>
                                @endif
                            </div>
                        </label>

                        <!-- Certainty Option (Expands when checked) -->
                        <div id="cf-container-{{ $g->id }}" class="hidden overflow-hidden border-t border-neutral-100 pt-3.5 flex flex-col gap-1.5">
                            <label for="input-{{ $g->id }}" class="text-xs font-bold text-neutral-700">Tingkat Keyakinan Gejala (0.1 s/d 1.0)</label>
                            <input
                                type="number"
                                name="symptoms[{{ $g->id }}]"
                                id="input-{{ $g->id }}"
                                min="0.1"
                                max="1.0"
                                step="0.1"
                                inputmode="decimal"
                                value="0.8"
                                disabled
                                class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition-all symptom-cf-value"
                            >
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-6 rounded-2xl text-center border border-neutral-100 shadow-md">
                        <p class="text-sm font-semibold text-neutral-800 mb-2">Tidak ada gejala tersedia</p>
                    </div>
                @endforelse
            </div>
        </form>
    </div>
</div>
@endsection

@section('navigation')
    <x-bottom-nav active="deteksi" />
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-symptoms');
    const filterPills = document.querySelectorAll('.filter-pill');
    const symptomCards = document.querySelectorAll('.symptom-card');
    const selectedBadge = document.getElementById('selected-badge');
    const btnDiagnose = document.getElementById('btn-diagnose');
    const btnDiagnoseText = document.getElementById('btn-diagnose-text');
    const checkboxes = document.querySelectorAll('.symptom-checkbox');
    const limitPopup = document.getElementById('limit-popup');
    const limitPopupClose = document.getElementById('limit-popup-close');
    const exitPopup = document.getElementById('exit-popup');
    const exitPopupCancel = document.getElementById('exit-popup-cancel');
    const diagnosisBack = document.getElementById('diagnosis-back');
    const maxSymptoms = 3;

    let activeCategory = 'semua';
    let searchQuery = '';

    function normalizeCfValue(input) {
        const rawValue = input.value.trim();
        const parsed = parseFloat(rawValue);
        const min = 0.1;
        const max = 1.0;

        if (!rawValue || Number.isNaN(parsed)) {
            input.value = '0.8';
            return;
        }

        const clampedValue = Math.min(max, Math.max(min, parsed));
        input.value = Number(clampedValue.toFixed(1)).toFixed(1);
    }

    function showLimitPopup() {
        limitPopup.classList.remove('hidden');
        limitPopup.classList.add('flex');
    }

    function hideLimitPopup() {
        limitPopup.classList.add('hidden');
        limitPopup.classList.remove('flex');
    }

    function selectedSymptomsCount() {
        return Array.from(checkboxes).filter(item => item.checked).length;
    }

    function showExitPopup() {
        exitPopup.classList.remove('hidden');
        exitPopup.classList.add('flex');
    }

    function hideExitPopup() {
        exitPopup.classList.add('hidden');
        exitPopup.classList.remove('flex');
    }

    limitPopupClose.addEventListener('click', hideLimitPopup);
    limitPopup.addEventListener('click', function (event) {
        if (event.target === limitPopup) {
            hideLimitPopup();
        }
    });

    exitPopupCancel.addEventListener('click', hideExitPopup);
    exitPopup.addEventListener('click', function (event) {
        if (event.target === exitPopup) {
            hideExitPopup();
        }
    });

    diagnosisBack.addEventListener('click', function (event) {
        if (selectedSymptomsCount() > 0) {
            event.preventDefault();
            showExitPopup();
        }
    });

    // Update Counter and Submit Button States
    function updateState() {
        let count = selectedSymptomsCount();

        // Update Badge Header
        selectedBadge.innerText = `${count} Terpilih`;

        // Update Submit Button
        if (count > 0 && count <= maxSymptoms) {
            btnDiagnose.removeAttribute('disabled');
            btnDiagnose.classList.remove('bg-neutral-300', 'text-neutral-500', 'cursor-not-allowed');
            btnDiagnose.classList.add('bg-[#0E4E37]', 'hover:bg-[#12583F]', 'text-white', 'cursor-pointer');
            btnDiagnoseText.innerText = `Diagnosa (${count})`;
        } else {
            btnDiagnose.setAttribute('disabled', 'true');
            btnDiagnose.classList.add('bg-neutral-300', 'text-neutral-500', 'cursor-not-allowed');
            btnDiagnose.classList.remove('bg-[#0E4E37]', 'hover:bg-[#12583F]', 'text-white', 'cursor-pointer');
            btnDiagnoseText.innerText = 'Diagnosa';
        }
    }

    // Filter List based on Search & Category Pill
    function filterList() {
        symptomCards.forEach(card => {
            const cat = card.getAttribute('data-category');
            const code = card.getAttribute('data-code');
            const name = card.getAttribute('data-name');
            
            const matchCategory = (activeCategory === 'semua' || cat === activeCategory);
            const matchSearch = (code.includes(searchQuery) || name.includes(searchQuery));
            
            if (matchCategory && matchSearch) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // 1. Search Filter Listener
    searchInput.addEventListener('input', function () {
        searchQuery = searchInput.value.toLowerCase().trim();
        filterList();
    });

    // 2. Category Pill Filter Listener
    filterPills.forEach(pill => {
        pill.addEventListener('click', function () {
            // Toggle active visual class
            filterPills.forEach(p => {
                p.classList.remove('bg-[#0E4E37]', 'text-white', 'shadow-sm');
                p.classList.add('bg-neutral-100', 'text-neutral-600');
            });
            pill.classList.remove('bg-neutral-100', 'text-neutral-600');
            pill.classList.add('bg-[#0E4E37]', 'text-white', 'shadow-sm');

            activeCategory = pill.getAttribute('data-category');
            filterList();
        });
    });

    // 3. Checkbox Listener (Slides open certainty block)
    checkboxes.forEach(chk => {
        chk.addEventListener('change', function () {
            const id = chk.getAttribute('data-id');
            const container = document.getElementById(`cf-container-${id}`);
            const input = document.getElementById(`input-${id}`);
            const selectedCount = selectedSymptomsCount();

            if (chk.checked && selectedCount > maxSymptoms) {
                chk.checked = false;
                container.classList.add('hidden');
                input.setAttribute('disabled', 'true');
                updateState();
                showLimitPopup();
                return;
            }

            if (chk.checked) {
                // Show container
                container.classList.remove('hidden');
                // Enable input
                input.removeAttribute('disabled');
                // Set default value if empty
                if (!input.value || input.value == "0") {
                    input.value = "0.8";
                }
                normalizeCfValue(input);
            } else {
                // Hide container
                container.classList.add('hidden');
                // Disable input so it's not submitted
                input.setAttribute('disabled', 'true');
            }
            updateState();
        });
    });

    // Update CF badge as user types
    document.querySelectorAll('.symptom-cf-value').forEach(input => {
        input.addEventListener('input', function () {
            normalizeCfValue(input);
        });

        input.addEventListener('change', function () {
            normalizeCfValue(input);
        });
    });

    // Restore selected states if pre-existing in session
    @if(!empty($selected))
        @foreach($selected as $sId => $cfVal)
            const chk_{{ $sId }} = document.getElementById('check-{{ $sId }}');
            if (chk_{{ $sId }}) {
                chk_{{ $sId }}.checked = true;
                const container = document.getElementById('cf-container-{{ $sId }}');
                const input = document.getElementById('input-{{ $sId }}');
                container.classList.remove('hidden');
                input.removeAttribute('disabled');
                input.value = "{{ $cfVal }}";
                normalizeCfValue(input);
            }
        @endforeach
        updateState();
    @endif
});
</script>
@endsection
