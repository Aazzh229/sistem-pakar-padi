<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Padiku')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #eef5f0;
        }

        .admin-page > .flex.flex-col.w-full {
            gap: 1.5rem;
        }

        .admin-page > .flex.flex-col.w-full > .bg-gradient-to-b {
            border: 1px solid #e5e5e5;
            border-radius: 0.5rem;
            background: #ffffff !important;
            color: #171717 !important;
            padding: 1.5rem !important;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        }

        .admin-page > .flex.flex-col.w-full > .bg-gradient-to-b h1 {
            color: #171717;
            font-size: 1.875rem;
            line-height: 2.25rem;
            font-weight: 900;
        }

        .admin-page > .flex.flex-col.w-full > .bg-gradient-to-b p,
        .admin-page > .flex.flex-col.w-full > .bg-gradient-to-b span {
            color: #737373 !important;
            font-size: 0.875rem;
            line-height: 1.5rem;
        }

        .admin-page > .flex.flex-col.w-full > .bg-gradient-to-b a {
            color: #0E4E37 !important;
        }

        .admin-page > .flex.flex-col.w-full > .bg-gradient-to-b a[class*="bg-"] {
            color: #ffffff !important;
            background: #0E4E37 !important;
            border-radius: 0.5rem !important;
            padding: 0.625rem 1rem !important;
        }

        .admin-page .-mt-4,
        .admin-page .-mt-3 {
            margin-top: 0 !important;
        }

        .admin-page .rounded-2xl,
        .admin-page .rounded-xl,
        .admin-page .rounded-full {
            border-radius: 0.5rem !important;
        }

        .admin-page input,
        .admin-page select,
        .admin-page textarea {
            border-radius: 0.5rem !important;
        }

        .admin-page form button[type="submit"],
        .admin-page a[class*="bg-[#0E4E37]"] {
            border-radius: 0.5rem !important;
        }

        .admin-page .shadow-lg,
        .admin-page .shadow-md {
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08) !important;
        }

        @media (min-width: 1024px) {
            .admin-page > .flex.flex-col.w-full > div:not(.bg-gradient-to-b) {
                max-width: none;
            }

            .admin-page > .flex.flex-col.w-full > .px-6 {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .admin-page form.flex.flex-col {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1rem;
            }

            .admin-page form.flex.flex-col > .flex.flex-col,
            .admin-page form.flex.flex-col > .relative {
                min-width: 0;
            }

            .admin-page form.flex.flex-col textarea,
            .admin-page form.flex.flex-col button[type="submit"],
            .admin-page form.flex.flex-col > input[type="hidden"] {
                grid-column: 1 / -1;
            }

            .admin-page form.flex.flex-col > button[type="submit"] {
                justify-self: end;
                width: auto !important;
                min-width: 12rem;
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="antialiased min-h-screen text-neutral-800">
    <div class="min-h-screen lg:flex">
        <aside class="hidden lg:flex lg:w-72 lg:flex-col bg-[#0A3D2A] text-white">
            <div class="px-7 py-7 border-b border-white/10">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#3CD070]">Padiku</p>
                <h1 class="mt-2 text-2xl font-black">Admin Panel</h1>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-white text-[#0A3D2A]' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-bold transition {{ request()->routeIs('admin.users.*') ? 'bg-white text-[#0A3D2A]' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span>Pengguna</span>
                </a>
                <a href="{{ route('admin.rules.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-bold transition {{ request()->routeIs('admin.rules.*') ? 'bg-white text-[#0A3D2A]' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span>Basis Pengetahuan</span>
                </a>
                <a href="{{ route('admin.library.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-bold transition {{ request()->routeIs('admin.library.*') ? 'bg-white text-[#0A3D2A]' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                    <span>Library</span>
                </a>
                <a href="{{ route('deteksi.history') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-bold text-white/75 transition hover:bg-white/10 hover:text-white">
                    <span>Riwayat Diagnosa</span>
                </a>
            </nav>

            <div class="px-4 py-5 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full rounded-lg px-4 py-3 text-left text-sm font-bold text-white/75 transition hover:bg-white/10 hover:text-white">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="lg:hidden bg-[#0A3D2A] px-5 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#3CD070]">Padiku</p>
                        <p class="text-lg font-black">Admin Panel</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-white/80">Keluar</button>
                    </form>
                </div>
                <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
                    <a href="{{ route('admin.dashboard') }}" class="whitespace-nowrap rounded-full px-4 py-2 text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-white text-[#0A3D2A]' : 'bg-white/10 text-white' }}">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="whitespace-nowrap rounded-full px-4 py-2 text-xs font-bold {{ request()->routeIs('admin.users.*') ? 'bg-white text-[#0A3D2A]' : 'bg-white/10 text-white' }}">Pengguna</a>
                    <a href="{{ route('admin.rules.index') }}" class="whitespace-nowrap rounded-full px-4 py-2 text-xs font-bold {{ request()->routeIs('admin.rules.*') ? 'bg-white text-[#0A3D2A]' : 'bg-white/10 text-white' }}">Basis</a>
                    <a href="{{ route('admin.library.index') }}" class="whitespace-nowrap rounded-full px-4 py-2 text-xs font-bold {{ request()->routeIs('admin.library.*') ? 'bg-white text-[#0A3D2A]' : 'bg-white/10 text-white' }}">Library</a>
                </div>
            </header>

            <main class="admin-page flex-1 px-5 py-6 sm:px-8 lg:px-10 lg:py-8">
                <div class="mx-auto w-full max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
