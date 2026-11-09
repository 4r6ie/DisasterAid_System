<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            // Exactly 11 digits, digits only. Optional, but validated when given.
            'phone' => 'nullable|string|regex:/^[0-9]{11}$/',
            'address' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'The contact number must be exactly 11 digits (numbers only).',
        ];
    }
}