@extends('layouts.app')

@section('title', 'Pilih Gejala - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800 pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex justify-between items-center mb-2">
            <h1 class="text-xl font-bold">Pilih Gejala Tanaman</h1>
            <span id="selected-badge" class="bg-white/20 text-white text-[10px] font-bold px-3 py-1 rounded-full">
                0 Terpilih
            </span>
        </div>
        <p class="text-white/80 text-xs font-light">
            Pilih semua gejala yang terlihat pada tanaman padi Anda, lalu tentukan tingkat keyakinannya.
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
            <div class="relative w-full">
                <svg class="w-4 h-4 text-neutral-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" id="search-symptoms" placeholder="Cari nama atau kode gejala..." 
                       class="w-full bg-neutral-50 border border-neutral-200 text-neutral-800 text-xs rounded-xl py-3 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition-all">
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
                        <div id="cf-container-{{ $g->id }}" class="hidden overflow-hidden border-t border-neutral-100 pt-3.5 flex flex-col gap-2.5">
                            <div class="flex justify-between items-center">
                                <label class="text-[11px] font-bold text-neutral-500">Tingkat Keyakinan Gejala</label>
                                <span id="cf-val-label-{{ $g->id }}" class="text-[10px] text-[#0E4E37] font-extrabold bg-[#E2F2EB] px-2.5 py-1 rounded-md">0.0</span>
                            </div>
                            
                            <!-- Slider bar -->
                            <div class="flex gap-3 items-center">
                                <input
                                    type="range"
                                    id="slider-{{ $g->id }}"
                                    min="0"
                                    max="1"
                                    step="0.1"
                                    value="0"
                                    data-id="{{ $g->id }}"
                                    class="w-full accent-[#0E4E37] h-1.5 bg-neutral-100 rounded-lg cursor-pointer symptom-slider"
                                >
                                <input
                                    type="hidden"
                                    name="symptoms[{{ $g->id }}]"
                                    id="input-{{ $g->id }}"
                                    value="0"
                                    class="symptom-cf-value"
                                >
                            </div>
                            
                            <!-- Quick Preset Selector Pills -->
                            <div class="grid grid-cols-5 gap-1.5 mt-1">
                                <button type="button" data-val="0.2" data-id="{{ $g->id }}" class="cf-preset text-[9px] font-bold py-2 bg-neutral-50 text-neutral-500 border border-neutral-200 rounded-xl hover:bg-neutral-100 transition">0.2</button>
                                <button type="button" data-val="0.4" data-id="{{ $g->id }}" class="cf-preset text-[9px] font-bold py-2 bg-neutral-50 text-neutral-500 border border-neutral-200 rounded-xl hover:bg-neutral-100 transition">0.4</button>
                                <button type="button" data-val="0.6" data-id="{{ $g->id }}" class="cf-preset text-[9px] font-bold py-2 bg-neutral-50 text-neutral-500 border border-neutral-200 rounded-xl hover:bg-neutral-100 transition">0.6</button>
                                <button type="button" data-val="0.8" data-id="{{ $g->id }}" class="cf-preset text-[9px] font-bold py-2 bg-neutral-50 text-neutral-500 border border-neutral-200 rounded-xl hover:bg-neutral-100 transition">0.8</button>
                                <button type="button" data-val="1.0" data-id="{{ $g->id }}" class="cf-preset text-[9px] font-bold py-2 bg-neutral-50 text-neutral-500 border border-neutral-200 rounded-xl hover:bg-neutral-100 transition">1.0</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-6 rounded-2xl text-center border border-neutral-100 shadow-md">
                        <p class="text-sm font-semibold text-neutral-800 mb-2">Tidak ada gejala tersedia</p>
                    </div>
                @endforelse
            </div>

            <!-- Sticky Bottom / Actions -->
            <div class="flex flex-col gap-3 mt-6">
                <!-- Floating Submit Button -->
                <button type="submit" id="btn-diagnose" disabled
                        class="w-full bg-neutral-300 text-neutral-500 cursor-not-allowed text-sm font-bold py-4 rounded-full shadow-md transition-all flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="btn-diagnose-text">Pilih Gejala Terlebih Dahulu</span>
                </button>

                <!-- Cancel Button -->
                <a href="{{ route('deteksi.index') }}" 
                   class="w-full bg-neutral-100 hover:bg-neutral-200 text-neutral-600 text-sm font-semibold py-3.5 rounded-full transition flex items-center justify-center text-center shadow-sm"
                >
                    Kembali / Batal
                </a>
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
    const sliders = document.querySelectorAll('.symptom-slider');
    const presets = document.querySelectorAll('.cf-preset');

    let activeCategory = 'semua';
    let searchQuery = '';

    // CF Value Helper Text Map
    function getCfText(val) {
        if (val <= 0) return '0.0 (Tidak tahu)';
        if (val <= 0.2) return '0.2 (Kurang yakin)';
        if (val <= 0.4) return '0.4 (Cukup yakin)';
        if (val <= 0.6) return '0.6 (Yakin)';
        if (val <= 0.8) return '0.8 (Sangat yakin)';
        return '1.0 (Pasti)';
    }

    // Update Counter and Submit Button States
    function updateState() {
        let count = 0;
        checkboxes.forEach(chk => {
            if (chk.checked) count++;
        });

        // Update Badge Header
        selectedBadge.innerText = `${count} Terpilih`;

        // Update Submit Button
        if (count > 0) {
            btnDiagnose.removeAttribute('disabled');
            btnDiagnose.classList.remove('bg-neutral-300', 'text-neutral-500', 'cursor-not-allowed');
            btnDiagnose.classList.add('bg-[#0E4E37]', 'hover:bg-[#12583F]', 'text-white', 'cursor-pointer');
            btnDiagnoseText.innerText = `Mulai Analisa Diagnosa (${count} Gejala)`;
        } else {
            btnDiagnose.setAttribute('disabled', 'true');
            btnDiagnose.classList.add('bg-neutral-300', 'text-neutral-500', 'cursor-not-allowed');
            btnDiagnose.classList.remove('bg-[#0E4E37]', 'hover:bg-[#12583F]', 'text-white', 'cursor-pointer');
            btnDiagnoseText.innerText = 'Pilih Gejala Terlebih Dahulu';
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
            const slider = document.getElementById(`slider-${id}`);
            const label = document.getElementById(`cf-val-label-${id}`);

            if (chk.checked) {
                // Show container
                container.classList.remove('hidden');
                // Set default value to 1.0 (Pasti) when checked
                input.value = "1.0";
                slider.value = "1";
                label.innerText = getCfText(1.0);
                
                // Highlight the 1.0 preset button
                const presetsInCard = container.querySelectorAll('.cf-preset');
                presetsInCard.forEach(p => {
                    if (p.getAttribute('data-val') === "1.0") {
                        p.classList.remove('bg-neutral-50', 'text-neutral-500', 'border-neutral-200');
                        p.classList.add('bg-[#0E4E37]', 'text-white', 'border-[#0E4E37]');
                    } else {
                        p.classList.add('bg-neutral-50', 'text-neutral-500', 'border-neutral-200');
                        p.classList.remove('bg-[#0E4E37]', 'text-white', 'border-[#0E4E37]');
                    }
                });
            } else {
                // Hide container
                container.classList.add('hidden');
                // Reset value
                input.value = "0";
                slider.value = "0";
                label.innerText = getCfText(0);
                
                // Clear preset buttons highlight
                const presetsInCard = container.querySelectorAll('.cf-preset');
                presetsInCard.forEach(p => {
                    p.classList.add('bg-neutral-50', 'text-neutral-500', 'border-neutral-200');
                    p.classList.remove('bg-[#0E4E37]', 'text-white', 'border-[#0E4E37]');
                });
            }
            updateState();
        });
    });

    // 4. Slider Listener
    sliders.forEach(slider => {
        slider.addEventListener('input', function () {
            const id = slider.getAttribute('data-id');
            const input = document.getElementById(`input-${id}`);
            const label = document.getElementById(`cf-val-label-${id}`);
            const val = parseFloat(slider.value).toFixed(1);

            input.value = val;
            label.innerText = getCfText(val);

            // Update active preset button highlight
            const container = document.getElementById(`cf-container-${id}`);
            const presetsInCard = container.querySelectorAll('.cf-preset');
            presetsInCard.forEach(p => {
                const presetVal = parseFloat(p.getAttribute('data-val')).toFixed(1);
                if (presetVal === val) {
                    p.classList.remove('bg-neutral-50', 'text-neutral-500', 'border-neutral-200');
                    p.classList.add('bg-[#0E4E37]', 'text-white', 'border-[#0E4E37]');
                } else {
                    p.classList.add('bg-neutral-50', 'text-neutral-500', 'border-neutral-200');
                    p.classList.remove('bg-[#0E4E37]', 'text-white', 'border-[#0E4E37]');
                }
            });
        });
    });

    // 5. Preset Buttons Listener
    presets.forEach(p => {
        p.addEventListener('click', function () {
            const id = p.getAttribute('data-id');
            const val = p.getAttribute('data-val');
            const input = document.getElementById(`input-${id}`);
            const slider = document.getElementById(`slider-${id}`);
            const label = document.getElementById(`cf-val-label-${id}`);

            input.value = val;
            slider.value = val;
            label.innerText = getCfText(val);

            // Toggle active style in this card only
            const container = document.getElementById(`cf-container-${id}`);
            const presetsInCard = container.querySelectorAll('.cf-preset');
            presetsInCard.forEach(btn => {
                if (btn === p) {
                    btn.classList.remove('bg-neutral-50', 'text-neutral-500', 'border-neutral-200');
                    btn.classList.add('bg-[#0E4E37]', 'text-white', 'border-[#0E4E37]');
                } else {
                    btn.classList.add('bg-neutral-50', 'text-neutral-500', 'border-neutral-200');
                    btn.classList.remove('bg-[#0E4E37]', 'text-white', 'border-[#0E4E37]');
                }
            });
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
                const slider = document.getElementById('slider-{{ $sId }}');
                const label = document.getElementById('cf-val-label-{{ $sId }}');
                
                container.classList.remove('hidden');
                input.value = "{{ $cfVal }}";
                slider.value = "{{ $cfVal }}";
                label.innerText = getCfText({{ $cfVal }});

                // Highlight correct preset
                const presetsInCard = container.querySelectorAll('.cf-preset');
                presetsInCard.forEach(p => {
                    if (p.getAttribute('data-val') === "{{ $cfVal }}") {
                        p.classList.remove('bg-neutral-50', 'text-neutral-500', 'border-neutral-200');
                        p.classList.add('bg-[#0E4E37]', 'text-white', 'border-[#0E4E37]');
                    }
                });
            }
        @endforeach
        updateState();
    @endif
});
</script>
@endsection
