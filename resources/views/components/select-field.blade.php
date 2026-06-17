@props([
    'name',
    'label' => null,
    'placeholder' => 'Select an option',
    'options' => [],        // array of ['value' =>, 'label' =>, 'price' =>?, 'meta' =>?]
    'selected' => null,
    'icon' => null,         // svg path "d" string shown on the left
    'dispatch' => null,     // optional Alpine event name emitted on change (detail: {value,label,price})
])

@php
    $opts = collect($options)->map(fn ($o) => [
        'value' => (string) ($o['value'] ?? ''),
        'label' => (string) ($o['label'] ?? ''),
        'price' => (float) ($o['price'] ?? 0),
        'meta'  => $o['meta'] ?? null,
    ])->values()->all();
    $current = collect($opts)->firstWhere('value', (string) ($selected ?? ''));
@endphp

<div x-data="{
        open: false,
        value: @js((string) ($selected ?? '')),
        label: @js($current['label'] ?? ''),
        choose(v, l, p) {
            this.value = v;
            this.label = l;
            this.open = false;
            @if ($dispatch) this.$dispatch(@js($dispatch), { value: v, label: l, price: p }); @endif
        },
     }"
     @click.outside="open = false"
     @keydown.escape="open = false"
     class="relative">

    @if ($label)
        <label class="form-label">{{ $label }}</label>
    @endif

    {{-- Real submitted value --}}
    <input type="hidden" name="{{ $name }}" :value="value">

    {{-- Trigger --}}
    <button type="button" @click="open = !open" :aria-expanded="open" aria-haspopup="listbox"
            class="flex w-full items-center justify-between gap-2 rounded-lg border bg-white px-4 py-3 text-left text-sm shadow-sm transition focus:outline-none sm:py-2.5"
            :class="open ? 'border-forest-500 ring-2 ring-forest-500/40' : 'border-slate-300 hover:border-slate-400'">
        <span class="flex min-w-0 items-center gap-2.5">
            @if ($icon)
                <svg class="h-4 w-4 shrink-0 text-forest-600" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
            @endif
            <span class="truncate" :class="value ? 'text-slate-800' : 'text-slate-400'" x-text="label || @js($placeholder)"></span>
        </span>
        <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform duration-200" :class="open && 'rotate-180'"
             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
    </button>

    {{-- Panel --}}
    <ul x-show="open" x-cloak x-transition.duration.150ms role="listbox"
        class="absolute inset-x-0 z-30 mt-2 max-h-60 overflow-auto rounded-xl border border-slate-100 bg-white p-1.5 shadow-xl shadow-slate-900/10">
        <li>
            <button type="button" @click="choose('', '', 0)"
                    class="block w-full rounded-lg px-3 py-2 text-left text-sm text-slate-400 transition hover:bg-slate-50">
                {{ $placeholder }}
            </button>
        </li>
        @foreach ($opts as $opt)
            <li>
                <button type="button" role="option"
                        @click="choose(@js($opt['value']), @js($opt['label']), {{ $opt['price'] }})"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm transition"
                        :class="value === @js($opt['value']) ? 'bg-forest-50 font-semibold text-forest-800' : 'text-slate-700 hover:bg-slate-50'">
                    <span class="min-w-0 flex-1 truncate">{{ $opt['label'] }}</span>
                    @if (!empty($opt['meta']))
                        <span class="shrink-0 text-xs font-medium text-slate-400">{{ $opt['meta'] }}</span>
                    @endif
                    <svg x-show="value === @js($opt['value'])" x-cloak class="h-4 w-4 shrink-0 text-forest-600"
                         fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </button>
            </li>
        @endforeach
    </ul>
</div>
