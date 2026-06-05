@extends('layouts.frontend')

@section('title', $destination->name . ' — ' . setting('site_name', config('app.name')))
@section('meta_description', $destination->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($destination->description), 150))

@section('content')
    <x-page-hero :title="$destination->name" :image="$destination->image_url"
                 :subtitle="$destination->country"
                 :breadcrumbs="['Destinations' => route('destinations.index'), $destination->name => null]" />

    <section class="py-16">
        <div class="container">
            @if ($destination->description)
                <div class="mx-auto mb-12 max-w-3xl text-center">
                    <h2 class="section-title mb-4">About {{ $destination->name }}</h2>
                    <div class="prose-content text-left sm:text-center">{!! nl2br(e($destination->description)) !!}</div>
                </div>
            @endif

            <x-section-heading title="Tours in {{ $destination->name }}" :center="false" />
            @if ($packages->isNotEmpty())
                <div class="mt-8 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($packages as $package)
                        <x-package-card :package="$package" />
                    @endforeach
                </div>
                <div class="mt-10">{{ $packages->links() }}</div>
            @else
                <p class="mt-8 text-slate-400">No packages available for this destination yet. <a href="{{ route('booking.create') }}" class="text-brand-600 underline">Request a custom tour</a>.</p>
            @endif
        </div>
    </section>
@endsection
