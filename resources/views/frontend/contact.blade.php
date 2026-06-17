@extends('layouts.frontend')

@section('title', 'Contact Us — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="Contact Us" subtitle="We'd love to hear from you. Reach out anytime."
                 :breadcrumbs="['Contact' => null]" />

    <section class="py-16">
        <div class="container grid gap-10 lg:grid-cols-3">
            {{-- Info --}}
            <div class="space-y-6">
                @php
                    $info = [
                        ['label' => 'Address', 'value' => setting('address'), 'icon' => 'M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                        ['label' => 'Phone', 'value' => setting('phone'), 'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                        ['label' => 'Email', 'value' => setting('email'), 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                        ['label' => 'WhatsApp', 'value' => setting('whatsapp'), 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 4v-4z'],
                    ];
                @endphp
                @foreach ($info as $item)
                    @if ($item['value'])
                        <div class="flex gap-4 rounded-2xl border border-slate-100 bg-white p-5 card-shadow">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $item['label'] }}</p>
                                <p class="text-sm text-slate-500">{{ $item['value'] }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Form --}}
            <div class="lg:col-span-2">
                <div class="rounded-2xl bg-white p-8 card-shadow">
                    <h2 class="mb-1 text-2xl font-bold text-slate-900">Send us a Message</h2>
                    <p class="mb-6 text-sm text-slate-500">Fill out the form and we'll respond as soon as possible.</p>

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="form-input-base">
                            </div>
                            <div>
                                <label class="form-label">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="form-input-base">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-input-base">
                            </div>
                            <div>
                                <label class="form-label">Subject</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="form-input-base">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Message <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="5" required class="form-input-base">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Map --}}
        @if (setting('map_embed'))
            <div class="container mt-12">
                <div class="overflow-hidden rounded-2xl card-shadow [&_iframe]:block [&_iframe]:h-[300px] [&_iframe]:w-full [&_iframe]:border-0 sm:[&_iframe]:h-[420px]">
                    {!! setting('map_embed') !!}
                </div>
            </div>
        @endif
    </section>
@endsection
