@extends('layouts.frontend')

@section('title', 'My Wishlist — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="My Wishlist" :breadcrumbs="['Account' => route('account.dashboard'), 'Wishlist' => null]" />

    <section class="py-12">
        <div class="container grid gap-8 lg:grid-cols-4">
            <div class="lg:col-span-1">@include('frontend.account._nav')</div>

            <div class="lg:col-span-3">
                @if ($packages->isEmpty())
                    <div class="rounded-2xl border border-slate-100 bg-white p-10 text-center card-shadow">
                        <p class="text-slate-500">Your wishlist is empty.</p>
                        <a href="{{ route('packages.index') }}" class="btn-primary mt-4">Browse Packages</a>
                    </div>
                @else
                    <div class="grid gap-7 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($packages as $package)
                            <x-package-card :package="$package" />
                        @endforeach
                    </div>
                    <div class="mt-8">{{ $packages->links() }}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
