@extends('layouts.frontend')

@section('title', 'Create Account — ' . setting('site_name', config('app.name')))

@section('content')
    <section class="relative overflow-hidden bg-slate-50 py-12 sm:py-16 lg:py-20">
        {{-- Soft decorative blobs --}}
        <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-brand-200/40 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-sand-200/40 blur-3xl"></div>

        <div class="container relative">
            <div class="mx-auto grid max-w-5xl overflow-hidden rounded-3xl border border-slate-100 bg-white card-shadow lg:grid-cols-2">

                {{-- ── Branding / image panel ─────────────────────────── --}}
                <div class="relative hidden min-h-[640px] flex-col justify-between overflow-hidden bg-slate-900 p-10 text-white lg:flex">
                    <img src="https://images.unsplash.com/photo-1530789253388-582c481c54b0?auto=format&fit=crop&w=1200&q=80"
                         alt="Traveller overlooking a valley" class="absolute inset-0 h-full w-full object-cover opacity-60">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-900/85 via-slate-900/70 to-slate-900/85"></div>

                    <div class="relative z-10">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-lg font-semibold tracking-tight">
                            @if (setting('logo'))
                                <img src="{{ setting_image('logo') }}" alt="{{ setting('site_name', config('app.name')) }}" class="h-9 w-auto brightness-0 invert">
                            @else
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/15 backdrop-blur">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                                </span>
                            @endif
                            <span>{{ setting('site_name', config('app.name')) }}</span>
                        </a>
                    </div>

                    <div class="relative z-10">
                        <h2 class="text-3xl font-bold leading-tight drop-shadow-sm">
                            Join us and explore<br>the world your way.
                        </h2>
                        <p class="mt-3 max-w-sm text-sm text-white/80">
                            Create a free account to book curated tours, build your wishlist, and keep every trip in one place.
                        </p>

                        <ul class="mt-8 space-y-4 text-sm">
                            @foreach ([
                                ['M5 13l4 4L19 7', 'Book and manage tours in a few clicks'],
                                ['M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'Save favourite destinations to your wishlist'],
                                ['M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'Claim past guest bookings automatically'],
                            ] as [$icon, $label])
                                <li class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/15 backdrop-blur">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                                    </span>
                                    <span class="text-white/90">{{ $label }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- ── Form panel ─────────────────────────────────────── --}}
                <div class="flex flex-col justify-center p-8 sm:p-12">
                    <div class="mx-auto w-full max-w-sm">
                        <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-brand-600 lg:hidden">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to home
                        </a>

                        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Create your account</h1>
                        <p class="mt-2 text-sm text-slate-500">It only takes a minute to get started.</p>

                        <form method="POST" action="{{ route('register.attempt') }}" class="mt-8 space-y-5" x-data="{ show: false }">
                            @csrf

                            <div>
                                <label class="form-label" for="name">Full Name</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </span>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                           autocomplete="name"
                                           class="form-input-base pl-11 @error('name') border-red-400 @enderror"
                                           placeholder="John Doe">
                                </div>
                                @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label" for="email">Email Address</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </span>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                           autocomplete="email"
                                           class="form-input-base pl-11 @error('email') border-red-400 @enderror"
                                           placeholder="you@example.com">
                                </div>
                                @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label" for="phone">Phone <span class="font-normal text-slate-400">(optional)</span></label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </span>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                           autocomplete="tel"
                                           class="form-input-base pl-11 @error('phone') border-red-400 @enderror"
                                           placeholder="+1 555 000 0000">
                                </div>
                                @error('phone')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label" for="password">Password</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </span>
                                    <input id="password" name="password" required autocomplete="new-password"
                                           :type="show ? 'text' : 'password'"
                                           class="form-input-base pl-11 pr-11 @error('password') border-red-400 @enderror"
                                           placeholder="At least 8 characters">
                                    <button type="button" @click="show = !show" tabindex="-1"
                                            :aria-label="show ? 'Hide password' : 'Show password'"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 transition hover:text-slate-600">
                                        <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                                @error('password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </span>
                                    <input id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                           :type="show ? 'text' : 'password'"
                                           class="form-input-base pl-11"
                                           placeholder="Repeat password">
                                </div>
                            </div>

                            <button type="submit" class="btn-primary w-full">
                                Create Account
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </button>
                        </form>

                        <div class="my-7 flex items-center gap-3 text-xs text-slate-400">
                            <span class="h-px flex-1 bg-slate-200"></span>
                            ALREADY A MEMBER?
                            <span class="h-px flex-1 bg-slate-200"></span>
                        </div>

                        <a href="{{ route('login') }}" class="btn-outline w-full">Sign in instead</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
