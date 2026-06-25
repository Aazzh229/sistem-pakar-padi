@extends('layouts.admin')

@section('title', 'Kelola Library - Padiku')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-lg border border-neutral-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-[#0E4E37] hover:underline">Dashboard</a>
            <h1 class="mt-2 text-3xl font-black text-neutral-900">Kelola Library</h1>
            <p class="mt-2 text-sm text-neutral-500">Artikel edukasi dan deskripsi hama penyakit.</p>
        </div>
        <a href="{{ route('admin.library.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#0E4E37] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#12583F]">
            + Tambah Artikel
        </a>
    </section>

    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <section class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Kategori</th>
                        <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Nama Latin</th>
                        <th class="px-5 py-3 text-right text-xs font-black uppercase tracking-wider text-neutral-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @foreach($libraries as $lib)
                        @php
                            $isPenyakit = $lib->jenis === 'penyakit';
                            $badgeBg = $isPenyakit ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-orange-50 text-orange-700 border-orange-200';
                        @endphp
                        <tr class="hover:bg-neutral-50">
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded border px-2 py-1 text-xs font-black uppercase tracking-wider {{ $badgeBg }}">{{ $lib->jenis }}</span>
                            </td>
                            <td class="px-5 py-4 text-sm font-bold text-neutral-900">{{ $lib->nama }}</td>
                            <td class="px-5 py-4 text-sm italic text-neutral-500">{{ $lib->nama_latin }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.library.edit', $lib->id) }}" class="inline-flex rounded-lg border border-neutral-200 px-3 py-2 text-xs font-bold text-neutral-700 transition hover:border-[#0E4E37] hover:text-[#0E4E37]">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.library.delete', $lib->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel library ini?')">
                                        @csrf
                                        <button type="submit" class="inline-flex rounded-lg border border-red-200 px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
