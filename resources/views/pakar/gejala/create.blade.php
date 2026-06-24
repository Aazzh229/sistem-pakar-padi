@extends('layouts.app')

@section('title', 'Tambah Gejala Baru - SiPakar Padi')

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
            <h1 class="text-xl font-bold">Tambah Gejala Baru</h1>
        </div>
        <p class="text-white/80 text-xs font-light">
            Masukkan kode, nama gejala, dan deskripsi gejala baru untuk ditambahkan ke basis data.
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

        <form action="{{ route('pakar.gejala.store') }}" method="POST" class="bg-white p-6 rounded-2xl border border-neutral-100 shadow-md flex flex-col gap-4">
            @csrf

            <div>
                <label class="text-xs font-semibold text-neutral-600 mb-1.5 block">Kode Gejala</label>
                <input type="text" name="kode_gejala" value="{{ old('kode_gejala', $suggestedCode) }}" required
                       class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37] uppercase">
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600 mb-1.5 block">Nama Gejala</label>
                <input type="text" name="nama_gejala" value="{{ old('nama_gejala') }}" required placeholder="Contoh: Daun mengalami bercak kuning melingkar"
                       class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600 mb-1.5 block">Bagian/Kategori</label>
                <select name="kategori" required
                        class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">
                    <option value="Daun" {{ old('kategori') == 'Daun' ? 'selected' : '' }}>Daun</option>
                    <option value="Batang" {{ old('kategori') == 'Batang' ? 'selected' : '' }}>Batang</option>
                    <option value="Akar" {{ old('kategori') == 'Akar' ? 'selected' : '' }}>Akar</option>
                    <option value="Bulir / Malai" {{ old('kategori') == 'Bulir / Malai' ? 'selected' : '' }}>Bulir / Malai</option>
                    <option value="Seluruh Tanaman" {{ old('kategori') == 'Seluruh Tanaman' ? 'selected' : '' }}>Seluruh Tanaman</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-semibold text-neutral-600 mb-1.5 block">Deskripsi Detail Gejala (Opsional)</label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan secara mendetail bentuk fisik atau karakteristik gejala ini..."
                          class="w-full bg-neutral-50 border border-neutral-200 text-sm rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#0E4E37]">{{ old('deskripsi') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full bg-[#0E4E37] hover:bg-[#12583F] text-white text-sm font-bold py-3.5 rounded-full shadow-lg transition flex items-center justify-center gap-2 mt-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambahkan Gejala Baru
            </button>
        </form>
    </div>
</div>
@endsection
