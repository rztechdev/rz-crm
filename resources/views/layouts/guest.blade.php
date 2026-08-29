<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RZ CRM') }}</title>
        <!-- Favicon & Brand Icons -->
        <link rel="icon" type="image/jpeg" href="{{ asset('images/logo_rz_teks.jpeg') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo_rz_teks.jpeg') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
        <script>
            // Inline theme check to prevent flickering (FOUC)
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 transition-colors duration-300 flex flex-col min-h-screen">
        
        <!-- Transparent Header -->
        <header class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6 flex items-center justify-between z-50 shrink-0">
            <!-- Branding -->
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group font-sans shrink-0">
                <img src="{{ asset('images/logo_rz_teks.png') }}" alt="RZ Digital Creative Logo" class="h-8 sm:h-9 w-auto object-contain brightness-0 dark:brightness-100 hover:opacity-95 group-hover:scale-105 transition-all duration-300">
                <span class="text-lg sm:text-xl font-extrabold tracking-tight text-zinc-900 dark:text-white group-hover:opacity-90 transition-opacity">RZ CRM</span>
                <span class="text-lg sm:text-xl font-extrabold text-emerald-600 dark:text-emerald-400">.</span>
            </a>

            <!-- Actions Row -->
            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Theme Switcher -->
                <button onclick="toggleTheme()" 
                        class="p-2 rounded-xl text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-zinc-200/50 dark:hover:bg-zinc-900/50 transition-colors focus:outline-none cursor-pointer"
                        title="Ganti Tema">
                    <span id="theme-toggle-icon" class="material-symbols-outlined text-[20px] sm:text-[22px] block">dark_mode</span>
                </button>
            </div>
        </header>

        <!-- Main Slot Content Wrapper (Clean neutral background with subtle grid) -->
        <main class="flex-1 flex flex-col justify-center items-center p-4 sm:p-6 relative overflow-hidden bg-grid-pattern">
            {{ $slot }}
        </main>
    </body>
</html>
