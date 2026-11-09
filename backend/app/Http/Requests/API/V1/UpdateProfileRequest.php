<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
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