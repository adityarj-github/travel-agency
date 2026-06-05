@extends('layouts.frontend')

@section('title', 'Book Your Trip — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="Booking Inquiry" subtitle="Tell us your travel plans and we'll take care of the rest."
                 :breadcrumbs="['Booking' => null]" />

    <section class="py-16">
        <div class="container grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="rounded-2xl bg-white p-8 card-shadow">
                    <h2 class="mb-1 text-2xl font-bold text-slate-900">Plan Your Journey</h2>
                    <p class="mb-6 text-sm text-slate-500">Fill in the form and our travel experts will get in touch.</p>
                    <x-booking-form :packages="$packages" :destinations="$destinations" :selectedPackage="$selectedPackage" />
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl bg-brand-50 p-7">
                    <h3 class="mb-4 text-lg font-bold text-slate-900">Why book with us?</h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> No booking fees — inquiries are completely free</li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> Personalised itineraries tailored to you</li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> Fast response within 24 hours</li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> Flexible payment options</li>
                    </ul>
                </div>

                @if (setting('phone') || setting('email'))
                <div class="rounded-2xl border border-slate-100 p-7">
                    <h3 class="mb-4 text-lg font-bold text-slate-900">Need help?</h3>
                    @if (setting('phone'))<p class="mb-2 text-sm text-slate-600">☎ <a href="tel:{{ setting('phone') }}" class="hover:text-brand-600">{{ setting('phone') }}</a></p>@endif
                    @if (setting('email'))<p class="text-sm text-slate-600">✉ <a href="mailto:{{ setting('email') }}" class="hover:text-brand-600">{{ setting('email') }}</a></p>@endif
                </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
