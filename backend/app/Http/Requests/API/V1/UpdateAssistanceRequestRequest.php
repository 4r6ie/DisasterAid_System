<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssistanceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assistance_type' => 'sometimes|string|in:food,water,medicine,shelter,clothing,other',
            'quantity' => 'sometimes|integer|min:1',
            'unit' => 'sometimes|string|max:50',
            'urgency' => 'sometimes|in:low,medium,high,critical',
            'status' => 'sometimes|in:pending,approved,dispatched,completed,rejected,cancelled',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'disaster_incident_id' => 'nullable|exists:disaster_incidents,id',
            'metadata' => 'nullable|array',
        ];
    }
}