<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'travel_date' => ['nullable', 'date', 'after_or_equal:today'],
            'adults' => ['required', 'integer', 'min:1', 'max:50'],
            'children' => ['nullable', 'integer', 'min:0', 'max:50'],
            'message' => ['nullable', 'string', 'max:3000'],
            'coupon_code' => ['nullable', 'string', 'max:40'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'adults' => $this->input('adults', 1),
            'children' => $this->input('children', 0),
        ]);
    }
}
