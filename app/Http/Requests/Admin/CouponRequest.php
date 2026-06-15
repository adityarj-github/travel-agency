<?php

namespace App\Http\Requests\Admin;

use App\Models\Coupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage_coupons');
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon')?->id;

        return [
            'code' => ['required', 'string', 'max:40', 'alpha_dash', Rule::unique('coupons', 'code')->ignore($couponId)],
            'description' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(Coupon::TYPES)],
            'value' => ['required', 'numeric', 'min:0', $this->input('type') === 'percent' ? 'max:100' : 'max:9999999'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->input('code'))),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
