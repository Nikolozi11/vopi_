<div class="min-h-screen flex flex-col justify-center py-8 px-4 sm:px-6 lg:px-8 transition-colors"
     :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <div class="sm:mx-auto sm:w-full sm:max-w-xl">
        
        <!-- ჰედერი -->
        <div class="text-center mb-6">
            <img src="/images/logo.png" alt="Vopi Logo" class="inline-flex items-center justify-center w-14 h-14">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight transition-colors" :class="darkMode ? 'text-white' : 'text-slate-900'">Vopi Platform</h2>
            <p class="mt-2 text-sm transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">შექმენით ანგარიში და მართეთ სასწავლო პროცესი AI-სთან ერთად</p>
        </div>

        <div class="backdrop-blur-xl py-6 px-4 sm:py-8 sm:px-10 shadow-2xl rounded-3xl transition-colors border"
             :class="darkMode ? 'bg-slate-900/90 border-slate-800/80 text-white shadow-2xl shadow-black/50' : 'bg-white border-slate-100 text-slate-900 shadow-xl'">

            {{-- ეტაპების ინდიკატორი (მოსწავლისთვის) --}}
            @if($role === 'student')
                <div class="mb-6 flex items-center justify-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ $step === 1 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : '' }}"
                              :class="darkMode ? 'bg-indigo-950 text-indigo-400 border border-indigo-800/50' : 'bg-indigo-50 text-indigo-600 border border-indigo-200'">1</span>
                        <span class="text-xs font-medium" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">ანგარიში</span>
                    </div>
                    <div class="w-12 h-0.5 {{ $step === 2 ? 'bg-indigo-600' : '' }}" :class="darkMode ? 'bg-slate-800' : 'bg-slate-200'"></div>
                    <div class="flex items-center space-x-2">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ $step === 2 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : '' }}"
                              :class="darkMode ? 'bg-slate-800 text-slate-400 border border-slate-700' : 'bg-slate-100 text-slate-500 border border-slate-200'">2</span>
                        <span class="text-xs font-medium" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">AI პროფილი</span>
                    </div>
                </div>
            @endif

            @if($step === 1)
                {{-- STEP 1: ძირითადი ინფორმაცია --}}
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">აირჩიეთ როლი</label>
                        
                        <!-- მკაფიოდ გამოკვეთილი აქტიური და არააქტიური ბარათები -->
                        <div class="grid grid-cols-3 gap-2 sm:gap-3">
                            <button type="button" wire:click="selectRole('student')" 
                                class="relative py-4 px-2 text-center rounded-2xl transition-all font-medium text-xs sm:text-sm flex flex-col items-center justify-center gap-1.5"
                                :class="darkMode ? 
                                    ($role === 'student' ? 'border-2 border-indigo-500 bg-indigo-600/25 text-white shadow-lg shadow-indigo-500/20' : 'border border-slate-800 bg-slate-900/40 text-slate-400 hover:border-slate-700 hover:text-slate-200') : 
                                    ($role === 'student' ? 'border-2 border-indigo-600 bg-indigo-50 text-indigo-900 shadow-md shadow-indigo-100' : 'border border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300 hover:text-slate-900')">
                                @if($role === 'student')
                                    <span class="absolute top-2 right-2 w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] font-bold">✓</span>
                                @endif
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
    <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/>
    <path d="M22 10v6"/>
    <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
</svg>
                                <span class="font-semibold">მოსწავლე</span>
                            </button>

                            <button type="button" wire:click="selectRole('teacher')" 
                                class="relative py-4 px-2 text-center rounded-2xl transition-all font-medium text-xs sm:text-sm flex flex-col items-center justify-center gap-1.5"
                                :class="darkMode ? 
                                    ($role === 'teacher' ? 'border-2 border-indigo-500 bg-indigo-600/25 text-white shadow-lg shadow-indigo-500/20' : 'border border-slate-800 bg-slate-900/40 text-slate-400 hover:border-slate-700 hover:text-slate-200') : 
                                    ($role === 'teacher' ? 'border-2 border-indigo-600 bg-indigo-50 text-indigo-900 shadow-md shadow-indigo-100' : 'border border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300 hover:text-slate-900')">
                                @if($role === 'teacher')
                                    <span class="absolute top-2 right-2 w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] font-bold">✓</span>
                                @endif
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
    <path d="M2 3h20"/>
    <path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/>
    <path d="m7 21 5-5 5 5"/>
