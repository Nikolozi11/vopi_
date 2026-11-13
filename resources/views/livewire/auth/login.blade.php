<div class="min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 transition-colors"
     :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    
    <!-- ლოგო / სათაური -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl border text-2xl font-bold mb-3 shadow-sm transition-colors"
             :class="darkMode ? 'bg-indigo-600/10 border-indigo-500/20 text-indigo-400' : 'bg-indigo-50 border-indigo-100 text-indigo-600'">
            V
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight transition-colors"
            :class="darkMode ? 'text-white' : 'text-slate-900'">
            მოგესალმები ისევ 👋
        </h2>
        <p class="mt-2 text-sm transition-colors"
           :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
            შედი შენს Vopi-ს სტუდენტურ სივრცეში
        </p>
    </div>

    <!-- ფორმის კარტა -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="backdrop-blur-xl py-8 px-6 shadow-2xl rounded-3xl sm:px-10 transition-colors border"
             :class="darkMode ? 'bg-slate-900/90 border-slate-800/80 text-white shadow-2xl shadow-black/50' : 'bg-white border-slate-100 text-slate-900 shadow-xl'">
            
            <form wire:submit.prevent="login" class="space-y-5">
                
                <!-- ელ-ფოსტა -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors"
                           :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                        ელ-ფოსტა
                    </label>
                    <input type="email" wire:model="email" required autofocus 
                        class="w-full px-4 py-3.5 border rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                        :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'"
                        placeholder="student@vopi.ge">
                    @error('email')
                        <span class="text-xs text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- პაროლი -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider transition-colors"
                               :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                            პაროლი
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-medium transition-colors"
                               :class="darkMode ? 'text-indigo-400 hover:text-indigo-300' : 'text-indigo-600 hover:text-indigo-500'">
                                დაგავიწყდა?
                            </a>
                        @endif
                    </div>
                    <input type="password" wire:model="password" required 
                        class="w-full px-4 py-3.5 border rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                        :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'"
                        placeholder="••••••••">
                    @error('password')
                        <span class="text-xs text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- მიმახსოვრე ჩექბოქსი -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" wire:model="remember" 
                               class="w-4 h-4 rounded border text-indigo-600 focus:ring-indigo-500"
                               :class="darkMode ? 'border-slate-800 bg-slate-950' : 'border-slate-300 bg-slate-50'">
                        <span class="transition-colors" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">მიმახსოვრე</span>
                    </label>
                </div>

                <!-- შესვლის ღილაკი -->
                <div>
                    <button type="submit" 
                        class="w-full py-4 px-4 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition-all duration-200 text-base">
                        შესვლა დეშბორდში ✨
                    </button>
                </div>
            </form>

            <!-- რეგისტრაციის ლინკი -->
            <div class="mt-8 text-center text-sm transition-colors"
                 :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                არ გაქვს ანგარიში? 
                <a href="{{ route('register') }}" class="font-semibold ml-1 transition-colors"
                   :class="darkMode ? 'text-indigo-400 hover:text-indigo-300' : 'text-indigo-600 hover:text-indigo-500'">
                    დარეგისტრირდი
                </a>
            </div>

        </div>
    </div>
</div>