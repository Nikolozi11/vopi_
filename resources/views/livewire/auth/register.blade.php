<div class="min-h-screen flex flex-col justify-center py-8 px-4 sm:px-6 lg:px-8 transition-colors"
     :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <div class="sm:mx-auto sm:w-full sm:max-w-xl">
        
        <!-- ჰედერი -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl border text-2xl font-bold mb-3 shadow-sm transition-colors"
                 :class="darkMode ? 'bg-indigo-600/10 border-indigo-500/20 text-indigo-400' : 'bg-indigo-50 border-indigo-100 text-indigo-600'">
                🎓
            </div>
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
                                <span class="text-2xl sm:text-3xl">🎓</span> 
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
                                <span class="text-2xl sm:text-3xl">👨‍🏫</span> 
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
                                <span class="text-2xl sm:text-3xl">👨‍👩‍👦</span> 
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
                        <p class="text-xs sm:text-sm leading-relaxed">
                            დაამატე შენი ყველა რეპეტიტორი და წრე სათითაოდ, რათა AI-მ დღის გეგმა ზუსტად გათვალოს!
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">საშუალო სწავლის დრო (სთ/დღე)</label>
                            <input type="number" wire:model="avg_study_hours_per_day" min="1" max="12" class="w-full px-4 py-3 border rounded-xl text-base transition-colors" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">დრო 1 საგანზე (წუთი)</label>
                            <input type="number" wire:model="avg_minutes_per_subject" min="15" max="180" class="w-full px-4 py-3 border rounded-xl text-base transition-colors" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                        </div>
                    </div>

                    {{-- სექცია 1: წრეები / ჰობი --}}
                    <div class="border-t pt-4 transition-colors" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-xs font-bold uppercase tracking-wider transition-colors" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">🎯 წრეები / ჰობი</label>
                            <button type="button" wire:click="addActivityInput" class="text-xs font-semibold transition-colors" :class="darkMode ? 'text-indigo-400 hover:text-indigo-300' : 'text-indigo-600 hover:text-indigo-500'">
                                + დამატება
                            </button>
                        </div>

                        <div class="space-y-3">
                            @foreach($activities as $index => $act)
                                <div class="flex items-center space-x-2 p-2.5 rounded-xl border transition-colors" :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50 border-slate-200'">
                                    <input type="text" wire:model="activities.{{ $index }}.name" placeholder="მაგ: გიტარა, ცურვა..." class="flex-1 px-3 py-2 border rounded-lg text-sm transition-colors" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                                    <input type="number" wire:model="activities.{{ $index }}.days_per_week" placeholder="დღე/კვ" min="1" max="7" class="w-20 px-2 py-2 border rounded-lg text-sm text-center transition-colors" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                                    @if(count($activities) > 1)
                                        <button type="button" wire:click="removeActivityInput({{ $index }})" class="text-rose-500 hover:text-rose-600 font-bold px-2 text-lg">&times;</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- სექცია 2: რეპეტიტორები --}}
                    <div class="border-t pt-4 transition-colors" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
                        <div class="flex items-center justify-between mb-3">
                            <label class="flex items-center space-x-3 cursor-pointer select-none">
                                <input type="checkbox" wire:model.live="has_tutor" class="w-4 h-4 rounded border text-indigo-600 focus:ring-indigo-500" :class="darkMode ? 'border-slate-800 bg-slate-950' : 'border-slate-300 bg-slate-50'">
                                <span class="text-xs font-bold uppercase tracking-wider transition-colors" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">👨‍🏫 გყავთ რეპეტიტორები?</span>
                            </label>
                            @if($has_tutor)
                                <button type="button" wire:click="addTutorInput" class="text-xs font-semibold transition-colors" :class="darkMode ? 'text-indigo-400 hover:text-indigo-300' : 'text-indigo-600 hover:text-indigo-500'">
                                    + დამატება
                                </button>
                            @endif
                        </div>

                        @if($has_tutor)
                            <div class="space-y-3">
                                @foreach($tutors as $index => $tutor)
                                    <div class="p-3 rounded-xl border space-y-2.5 transition-colors" :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50 border-slate-200'">
                                        <div class="flex items-center space-x-2">
                                            <input type="text" wire:model="tutors.{{ $index }}.subject" placeholder="საგანის სახელი (მაგ: მათემატიკა)" class="flex-1 px-3 py-2 border rounded-lg text-sm transition-colors" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                                            @if(count($tutors) > 1)
                                                <button type="button" wire:click="removeTutorInput({{ $index }})" class="text-rose-500 hover:text-rose-600 font-bold px-2 text-lg">&times;</button>
                                            @endif
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <div>
                                                <span class="text-[11px] mb-1 block transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">კვირაში რამდენჯერ:</span>
                                                <input type="number" wire:model="tutors.{{ $index }}.sessions_per_week" min="1" max="7" class="w-full px-3 py-2 border rounded-lg transition-colors" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                                            </div>
                                            <div>
                                                <span class="text-[11px] mb-1 block transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">ხანგრძლივობა (სთ):</span>
                                                <input type="number" step="0.5" wire:model="tutors.{{ $index }}.hours_per_session" min="0.5" max="5" class="w-full px-3 py-2 border rounded-lg transition-colors" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ნავიგაციის ღილაკები --}}
                    <div class="flex items-center space-x-3 pt-4 border-t transition-colors" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
                        <button type="button" wire:click="previousStep" class="w-1/3 border font-semibold py-3.5 px-4 rounded-xl transition text-sm" :class="darkMode ? 'bg-slate-900 border-slate-800 hover:bg-slate-800 text-slate-300' : 'bg-slate-100 border-slate-200 hover:bg-slate-200 text-slate-700'">
                            ← უკან
                        </button>
                        <button type="button" wire:click="register" class="w-2/3 bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white font-semibold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-600/30 transition text-sm">
                            დასრულება ✨
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>  
</div>