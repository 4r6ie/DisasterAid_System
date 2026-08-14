<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreResponderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|string|max:50|unique:responders,employee_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:responders,email',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'required|string|in:medical,fire,rescue,police,logistics,coordination,communications',
            'rank' => 'nullable|string|max:50',
            'status' => 'sometimes|in:available,on_duty,off_duty,on_leave,inactive',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string',
            'equipment' => 'nullable|array',
            'equipment.*' => 'string',
            'user_id' => 'nullable|exists:users,id',
            'hired_at' => 'nullable|date',
        ];
    }
}