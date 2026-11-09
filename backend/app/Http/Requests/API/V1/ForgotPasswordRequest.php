<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Deliberately no `exists` rule: the endpoint must answer the
            // same way whether or not the address is registered, otherwise
            // it becomes a user-enumeration oracle.
            'email' => 'required|email',
        ];
    }
}