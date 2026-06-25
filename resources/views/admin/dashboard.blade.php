@extends('layouts.admin')

@section('title', 'Dashboard Admin - Padiku')

@section('content')
<div class="space-y-8">
    <section class="rounded-lg bg-white border border-neutral-200 shadow-sm">
        <div class="flex flex-col gap-6 px-6 py-7 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#0E4E37]">Selamat Datang, {{ Auth::user()->name }}</p>
                <h1 class="mt-2 text-3xl font-black text-neutral-900">Dashboard Admin</h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-neutral-500">
                    Pengendali penuh aplikasi. Kelola akun pengguna, bobot pakar CF, edukasi library, dan pantau riwayat diagnosa.
                </p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-sm font-bold text-neutral-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">
                    Keluar
                </button>
            </form>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm">
            <span class="text-sm font-bold text-neutral-500">Total Akun</span>
            <span class="mt-3 block text-4xl font-black text-[#0E4E37]">{{ $usersCount }}</span>
        </div>
        <div class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm">
            <span class="text-sm font-bold text-neutral-500">Rule CF</span>
            <span class="mt-3 block text-4xl font-black text-[#0E4E37]">{{ $rulesCount }}</span>
        </div>
        <div class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm">
            <span class="text-sm font-bold text-neutral-500">Artikel Library</span>
            <span class="mt-3 block text-4xl font-black text-[#0E4E37]">{{ $libraryCount }}</span>
        </div>
        <div class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm">
            <span class="text-sm font-bold text-neutral-500">Riwayat Diagnosa</span>
            <span class="mt-3 block text-4xl font-black text-[#0E4E37]">{{ $historyCount }}</span>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <a href="{{ route('admin.users.index') }}" class="group rounded-lg border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-[#0E4E37] hover:shadow-md">
            <p class="text-base font-black text-neutral-900">Kelola User & Pakar</p>
            <p class="mt-2 text-sm text-neutral-500">Atur akun, role, dan status keaktifan pengguna sistem.</p>
            <span class="mt-5 inline-flex text-sm font-bold text-[#0E4E37]">Buka modul</span>
        </a>

        <a href="{{ route('admin.rules.index') }}" class="group rounded-lg border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-[#0E4E37] hover:shadow-md">
            <p class="text-base font-black text-neutral-900">Kelola Basis Pengetahuan</p>
            <p class="mt-2 text-sm text-neutral-500">Kelola gejala, hama, penyakit, dan bobot CF pakar.</p>
            <span class="mt-5 inline-flex text-sm font-bold text-[#0E4E37]">Buka modul</span>
        </a>

        <a href="{{ route('admin.library.index') }}" class="group rounded-lg border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-[#0E4E37] hover:shadow-md">
            <p class="text-base font-black text-neutral-900">Kelola Pengetahuan Library</p>
            <p class="mt-2 text-sm text-neutral-500">Perbarui artikel edukasi untuk penyakit dan hama padi.</p>
            <span class="mt-5 inline-flex text-sm font-bold text-[#0E4E37]">Buka modul</span>
        </a>

        <a href="{{ route('deteksi.history') }}" class="group rounded-lg border border-neutral-200 bg-white p-5 shadow-sm transition hover:border-[#0E4E37] hover:shadow-md">
            <p class="text-base font-black text-neutral-900">Monitoring Riwayat Diagnosa</p>
            <p class="mt-2 text-sm text-neutral-500">Pantau riwayat diagnosa yang tersimpan di aplikasi.</p>
            <span class="mt-5 inline-flex text-sm font-bold text-[#0E4E37]">Buka modul</span>
        </a>
    </section>
</div>
@endsection
