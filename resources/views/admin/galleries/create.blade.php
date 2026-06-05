@extends('layouts.admin')

@section('title', 'Upload Images')
@section('page_title', 'Upload Gallery Images')
@section('breadcrumb', 'Galleries / Upload')

@section('content')
    <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Image Details</h3>
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="form-label">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-input-base" placeholder="Optional title">
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="{{ old('category') }}" class="form-input-base" placeholder="e.g. Beaches" list="gallery-cat-list">
                        <datalist id="gallery-cat-list"><option>Beaches</option><option>Mountains</option><option>Cities</option><option>Wildlife</option><option>Culture</option></datalist>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    Active (visible on site)
                </label>

                <div>
                    <x-admin.image-input name="images" label="Images" :multiple="true" :required="true" hint="Select one or more images." />
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="btn-primary">Upload Images</button>
            <a href="{{ route('admin.galleries.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
@endsection
