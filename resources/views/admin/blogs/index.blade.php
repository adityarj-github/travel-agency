@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('page_title', 'Blog Posts')
@section('breadcrumb', 'Manage your blog posts')

@section('page_actions')
    <a href="{{ route('admin.blogs.create') }}" class="btn-primary">+ Add Post</a>
@endsection

@section('content')
    {{-- Filters --}}
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl bg-white p-4 shadow-sm sm:grid-cols-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-input-base" placeholder="Search by title...">
        <select name="category" class="form-input-base">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-input-base">
            <option value="">All statuses</option>
            <option value="published" @selected(request('status') == 'published')>Published</option>
            <option value="draft" @selected(request('status') == 'draft')>Draft</option>
        </select>
        <div class="flex gap-2">
            <button class="btn-primary flex-1 !py-2">Filter</button>
            <a href="{{ route('admin.blogs.index') }}" class="btn-outline !py-2">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Post</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Author</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($blogs as $blog)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $blog->featured_image_url }}" loading="lazy" decoding="async" width="64" height="48" class="h-12 w-16 rounded-lg object-cover" alt="">
                                    <p class="font-medium text-slate-800">{{ $blog->title }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ optional($blog->category)->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $blog->author ?? '—' }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.blogs.toggle', $blog) }}">
                                    @csrf @method('PATCH')
                                    <button class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $blog->is_published ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $blog->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ optional($blog->published_at ?? $blog->created_at)->format('M d, Y') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="text-brand-600 hover:underline">Edit</a>
                                    <x-admin.delete-form :action="route('admin.blogs.destroy', $blog)" message="Delete this post?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No posts found. <a href="{{ route('admin.blogs.create') }}" class="text-brand-600 underline">Add one</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $blogs->links() }}</div>
@endsection
