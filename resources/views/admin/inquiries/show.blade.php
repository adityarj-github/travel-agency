@extends('layouts.admin')

@section('title', 'Inquiry from ' . $inquiry->name)
@section('page_title', 'Inquiry Details')
@section('breadcrumb', 'Inquiries / Details')

@section('page_actions')
    <a href="{{ route('admin.inquiries.index') }}" class="btn-outline">&larr; Back to Inquiries</a>
@endsection

@section('content')
    <div class="mx-auto max-w-3xl rounded-2xl bg-white p-6 shadow-sm">
        <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h3 class="text-lg font-bold text-slate-900">{{ $inquiry->subject ?: 'No subject' }}</h3>
                <p class="text-sm text-slate-400">Received {{ $inquiry->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            @if ($inquiry->is_read)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                    <span class="h-2 w-2 rounded-full bg-slate-400"></span> Read
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                    <span class="h-2 w-2 rounded-full bg-red-500"></span> Unread
                </span>
            @endif
        </div>

        <dl class="grid gap-4 border-y border-slate-100 py-5 sm:grid-cols-2">
            <div>
                <dt class="text-xs uppercase text-slate-400">Name</dt>
                <dd class="text-slate-800">{{ $inquiry->name }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase text-slate-400">Email</dt>
                <dd><a href="mailto:{{ $inquiry->email }}" class="text-brand-600 hover:underline">{{ $inquiry->email }}</a></dd>
            </div>
            <div>
                <dt class="text-xs uppercase text-slate-400">Phone</dt>
                <dd>
                    @if ($inquiry->phone)
                        <a href="tel:{{ $inquiry->phone }}" class="text-brand-600 hover:underline">{{ $inquiry->phone }}</a>
                    @else
                        <span class="text-slate-400">—</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs uppercase text-slate-400">Subject</dt>
                <dd class="text-slate-800">{{ $inquiry->subject ?: '—' }}</dd>
            </div>
        </dl>

        <div class="py-5">
            <dt class="mb-2 text-xs uppercase text-slate-400">Message</dt>
            <p class="whitespace-pre-line text-slate-700">{{ $inquiry->message }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
            <a href="mailto:{{ $inquiry->email }}?subject={{ rawurlencode('Re: ' . ($inquiry->subject ?: 'Your inquiry')) }}" class="btn-primary">Reply via Email</a>
            <form method="POST" action="{{ route('admin.inquiries.read', $inquiry) }}" class="inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn-outline">{{ $inquiry->is_read ? 'Mark as Unread' : 'Mark as Read' }}</button>
            </form>
            <x-admin.delete-form :action="route('admin.inquiries.destroy', $inquiry)" label="Delete" message="Delete this inquiry?" class="text-red-600 hover:text-red-800 text-sm font-medium" />
        </div>
    </div>
@endsection
