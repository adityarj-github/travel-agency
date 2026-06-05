@extends('layouts.admin')

@section('title', 'Coupons')
@section('page_title', 'Coupons')
@section('breadcrumb', 'Manage discount & promo codes')

@section('page_actions')
    <a href="{{ route('admin.coupons.create') }}" class="btn-primary">+ Add Coupon</a>
@endsection

@section('content')
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Code</th>
                        <th class="px-5 py-3">Discount</th>
                        <th class="px-5 py-3">Usage</th>
                        <th class="px-5 py-3">Validity</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($coupons as $coupon)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <p class="font-mono font-semibold text-slate-800">{{ $coupon->code }}</p>
                                <p class="text-xs text-slate-400">{{ $coupon->description ?: '—' }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-700">
                                @if ($coupon->type === 'percent')
                                    {{ rtrim(rtrim(number_format($coupon->value, 2), '0'), '.') }}%
                                    @if ($coupon->max_discount)<span class="text-xs text-slate-400">(max {{ setting('currency_symbol', '$') }}{{ number_format($coupon->max_discount, 0) }})</span>@endif
                                @else
                                    {{ setting('currency_symbol', '$') }}{{ number_format($coupon->value, 2) }}
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}
                            </td>
                            <td class="px-5 py-3 text-xs text-slate-500">
                                {{ $coupon->starts_at ? $coupon->starts_at->format('M d, Y') : 'Any' }}
                                &rarr;
                                @if ($coupon->expires_at)
                                    <span class="{{ $coupon->is_expired ? 'text-red-500' : '' }}">{{ $coupon->expires_at->format('M d, Y') }}</span>
                                @else
                                    Never
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}">
                                    @csrf @method('PATCH')
                                    <button class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $coupon->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-brand-600 hover:underline">Edit</a>
                                    <x-admin.delete-form :action="route('admin.coupons.destroy', $coupon)" message="Delete this coupon?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No coupons yet. <a href="{{ route('admin.coupons.create') }}" class="text-brand-600 underline">Create one</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $coupons->links() }}</div>
@endsection
