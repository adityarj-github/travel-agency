@extends('layouts.frontend')

@section('title', 'Travel Packages — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="Travel Packages" subtitle="Browse our curated collection of unforgettable tours."
                 :breadcrumbs="['Packages' => null]" />

    <section class="py-16">
        <div class="container grid gap-8 lg:grid-cols-4">
            {{-- Filters sidebar --}}
            <aside class="lg:col-span-1">
                <form method="GET" action="{{ route('packages.index') }}" class="sticky top-24 space-y-5 rounded-2xl border border-slate-100 bg-white p-6 card-shadow">
                    <h3 class="text-lg font-bold text-slate-900">Filter Tours</h3>

                    <div>
                        <label class="form-label">Search</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-input-base" placeholder="Keyword...">
                    </div>

                    <div>
                        <label class="form-label">Destination</label>
                        <select name="destination" class="form-input-base">
                            <option value="">All destinations</option>
                            @foreach ($destinations as $d)
                                <option value="{{ $d->slug }}" @selected(request('destination') == $d->slug)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input-base">
                            <option value="">All categories</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c }}" @selected(request('category') == $c)>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Package Type</label>
                        <select name="type" class="form-input-base">
                            <option value="">All types</option>
                            @foreach ($types as $t)
                                <option value="{{ $t }}" @selected(request('type') == $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Duration</label>
                        <select name="duration" class="form-input-base">
                            <option value="">Any duration</option>
                            <option value="1-3" @selected(request('duration') == '1-3')>1 - 3 Days</option>
                            <option value="4-7" @selected(request('duration') == '4-7')>4 - 7 Days</option>
                            <option value="8-14" @selected(request('duration') == '8-14')>8 - 14 Days</option>
                            <option value="15+" @selected(request('duration') == '15+')>15+ Days</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Min {{ setting('currency_symbol', '$') }}</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-input-base" placeholder="0">
                        </div>
                        <div>
                            <label class="form-label">Max {{ setting('currency_symbol', '$') }}</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-input-base" placeholder="9999">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">Apply Filters</button>
                    <a href="{{ route('packages.index') }}" class="block text-center text-sm text-slate-400 hover:text-brand-600">Reset filters</a>
                </form>
            </aside>

            {{-- Results --}}
            <div class="lg:col-span-3">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">Showing {{ $packages->total() }} package(s)</p>
                    <form method="GET" action="{{ route('packages.index') }}">
                        @foreach (request()->except('sort', 'page') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <select name="sort" onchange="this.form.submit()" class="form-input-base !py-2 text-sm">
                            <option value="">Sort: Featured</option>
                            <option value="price_asc" @selected(request('sort') == 'price_asc')>Price: Low to High</option>
                            <option value="price_desc" @selected(request('sort') == 'price_desc')>Price: High to Low</option>
                            <option value="name" @selected(request('sort') == 'name')>Name: A - Z</option>
                        </select>
                    </form>
                </div>

                @if ($packages->isNotEmpty())
                    <div class="grid gap-7 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($packages as $package)
                            <x-package-card :package="$package" />
                        @endforeach
                    </div>
                    <div class="mt-10">{{ $packages->links() }}</div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-200 p-16 text-center">
                        <p class="text-lg font-semibold text-slate-700">No packages found</p>
                        <p class="mt-1 text-sm text-slate-400">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('packages.index') }}" class="btn-outline mt-5">Clear Filters</a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
