@props(['title' => '', 'subtitle' => null, 'image' => null, 'breadcrumbs' => []])

@php
    $bg = $image ?: (setting('page_header') ? setting_image('page_header') : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80');
@endphp

<section class="relative flex h-[40vh] min-h-[280px] items-center justify-center overflow-hidden bg-slate-900">
    <img src="{{ $bg }}" alt="{{ $title }}" fetchpriority="high" class="absolute inset-0 h-full w-full object-cover opacity-50">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/40 to-transparent"></div>
    <div class="relative z-10 px-4 text-center text-white">
        <h1 class="text-4xl font-bold drop-shadow-lg sm:text-5xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mx-auto mt-3 max-w-2xl text-white/90">{{ $subtitle }}</p>
        @endif
        <nav class="mt-4 flex items-center justify-center gap-2 text-sm text-white/80">
            <a href="{{ route('home') }}" class="hover:text-white">Home</a>
            @foreach ($breadcrumbs as $label => $url)
                <span>/</span>
                @if ($url)
                    <a href="{{ $url }}" class="hover:text-white">{{ $label }}</a>
                @else
                    <span class="text-brand-300">{{ $label }}</span>
                @endif
            @endforeach
        </nav>
    </div>
</section>
