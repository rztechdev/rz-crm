<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-id" content="{{ auth()->id() }}">

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
        
        <style>[x-cloak] { display: none !important; }</style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans antialiased overflow-x-hidden bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 transition-colors duration-300" 
          x-data="appLayout">
        
        <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 transition-colors duration-300">
            
            @include('layouts.navigation')
            
            <div class="lg:pl-64 pt-16 min-h-screen flex flex-col w-full">
                
                @isset($header)
                    <div class="bg-white dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-800 shadow-sm relative z-20 transition-colors duration-300">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-8">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <main class="flex-1 p-4 sm:p-8 relative">
                    {{ $slot }}
                </main>

            </div>
            
        </div>
        
        <div id="toast-container" class="fixed bottom-5 right-5 z-[60] pointer-events-none flex flex-col items-end w-full sm:max-w-sm"></div>
        @stack('scripts')
    </body>
</html>