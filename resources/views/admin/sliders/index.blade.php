@extends('layouts.admin')

@section('title', 'Sliders')
@section('page_title', 'Sliders')
@section('breadcrumb', 'Manage your homepage hero banners')

@section('page_actions')
    <a href="{{ route('admin.sliders.create') }}" class="btn-primary">+ Add Slider</a>
@endsection

@section('content')
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Image</th>
                        <th class="px-5 py-3">Title</th>
                        <th class="px-5 py-3">Subtitle</th>
                        <th class="px-5 py-3">Order</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($sliders as $slider)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <img src="{{ $slider->image_url }}" loading="lazy" decoding="async" width="96" height="48" class="h-12 w-24 rounded-lg object-cover" alt="">
                            </td>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $slider->title }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $slider->subtitle ?: '—' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $slider->sort_order }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.sliders.toggle', $slider) }}">
                                    @csrf @method('PATCH')
                                    <button class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $slider->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="text-brand-600 hover:underline">Edit</a>
                                    <x-admin.delete-form :action="route('admin.sliders.destroy', $slider)" message="Delete this slider?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No sliders found. <a href="{{ route('admin.sliders.create') }}" class="text-brand-600 underline">Add one</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $sliders->links() }}</div>
@endsection
