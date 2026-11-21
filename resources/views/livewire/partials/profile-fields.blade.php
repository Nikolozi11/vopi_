@php
    $days = \App\Services\ScheduleService::DAYS;
    $in = 'w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors';
@endphp

{{-- სკოლა --}}
<div class="border-t pt-4" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
    <label class="text-xs font-bold uppercase tracking-wider block mb-3" :class="darkMode ? 'text-slate-300' : 'text-slate-700'"><span class="inline-flex items-center gap-2"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.42 10.9a1 1 0 0 0 0-1.84L12.83 5.2a2 2 0 0 0-1.66 0L2.6 9.1a1 1 0 0 0 0 1.83l8.57 3.9a2 2 0 0 0 1.66 0Z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>სკოლა</span></label>
    <div class="grid grid-cols-2 gap-3 mb-3">
        <div>
            <span class="text-[11px] block mb-1" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">იწყება</span>
            <input type="time" wire:model="school_start_time" class="{{ $in }}" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
            @error('school_start_time') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <span class="text-[11px] block mb-1" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">მთავრდება</span>
            <input type="time" wire:model="school_end_time" class="{{ $in }}" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
            @error('school_end_time') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($days as $n => $label)
            <label class="cursor-pointer select-none">
                <input type="checkbox" value="{{ $n }}" wire:model="school_days" class="peer sr-only">
                <span class="px-3 py-1.5 rounded-full border text-xs font-semibold transition-colors peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600"
                      :class="darkMode ? 'border-slate-800 text-slate-400' : 'border-slate-200 text-slate-600'">{{ mb_substr($label, 0, 3) }}</span>
            </label>
        @endforeach
    </div>
</div>

{{-- წრეები --}}
<div class="border-t pt-4" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
    <div class="flex justify-between items-center mb-3">
        <label class="text-xs font-bold uppercase tracking-wider" :class="darkMode ? 'text-slate-300' : 'text-slate-700'"><span class="inline-flex items-center gap-2"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>წრეები / ჰობი</span></label>
        <button type="button" wire:click="addActivityInput" class="text-xs font-semibold" :class="darkMode ? 'text-indigo-400' : 'text-indigo-600'">+ დამატება</button>
    </div>
    <div class="space-y-3">
        @foreach($activities as $i => $act)
            <div wire:key="act-{{ $i }}" class="p-3 rounded-xl border space-y-2" :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50 border-slate-200'">
                <div class="flex items-center gap-2">
                    <input type="text" wire:model="activities.{{ $i }}.name" placeholder="მაგ: გიტარა, ცურვა..." class="flex-1 {{ $in }}" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                    @if(count($activities) > 1)
                        <button type="button" wire:click="removeActivityInput({{ $i }})" class="text-rose-500 font-bold px-2 text-lg">&times;</button>
                    @endif
                </div>
                @foreach($act['schedule'] as $j => $slot)
                    <div wire:key="act-{{ $i }}-{{ $j }}" class="flex flex-wrap items-center gap-1.5">
                        <select wire:model="activities.{{ $i }}.schedule.{{ $j }}.day" class="{{ $in }} !w-auto" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                            @foreach($days as $n => $label) <option value="{{ $n }}">{{ mb_substr($label, 0, 3) }}</option> @endforeach
                        </select>
                        <input type="time" wire:model="activities.{{ $i }}.schedule.{{ $j }}.start" class="{{ $in }} !w-auto flex-1 min-w-[6.5rem]" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                        <span class="text-xs">–</span>
                        <input type="time" wire:model="activities.{{ $i }}.schedule.{{ $j }}.end" class="{{ $in }} !w-auto flex-1 min-w-[6.5rem]" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                        @if(count($act['schedule']) > 1)
                            <button type="button" wire:click="removeSlot('activities', {{ $i }}, {{ $j }})" class="text-rose-500 font-bold px-1">&times;</button>
                        @endif
                    </div>
                    @error("activities.$i.schedule.$j.end") <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror
                @endforeach
                <button type="button" wire:click="addSlot('activities', {{ $i }})" class="text-[11px] font-semibold" :class="darkMode ? 'text-indigo-400' : 'text-indigo-600'">+ კიდევ ერთი დღე</button>
            </div>
        @endforeach
    </div>
</div>

{{-- რეპეტიტორები --}}
<div class="border-t pt-4" :class="darkMode ? 'border-slate-800' : 'border-slate-100'">
    <div class="flex items-center justify-between mb-3">
        <label class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" wire:model.live="has_tutor" class="vp-switch" aria-label="გყავს რეპეტიტორი?">
            <span class="text-xs font-bold uppercase tracking-wider" :class="darkMode ? 'text-slate-300' : 'text-slate-700'"><span class="inline-flex items-center gap-2"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h20"/><path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/><path d="m7 21 5-5 5 5"/></svg>გყავს რეპეტიტორი?</span></span>
        </label>
        @if($has_tutor)
            <button type="button" wire:click="addTutorInput" class="text-xs font-semibold" :class="darkMode ? 'text-indigo-400' : 'text-indigo-600'">+ დამატება</button>
        @endif
    </div>
    @if($has_tutor)
        <div class="space-y-3">
            @foreach($tutors as $i => $tutor)
                <div wire:key="tut-{{ $i }}" class="p-3 rounded-xl border space-y-2" :class="darkMode ? 'bg-slate-950/60 border-slate-800' : 'bg-slate-50 border-slate-200'">
                    <div class="flex items-center gap-2">
                        <input type="text" wire:model="tutors.{{ $i }}.subject" placeholder="საგანი (მაგ: მათემატიკა)" class="flex-1 {{ $in }}" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                        @if(count($tutors) > 1)
                            <button type="button" wire:click="removeTutorInput({{ $i }})" class="text-rose-500 font-bold px-2 text-lg">&times;</button>
                        @endif
                    </div>
                    @foreach($tutor['schedule'] as $j => $slot)
                        <div wire:key="tut-{{ $i }}-{{ $j }}" class="flex flex-wrap items-center gap-1.5">
                            <select wire:model="tutors.{{ $i }}.schedule.{{ $j }}.day" class="{{ $in }} !w-auto" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                                @foreach($days as $n => $label) <option value="{{ $n }}">{{ mb_substr($label, 0, 3) }}</option> @endforeach
                            </select>
                            <input type="time" wire:model="tutors.{{ $i }}.schedule.{{ $j }}.start" class="{{ $in }} !w-auto flex-1 min-w-[6.5rem]" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                            <span class="text-xs">–</span>
                            <input type="time" wire:model="tutors.{{ $i }}.schedule.{{ $j }}.end" class="{{ $in }} !w-auto flex-1 min-w-[6.5rem]" :class="darkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-200 text-slate-900'">
                            @if(count($tutor['schedule']) > 1)
                                <button type="button" wire:click="removeSlot('tutors', {{ $i }}, {{ $j }})" class="text-rose-500 font-bold px-1">&times;</button>
                            @endif
                        </div>
                        @error("tutors.$i.schedule.$j.end") <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror
                    @endforeach
                    <button type="button" wire:click="addSlot('tutors', {{ $i }})" class="text-[11px] font-semibold" :class="darkMode ? 'text-indigo-400' : 'text-indigo-600'">+ კიდევ ერთი დღე</button>
                </div>
            @endforeach
        </div>
    @endif
</div>
