<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmergencyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'emergency_type' => 'sometimes|string|in:medical,fire,rescue,evacuation,other',
            'description' => 'sometimes|string',
            'priority' => 'sometimes|in:low,medium,high,critical',
            'status' => 'sometimes|in:pending,assigned,in_progress,resolved,cancelled',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'address' => 'nullable|string',
            'people_needing_help' => 'sometimes|integer|min:1',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'disaster_incident_id' => 'nullable|exists:disaster_incidents,id',
            'metadata' => 'nullable|array',
        ];
    }
}