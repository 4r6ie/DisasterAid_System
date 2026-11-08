<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvacuationCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'capacity' => 'sometimes|integer|min:1',
            'current_occupancy' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:active,inactive,full,maintenance',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'managed_by' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ];
    }
}