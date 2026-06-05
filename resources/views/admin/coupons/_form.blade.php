@php $symbol = setting('currency_symbol', '$'); @endphp

<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="form-label">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                           class="form-input-base uppercase @error('code') border-red-400 @enderror" placeholder="SUMMER25">
                    @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <input type="text" name="description" value="{{ old('description', $coupon->description) }}"
                           class="form-input-base" placeholder="Summer sale 25% off">
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="form-label">Type <span class="text-red-500">*</span></label>
                    <select name="type" class="form-input-base">
                        <option value="percent" @selected(old('type', $coupon->type) === 'percent')>Percentage (%)</option>
                        <option value="fixed" @selected(old('type', $coupon->type) === 'fixed')>Fixed amount ({{ $symbol }})</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Value <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $coupon->value) }}" required
                           class="form-input-base @error('value') border-red-400 @enderror" placeholder="25">
                    @error('value')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="form-label">Minimum Spend ({{ $symbol }})</label>
                    <input type="number" step="0.01" min="0" name="min_amount" value="{{ old('min_amount', $coupon->min_amount) }}"
                           class="form-input-base" placeholder="Optional">
                </div>
                <div>
                    <label class="form-label">Max Discount ({{ $symbol }}) <span class="text-slate-400">— percent only</span></label>
                    <input type="number" step="0.01" min="0" name="max_discount" value="{{ old('max_discount', $coupon->max_discount) }}"
                           class="form-input-base" placeholder="Optional cap">
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div>
                <label class="form-label">Usage Limit</label>
                <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}"
                       class="form-input-base" placeholder="Unlimited">
                <p class="mt-1 text-xs text-slate-400">Leave blank for unlimited redemptions.</p>
            </div>

            <div class="mt-4">
                <label class="form-label">Starts On</label>
                <input type="date" name="starts_at" value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d')) }}" class="form-input-base">
            </div>
            <div class="mt-4">
                <label class="form-label">Expires On</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d')) }}"
                       class="form-input-base @error('expires_at') border-red-400 @enderror">
                @error('expires_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <label class="mt-4 flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active))
                       class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Active
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1">{{ $submitLabel }}</button>
            <a href="{{ route('admin.coupons.index') }}" class="btn-outline">Cancel</a>
        </div>
    </div>
</div>
