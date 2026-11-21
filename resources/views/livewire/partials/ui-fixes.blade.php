@once
<style>
    /* ---- ლამაზი თხელი სქროლბარები ---- */
    * { scrollbar-width: thin; scrollbar-color: rgba(129, 140, 248, .45) transparent; }
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(129, 140, 248, .4); border-radius: 999px; border: 2px solid transparent; background-clip: content-box; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(129, 140, 248, .75); background-clip: content-box; }
    ::-webkit-scrollbar-corner { background: transparent; }
    #chat-scroll { overscroll-behavior: contain; }


    /* ---- კურსორი: ყველაფერზე, რაზეც შეიძლება დაჭერა ---- */
    button, summary, select, a[href], [role="button"], [wire\:click], [x-on\:click], [\@click],
    input[type="button"], input[type="submit"], input[type="checkbox"], input[type="radio"], input[type="file"],
    input[type="time"], input[type="date"], input[type="datetime-local"],
    label:has(input[type="checkbox"]), label:has(input[type="radio"]), label:has(input[type="file"]) { cursor: pointer; }
    button:disabled, [disabled] { cursor: not-allowed; }

    /* ---- ლამაზი ჩეკბოქსები და გადამრთველები ---- */
    input.vp-check {
        -webkit-appearance: none; appearance: none; flex-shrink: 0; display: inline-block; vertical-align: middle;
        width: 1.4rem; height: 1.4rem; border-radius: .5rem; cursor: pointer;
        border: 2px solid rgba(148, 163, 184, .7); background: transparent center / 72% no-repeat;
        transition: background-color .15s, border-color .15s, box-shadow .15s, transform .1s;
    }
    input.vp-check.vp-round { border-radius: 999px; }
    input.vp-check:hover { border-color: #818cf8; }
    input.vp-check:active { transform: scale(.92); }
    input.vp-check:checked {
        background-color: #4f46e5; border-color: #4f46e5; box-shadow: 0 2px 8px -2px rgba(79, 70, 229, .6);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M5 12l5 5L20 7'/%3E%3C/svg%3E");
    }
    input.vp-check:focus-visible, input.vp-switch:focus-visible { outline: 2px solid #818cf8; outline-offset: 2px; }

    input.vp-switch {
        -webkit-appearance: none; appearance: none; flex-shrink: 0; position: relative; cursor: pointer;
        width: 2.9rem; height: 1.6rem; border-radius: 999px; border: 0;
        background: rgba(148, 163, 184, .45); transition: background-color .2s;
    }
    input.vp-switch::after {
        content: ''; position: absolute; top: 3px; left: 3px; width: 1.2rem; height: 1.2rem; border-radius: 50%;
        background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .35); transition: transform .2s;
    }
    input.vp-switch:checked { background: #4f46e5; }
    input.vp-switch:checked::after { transform: translateX(1.3rem); }
    @media (prefers-reduced-motion: reduce) { input.vp-switch, input.vp-switch::after, input.vp-check { transition: none; } }

    /* ხატულები და ჩეკბოქსები flex-ში არ იკუმშება */
    svg { flex-shrink: 0; }
    input[type="checkbox"], input[type="radio"] { flex-shrink: 0; }

    /* ---- ტელეფონი ---- */
    html { -webkit-text-size-adjust: 100%; -webkit-tap-highlight-color: transparent; }
    @media (max-width: 640px) {
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        /* 16px — რომ iPhone-მა ველზე დაჭერისას არ გაადიდოს გვერდი */
        input:not([type="checkbox"]):not([type="radio"]):not([type="file"]), select, textarea { font-size: 16px !important; }
        input[type="datetime-local"], input[type="time"], input[type="date"] { min-width: 0; max-width: 100%; }
        button, summary, label { touch-action: manipulation; }
    }
</style>
@endonce
