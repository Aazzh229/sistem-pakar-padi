@extends(Auth::user()->role === 'admin' ? 'layouts.admin' : 'layouts.app')

@section('title', 'Riwayat Diagnosa - Padiku')

@section('content')
@if(Auth::user()->role === 'admin')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-lg border border-neutral-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-[#0E4E37] hover:underline">Dashboard</a>
            <h1 class="mt-2 text-3xl font-black text-neutral-900">Riwayat Diagnosa</h1>
            <p class="mt-2 text-sm text-neutral-500">Daftar laporan hasil konsultasi sistem pakar yang pernah dilakukan.</p>
        </div>
        @if($histories->isNotEmpty())
            <div class="flex gap-2">
                <button type="button" form="history-delete-form" id="toggle-select-history" class="rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-sm font-bold text-neutral-700 transition hover:border-[#0E4E37] hover:text-[#0E4E37]">
                    Pilih Riwayat
                </button>
                <button type="submit" form="history-delete-form" id="delete-selected-history" disabled class="rounded-lg bg-neutral-200 px-4 py-2.5 text-sm font-bold text-neutral-400 transition cursor-not-allowed">
                    Hapus Terpilih
                </button>
            </div>
        @endif
    </section>

    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    @if($histories->isNotEmpty())
        <form action="{{ route('deteksi.history.delete') }}" method="POST" id="history-delete-form">
            @csrf
            <section class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="w-12 px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Pilih</th>
                                <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Tanggal</th>
                                <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Petani</th>
                                <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Hasil Teratas</th>
                                <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Gejala Cocok</th>
                                <th class="px-5 py-3 text-right text-xs font-black uppercase tracking-wider text-neutral-500">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 bg-white">
                            @foreach($histories as $history)
                                @php
                                    $hasilArray = is_array($history->hasil) ? $history->hasil : json_decode($history->hasil, true);
                                    $topResult = !empty($hasilArray) ? $hasilArray[0] : null;
                                @endphp
                                <tr class="hover:bg-neutral-50">
                                    <td class="px-5 py-4 align-top">
                                        <label class="history-select hidden">
                                            <input type="checkbox" name="history_ids[]" value="{{ $history->id }}" class="history-checkbox sr-only">
                                            <span class="w-5 h-5 border-2 border-neutral-300 rounded-lg flex items-center justify-center text-white transition">
                                                <svg class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </span>
                                        </label>
                                    </td>
                                    <td class="px-5 py-4 align-top text-sm text-neutral-500">{{ $history->created_at->format('d M Y, H:i') }} WIB</td>
                                    <td class="px-5 py-4 align-top text-sm font-bold text-neutral-900">{{ $history->user->name }}</td>
                                    <td class="px-5 py-4 align-top text-sm font-bold text-neutral-900">
                                        {{ $topResult['name'] ?? 'Tidak ada anomali terdeteksi' }}
                                    </td>
                                    <td class="px-5 py-4 align-top">
                                        @if(isset($topResult['matched_symptoms']))
                                            <div class="flex max-w-xl flex-wrap gap-1">
                                                @foreach($topResult['matched_symptoms'] as $ms)
                                                    <span class="rounded bg-neutral-100 px-2 py-1 text-xs font-bold text-neutral-600">
                                                        {{ $ms['kode'] }}: {{ $ms['nama'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-sm italic text-neutral-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 align-top text-right">
                                        <span class="block text-sm font-black text-[#0E4E37]">{{ $history->persentase }}%</span>
                                        @if($history->trashed())
                                            <span class="mt-1 inline-flex rounded border border-amber-200 bg-amber-50 px-2 py-1 text-[10px] font-black uppercase tracking-wider text-amber-700">
                                                Disembunyikan user
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </form>
    @else
        <section class="rounded-lg border border-neutral-200 bg-white p-10 text-center shadow-sm">
            <p class="text-sm font-bold text-neutral-500">Belum ada riwayat diagnosa yang tercatat.</p>
        </section>
    @endif
</div>
@else
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
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif
        
        @if($histories->isNotEmpty())
            <form action="{{ route('deteksi.history.delete') }}" method="POST" id="history-delete-form" class="flex flex-col gap-4">
                @csrf

                <div class="bg-white p-3 rounded-2xl border border-neutral-100 shadow-md flex items-center justify-between gap-3">
                    <button type="button" id="toggle-select-history" class="bg-neutral-100 text-neutral-700 text-xs font-bold px-4 py-2 rounded-full">
                        Pilih Riwayat
                    </button>
                    <button type="submit" id="delete-selected-history" disabled
                            class="bg-neutral-200 text-neutral-400 cursor-not-allowed text-xs font-bold px-4 py-2 rounded-full transition">
                        Hapus Terpilih
                    </button>
                </div>

        @endif

        @forelse($histories as $history)
            <div class="history-card bg-white p-5 rounded-2xl border border-neutral-100 shadow-md">
                <div class="flex justify-between items-start mb-3 border-b pb-2">
                    <div class="flex items-start gap-3 min-w-0">
                        <label class="history-select hidden mt-0.5">
                            <input type="checkbox" name="history_ids[]" value="{{ $history->id }}" class="history-checkbox sr-only">
                            <span class="w-5 h-5 border-2 border-neutral-300 rounded-lg flex items-center justify-center text-white transition">
                                <svg class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                        </label>
                        <div class="min-w-0">
                        <span class="text-[10px] text-neutral-400 block">{{ $history->created_at->format('d M Y, H:i') }} WIB</span>
                        @if(Auth::user()->role === 'admin')
                            <span class="text-xs font-bold text-[#0E4E37] block mt-0.5">Petani: {{ $history->user->name }}</span>
                        @endif
                        </div>
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

        @if($histories->isNotEmpty())
            </form>
        @endif

        <a href="{{ route('beranda') }}" class="w-full text-center bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-xs font-bold py-3.5 rounded-full transition my-6 block">
            Kembali ke Beranda
        </a>

    </div>
</div>
@endif
@endsection

@section('navigation')
    @if(Auth::user()->role !== 'admin')
        <x-bottom-nav active="deteksi" />
    @endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggle-select-history');
    const deleteButton = document.getElementById('delete-selected-history');
    const deleteForm = document.getElementById('history-delete-form');
    const selectors = document.querySelectorAll('.history-select');
    const checkboxes = document.querySelectorAll('.history-checkbox');

    if (!toggleButton || !deleteButton || !deleteForm) {
        return;
    }

    let selectMode = false;

    function selectedCount() {
        return Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
    }

    function updateDeleteButton() {
        const count = selectedCount();
        deleteButton.disabled = count === 0;

        if (count > 0) {
            deleteButton.classList.remove('bg-neutral-200', 'text-neutral-400', 'cursor-not-allowed');
            deleteButton.classList.add('bg-[#D85C30]', 'text-white');
            deleteButton.textContent = `Hapus (${count})`;
        } else {
            deleteButton.classList.add('bg-neutral-200', 'text-neutral-400', 'cursor-not-allowed');
            deleteButton.classList.remove('bg-[#D85C30]', 'text-white');
            deleteButton.textContent = 'Hapus Terpilih';
        }
    }

    function syncCheckboxVisual(checkbox) {
        const box = checkbox.nextElementSibling;
        const icon = box.querySelector('svg');

        if (checkbox.checked) {
            box.classList.remove('border-neutral-300');
            box.classList.add('border-[#0E4E37]', 'bg-[#0E4E37]');
            icon.classList.remove('hidden');
        } else {
            box.classList.add('border-neutral-300');
            box.classList.remove('border-[#0E4E37]', 'bg-[#0E4E37]');
            icon.classList.add('hidden');
        }
    }

    toggleButton.addEventListener('click', function () {
        selectMode = !selectMode;
        selectors.forEach(selector => selector.classList.toggle('hidden', !selectMode));
        toggleButton.textContent = selectMode ? 'Batal Pilih' : 'Pilih Riwayat';

        if (!selectMode) {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
                syncCheckboxVisual(checkbox);
            });
            updateDeleteButton();
        }
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            syncCheckboxVisual(checkbox);
            updateDeleteButton();
        });
    });

    deleteForm.addEventListener('submit', function (event) {
        const count = selectedCount();

        if (count === 0 || !confirm(`Sembunyikan ${count} riwayat diagnosa dari daftar?`)) {
            event.preventDefault();
        }
    });
});
</script>
@endsection
