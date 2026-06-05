@extends('layouts.admin')

@section('title', 'Galleries')
@section('page_title', 'Photo Gallery')
@section('breadcrumb', 'Manage your gallery images')

@section('page_actions')
    <a href="{{ route('admin.galleries.create') }}" class="btn-primary">+ Upload Images</a>
@endsection

@section('content')
    {{-- Filters --}}
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl bg-white p-4 shadow-sm sm:grid-cols-4">
        <select name="category" class="form-input-base">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(request('category') == $category)>{{ $category }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button class="btn-primary flex-1 !py-2">Filter</button>
            <a href="{{ route('admin.galleries.index') }}" class="btn-outline !py-2">Reset</a>
        </div>
    </form>

    @if ($images->isEmpty())
        <div class="rounded-2xl bg-white p-12 text-center text-slate-400 shadow-sm">
            No images found. <a href="{{ route('admin.galleries.create') }}" class="text-brand-600 underline">Upload some</a>.
        </div>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($images as $image)
                <div class="group relative overflow-hidden rounded-2xl bg-white shadow-sm">
                    <div class="relative aspect-square">
                        <img src="{{ $image->image_url }}" alt="{{ $image->title }}" loading="lazy" decoding="async" class="h-full w-full rounded-t-2xl object-cover">

                        {{-- Toggle active --}}
                        <form method="POST" action="{{ route('admin.galleries.toggle', $image) }}" class="absolute left-2 top-2">
                            @csrf @method('PATCH')
                            <button class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $image->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $image->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>

                        @if ($image->title || $image->category)
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-3 text-white">
                                @if ($image->title)<p class="truncate text-sm font-medium">{{ $image->title }}</p>@endif
                                @if ($image->category)<p class="truncate text-xs text-white/70">{{ $image->category }}</p>@endif
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-3 px-3 py-2 text-sm">
                        <a href="{{ route('admin.galleries.edit', $image) }}" class="text-brand-600 hover:underline">Edit</a>
                        <x-admin.delete-form :action="route('admin.galleries.destroy', $image)" message="Delete this image?" />
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">{{ $images->links() }}</div>
    @endif
@endsection
