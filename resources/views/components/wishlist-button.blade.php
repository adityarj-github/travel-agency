@props([
    'package',
    'variant' => 'icon',   // 'icon' (floating heart) | 'button' (full-width labelled)
])

@php
    $active = in_array($package->id, $myWishlistIds ?? [], true);
    $guest = ! (auth()->check() && auth()->user()->isCustomer());
@endphp

@if ($guest)
    {{-- Guests are sent to login; we remember where they were going. --}}
    @if ($variant === 'button')
        <a href="{{ route('login') }}" class="btn-outline w-full">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            Save to Wishlist
        </a>
    @else
        <a href="{{ route('login') }}" title="Sign in to save"
           class="flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-slate-500 shadow transition hover:text-pink-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </a>
    @endif
@else
    <div x-data="wishlistToggle({{ $package->id }}, {{ $active ? 'true' : 'false' }})" class="{{ $variant === 'button' ? 'w-full' : '' }}">
        @if ($variant === 'button')
            <button type="button" @click="toggle" :disabled="loading"
                    class="btn w-full border transition"
                    :class="active ? 'border-pink-500 bg-pink-50 text-pink-600' : 'border-brand-600 text-brand-700 hover:bg-brand-50'">
                <svg class="h-5 w-5" :fill="active ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span x-text="active ? 'Saved to Wishlist' : 'Save to Wishlist'"></span>
            </button>
        @else
            <button type="button" @click="toggle" :disabled="loading" title="Toggle wishlist"
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-white/90 shadow transition hover:scale-110"
                    :class="active ? 'text-pink-600' : 'text-slate-500 hover:text-pink-600'">
                <svg class="h-5 w-5" :fill="active ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
        @endif
    </div>
@endif
