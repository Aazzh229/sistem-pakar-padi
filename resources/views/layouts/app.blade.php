<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Padiku')</title>
    <!-- Google Fonts: Instrument Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
    
    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #121212; /* Dark background on desktop to focus on the mobile app */
        }
        /* Custom hide scrollbars for horizontal lists */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @yield('styles')
</head>
<body class="antialiased min-h-screen flex justify-center items-stretch">
    <!-- Mobile Container -->
    <div class="w-full max-w-md bg-[#F4FAF6] min-h-screen shadow-2xl relative flex flex-col justify-between overflow-x-hidden">
        
        <!-- Main Content Area -->
        <main class="flex-grow pb-24">
            @yield('content')
        </main>

        <!-- Bottom Navigation component will be placed here -->
        @yield('navigation')
        
    </div>

    @yield('scripts')
</body>
</html>
