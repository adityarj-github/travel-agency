@extends('layouts.frontend')

@section('title', 'Destinations — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="Destinations" subtitle="Explore breathtaking places waiting to be discovered."
                 :breadcrumbs="['Destinations' => null]" />

    <section class="py-16">
        <div class="container">
            @if ($destinations->isNotEmpty())
                <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($destinations as $destination)
                        <x-destination-card :destination="$destination" />
                    @endforeach
                </div>
                <div class="mt-10">{{ $destinations->links() }}</div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-200 p-16 text-center">
                    <p class="text-lg font-semibold text-slate-700">No destinations yet</p>
                    <p class="mt-1 text-sm text-slate-400">Destinations will appear here once added from the admin panel.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
