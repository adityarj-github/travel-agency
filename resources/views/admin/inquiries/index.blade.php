@extends('layouts.admin')

@section('title', 'Inquiries')
@section('page_title', 'Contact Inquiries')
@section('breadcrumb', 'Manage contact messages')

@section('content')
    {{-- Filters --}}
    <form method="GET" class="mb-5 grid gap-3 rounded-2xl bg-white p-4 shadow-sm sm:grid-cols-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-input-base" placeholder="Search name, email, subject...">
        <select name="status" class="form-input-base">
            <option value="">All</option>
            <option value="unread" @selected(request('status') == 'unread')>Unread</option>
            <option value="read" @selected(request('status') == 'read')>Read</option>
        </select>
        <div class="flex gap-2 sm:col-span-2">
            <button class="btn-primary !py-2">Filter</button>
            <a href="{{ route('admin.inquiries.index') }}" class="btn-outline !py-2">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Subject</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($inquiries as $inquiry)
                        <tr class="hover:bg-slate-50 {{ $inquiry->is_read ? '' : 'bg-brand-50 font-semibold' }}">
                            <td class="px-5 py-3">
                                <p class="text-slate-800">{{ $inquiry->name }}</p>
                                <p class="text-xs font-normal text-slate-400">{{ $inquiry->email }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $inquiry->subject ?: '—' }}</td>
                            <td class="px-5 py-3 font-normal text-slate-600">{{ $inquiry->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3">
                                @if ($inquiry->is_read)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span> Read
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        <span class="h-2 w-2 rounded-full bg-red-500"></span> Unread
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3 font-normal">
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="text-brand-600 hover:underline">View</a>
                                    <form method="POST" action="{{ route('admin.inquiries.read', $inquiry) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-slate-500 hover:text-slate-700">{{ $inquiry->is_read ? 'Mark unread' : 'Mark read' }}</button>
                                    </form>
                                    <x-admin.delete-form :action="route('admin.inquiries.destroy', $inquiry)" message="Delete this inquiry?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No inquiries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $inquiries->links() }}</div>
@endsection
