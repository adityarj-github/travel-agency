@props(['destination'])

<a href="{{ route('destinations.show', $destination->slug) }}"
   class="group relative block h-72 overflow-hidden rounded-2xl card-shadow">
    <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" loading="lazy" decoding="async"
         class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/85 via-slate-900/20 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full p-5 text-white">
        <p class="text-xs uppercase tracking-widest text-brand-200">{{ $destination->country ?? 'Destination' }}</p>
        <h3 class="mt-1 text-xl font-bold">{{ $destination->name }}</h3>
        <p class="mt-1 text-sm text-white/80">{{ $destination->packages_count ?? $destination->packages()->count() }} tour package(s)</p>
    </div>
</a>
