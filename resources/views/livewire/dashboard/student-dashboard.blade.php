<div class="min-h-screen relative pb-24 transition-colors duration-200"
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

    {{-- Main Content Grid --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 sm:mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

        {{-- მარცხენა სვეტი --}}
        <div class="space-y-6">

            {{-- AI პროფილის ბარათი --}}
            <div class="border border-indigo-500/20 text-white rounded-3xl p-6 shadow-xl transition-colors"
                 :class="darkMode ? 'bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-900 shadow-black/40' : 'bg-gradient-to-br from-indigo-900 to-indigo-700 shadow-lg'">
                <h3 class="font-bold text-base mb-2 flex items-center space-x-2 text-indigo-200">
                    <span>🤖</span> <span>შენი AI პროფილი</span>
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
            </div>

            {{-- დავალების ჩანიშვნის ფორმა --}}
            <div class="backdrop-blur-xl p-6 rounded-3xl border shadow-xl transition-colors"
                 :class="darkMode ? 'bg-slate-900/90 border-slate-800/80 text-white shadow-2xl shadow-black/50' : 'bg-white border-slate-100 text-slate-900 shadow-xl'">
                <h3 class="font-bold text-base mb-4 transition-colors" :class="darkMode ? 'text-white' : 'text-slate-900'">დავალების ჩანიშვნა</h3>
                <form wire:submit="addTask" class="space-y-4">
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

                    <div class="grid grid-cols-2 gap-3">
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

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white text-sm font-semibold py-3.5 rounded-xl shadow-lg shadow-indigo-600/30 transition">
                        + კალენდარში დამატება
                    </button>
                </form>
            </div>

            {{-- მაღვიძარას და ფუშ-შეტყობინებების ბარათი --}}
            <div class="backdrop-blur-xl p-6 rounded-3xl border shadow-xl transition-colors"
                 :class="darkMode ? 'bg-slate-900/90 border-slate-800/80 text-white shadow-2xl shadow-black/50' : 'bg-white border-slate-100 text-slate-900 shadow-xl'">
                <h3 class="font-bold text-base mb-3 transition-colors" :class="darkMode ? 'text-white' : 'text-slate-900'">🔔 მაღვიძარა და შეტყობინებები</h3>
                <p class="text-xs mb-4 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">ჩართე მაღვიძარა, რომ დროის მოსვლისას ტელეფონზე შეტყობინება მიიღო.</p>
                
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input 
                        type="checkbox" 
                        wire:model="pushEnabled" 
                        onchange="togglePushAlarm(this)"
                        class="w-5 h-5 rounded border text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                        :class="darkMode ? 'border-slate-800 bg-slate-950' : 'border-slate-300 bg-white'"
                    >
                    <span class="text-sm font-medium" :class="darkMode ? 'text-white' : 'text-slate-900'">ფუშ მაღვიძარას ჩართვა</span>
                </label>
            </div>
        </div>

        {{-- მარჯვენა სვეტი: დღის განრიგი --}}
        <div class="backdrop-blur-xl rounded-3xl border shadow-xl p-6 lg:col-span-2 transition-colors"
             :class="darkMode ? 'bg-slate-900/90 border-slate-800/80 text-white shadow-2xl shadow-black/50' : 'bg-white border-slate-100 text-slate-900 shadow-xl'">
            <h3 class="font-bold text-base sm:text-lg mb-6 flex items-center justify-between transition-colors" :class="darkMode ? 'text-white' : 'text-slate-900'">
                <span>📅 დავალებებისა და გაკვეთილების განრიგი</span>
                <span class="text-xs font-normal transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">{{ now()->format('Y-m-d') }}</span>
            </h3>

            <div class="space-y-3.5">
                @forelse($tasks as $task)
                    <div wire:key="task-{{ $task->id }}" class="flex items-start p-4 rounded-2xl border transition-all {{ $task->is_completed ? 'opacity-50' : '' }}"
                         :class="darkMode ? ($task->is_completed ? 'bg-slate-950/40 border-slate-800/60' : 'bg-slate-950/60 border-slate-800 hover:border-slate-700') : ($task->is_completed ? 'bg-slate-100 border-slate-200' : 'bg-slate-50/60 border-slate-200 hover:border-slate-300')">
                        <input type="checkbox" wire:click="toggleTaskCompletion({{ $task->id }})" {{ $task->is_completed ? 'checked' : '' }} class="mt-1 w-5 h-5 rounded border text-indigo-600 focus:ring-indigo-500 cursor-pointer" :class="darkMode ? 'border-slate-800 bg-slate-950' : 'border-slate-300 bg-white'">

                        <div class="ml-4 flex-1">
                            <h4 class="text-sm font-bold {{ $task->is_completed ? 'line-through' : '' }}"
                                :class="darkMode ? ($task->is_completed ? 'text-slate-500' : 'text-white') : ($task->is_completed ? 'text-slate-400' : 'text-slate-900')">
                                {{ $task->title }}
                            </h4>

                            @if($task->description)
                                <p class="text-xs mt-1 transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">
                                    {{ $task->description }}
                                </p>
                            @endif

                            <p class="text-xs mt-1.5 transition-colors" :class="darkMode ? 'text-slate-500' : 'text-slate-400'">
                                🕒 {{ \Carbon\Carbon::parse($task->start_time)->format('Y-m-d H:i') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}
                            </p>
                        </div>

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
    <button wire:click="toggleChat" class="fixed bottom-6 right-6 h-14 w-14 rounded-full bg-indigo-600 text-white shadow-2xl shadow-indigo-600/50 hover:bg-indigo-500 flex items-center justify-center text-2xl transition transform hover:scale-105 z-50">
        💬
    </button>

    {{-- AI ჩატი --}}
    @if($isChatOpen)
        <div class="fixed bottom-24 right-4 sm:right-6 w-[calc(100vw-2rem)] sm:w-96 rounded-3xl shadow-2xl border z-50 overflow-hidden flex flex-col h-[480px] transition-colors"
             :class="darkMode ? 'bg-slate-900 border-slate-800 shadow-black/60' : 'bg-white border-slate-200 shadow-xl'">
            <div class="bg-indigo-600 p-4 text-white flex justify-between items-center shadow-md">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">🤖</span>
                    <span class="font-bold text-sm">AI ასისტენტი & დაგეგმვა</span>
                </div>
                <button wire:click="toggleChat" class="text-white hover:text-slate-200 text-xl font-bold">&times;</button>
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
                            {{ $msg['text'] }}
                        </div>
                    </div>
                @endforeach

                <div wire:loading wire:target="sendMessageToAi" class="text-xs transition-colors" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                    AI წერს...
                </div>
            </div>

            <div class="p-3 border-t flex space-x-2 transition-colors" :class="darkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200'">
                <input type="text" wire:model="aiMessage" wire:keydown.enter="sendMessageToAi" placeholder="ჩაწერე დავალება ან გრაფიკი..." class="flex-1 px-4 py-2.5 border rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" :class="darkMode ? 'bg-slate-950 border-slate-800 text-white placeholder-slate-600' : 'bg-slate-50 border-slate-200 text-slate-900 placeholder-slate-400'">
                <button wire:click="sendMessageToAi" wire:loading.attr="disabled" wire:target="sendMessageToAi" class="bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white px-4 py-2.5 rounded-xl text-xs font-semibold shadow-md transition">
                    გაგზავნა
                </button>
            </div>
        </div>
    @endif

    {{-- სკრიპტები: ჩატის სქროლი და ფუშ მაღვიძარას ლოგიკა --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                const el = document.getElementById('chat-scroll');
                if (el) el.scrollTop = el.scrollHeight;
            });
        });

        async function togglePushAlarm(checkbox) {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                alert('შენს ბრაუზერს არ აქვს Push შეტყობინებების მხარდაჭერა.');
                checkbox.checked = false;
                return;
            }

            try {
                const registration = await navigator.serviceWorker.ready;

                if (checkbox.checked) {
                    const permission = await Notification.requestPermission();
                    if (permission !== 'granted') {
                        alert('შეტყობინებების ნებართვა უარყოფილია ბრაუზერის მიერ.');
                        checkbox.checked = false;
                        return;
                    }

                    const publicVapidKey = "{{ config('services.vapid.public') }}";
                    const convertedVapidKey = urlBase64ToUint8Array(publicVapidKey);

                    const subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: convertedVapidKey
                    });

                    await @this.call('savePushSubscription', subscription.toJSON());
                    console.log('მაღვიძარა ჩაირთო!');
                } else {
                    const subscription = await registration.pushManager.getSubscription();
                    let endpoint = null;

                    if (subscription) {
                        endpoint = subscription.endpoint;
                        await subscription.unsubscribe();
                    }

                    await @this.call('deletePushSubscription', endpoint);
                    console.log('მაღვიძარა გაითიშა!');
                }
            } catch (error) {
                console.error('მაღვიძარას შეცდომა:', error);
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