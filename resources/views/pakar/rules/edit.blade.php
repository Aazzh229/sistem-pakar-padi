@extends('layouts.app')

@section('title', 'Ubah Rule CF - Padiku')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('pakar.rules.index') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Ubah Aturan</h1>
        </div>
        <p class="text-white/80 text-xs font-light">
            Perbarui pemetaan gejala ke hama/penyakit beserta nilai CF pakar.
        </p>
    </div>

    <!-- Rule Form -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col mb-8">
        <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-lg">
            
            <form action="{{ route('pakar.rules.update', $rule->id) }}" method="POST" class="flex flex-col gap-4">
                @csrf

                <!-- Gejala -->
                <div class="flex flex-col gap-1.5">
                    <label for="gejala_id" class="text-xs font-bold text-neutral-700">Pilih Gejala</label>
                    <select id="gejala_id" name="gejala_id" required 
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                        @foreach($gejala as $g)
                            <option value="{{ $g->id }}" {{ $rule->gejala_id == $g->id ? 'selected' : '' }}>{{ $g->kode_gejala }}: {{ $g->nama_gejala }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Target Type -->
                <div class="flex flex-col gap-1.5">
                    <label for="target_type" class="text-xs font-bold text-neutral-700">Jenis Target</label>
                    <select id="target_type" name="target_type" required 
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                            onchange="toggleTargets()"
                    >
                        <option value="penyakit" {{ $rule->target_type === 'penyakit' ? 'selected' : '' }}>Penyakit</option>
                        <option value="hama" {{ $rule->target_type === 'hama' ? 'selected' : '' }}>Hama</option>
                    </select>
                </div>

                <!-- Target ID (Disease) -->
                <div class="flex flex-col gap-1.5" id="penyakit-wrapper">
                    <label for="target_penyakit" class="text-xs font-bold text-neutral-700">Pilih Penyakit</label>
                    <select id="target_penyakit" name="target_id_penyakit" 
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                        @foreach($penyakit as $p)
                            <option value="{{ $p->id }}" {{ $rule->target_type === 'penyakit' && $rule->target_id == $p->id ? 'selected' : '' }}>{{ $p->nama_penyakit }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Target ID (Pest) -->
                <div class="flex flex-col gap-1.5 hidden" id="hama-wrapper">
                    <label for="target_hama" class="text-xs font-bold text-neutral-700">Pilih Hama</label>
                    <select id="target_hama" name="target_id_hama" 
                            class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                        @foreach($hama as $h)
                            <option value="{{ $h->id }}" {{ $rule->target_type === 'hama' && $rule->target_id == $h->id ? 'selected' : '' }}>{{ $h->nama_hama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Hidden sync field -->
                <input type="hidden" id="real_target_id" name="target_id" value="{{ $rule->target_id }}">

                <!-- CF Pakar -->
                <div class="flex flex-col gap-1.5">
                    <label for="cf_pakar" class="text-xs font-bold text-neutral-700">Bobot CF Pakar (0.1 s/d 1.0)</label>
                    <input type="number" step="0.1" min="0.1" max="1.0" id="cf_pakar" name="cf_pakar" required value="{{ $rule->cf_pakar }}"
                           class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] focus:bg-white transition"
                    >
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        onclick="syncTargetId()"
                        class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 rounded-full shadow-md transition mt-2 flex justify-center items-center"
                >
                    Perbarui Aturan
                </button>
            </form>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleTargets() {
        const type = document.getElementById('target_type').value;
        const diseaseWrapper = document.getElementById('penyakit-wrapper');
        const pestWrapper = document.getElementById('hama-wrapper');

        if (type === 'penyakit') {
            diseaseWrapper.classList.remove('hidden');
            pestWrapper.classList.add('hidden');
        } else {
            diseaseWrapper.classList.add('hidden');
            pestWrapper.classList.remove('hidden');
        }
    }

    function syncTargetId() {
        const type = document.getElementById('target_type').value;
        let targetId = '';
        if (type === 'penyakit') {
            targetId = document.getElementById('target_penyakit').value;
        } else {
            targetId = document.getElementById('target_hama').value;
        }
        document.getElementById('real_target_id').value = targetId;
    }

    window.onload = function() {
        toggleTargets();
        syncTargetId();
    };
</script>
@endsection
