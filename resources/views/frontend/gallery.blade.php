@extends('layouts.frontend')

@section('title', 'Gallery — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="Gallery" subtitle="A glimpse of the unforgettable moments we create."
                 :breadcrumbs="['Gallery' => null]" />

    <section class="py-16" x-data="{ lightbox: null }">
        <div class="container">
            {{-- Category filter --}}
            @if ($categories->isNotEmpty())
                <div class="mb-10 flex flex-wrap justify-center gap-2">
                    <a href="{{ route('gallery') }}" class="rounded-full px-4 py-2 text-sm font-medium {{ !request('category') ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All</a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('gallery', ['category' => $cat]) }}" class="rounded-full px-4 py-2 text-sm font-medium {{ request('category') == $cat ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">{{ $cat }}</a>
                    @endforeach
                </div>
            @endif

            @if ($images->isNotEmpty())
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($images as $image)
                        <button @click="lightbox = '{{ $image->image_url }}'"
                                class="group relative aspect-square overflow-hidden rounded-xl">
                            <img src="{{ $image->image_url }}" alt="{{ $image->title }}" loading="lazy"
                                 class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 flex items-end bg-gradient-to-t from-slate-900/70 to-transparent opacity-0 transition group-hover:opacity-100">
                                <div class="p-3 text-left text-white">
                                    @if ($image->title)<p class="text-sm font-semibold">{{ $image->title }}</p>@endif
                                    @if ($image->category)<p class="text-xs text-white/70">{{ $image->category }}</p>@endif
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
                <div class="mt-10">{{ $images->links() }}</div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-200 p-16 text-center">
                    <p class="text-lg font-semibold text-slate-700">No images yet</p>
                    <p class="mt-1 text-sm text-slate-400">Photos will appear here once added from the admin panel.</p>
                </div>
            @endif

            {{-- Lightbox --}}
            <div x-show="lightbox" x-cloak @click="lightbox = null" @keydown.escape.window="lightbox = null"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/90 p-4">
                <img :src="lightbox" class="max-h-[90vh] max-w-[90vw] rounded-lg">
                <button @click="lightbox = null" class="absolute right-6 top-6 text-3xl text-white">&times;</button>
            </div>
        </div>
    </section>
@endsection
