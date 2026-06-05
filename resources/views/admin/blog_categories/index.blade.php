@extends('layouts.admin')

@section('title', 'Blog Categories')
@section('page_title', 'Blog Categories')
@section('breadcrumb', 'Manage your blog categories')

@section('page_actions')
    <a href="{{ route('admin.blog-categories.create') }}" class="btn-primary">+ Add Category</a>
@endsection

@section('content')
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Posts</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $category->name }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $category->blogs_count }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.blog-categories.edit', $category) }}" class="text-brand-600 hover:underline">Edit</a>
                                    <x-admin.delete-form :action="route('admin.blog-categories.destroy', $category)" message="Delete this category?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-12 text-center text-slate-400">No categories found. <a href="{{ route('admin.blog-categories.create') }}" class="text-brand-600 underline">Add one</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $categories->links() }}</div>
@endsection
