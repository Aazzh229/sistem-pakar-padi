@extends('layouts.admin')

@section('title', 'Kelola Pengguna - Padiku')

@section('content')
<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-lg border border-neutral-200 bg-white p-6 shadow-sm lg:flex-row lg:items-center lg:justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-[#0E4E37] hover:underline">Dashboard</a>
            <h1 class="mt-2 text-3xl font-black text-neutral-900">Kelola Pengguna</h1>
            <p class="mt-2 text-sm text-neutral-500">Total {{ $users->count() }} akun terdaftar.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#0E4E37] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#12583F]">
            + Tambah Pengguna
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
                        <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Role</th>
                        <th class="px-5 py-3 text-left text-xs font-black uppercase tracking-wider text-neutral-500">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-black uppercase tracking-wider text-neutral-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 bg-white">
                    @forelse($users as $user)
                        @php
                            $isInactive = $user->status === 'inactive';
                            $roleColor  = match($user->role) {
                                'admin'  => 'bg-red-50 text-red-700 border-red-200',
                                'pakar'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                default  => 'bg-emerald-50 text-emerald-700 border-emerald-200'
                            };
                        @endphp
                        <tr class="hover:bg-neutral-50">
                            <td class="px-5 py-4 text-sm font-bold text-neutral-900">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-sm text-neutral-500">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded border px-2 py-1 text-xs font-black uppercase tracking-wider {{ $roleColor }}">{{ $user->role }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($isInactive)
                                    <span class="inline-flex rounded border border-neutral-200 bg-neutral-50 px-2 py-1 text-xs font-black uppercase tracking-wider text-neutral-500">Nonaktif</span>
                                @else
                                    <span class="inline-flex rounded border border-emerald-200 bg-emerald-50 px-2 py-1 text-xs font-black uppercase tracking-wider text-emerald-700">Aktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex rounded-lg border border-neutral-200 px-3 py-2 text-xs font-bold text-neutral-700 transition hover:border-[#0E4E37] hover:text-[#0E4E37]">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm font-bold text-neutral-400">Belum ada pengguna terdaftar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
