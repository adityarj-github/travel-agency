@props(['package'])

<article class="group flex h-full flex-col overflow-hidden rounded-2xl bg-white card-shadow transition hover:-translate-y-1">
    <div class="relative h-52 overflow-hidden">
        <img src="{{ $package->main_image_url }}" alt="{{ $package->title }}" loading="lazy" decoding="async"
             class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
        <div class="absolute left-3 top-3 flex flex-col gap-2">
            @if ($package->has_discount)
                <span class="rounded-full bg-red-500 px-3 py-1 text-xs font-semibold text-white">
                    -{{ $package->discount_percent }}%
                </span>
            @endif
            @if ($package->category)
                <span class="rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-brand-700">
                    {{ $package->category }}
                </span>
            @endif
        </div>
        <div class="absolute right-3 top-3">
            <x-wishlist-button :package="$package" />
        </div>
        <div class="absolute bottom-3 left-3 flex items-center gap-1 rounded-full bg-slate-900/70 px-3 py-1 text-xs text-white">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
            {{ $package->location ?? optional($package->destination)->name ?? 'Worldwide' }}
        </div>
    </div>

    <div class="flex flex-1 flex-col p-5">
        <div class="mb-2 flex items-center justify-between text-xs text-slate-500">
            <span class="inline-flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 2"/></svg>
                {{ $package->duration_label }}
            </span>
            @if ($package->tour_type)
                <span class="rounded bg-slate-100 px-2 py-0.5">{{ $package->tour_type }}</span>
            @endif
        </div>

        <h3 class="mb-2 text-lg font-bold leading-snug text-slate-900">
            <a href="{{ route('packages.show', $package->slug) }}" class="transition hover:text-brand-600">{{ $package->title }}</a>
        </h3>

        <p class="mb-4 line-clamp-2 flex-1 text-sm text-slate-500">{{ $package->short_description }}</p>

        <div class="mt-auto flex items-end justify-between border-t border-slate-100 pt-4">
            <div>
                <span class="text-xs text-slate-400">From</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-bold text-brand-600">{{ setting('currency_symbol', '$') }}{{ number_format($package->effective_price, 0) }}</span>
                    @if ($package->has_discount)
                        <span class="text-sm text-slate-400 line-through">{{ setting('currency_symbol', '$') }}{{ number_format($package->price, 0) }}</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('packages.show', $package->slug) }}" class="btn-outline !px-4 !py-2 text-xs">View Details</a>
        </div>
    </div>
</article>
