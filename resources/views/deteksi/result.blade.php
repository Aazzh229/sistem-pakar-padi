@extends('layouts.app')

@section('title', 'Hasil Diagnosa - Padiku')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex justify-between items-center mb-2">
            <h1 class="text-2xl font-bold">Hasil Diagnosa</h1>
            @if(Auth::check() && (Auth::user()->role === 'pakar' || Auth::user()->role === 'admin'))
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('pakar.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white text-[10px] font-bold px-3 py-1.5 rounded-full transition">
                    Dashboard
                </a>
            @endif
        </div>
        <p class="text-white/80 text-xs mt-1.5 font-light">
            Berdasarkan hasil analisa Certainty Factor (CF) dari gejala yang Anda masukkan.
        </p>
    </div>

    <!-- Selected Symptoms Summary -->
    <div class="px-6 -mt-4 relative z-10">
        <div class="bg-white p-4 rounded-2xl shadow-md border border-neutral-100 mb-6">
            <h3 class="font-bold text-neutral-800 text-xs mb-2 uppercase tracking-wide">Gejala yang Anda Pilih:</h3>
            <ul class="flex flex-col gap-1.5">
                @foreach($selected as $id => $cf)
                    @if($cf > 0)
                        @php
                            $gejala = \App\Models\Gejala::find($id);
                            $cfVal = (float)$cf;
                            if ($cfVal >= 0.8) {
                                $cfText = 'Sangat Yakin';
                            } elseif ($cfVal >= 0.4) {
                                $cfText = 'Yakin';
                            } else {
                                $cfText = 'Tidak Yakin';
                            }
                        @endphp
                        @if($gejala)
                            <li class="flex items-start gap-2">
                                <span class="bg-[#E2F2EB] text-[#0A3D2A] text-[9px] font-bold px-1.5 py-0.5 rounded mt-0.5 flex-shrink-0">
                                    {{ $gejala->kode_gejala }}
                                </span>
                                <span class="text-xs text-neutral-600 font-light">
                                    {{ $gejala->nama_gejala }}
                                    <strong class="text-[#0E4E37] font-semibold">(CF {{ number_format($cfVal, 1) }} · {{ $cfText }})</strong>
                                </span>
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>

        <!-- Diagnostic Rankings -->
        <h3 class="font-bold text-neutral-800 text-sm mb-4">Urutan Kemungkinan Terbesar:</h3>
        
        <div class="flex flex-col gap-6">
            @forelse($results as $res)
                @php
                    $isPenyakit = $res['type'] === 'penyakit';
                    $bgLight = $isPenyakit ? '#E2F2EB' : '#FDECE8';
                    $textDark = $isPenyakit ? '#0A3D2A' : '#D85C30';
                @endphp
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-lg overflow-hidden">
                    
                    <!-- Top Ribbon info with certainty bar -->
                    <div class="p-5 flex justify-between items-center border-b border-neutral-50" style="background-color: {{ $bgLight }}20;">
                        <div>
                            <span class="inline-block text-[9px] font-bold px-2 py-0.5 rounded-full mb-1" 
                                  style="background-color: {{ $bgLight }}; color: {{ $textDark }};">
                                {{ $isPenyakit ? 'Penyakit' : 'Hama' }}
                            </span>
                            <h4 class="font-bold text-neutral-800 text-base leading-tight">{{ $res['name'] }}</h4>
                        </div>
                        <!-- Big certainty percentage -->
                        <div class="text-right">
                            <span class="text-2xl font-bold block" style="color: {{ $textDark }};">{{ $res['percentage'] }}%</span>
                            <span class="text-[9px] text-neutral-400 font-medium uppercase">Keyakinan</span>
                        </div>
                    </div>

                    <!-- Details of result card -->
                    <div class="p-5 flex flex-col gap-4">
                        <!-- Progress bar visualization -->
                        <div class="w-full bg-neutral-100 h-2 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500" 
                                 style="width: {{ $res['percentage'] }}%; background-color: {{ $textDark }};"></div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <h5 class="text-xs font-bold text-neutral-800 mb-1">Deskripsi Singkat:</h5>
                            <p class="text-xs text-neutral-600 font-light leading-relaxed">{{ $res['description'] }}</p>
                        </div>

                        <!-- Gejala Cocok -->
                        <div>
                            <h5 class="text-xs font-bold text-neutral-800 mb-1">Gejala yang Cocok:</h5>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($res['matched_symptoms'] as $mg)
                                    <span class="bg-neutral-100 text-neutral-700 text-[10px] px-2 py-0.5 rounded-md font-medium">
                                        {{ $mg['kode'] }}: {{ $mg['nama'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Solusi -->
                        <div>
                            <h5 class="text-xs font-bold text-neutral-800 mb-1">Solusi Singkat:</h5>
                            <p class="text-xs text-neutral-600 font-light leading-relaxed">{{ $res['solusi'] }}</p>
                        </div>

                        <!-- View detail in encyclopedia button -->
                        <a href="{{ route('ensiklopedia.show', $res['slug']) }}" 
                           class="w-full text-center border py-2.5 rounded-xl text-xs font-bold transition hover:bg-neutral-50"
                           style="border-color: {{ $textDark }}; color: {{ $textDark }};"
                        >
                            Lihat Selengkapnya di Ensiklopedia
                        </a>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-neutral-100 p-8 rounded-2xl text-center shadow-md">
                    <p class="text-sm font-semibold text-neutral-700 mb-1">Tidak ada hasil yang memadai</p>
                    <p class="text-xs text-neutral-500 font-light">Kombinasi gejala yang Anda masukkan tidak mengarah pada hama atau penyakit terdaftar.</p>
                </div>
            @endforelse

            <!-- Action buttons -->
            <div class="flex flex-col gap-3 my-6">
                <form action="{{ route('deteksi.start') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 rounded-full shadow-md transition flex items-center justify-center gap-2">
                        Mulai Diagnosa Baru
                    </button>
                </form>

                <a href="{{ route('beranda') }}" class="w-full text-center bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-xs font-bold py-3.5 rounded-full transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('navigation')
    <x-bottom-nav active="deteksi" />
@endsection