</svg>
                                <span class="font-semibold">მასწავლებელი</span>
                            </button>

                            <button type="button" wire:click="selectRole('parent')" 
                                class="relative py-4 px-2 text-center rounded-2xl transition-all font-medium text-xs sm:text-sm flex flex-col items-center justify-center gap-1.5"
                                :class="darkMode ? 
                                    ($role === 'parent' ? 'border-2 border-indigo-500 bg-indigo-600/25 text-white shadow-lg shadow-indigo-500/20' : 'border border-slate-800 bg-slate-900/40 text-slate-400 hover:border-slate-700 hover:text-slate-200') : 
                                    ($role === 'parent' ? 'border-2 border-indigo-600 bg-indigo-50 text-indigo-900 shadow-md shadow-indigo-100' : 'border border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300 hover:text-slate-900')">
                                @if($role === 'parent')
                                    <span class="absolute top-2 right-2 w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] font-bold">✓</span>
                                @endif
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
    <circle cx="9" cy="7" r="4"/>
    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
</svg> 
                                <span class="font-semibold">მშობელი</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">სრული სახელი</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-3.5 border rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'" placeholder="ნიკოლოზ გასვიანი">
                        @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">ელ-ფოსტა</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-3.5 border rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'" placeholder="student@vopi.ge">
                        @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">პაროლი</label>
                            <input type="password" wire:model="password" class="w-full px-4 py-3.5 border rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'" placeholder="••••••••">
                            @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">გაიმეორეთ</label>
                            <input type="password" wire:model="password_confirmation" class="w-full px-4 py-3.5 border rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'" placeholder="••••••••">
                        </div>
                    </div>

                    <button type="button" wire:click="nextStep" class="w-full mt-4 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white font-semibold py-4 px-4 rounded-xl shadow-lg shadow-indigo-600/30 transition duration-200 text-base">
                        {{ $role === 'student' ? 'შემდეგი ეტაპი (AI კითხვები) →' : 'რეგისტრაცია' }}
                    </button>

                    <div class="mt-6 text-center text-sm transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                        უკვე გაქვთ ანგარიში? 
                        <a href="{{ route('login') }}" class="font-semibold ml-1 transition-colors" :class="darkMode ? 'text-indigo-400 hover:text-indigo-300' : 'text-indigo-600 hover:text-indigo-500'">შესვლა</a>
                    </div>
                </div>

            @else

                {{-- STEP 2: AI ONBOARDING --}}
                <div class="space-y-5">
                    <div class="border rounded-2xl p-4 flex items-start space-x-3 transition-colors" :class="darkMode ? 'bg-indigo-950/30 border-indigo-500/20 text-indigo-200' : 'bg-indigo-50/70 border-indigo-100 text-indigo-900'">
                        <span class="text-2xl">🤖</span>
                        <p class="text-xs sm:text-sm leading-relaxed">მიუთითე სკოლის, რეპეტიტორისა და წრეების დღეები და საათები, რათა AI-მ დღის გეგმა ზუსტად გათვალოს. ყველაფრის შეცვლა მერე პროფილიდან შეგიძლია.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-2" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">საშუალო სწავლის დრო (სთ/დღე)</label>
                            <input type="number" wire:model="avg_study_hours_per_day" min="1" max="12" class="w-full px-4 py-3 border rounded-xl text-base" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                            @error('avg_study_hours_per_day') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-2" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">დრო 1 საგანზე (წუთი)</label>
                            <input type="number" wire:model="avg_minutes_per_subject" min="15" max="180" class="w-full px-4 py-3 border rounded-xl text-base" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                            @error('avg_minutes_per_subject') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @include('livewire.partials.profile-fields')

                    <div class="flex items-center space-x-3 pt-4 border-t" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
                        <button type="button" wire:click="previousStep" class="w-1/3 border font-semibold py-3.5 px-4 rounded-xl text-sm" :class="darkMode ? 'bg-slate-900 border-slate-800 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">← უკან</button>
                        <button type="button" wire:click="register" class="w-2/3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-600/30 text-sm">დასრულება ✨</button>
                    </div>
                </div>
            @endif

        </div>
    </div>  
</div>