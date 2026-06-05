@extends('layouts.frontend')

@section('title', 'My Profile — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="My Profile" :breadcrumbs="['Account' => route('account.dashboard'), 'Profile' => null]" />

    <section class="py-12">
        <div class="container grid gap-8 lg:grid-cols-4">
            <div class="lg:col-span-1">@include('frontend.account._nav')</div>

            <div class="space-y-8 lg:col-span-3">
                {{-- Profile details --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-6 card-shadow">
                    <h3 class="mb-4 font-bold text-slate-900">Profile Details</h3>
                    <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label" for="name">Full Name</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                       class="form-input-base @error('name') border-red-400 @enderror">
                                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="phone">Phone</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                       class="form-input-base @error('phone') border-red-400 @enderror">
                                @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="form-label" for="email">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="form-input-base @error('email') border-red-400 @enderror">
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </form>
                </div>

                {{-- Change password --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-6 card-shadow">
                    <h3 class="mb-4 font-bold text-slate-900">Change Password</h3>
                    <form method="POST" action="{{ route('account.password.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="form-label" for="current_password">Current Password</label>
                            <input id="current_password" type="password" name="current_password" required
                                   class="form-input-base @error('current_password') border-red-400 @enderror">
                            @error('current_password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="form-label" for="password">New Password</label>
                                <input id="password" type="password" name="password" required
                                       class="form-input-base @error('password') border-red-400 @enderror">
                                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required class="form-input-base">
                            </div>
                        </div>
                        <button type="submit" class="btn-primary">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
