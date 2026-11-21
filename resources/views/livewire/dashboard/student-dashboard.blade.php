<div class="min-h-screen relative pb-32 sm:pb-24 transition-colors duration-200"
     x-data="{ 
         darkMode: localStorage.getItem('darkMode') === 'true' || document.documentElement.classList.contains('dark'),
         updateTheme() {
             this.darkMode = localStorage.getItem('darkMode') === 'true' || document.documentElement.classList.contains('dark');
         }
     }"
     x-init="
         setInterval(() => updateTheme(), 150);
         window.addEventListener('storage', () => updateTheme());
     "
     :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    @include('livewire.partials.ui-fixes')


    {{-- Main Content Grid --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 sm:mt-8 grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-8">

        {{-- მარცხენა სვეტი --}}
        <div class="space-y-5 sm:space-y-6">

            {{-- AI პროფილის ბარათი --}}
            <div class="border border-indigo-500/20 text-white rounded-3xl p-4 sm:p-6 shadow-xl transition-colors"
                 :class="darkMode ? 'bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-900 shadow-black/40' : 'bg-gradient-to-br from-indigo-900 to-indigo-700 shadow-lg'">
                <h3 class="font-bold text-base mb-2 flex items-center space-x-2 text-indigo-200">
                    <img src="/images/logo.png" alt="AI Assistant" class="w-6 h-6"> <span>შენი AI პროფილი</span>
                </h3>
                <div class="text-xs text-indigo-100 space-y-3 mt-4">
                    <div class="flex justify-between border-b pb-2.5 transition-colors" :class="darkMode ? 'border-slate-800' : 'border-indigo-600/50'">
                        <span class="text-indigo-200/80">დღიური მეცადინეობა:</span>
                        <span class="font-semibold text-white">{{ $profile->avg_study_hours_per_day ?? 2 }} საათი</span>
                    </div>
                    <div class="flex justify-between border-b pb-2.5 transition-colors" :class="darkMode ? 'border-slate-800' : 'border-indigo-600/50'">
                        <span class="text-indigo-200/80">დრო 1 საგანზე:</span>
                        <span class="font-semibold text-white">{{ $profile->avg_minutes_per_subject ?? 45 }} წუთი</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-indigo-200/80">რეპეტიტორი:</span>
                        <span class="font-semibold text-white">{{ ($profile->has_tutor ?? false) ? 'დიახ' : 'არა' }}</span>
                    </div>
                </div>
                <a href="/profile" class="mt-4 block text-center text-xs font-semibold text-indigo-200 hover:text-white underline">პროფილის / გრაფიკის რედაქტირება</a>
            </div>

            {{-- დავალების ჩანიშვნის ფორმა --}}
            <div class="backdrop-blur-xl p-4 sm:p-6 rounded-3xl border shadow-xl transition-colors"
                 :class="darkMode ? 'bg-slate-900/90 border-slate-800/80 text-white shadow-2xl shadow-black/50' : 'bg-white border-slate-100 text-slate-900 shadow-xl'">
                <h3 class="font-bold text-base mb-4 transition-colors" :class="darkMode ? 'text-white' : 'text-slate-900'">{{ $editingId ? 'დავალების რედაქტირება' : 'დავალების ჩანიშვნა' }}</h3>
                <form wire:submit="saveTask" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">სათაური / საგანი</label>
                        <input type="text" wire:model="title" placeholder="მაგ: მათემატიკა" class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'">
                        @error('title') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">აღწერა (არასავალდებულო)</label>
                        <textarea wire:model="description" rows="2" placeholder="დეტალები..." class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'"></textarea>
                        @error('description') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">ტიპი</label>
                        <select wire:model="type" class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                            <option value="school_task" class="dark:bg-slate-900">სკოლის დავალება</option>
                            <option value="tutor" class="dark:bg-slate-900">რეპეტიტორი</option>
                            <option value="activity" class="dark:bg-slate-900">წრე / ჰობი</option>
                            <option value="break" class="dark:bg-slate-900">დასვენება</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">დასაწყისი</label>
                            <input type="datetime-local" wire:model="start_time" class="w-full px-3 py-2.5 border rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                            @error('start_time') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-2 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">დასასრული</label>
                            <input type="datetime-local" wire:model="end_time" class="w-full px-3 py-2.5 border rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white' : 'bg-slate-50 border-slate-200 text-slate-900'">
                            @error('end_time') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white text-sm font-semibold py-3.5 rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center justify-center gap-2">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M5 12h14"/>
        <path d="M12 5v14"/>
    </svg>
    {{ $editingId ? 'შენახვა' : 'კალენდარში დამატება' }}
</button>
                    @if($editingId)
                        <button type="button" wire:click="cancelEdit" class="w-full text-sm py-2 text-slate-500">გაუქმება</button>
                    @endif

                    @if($conflictWarnings)
                        <div class="rounded-xl border border-amber-500/40 bg-amber-500/10 p-3 text-xs text-amber-600 space-y-1">
                            <div class="font-semibold flex items-center gap-1.5"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>ამ დროს უკვე დაკავებულია:</div>
                            @foreach($conflictWarnings as $w) <div>• {{ $w }}</div> @endforeach
                            <button type="button" wire:click="confirmConflict" class="mt-2 font-semibold underline">მაინც დამატება</button>
                        </div>
                    @endif
                </form>
            </div>

            {{-- მაღვიძარას და ფუშ-შეტყობინებების ბარათი --}}
            <div class="backdrop-blur-xl p-4 sm:p-6 rounded-3xl border shadow-xl transition-colors"
                 :class="darkMode ? 'bg-slate-900/90 border-slate-800/80 text-white shadow-2xl shadow-black/50' : 'bg-white border-slate-100 text-slate-900 shadow-xl'">
                 <h3 class="font-bold text-base mb-3 transition-colors flex items-center gap-2" :class="darkMode ? 'text-white' : 'text-slate-900'">
    <svg class="w-5 h-5 text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
    </svg>
    მაღვიძარა და შეტყობინებები
</h3>
                <p class="text-xs mb-4 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">ჩართე მაღვიძარა, რომ ტელეფონიდან გასვლისასაც მიიღო შეტყობინება.</p>
                
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" wire:model="pushEnabled" onchange="togglePushAlarm(this)" class="vp-switch" aria-label="ფუშ მაღვიძარას ჩართვა">
                    <span class="text-sm font-medium" :class="darkMode ? 'text-white' : 'text-slate-900'">ფუშ მაღვიძარას ჩართვა</span>
                </label>
            </div>
        </div>

        {{-- მარჯვენა სვეტი: დღის განრიგი --}}
        <div class="backdrop-blur-xl rounded-3xl border shadow-xl p-4 sm:p-6 lg:col-span-2 transition-colors"
             :class="darkMode ? 'bg-slate-900/90 border-slate-800/80 text-white shadow-2xl shadow-black/50' : 'bg-white border-slate-100 text-slate-900 shadow-xl'">
            <h3 class="font-bold text-base sm:text-lg mb-5 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 transition-colors" :class="darkMode ? 'text-white' : 'text-slate-900'">
                <span class="inline-flex items-center gap-2">
    <svg class="w-5 h-5 text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M8 2v4"/>
        <path d="M16 2v4"/>
        <rect width="18" height="18" x="3" y="4" rx="2"/>
        <path d="M3 10h18"/>
    </svg>
    დავალებებისა და გაკვეთილების განრიგი
</span>
                <span class="text-xs font-normal flex items-center justify-between sm:justify-start gap-3 w-full sm:w-auto" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                    <button wire:click="deleteCompleted" wire:confirm="წავშალოთ ყველა შესრულებული?" class="text-rose-500 hover:underline">შესრულებულების წაშლა</button>
                    {{ now()->format('Y-m-d') }}
                </span>
            </h3>

            <div class="space-y-3.5">
                @forelse($tasks as $task)
                    <div wire:key="task-{{ $task->id }}" class="flex flex-wrap sm:flex-nowrap items-start p-3 sm:p-4 rounded-2xl border transition-all {{ $task->is_completed ? 'opacity-50' : '' }}"
                         :class="darkMode ? ($task->is_completed ? 'bg-slate-950/40 border-slate-800/60' : 'bg-slate-950/60 border-slate-800 hover:border-slate-700') : ($task->is_completed ? 'bg-slate-100 border-slate-200' : 'bg-slate-50/60 border-slate-200 hover:border-slate-300')">
                        <input type="checkbox" wire:click="toggleTaskCompletion({{ $task->id }})" {{ $task->is_completed ? 'checked' : '' }} class="vp-check vp-round mt-0.5" aria-label="შესრულებულია">

                        <div class="ml-3 sm:ml-4 flex-1 min-w-0 break-words">
                            <h4 class="text-sm font-bold {{ $task->is_completed ? 'line-through' : '' }}"
                                :class="darkMode ? ($task->is_completed ? 'text-slate-500' : 'text-white') : ($task->is_completed ? 'text-slate-400' : 'text-slate-900')">
                                {{ $task->title }}
                            </h4>

                            @if($task->description)
                                <p class="text-xs mt-1 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                                    {{ $task->description }}
                                </p>
                            @endif

                            <p class="text-xs mt-1.5 transition-colors flex items-center gap-1.5" :class="darkMode ? 'text-slate-500' : 'text-slate-400'">
    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
    </svg>
    {{ \Carbon\Carbon::parse($task->start_time)->format('Y-m-d H:i') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}
</p>
                        </div>

                        <div class="w-full sm:w-auto mt-2 sm:mt-0 pl-8 sm:pl-0 flex flex-row sm:flex-col items-center sm:items-end justify-between sm:gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border"
                            :class="darkMode ? (
                                '{{ $task->type }}' === 'school_task' ? 'bg-indigo-600/10 text-indigo-400 border-indigo-500/20' :
                                ('{{ $task->type }}' === 'tutor' ? 'bg-purple-500/10 text-purple-400 border-purple-500/20' :
                                ('{{ $task->type }}' === 'activity' ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'))
                            ) : (
                                '{{ $task->type }}' === 'school_task' ? 'bg-indigo-50 text-indigo-600 border-indigo-200' :
                                ('{{ $task->type }}' === 'tutor' ? 'bg-purple-50 text-purple-600 border-purple-200' :
                                ('{{ $task->type }}' === 'activity' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200'))
                            )">
                            {{ match($task->type) {
                                'school_task' => 'სკოლა',
                                'tutor' => 'რეპეტიტორი',
                                'activity' => 'წრე / ჰობი',
                                'break' => 'დასვენება',
                                default => $task->type,
                            } }}
                        </span>
                        <div class="flex gap-3 text-xs">
                            <button wire:click="editTask({{ $task->id }})" class="text-indigo-500 hover:text-indigo-400 p-2.5 sm:p-1" aria-label="რედაქტირება" title="რედაქტირება"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></button>
                            <button wire:click="deleteTask({{ $task->id }})" wire:confirm="წავშალოთ დავალება?" class="text-rose-500 hover:text-rose-400 p-2.5 sm:p-1" aria-label="წაშლა" title="წაშლა"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg></button>
                        </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-sm transition-colors" :class="darkMode ? 'text-slate-500' : 'text-slate-400'">
                        დავალებები ჯერ არ არის ჩანიშნული. ისარგებლეთ AI ჩატით ან დაამატეთ ხელით!
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    {{-- მცურავი AI ჩატის ღილაკი --}}
    <button wire:click="toggleChat" class="{{ $isChatOpen ? 'max-sm:hidden' : '' }} fixed right-4 sm:right-6 bottom-[calc(1.25rem+env(safe-area-inset-bottom))] sm:bottom-6 h-14 w-14 rounded-full bg-indigo-600 text-white shadow-2xl shadow-indigo-600/50 hover:bg-indigo-500 flex items-center justify-center text-2xl transition transform hover:scale-105 z-[9998]">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
    <path d="M8 12h.01"/>
    <path d="M12 12h.01"/>
    <path d="M16 12h.01"/>
</svg>
    </button>

    {{-- AI ჩატი --}}
    @if($isChatOpen)
        <div class="fixed inset-x-2 bottom-2 sm:inset-x-auto sm:bottom-24 sm:right-6 sm:w-96 h-[calc(100dvh-4.5rem)] max-h-[680px] sm:h-[480px] rounded-3xl shadow-2xl border z-[9999] overflow-hidden flex flex-col transition-colors pb-[env(safe-area-inset-bottom)]"
             :class="darkMode ? 'bg-slate-900 border-slate-800 shadow-black/60' : 'bg-white border-slate-200 shadow-xl'">
            <div class="bg-indigo-600 p-4 text-white flex justify-between items-center shadow-md">
                <div class="flex items-center space-x-2">
                    <img src="/images/logo.png" alt="AI Assistant" class="w-6 h-6">
                    <span class="font-bold text-sm">AI ასისტენტი & დაგეგმვა</span>
                </div>
                <button wire:click="toggleChat" class="text-white hover:text-slate-200 text-2xl leading-none font-bold px-2 py-1" aria-label="დახურვა">&times;</button>
            </div>

            <div id="chat-scroll" class="flex-1 p-4 overflow-y-auto space-y-3 transition-colors" :class="darkMode ? 'bg-slate-950/60' : 'bg-slate-50/50'">
                @foreach($chatHistory as $i => $msg)
                    <div wire:key="msg-{{ $i }}" class="flex {{ $msg['sender'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%] whitespace-pre-line rounded-2xl p-3 text-xs leading-relaxed transition-colors shadow-sm"
                             :class="darkMode ? (
                                '{{ $msg['sender'] }}' === 'user' ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-slate-900 text-slate-200 border border-slate-800 rounded-bl-none'
                             ) : (
                                '{{ $msg['sender'] }}' === 'user' ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-slate-800 border border-slate-200 rounded-bl-none'
                             )">
                            @if(!empty($msg['image']))<img src="{{ $msg['image'] }}" alt="გაგზავნილი ფოტო" class="block rounded-xl mb-2 max-h-40 w-auto">@endif{{ $msg['text'] }}
                        </div>
                    </div>
                @endforeach

                <div wire:loading wire:target="sendMessageToAi" class="text-xs transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                    AI წერს...
                </div>
            </div>

            <div class="p-3 border-t transition-colors" :class="darkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200'"
                 x-data="voiceInput()">
                @if($photo)
                    <div class="flex items-center gap-3 mb-2">
                        @if(str_starts_with((string) $photo->getMimeType(), 'image/'))
                            <img src="{{ $photo->temporaryUrl() }}" alt="არჩეული ფოტო" class="w-16 h-16 rounded-xl object-cover border border-slate-500/30">
                        @endif
                        <span class="flex-1 text-xs text-indigo-500">ფოტო მზადაა გასაგზავნად</span>
                        <button type="button" wire:click="removePhoto" class="p-1 text-slate-400 hover:text-rose-500" aria-label="ფოტოს მოხსნა"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
                    </div>
                @endif
                <div wire:loading wire:target="photo" class="text-[11px] mb-2 text-slate-500">ფოტო იტვირთება...</div>
                @error('photo') <div class="text-[11px] mb-2 text-rose-500">{{ $message }}</div> @enderror
                <div class="flex items-center gap-2">
                    <label class="cursor-pointer p-2 rounded-xl text-slate-400 hover:text-indigo-400 transition-colors" title="ფოტოს ატვირთვა" aria-label="ფოტოს ატვირთვა"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z"/><circle cx="12" cy="13" r="3"/></svg>
                        <input type="file" accept="image/*" class="hidden" @change="pick($event)">
                    </label>
                    <button type="button" x-show="supported" @click="toggle()" :class="listening ? 'animate-pulse bg-rose-500 text-white' : 'text-slate-400 hover:text-indigo-400'" class="p-2 rounded-xl transition-colors" title="ხმით საუბარი" aria-label="ხმით საუბარი"><svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 11a7 7 0 0 0 14 0"/><path d="M12 18v4"/></svg></button>
                    <input type="text" wire:model="aiMessage" wire:keydown.enter="sendMessageToAi" placeholder="ჩაწერე ან თქვი დავალება..." class="flex-1 min-w-0 px-4 py-2.5 border rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'">
                    <button wire:click="sendMessageToAi" wire:loading.attr="disabled" wire:target="sendMessageToAi,photo" title="გაგზავნა" class="bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white p-2.5 rounded-xl shadow-md transition flex items-center justify-center cursor-pointer">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- სკრიპტები --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                const el = document.getElementById('chat-scroll');
                if (el) el.scrollTop = el.scrollHeight;
            });
        });

        // ფოტოს დამცირება ბრაუზერში (სწრაფი ატვირთვა + მინიატურა, GD არ სჭირდება)
        async function resizeImage(file, max, quality) {
            let bmp;
            try { bmp = await createImageBitmap(file, { imageOrientation: 'from-image' }); }
            catch (e) { bmp = await new Promise((res, rej) => { const i = new Image(); i.onload = () => res(i); i.onerror = rej; i.src = URL.createObjectURL(file); }); }
            const w = bmp.width, h = bmp.height, k = Math.min(1, max / Math.max(w, h));
            const c = document.createElement('canvas');
            c.width = Math.max(1, Math.round(w * k)); c.height = Math.max(1, Math.round(h * k));
            const ctx = c.getContext('2d');
            ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, c.width, c.height);
            ctx.drawImage(bmp, 0, 0, c.width, c.height);
            return new Promise((res) => c.toBlob(res, 'image/jpeg', quality));
        }

        function voiceInput() {
            const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
            return {
                supported: !!SR,
                listening: false,
                rec: null,
                async pick(e) {
                    const f = e.target.files[0];
                    if (!f) return;
                    try {
                        const [big, small] = await Promise.all([resizeImage(f, 1600, 0.85), resizeImage(f, 240, 0.72)]);
                        const thumb = await new Promise((r) => { const fr = new FileReader(); fr.onload = () => r(fr.result); fr.readAsDataURL(small); });
                        this.$wire.set('photoThumb', thumb, false);
                        this.$wire.upload('photo', new File([big], 'photo.jpg', { type: 'image/jpeg' }));
                    } catch (err) {
                        this.$wire.upload('photo', f);   // თუ დამცირება ვერ მოხერხდა, ორიგინალი ავტვირთოთ
                    }
                    e.target.value = '';
                },
                toggle() {
                    if (this.listening) { this.rec && this.rec.stop(); return; }
                    this.rec = new SR();
                    this.rec.lang = 'ka-GE';
                    this.rec.interimResults = false;
                    this.rec.onresult = (e) => {
                        const t = Array.from(e.results).map(r => r[0].transcript).join(' ');
                        const cur = this.$wire.aiMessage || '';
                        this.$wire.set('aiMessage', (cur ? cur + ' ' : '') + t);
                    };
                    this.rec.onend = () => { this.listening = false; };
                    this.rec.onerror = () => { this.listening = false; };
                    this.listening = true;
                    this.rec.start();
                },
            };
        }

        // გვერდის ჩატვირთვისას ვამოწმებთ ამ მოწყობილობის რეალურ გამოწერას და გადამრთველი ამას იმახსოვრებს
        async function syncPushState() {
            const cb = document.querySelector('input[onchange="togglePushAlarm(this)"]');
            if (!cb || !('serviceWorker' in navigator) || !('PushManager' in window)) return;
            try {
                const reg = await navigator.serviceWorker.ready;
                const sub = await reg.pushManager.getSubscription();
                const on = !!sub && Notification.permission === 'granted';
                cb.checked = on;
                await @this.set('pushEnabled', on, false);
                if (on) await @this.call('savePushSubscription', sub.toJSON());
            } catch (e) { console.error(e); }
        }
        document.addEventListener('livewire:initialized', () => setTimeout(syncPushState, 200));

        async function togglePushAlarm(checkbox) {
            try {
                if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                    alert('შენს ბრაუზერს არ აქვს Push შეტყობინებების მხარდაჭერა.');
                    checkbox.checked = false;
                    return;
                }

                const registration = await navigator.serviceWorker.ready.catch(err => {
                    throw new Error('Service Worker მზად არ არის: ' + err.message);
                });

                if (checkbox.checked) {
                    const permission = await Notification.requestPermission();
                    if (permission !== 'granted') {
                        alert('შეტყობინებების ნებართვა უარყოფილია ბრაუზერის მიერ.');
                        checkbox.checked = false;
                        return;
                    }

                    const publicVapidKey = "{{ config('services.vapid.public') }}";
                    if (!publicVapidKey) {
                        alert('VAPID საჯარო გასაღები არ არის კონფიგურირებული სერვერზე!');
                        checkbox.checked = false;
                        return;
                    }

                    const convertedVapidKey = urlBase64ToUint8Array(publicVapidKey);

                    const subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: convertedVapidKey
                    });

                    await @this.call('savePushSubscription', subscription.toJSON());
                    alert('მაღვიძარა წარმატებით ჩაირთო და სუბსკრიფცია შენახულია ბაზაში!');
                } else {
                    const subscription = await registration.pushManager.getSubscription();
                    let endpoint = null;

                    if (subscription) {
                        endpoint = subscription.endpoint;
                        await subscription.unsubscribe();
                    }

                    await @this.call('deletePushSubscription', endpoint);
                    alert('მაღვიძარა გათიშულია!');
                }
            } catch (error) {
                alert('შეცდომა: ' + error.message);
                console.error(error);
                checkbox.checked = !checkbox.checked;
            }
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }
    </script>
</div>