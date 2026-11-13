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
    <title>Vopi - სტუდენტური პორტალი</title>

    {{-- PWA პარამეტრები და მანიფესტი --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/images/icon-192.png">

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
                        <div class="h-10 w-10 rounded-2xl border flex items-center justify-center font-bold shadow-sm transition-colors"
                             :class="darkMode ? 'bg-indigo-600/10 border-indigo-500/20 text-indigo-400' : 'bg-indigo-50 border-indigo-200 text-indigo-600'">
                            🎓
                        </div>
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
                        <span x-show="!darkMode" class="text-lg">🌙</span>
                        <span x-show="darkMode" class="text-lg" style="display: none;">☀️</span>
                    </button>

                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold px-3.5 py-2 rounded-xl border transition"
                                    :class="darkMode ? 'bg-rose-500/10 text-rose-400 border-rose-500/20 hover:bg-rose-500/25' : 'bg-rose-50 text-rose-600 border-rose-200 hover:bg-rose-100'">
                                გამოსვლა
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
            <span>📲 Vopi-ს აპლიკაციის გადმოწერა</span>
        </button>
    </div>

    @livewireScripts
</body>
</html>