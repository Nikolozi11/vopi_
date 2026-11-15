<!DOCTYPE html>
<html lang="ka" class="h-full" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          toggle() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
              window.dispatchEvent(new CustomEvent('storage'));
          }
      }" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    {{-- PWA პარამეტრები და მანიფესტი --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#030712">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/images/icon-192.png">

    <!-- ძირითადი SEO მეტა ტეგები -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Vopi Platform - სტუდენტური პლატფორმა' }}</title>
    <meta name="description" content="{{ $description ?? 'შექმენით ანგარიში და მართეთ სასწავლო პროცესი AI-სთან ერთად Vopi Platform-ზე.' }}">

    <!-- Open Graph / Facebook / WhatsApp / Discord / Telegram -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'Vopi Platform - სტუდენტური პლატფორმა' }}">
    <meta property="og:description" content="{{ $description ?? 'შექმენით ანგარიში და მართეთ სასწავლო პროცესი AI-სთან ერთად Vopi Platform-ზე.' }}">
    <meta property="og:image" content="{{ asset('images/banner.png') }}">
    <meta property="og:site_name" content="Vopi Platform">
    <meta property="og:locale" content="ka_GE">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $title ?? 'Vopi Platform - სტუდენტური პლატფორმა' }}">
    <meta name="twitter:description" content="{{ $description ?? 'შექმენით ანგარიში და მართეთ სასწავლო პროცესი AI-სთან ერთად Vopi Platform-ზე.' }}">
    <meta name="twitter:image" content="{{ asset('images/banner.png') }}">

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full antialiased transition-colors duration-300"
      :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">

    <div class="min-h-screen flex flex-col relative pb-20">
        
        {{-- ზედა ჰედერი --}}
        <header class="backdrop-blur-xl border-b px-4 sm:px-6 py-4 sticky top-0 z-40 transition-colors"
                :class="darkMode ? 'bg-slate-900/80 border-slate-800 text-white' : 'bg-white/80 border-slate-200 text-slate-900'">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <img src="/images/logo.png" alt="Vopi Logo" class="h-10 w-10">
                            <div>
                            <h1 class="text-base sm:text-lg font-extrabold transition-colors" :class="darkMode ? 'text-white' : 'text-slate-900'">Vopi Platform</h1>
                            <p class="text-xs transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                                @auth მოგესალმები, {{ auth()->user()->name }} @else სტუდენტური პლატფორმა @endauth
                            </p>
                        </div>
                    </a>
                </div>

                <div class="flex items-center space-x-3">
                    {{-- თემის გადამრთველი ღილაკი --}}
                    <button @click="toggle()" 
                        class="p-2.5 rounded-xl border transition-all flex items-center justify-center shadow-sm cursor-pointer"
                        :class="darkMode ? 'border-slate-800 bg-slate-950 text-slate-300 hover:border-indigo-500' : 'border-slate-200 bg-slate-100 text-slate-600 hover:border-indigo-500'"
                        title="თემის შეცვლა">
                        <!-- მთვარის აიქონი (ჩანს, როცა darkMode არ არის აქტიური) -->
<span x-show="!darkMode" class="inline-flex items-center justify-center">
    <svg class="w-5 h-5 text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
    </svg>
</span>

<!-- მზის აიქონი (ჩანს, როცა darkMode აქტიურია) -->
<span x-show="darkMode" class="inline-flex items-center justify-center" style="display: none;">
    <svg class="w-5 h-5 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="4"/>
        <path d="M12 2v2"/>
        <path d="M12 20v2"/>
        <path d="m4.93 4.93 1.41 1.41"/>
        <path d="m17.66 17.66 1.41 1.41"/>
        <path d="M2 12h2"/>
        <path d="M20 12h2"/>
        <path d="m6.34 17.66-1.41 1.41"/>
        <path d="m19.07 4.93-1.41 1.41"/>
    </svg>
</span>
                    </button>

                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="გამოსვლა" class="p-2.5 rounded-xl border transition flex items-center justify-center"
    :class="darkMode ? 'bg-rose-500/10 text-rose-400 border-rose-500/20 hover:bg-rose-500/25' : 'bg-rose-50 text-rose-600 border-rose-200 hover:bg-rose-100'">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" x2="9" y1="12" y2="12"/>
    </svg>
</button>
                        </form>
                    @endauth
                </div>

            </div>
        </header>

        {{-- მთავარი კონტენტი --}}
        <main class="flex-1">
            {{ $slot }}
        </main>

    </div>

    {{-- PWA ინსტალაციის მცურავი ღილაკი (გამოჩნდება ყველა გვერდზე) --}}
    <div x-data="{ 
        installPrompt: null, 
        isInstallable: false,
        init() {
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.installPrompt = e;
                this.isInstallable = true;
            });
        },
        installApp() {
            if (this.installPrompt) {
                this.installPrompt.prompt();
                this.installPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        this.isInstallable = false;
                    }
                    this.installPrompt = null;
                });
            }
        }
    }" x-show="isInstallable" class="fixed bottom-6 left-6 z-50" style="display: none;">
        <button @click="installApp()" class="px-4 py-3 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white rounded-2xl shadow-2xl shadow-indigo-600/50 font-semibold text-xs flex items-center gap-2 transition cursor-pointer">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
        <polyline points="7 10 12 15 17 10"/>
        <line x1="12" x2="12" y1="3" y2="15"/>
    </svg>
    Vopi-ს აპლიკაციის გადმოწერა
</button>
    </div>

    @livewireScripts
</body>
</html>