<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $imageRule = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];

        return [
            'title' => ['required', 'string', 'max:255'],
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'category' => ['nullable', 'string', 'max:100'],
            'package_type' => ['nullable', 'string', 'max:100'],
            'tour_type' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'duration_nights' => ['nullable', 'integer', 'min:0', 'max:365'],
            'location' => ['nullable', 'string', 'max:255'],
            'max_people' => ['nullable', 'integer', 'min:1'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'terms' => ['nullable', 'string'],
            'inclusions' => ['nullable', 'string'],
            'exclusions' => ['nullable', 'string'],
            'available_dates' => ['nullable', 'string'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.day' => ['nullable', 'string', 'max:50'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.detail' => ['nullable', 'string', 'max:2000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'main_image' => $this->isMethod('post') ? array_merge(['required'], array_slice($imageRule, 1)) : $imageRule,
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
