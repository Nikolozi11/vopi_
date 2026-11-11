<div class="min-h-screen bg-slate-50 relative pb-20">

    {{-- Top Header --}}
    <header class="bg-white border-b border-slate-200 px-6 py-4 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold">
                    🎓
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">მოსწავლის პორტალი</h1>
                    <p class="text-xs text-slate-500">მოგესალმები, {{ auth()->user()->name }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                    ● AI ასისტენტი აქტიურია
                </span>
            </div>
        </div>
    </header>

    {{-- Main Content Grid --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- მარცხენა სვეტი --}}
        <div class="space-y-6">

            {{-- AI პროფილის ბარათი --}}
            <div class="bg-gradient-to-br from-blue-900 to-blue-700 text-white rounded-2xl p-6 shadow-xl shadow-blue-900/20">
                <h3 class="font-bold text-lg mb-2 flex items-center space-x-2">
                    <span>🤖</span> <span>შენი AI პროფილი</span>
                </h3>
                <div class="text-xs text-blue-100 space-y-2 mt-4">
                    <div class="flex justify-between border-b border-blue-600/50 pb-2">
                        <span>დღიური მეცადინეობა:</span>
                        <span class="font-semibold text-white">{{ $profile->avg_study_hours_per_day ?? 2 }} საათი</span>
                    </div>
                    <div class="flex justify-between border-b border-blue-600/50 pb-2">
                        <span>დრო 1 საგანზე:</span>
                        <span class="font-semibold text-white">{{ $profile->avg_minutes_per_subject ?? 45 }} წუთი</span>
                    </div>
                    <div class="flex justify-between">
                        <span>რეპეტიტორი:</span>
                        <span class="font-semibold text-white">{{ ($profile->has_tutor ?? false) ? 'დიახ' : 'არა' }}</span>
                    </div>
                </div>
            </div>

            {{-- დავალების ჩანიშვნის ფორმა --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 text-lg mb-4">დავალების ჩანიშვნა</h3>
                <form wire:submit="addTask" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">სათაური / საგანი</label>
                        <input type="text" wire:model="title" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                        @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">აღწერა (არასავალდებულო)</label>
                        <textarea wire:model="description" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
                        @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">ტიპი</label>
                        <select wire:model="type" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="school_task">სკოლის დავალება</option>
                            <option value="tutor">რეპეტიტორი</option>
                            <option value="activity">წრე / ჰობი</option>
                            <option value="break">დასვენება</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">დასაწყისი</label>
                            <input type="datetime-local" wire:model="start_time" class="w-full px-2 py-2 border border-slate-300 rounded-xl text-xs">
                            @error('start_time') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">დასასრული</label>
                            <input type="datetime-local" wire:model="end_time" class="w-full px-2 py-2 border border-slate-300 rounded-xl text-xs">
                            @error('end_time') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-xl shadow-md transition">
                        + კალენდარში დამატება
                    </button>
                </form>
            </div>
        </div>

        {{-- მარჯვენა სვეტი: დღის განრიგი --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 text-lg mb-6 flex items-center justify-between">
                <span>📅 დავალებებისა და გაკვეთილების განრიგი</span>
                <span class="text-xs font-normal text-slate-500">{{ now()->format('Y-m-d') }}</span>
            </h3>

            <div class="space-y-4">
                @forelse($tasks as $task)
                    <div wire:key="task-{{ $task->id }}" class="flex items-start p-4 rounded-xl border transition-all {{ $task->is_completed ? 'bg-slate-50 border-slate-200 opacity-60' : 'bg-blue-50/30 border-blue-100 hover:border-blue-300' }}">
                        <input type="checkbox" wire:click="toggleTaskCompletion({{ $task->id }})" {{ $task->is_completed ? 'checked' : '' }} class="mt-1 w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">

                        <div class="ml-4 flex-1">
                            <h4 class="text-sm font-bold {{ $task->is_completed ? 'line-through text-slate-500' : 'text-slate-800' }}">
                                {{ $task->title }}
                            </h4>

                            @if($task->description)
                                <p class="text-xs text-slate-600 mt-1">
                                    {{ $task->description }}
                                </p>
                            @endif

                            <p class="text-xs text-slate-400 mt-1">
                                🕒 {{ \Carbon\Carbon::parse($task->start_time)->format('Y-m-d H:i') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $task->type === 'school_task' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $task->type === 'tutor' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $task->type === 'activity' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ $task->type === 'break' ? 'bg-emerald-100 text-emerald-800' : '' }}">
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
                    <div class="text-center py-12 text-slate-400 text-sm">
                        დავალებები ჯერ არ არის ჩანიშნული. ისარგებლეთ AI ჩატით ან დაამატეთ ხელით!
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    {{-- მცურავი AI ჩატის ღილაკი --}}
    <button wire:click="toggleChat" class="fixed bottom-6 right-6 h-14 w-14 rounded-full bg-blue-600 text-white shadow-xl shadow-blue-600/40 hover:bg-blue-700 flex items-center justify-center text-2xl transition transform hover:scale-105 z-50">
        💬
    </button>

    {{-- AI ჩატი --}}
    @if($isChatOpen)
        <div class="fixed bottom-24 right-6 w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden flex flex-col h-[500px]">
            <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">🤖</span>
                    <span class="font-bold text-sm">AI ასისტენტი & დაგეგმვა</span>
                </div>
                <button wire:click="toggleChat" class="text-white hover:text-slate-200 text-xl font-bold">&times;</button>
            </div>

            <div id="chat-scroll" class="flex-1 p-4 overflow-y-auto space-y-3 bg-slate-50">
                @foreach($chatHistory as $i => $msg)
                    <div wire:key="msg-{{ $i }}" class="flex {{ $msg['sender'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] whitespace-pre-line rounded-2xl p-3 text-xs leading-relaxed {{ $msg['sender'] === 'user' ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white text-slate-800 border border-slate-200 shadow-sm rounded-bl-none' }}">
                            {{ $msg['text'] }}
                        </div>
                    </div>
                @endforeach

                <div wire:loading wire:target="sendMessageToAi" class="text-xs text-slate-400">
                    AI წერს...
                </div>
            </div>

            <div class="p-3 bg-white border-t border-slate-200 flex space-x-2">
                <input type="text" wire:model="aiMessage" wire:keydown.enter="sendMessageToAi" placeholder="ჩაწერე დავალება ან გრაფიკი..." class="flex-1 px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-500">
                <button wire:click="sendMessageToAi" wire:loading.attr="disabled" wire:target="sendMessageToAi" class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-md">
                    გაგზავნა
                </button>
            </div>
        </div>
    @endif

    {{-- ჩატის ავტომატური ჩამოსქროლვა ახალ შეტყობინებაზე --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                const el = document.getElementById('chat-scroll');
                if (el) el.scrollTop = el.scrollHeight;
            });
        });
    </script>
</div>