@props(['eyebrow' => null, 'title' => '', 'subtitle' => null, 'center' => true])

<div {{ $attributes->merge(['class' => $center ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl']) }}>
    @if ($eyebrow)
        <span class="mb-2 inline-block text-sm font-semibold uppercase tracking-widest text-brand-600">{{ $eyebrow }}</span>
    @endif
    <h2 class="section-title">{{ $title }}</h2>
    @if ($subtitle)
        <p class="mt-3 text-slate-500">{{ $subtitle }}</p>
    @endif
</div>
