<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'მოსწავლის პორტალი') }}</title>

        <!-- PWA Manifest & Theme -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#2563eb">

        <!-- Fonts & Styles (Tailwind / Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        
        <!-- გვერდის შიგთავსი -->
        <main>
            {{ $slot }}
        </main>

        @livewireScripts

        <!-- Service Worker-ის რეგისტრაცია ფონური მუშაობისთვის -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(reg => console.log('Service Worker წარმატებით დარეგისტრირდა!', reg))
                        .catch(err => console.log('Service Worker-ის შეცდომა:', err));
                });
            }
        </script>
    </body>
</html>