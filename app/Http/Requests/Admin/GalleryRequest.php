<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        // On create we allow multiple images; on update a single optional replacement.
        if ($this->isMethod('post')) {
            return [
                'title' => ['nullable', 'string', 'max:255'],
                'category' => ['nullable', 'string', 'max:100'],
                'is_active' => ['nullable', 'boolean'],
                'images' => ['required', 'array', 'min:1'],
                'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ];
        }

        return [
            'title' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }
}
