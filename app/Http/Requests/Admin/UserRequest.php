<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('manage_users');
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $creating = $userId === null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(User::STAFF_ROLES)],
            'password' => [$creating ? 'required' : 'nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];
    }
}
