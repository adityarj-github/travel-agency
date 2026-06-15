@extends('layouts.admin')

@section('title', 'Payment Links')
@section('page_title', 'Payment Links')
@section('breadcrumb', 'Generate a payment link to share with a customer')

@section('content')
@php $symbol = setting('currency_symbol', '$'); @endphp

{{-- Freshly generated link — shown once after creation, with a copy button --}}
@if (session('new_payment_link'))
    <div x-data="{ copied: false }" class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-5">
        <p class="mb-2 text-sm font-semibold text-green-800">Payment link ready — share it with your customer:</p>
        <div class="flex flex-col gap-2 sm:flex-row">
            <input type="text" readonly x-ref="newlink" value="{{ session('new_payment_link') }}"
                   @click="$refs.newlink.select()"
                   class="form-input-base flex-1 bg-white font-mono text-xs">
            <button type="button"
                    @click="navigator.clipboard.writeText($refs.newlink.value); copied = true; setTimeout(() => copied = false, 1800)"
                    class="btn-primary whitespace-nowrap">
                <span x-show="!copied">Copy Link</span>
                <span x-show="copied" x-cloak>Copied!</span>
            </button>
        </div>
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Create form --}}
    <div class="lg:col-span-1">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">New Payment Link</h3>
            <form method="POST" action="{{ route('admin.payment-links.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Customer Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input-base" placeholder="John Doe">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Customer Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input-base" placeholder="john@example.com">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input-base" placeholder="Optional">
                    @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Amount ({{ $symbol }}) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="1" required class="form-input-base" placeholder="0.00">
                    @error('amount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Description / Note</label>
                    <textarea name="description" rows="3" class="form-input-base" placeholder="What is this payment for?">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full">Generate Link</button>
            </form>
        </div>
    </div>

    {{-- Existing links --}}
    <div class="lg:col-span-2">
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3">Payment</th>
                            <th class="px-5 py-3">Link</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($links as $link)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-slate-800">{{ $link->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $link->email }}</p>
                                    <p class="font-mono text-[11px] text-slate-400">{{ $link->reference }}</p>
                                </td>
                                <td class="px-5 py-3 font-semibold text-slate-700">{{ $symbol }}{{ number_format($link->total, 2) }}</td>
                                <td class="px-5 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $link->payment_badge }}">
                                        {{ ucfirst($link->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div x-data="{ copied: false }" class="flex items-center gap-2">
                                        <input type="text" readonly value="{{ $link->pay_url }}" x-ref="url{{ $link->id }}"
                                               @click="$refs['url{{ $link->id }}'].select()"
                                               class="w-40 rounded-md border-slate-200 bg-slate-50 px-2 py-1 font-mono text-[11px] text-slate-500">
                                        <button type="button"
                                                @click="navigator.clipboard.writeText($refs['url{{ $link->id }}'].value); copied = true; setTimeout(() => copied = false, 1500)"
                                                class="whitespace-nowrap text-xs font-semibold text-brand-600 hover:underline">
                                            <span x-show="!copied">Copy</span>
                                            <span x-show="copied" x-cloak>✓</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.bookings.show', $link) }}" class="text-brand-600 hover:underline">View</a>
                                        <x-admin.delete-form :action="route('admin.payment-links.destroy', $link)" message="Delete this payment link?" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No payment links yet. Create one using the form.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5">{{ $links->links() }}</div>
    </div>
</div>
@endsection
