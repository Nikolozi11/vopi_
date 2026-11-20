<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 transition-colors"
     x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || document.documentElement.classList.contains('dark') }"
     x-init="setInterval(() => darkMode = localStorage.getItem('darkMode') === 'true' || document.documentElement.classList.contains('dark'), 150)"
     :class="darkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <div class="max-w-xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold">ჩემი პროფილი</h1>
            <a href="/dashboard" class="text-sm font-semibold" :class="darkMode ? 'text-indigo-400' : 'text-indigo-600'">← განრიგი</a>
        </div>

        <div class="p-6 rounded-3xl border space-y-5" :class="darkMode ? 'bg-slate-900/90 border-slate-800/80' : 'bg-white border-slate-100 shadow-xl'">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold mb-2" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">სწავლის დრო (სთ/დღე)</label>
                    <input type="number" wire:model="avg_study_hours_per_day" min="1" max="12" class="w-full px-4 py-3 border rounded-xl" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white' : 'bg-slate-50 border-slate-200'">
                    @error('avg_study_hours_per_day') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-2" :class="darkMode ? 'text-slate-400' : 'text-slate-600'">დრო 1 საგანზე (წუთი)</label>
                    <input type="number" wire:model="avg_minutes_per_subject" min="15" max="180" class="w-full px-4 py-3 border rounded-xl" :class="darkMode ? 'bg-slate-950/60 border-slate-800 text-white' : 'bg-slate-50 border-slate-200'">
                    @error('avg_minutes_per_subject') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
            </div>

            @include('livewire.partials.profile-fields')

            <div class="pt-4 border-t flex items-center gap-3" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
                <button type="button" wire:click="save" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-indigo-600/30 transition">შენახვა</button>
                @if($saved)
                    <span class="text-sm text-emerald-500 font-semibold inline-flex items-center gap-1"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5L20 7"/></svg>შენახულია</span>
                @endif
            </div>
        </div>
    </div>
</div>
