@extends('layouts.app')

@section('title', 'Riwayat Diagnosa - SiPakar Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-10 text-white relative">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('beranda') }}" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold">Riwayat Diagnosa</h1>
        </div>
        <p class="text-white/80 text-xs font-light">
            Daftar laporan hasil konsultasi sistem pakar yang pernah dilakukan sebelumnya.
        </p>
    </div>

    <!-- History List -->
    <div class="px-6 -mt-4 relative z-10 flex flex-col gap-4">
        
        @forelse($histories as $history)
            <div class="bg-white p-5 rounded-2xl border border-neutral-100 shadow-md">
                <div class="flex justify-between items-start mb-3 border-b pb-2">
                    <div>
                        <span class="text-[10px] text-neutral-400 block">{{ $history->created_at->format('d M Y, H:i') }} WIB</span>
                        @if(Auth::user()->role === 'admin')
                            <span class="text-xs font-bold text-[#0E4E37] block mt-0.5">Petani: {{ $history->user->name }}</span>
                        @endif
                    </div>
                    <span class="text-lg font-extrabold text-[#0E4E37]">{{ $history->persentase }}%</span>
                </div>

                <!-- Hasil Diagnosis details -->
                @php
                    $hasilArray = is_array($history->hasil) ? $history->hasil : json_decode($history->hasil, true);
                @endphp

                @if(!empty($hasilArray))
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider">Hasil Teratas:</span>
                            <span class="text-sm font-bold text-neutral-800">{{ $hasilArray[0]['name'] }}</span>
                            
                            <!-- Matched symptoms list -->
                            @if(isset($hasilArray[0]['matched_symptoms']))
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($hasilArray[0]['matched_symptoms'] as $ms)
                                        <span class="bg-neutral-100 text-neutral-600 text-[9px] px-1.5 py-0.5 rounded">
                                            {{ $ms['kode'] }}: {{ $ms['nama'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- All possibilities -->
                        @if(count($hasilArray) > 1)
                            <div class="border-t pt-2 mt-1">
                                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block mb-1">Kemungkinan Lainnya:</span>
                                <div class="flex flex-col gap-1">
                                    @foreach(array_slice($hasilArray, 1, 2) as $alt)
                                        <div class="flex justify-between text-xs text-neutral-600">
                                            <span class="font-light">{{ $alt['name'] }}</span>
                                            <span class="font-bold text-[#0E4E37]">{{ $alt['percentage'] }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('ensiklopedia.show', $hasilArray[0]['slug']) }}" class="w-full text-center border border-neutral-200 hover:bg-neutral-50 text-neutral-700 text-xs font-bold py-2 rounded-xl transition mt-2 block">
                            Lihat Penanganan Detail
                        </a>
                    </div>
                @else
                    <span class="text-xs text-neutral-500 italic">Tidak ada anomali terdeteksi.</span>
                @endif

            </div>
        @empty
            <div class="bg-white border border-neutral-100 p-8 rounded-2xl text-center shadow-md">
                <svg class="w-12 h-12 text-neutral-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path>
                </svg>
                <p class="text-xs text-neutral-500 font-medium">Belum ada riwayat diagnosa yang tercatat.</p>
            </div>
        @endforelse

        <a href="{{ route('beranda') }}" class="w-full text-center bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-xs font-bold py-3.5 rounded-full transition my-6 block">
            Kembali ke Beranda
        </a>

    </div>
</div>
@endsection

@section('navigation')
    <x-bottom-nav active="deteksi" />
@endsection
