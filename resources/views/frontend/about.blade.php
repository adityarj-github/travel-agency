@extends('layouts.frontend')

@section('title', 'About Us — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="About Us" subtitle="Crafting unforgettable travel experiences since day one."
                 :breadcrumbs="['About' => null]" />

    {{-- Intro --}}
    <section class="py-20">
        <div class="container grid items-center gap-12 lg:grid-cols-2">
            <div>
                <span class="mb-2 inline-block text-sm font-semibold uppercase tracking-widest text-brand-600">Who we are</span>
                <h2 class="section-title mb-5">{{ setting('about_heading', 'Your Trusted Travel Partner') }}</h2>
                <div class="prose-content">
                    {!! setting('about_content')
                        ? nl2br(e(setting('about_content')))
                        : '<p>We are a passionate team of travel experts dedicated to turning your travel dreams into reality. With years of experience and a deep love for exploration, we craft personalised journeys that create lifelong memories.</p><p>From exotic beach getaways to thrilling mountain adventures, we handle every detail so you can focus on what matters most — enjoying the experience.</p>' !!}
                </div>
                <div class="mt-8 grid grid-cols-3 gap-6 text-center">
                    <div><p class="text-3xl font-bold text-brand-600">{{ setting('stat_travellers', '5000+') }}</p><p class="text-sm text-slate-500">Happy Travellers</p></div>
                    <div><p class="text-3xl font-bold text-brand-600">{{ setting('stat_destinations', '120+') }}</p><p class="text-sm text-slate-500">Destinations</p></div>
                    <div><p class="text-3xl font-bold text-brand-600">{{ setting('stat_years', '10+') }}</p><p class="text-sm text-slate-500">Years Experience</p></div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <img src="https://images.unsplash.com/photo-1530789253388-582c481c54b0?auto=format&fit=crop&w=800&q=80" loading="lazy" decoding="async" class="h-64 w-full rounded-2xl object-cover" alt="">
                <img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=800&q=80" loading="lazy" decoding="async" class="mt-8 h-64 w-full rounded-2xl object-cover" alt="">
            </div>
        </div>
    </section>

    {{-- Mission & Vision --}}
    <section class="bg-slate-50 py-20">
        <div class="container grid gap-7 md:grid-cols-2">
            <div class="rounded-2xl bg-white p-8 card-shadow">
                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-slate-900">Our Mission</h3>
                <p class="text-slate-600">{{ setting('mission', 'To make extraordinary travel accessible to everyone by delivering seamless, personalised and memorable journeys at exceptional value.') }}</p>
            </div>
            <div class="rounded-2xl bg-white p-8 card-shadow">
                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-sand-100 text-sand-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-slate-900">Our Vision</h3>
                <p class="text-slate-600">{{ setting('vision', 'To be the most trusted and loved travel agency, inspiring people to explore the world and connect with diverse cultures.') }}</p>
            </div>
        </div>
    </section>

    {{-- Highlights --}}
    <section class="py-20">
        <div class="container">
            <x-section-heading eyebrow="Why us" title="What Makes Us Special" />
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $highlights = [
                        ['t' => 'Expert Guides', 'd' => 'Knowledgeable local guides for authentic experiences.'],
                        ['t' => 'Tailored Trips', 'd' => 'Itineraries customised to your interests and budget.'],
                        ['t' => 'Safe Travel', 'd' => 'Your safety and comfort are our top priorities.'],
                        ['t' => 'Great Value', 'd' => 'Premium experiences at transparent, fair prices.'],
                    ];
                @endphp
                @foreach ($highlights as $h)
                    <div class="rounded-2xl border border-slate-100 p-7 text-center transition hover:shadow-lg">
                        <h3 class="mb-2 text-lg font-bold text-slate-900">{{ $h['t'] }}</h3>
                        <p class="text-sm text-slate-500">{{ $h['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
    <section class="bg-brand-950 py-20 text-white">
        <div class="container">
            <x-section-heading eyebrow="Happy Travellers" title="What Our Customers Say" />
            <div class="mt-12 grid gap-7 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $t)
                    <figure class="rounded-2xl bg-white/5 p-7 ring-1 ring-white/10">
                        <x-star-rating :rating="$t->rating" />
                        <blockquote class="mt-4 text-sm leading-relaxed text-white/80">“{{ $t->message }}”</blockquote>
                        <figcaption class="mt-5 flex items-center gap-3">
                            <img src="{{ $t->image_url }}" alt="{{ $t->name }}" loading="lazy" decoding="async" width="44" height="44" class="h-11 w-11 rounded-full object-cover">
                            <div>
                                <p class="font-semibold">{{ $t->name }}</p>
                                @if ($t->location)<p class="text-xs text-white/60">{{ $t->location }}</p>@endif
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
