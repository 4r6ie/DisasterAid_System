<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResponderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'sometimes|string|max:50|unique:responders,employee_id,' . $this->route('responder'),
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:responders,email,' . $this->route('responder'),
            'phone' => 'nullable|string|max:20',
            'specialization' => 'sometimes|string|in:medical,fire,rescue,police,logistics,coordination,communications',
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