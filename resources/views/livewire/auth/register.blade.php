<div>
    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="h-12 w-12 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <h2 class="mt-4 text-center text-3xl font-extrabold text-slate-900">EduAI Platform</h2>
            <p class="mt-2 text-center text-sm text-slate-600">შექმენით ანგარიში და მართეთ სასწავლო პროცესი AI-სთან ერთად</p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
            <div class="bg-white py-8 px-6 shadow-xl shadow-slate-200/50 rounded-2xl border border-slate-100 sm:px-10">

                {{-- ეტაპების ინდიკატორი (მოსწავლისთვის) --}}
                @if($role === 'student')
                    <div class="mb-8 flex items-center justify-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ $step === 1 ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'bg-blue-100 text-blue-700' }}">1</span>
                            <span class="text-xs font-medium text-slate-700">ანგარიში</span>
                        </div>
                        <div class="w-12 h-0.5 {{ $step === 2 ? 'bg-blue-600' : 'bg-slate-200' }}"></div>
                        <div class="flex items-center space-x-2">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold {{ $step === 2 ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'bg-slate-100 text-slate-500' }}">2</span>
                            <span class="text-xs font-medium text-slate-700">AI პროფილი</span>
                        </div>
                    </div>
                @endif

                @if($step === 1)
                    {{-- STEP 1: ძირითადი ინფორმცია --}}
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">აირჩიეთ როლი</label>
                            <div class="grid grid-cols-3 gap-3">
                                <button type="button" wire:click="selectRole('student')" class="p-3 text-center rounded-xl border-2 transition-all font-medium text-sm {{ $role === 'student' ? 'border-blue-600 bg-blue-50/50 text-blue-700 font-semibold' : 'border-slate-200 hover:border-slate-300 text-slate-600' }}">
                                    🎓 მოსწავლე
                                </button>
                                <button type="button" wire:click="selectRole('teacher')" class="p-3 text-center rounded-xl border-2 transition-all font-medium text-sm {{ $role === 'teacher' ? 'border-blue-600 bg-blue-50/50 text-blue-700 font-semibold' : 'border-slate-200 hover:border-slate-300 text-slate-600' }}">
                                    👨‍🏫 მასწავლებელი
                                </button>
                                <button type="button" wire:click="selectRole('parent')" class="p-3 text-center rounded-xl border-2 transition-all font-medium text-sm {{ $role === 'parent' ? 'border-blue-600 bg-blue-50/50 text-blue-700 font-semibold' : 'border-slate-200 hover:border-slate-300 text-slate-600' }}">
                                    👨‍👩‍👦 მშობელი
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">სრული სახელი</label>
                            <input type="text" wire:model="name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-800 transition">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">ელ-ფოსტა</label>
                            <input type="email" wire:model="email" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-800 transition">
                            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">პაროლი</label>
                                <input type="password" wire:model="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-800 transition">
                                @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">გაიმეორეთ პაროლი</label>
                                <input type="password" wire:model="password_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-800 transition">
                            </div>
                        </div>

                        <button type="button" wire:click="nextStep" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/25 transition duration-200">
                            {{ $role === 'student' ? 'შემდეგი ეტაპი (AI კითხვები) →' : 'რეგისტრაცია' }}
                        </button>
                    </div>

                @else
                    {{-- STEP 2: AI ONBOARDING (მოსწავლისთვის დინამიკური წრეებით და რეპეტიტორებით) --}}
                    <div class="space-y-6">
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start space-x-3">
                            <span class="text-2xl">🤖</span>
                            <p class="text-xs text-blue-900 leading-relaxed">
                                დაამატე შენი ყველა რეპეტიტორი და წრე სათითაოდ, რათა AI-მ დღის გეგმა ზუსტად გათვალოს!
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">საშუალო სწავლის დრო (სთ/დღე)</label>
                                <input type="number" wire:model="avg_study_hours_per_day" min="1" max="12" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">დრო 1 საგანზე (წუთი)</label>
                                <input type="number" wire:model="avg_minutes_per_subject" min="15" max="180" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm">
                            </div>
                        </div>

                        {{-- სექცია 1: წრეები / ჰობი (დინამიკური) --}}
                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <label class="text-xs font-bold uppercase text-slate-700 tracking-wider">🎯 წრეები / ჰობი</label>
                                <button type="button" wire:click="addActivityInput" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center space-x-1">
                                    <span>+ წრის დამატება</span>
                                </button>
                            </div>

                            <div class="space-y-3">
                                @foreach($activities as $index => $act)
                                    <div class="flex items-center space-x-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                                        <input type="text" wire:model="activities.{{ $index }}.name" placeholder="მაგ: გიტარა, ცურვა..." class="flex-1 px-3 py-1.5 rounded-lg border border-slate-300 text-xs">
                                        <input type="number" wire:model="activities.{{ $index }}.days_per_week" title="კვირაში რამდენჯერ" placeholder="დღე/კვ" min="1" max="7" class="w-20 px-2 py-1.5 rounded-lg border border-slate-300 text-xs text-center">
                                        
                                        @if(count($activities) > 1)
                                            <button type="button" wire:click="removeActivityInput({{ $index }})" class="text-red-500 hover:text-red-700 font-bold px-2 text-base">&times;</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- სექცია 2: რეპეტიტორები (დინამიკური) --}}
                        <div class="border-t border-slate-200 pt-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" wire:model.live="has_tutor" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                    <span class="text-xs font-bold uppercase text-slate-700 tracking-wider">👨‍🏫 გყავთ რეპეტიტორები?</span>
                                </label>

                                @if($has_tutor)
                                    <button type="button" wire:click="addTutorInput" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                        + რეპეტიტორის დამატება
                                    </button>
                                @endif
                            </div>

                            @if($has_tutor)
                                <div class="space-y-3">
                                    @foreach($tutors as $index => $tutor)
                                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <input type="text" wire:model="tutors.{{ $index }}.subject" placeholder="საგანის სახელი (მაგ: მათემატიკა)" class="flex-1 px-3 py-1.5 rounded-lg border border-slate-300 text-xs">
                                                
                                                @if(count($tutors) > 1)
                                                    <button type="button" wire:click="removeTutorInput({{ $index }})" class="text-red-500 hover:text-red-700 font-bold px-2 text-base">&times;</button>
                                                @endif
                                            </div>
                                            <div class="grid grid-cols-2 gap-2 text-xs">
                                                <div>
                                                    <span class="text-slate-500 text-[10px]">კვირაში რამდენჯერ:</span>
                                                    <input type="number" wire:model="tutors.{{ $index }}.sessions_per_week" min="1" max="7" class="w-full px-2 py-1 rounded-lg border border-slate-300">
                                                </div>
                                                <div>
                                                    <span class="text-slate-500 text-[10px]">ხანგრძლივობა (სთ):</span>
                                                    <input type="number" step="0.5" wire:model="tutors.{{ $index }}.hours_per_session" min="0.5" max="5" class="w-full px-2 py-1 rounded-lg border border-slate-300">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- ღილაკები --}}
                        <div class="flex items-center space-x-3 pt-4 border-t border-slate-200">
                            <button type="button" wire:click="previousStep" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl transition text-sm">
                                ← უკან
                            </button>
                            <button type="button" wire:click="register" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/25 transition text-sm">
                                დასრულება და შესვლა ✨
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>