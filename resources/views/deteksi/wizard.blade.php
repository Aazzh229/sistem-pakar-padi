@extends('layouts.app')

@section('title', 'Pilih Gejala - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex justify-between items-center mb-2">
            <h1 class="text-xl font-bold">Diagnosa Gejala (Ronde {{ $round }} / 3)</h1>
            <span class="bg-white/20 text-white text-[10px] font-bold px-3 py-1 rounded-full">
                {{ $selectedCount }} Terpilih
            </span>
        </div>
        <p class="text-white/80 text-xs font-light">
            @if($round == 1)
                Pilih gejala awal yang paling umum muncul pada tanaman padi Anda.
            @elseif($round == 2)
                Pilih gejala lanjutan yang terkait dengan dugaan hama/penyakit padi Anda.
            @else
                Pilih gejala sisa lainnya untuk mempresisikan hasil analisa certainty factor.
            @endif
        </p>
    </div>

    <!-- Symptoms Form -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col gap-4">
        
        <form action="{{ route('deteksi.wizard.process') }}" method="POST" id="wizard-form" class="flex flex-col gap-4">
            @csrf

            @if($symptoms->isEmpty())
                <div class="bg-white p-6 rounded-2xl text-center border border-neutral-100 shadow-md">
                    <p class="text-sm font-semibold text-neutral-800 mb-2">Semua gejala telah ditampilkan</p>
                    <p class="text-xs text-neutral-500 font-light mb-4">Silakan klik tombol di bawah ini untuk melihat hasil diagnosa.</p>
                </div>
            @else
                @foreach($symptoms as $g)
                    <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-md flex flex-col gap-3">
                        <div class="flex gap-2">
                            <span class="bg-[#E2F2EB] text-[#0A3D2A] text-xs font-bold px-2 py-0.5 rounded h-fit flex-shrink-0">
                                {{ $g->kode_gejala }}
                            </span>
                            <h3 class="font-bold text-neutral-800 text-sm leading-snug">{{ $g->nama_gejala }}</h3>
                        </div>

                        <!-- Certainty Dropdown Option -->
                        <div class="relative">
                            <select name="symptoms[{{ $g->id }}]" 
                                    class="w-full bg-neutral-50 border border-neutral-200 text-neutral-800 text-sm font-medium rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition-all appearance-none cursor-pointer"
                            >
                                <option value="0">-- Tidak Ada Gejala Ini --</option>
                                <option value="0.2">Tidak Yakin (Tingkat Keyakinan: 0.2)</option>
                                <option value="0.4">Sedikit Yakin (Tingkat Keyakinan: 0.4)</option>
                                <option value="0.6">Cukup Yakin (Tingkat Keyakinan: 0.6)</option>
                                <option value="0.8">Yakin (Tingkat Keyakinan: 0.8)</option>
                                <option value="1.0">Sangat Yakin (Tingkat Keyakinan: 1.0)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-neutral-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Bottom Control Buttons -->
            <div class="flex flex-col gap-3 mt-4">
                
                <button type="submit" name="diagnose" value="1" id="btn-diagnose"
                        class="{{ $selectedCount >= 1 ? '' : 'hidden' }} w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 rounded-full shadow-md transition flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="btn-diagnose-text">Lihat Hasil Diagnosa ({{ $selectedCount }} Gejala)</span>
                </button>

                @if(!$symptoms->isEmpty())
                    <button type="submit" 
                            class="w-full bg-white hover:bg-neutral-50 text-[#0E4E37] border border-[#0E4E37] text-sm font-bold py-3.5 rounded-full transition flex items-center justify-center gap-2"
                    >
                        Simpan & Lanjutkan
                    </button>
                @endif
            </div>
        </form>

        <!-- Next Symptoms Skip Form -->
        @if(!$symptoms->isEmpty())
            <form action="{{ route('deteksi.next-symptoms') }}" method="GET" class="w-full">
                @foreach($symptoms as $g)
                    <input type="hidden" name="current_ids[]" value="{{ $g->id }}">
                @endforeach
                <button type="submit" 
                        class="w-full bg-white hover:bg-neutral-50 text-neutral-600 border border-neutral-200 text-sm font-bold py-3.5 rounded-full transition flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                    Next Gejala (Lihat Gejala Lain)
                </button>
            </form>
        @endif

        <!-- Reset Form -->
        <form action="{{ route('deteksi.reset') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" 
                    class="w-full bg-neutral-200 hover:bg-neutral-300 text-neutral-700 text-sm font-semibold py-3 rounded-full transition flex items-center justify-center gap-2"
            >
                Batal / Reset Deteksi
            </button>
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
        const selects = document.querySelectorAll('select[name^="symptoms"]');
        const btnDiagnose = document.getElementById('btn-diagnose');
        const btnDiagnoseText = document.getElementById('btn-diagnose-text');
        const initialSelectedCount = {{ $selectedCount }};

        function updateDiagnoseButton() {
            let currentSelected = 0;
            selects.forEach(select => {
                if (parseFloat(select.value) > 0) {
                    currentSelected++;
                }
            });

            const totalSelected = initialSelectedCount + currentSelected;
            if (totalSelected >= 1) {
                btnDiagnose.classList.remove('hidden');
                btnDiagnoseText.textContent = `Lihat Hasil Diagnosa (${totalSelected} Gejala)`;
            } else {
                btnDiagnose.classList.add('hidden');
            }
        }

        selects.forEach(select => {
            select.addEventListener('change', updateDiagnoseButton);
        });

        // Run once on load to ensure state is correct
        updateDiagnoseButton();
    });
</script>
@endsection
