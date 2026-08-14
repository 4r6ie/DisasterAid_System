<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmergencyReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'emergency_type' => 'required|string|in:medical,fire,rescue,evacuation,other',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'sometimes|in:pending,assigned,in_progress,resolved,cancelled',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string',
            'people_needing_help' => 'required|integer|min:1',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'disaster_incident_id' => 'nullable|exists:disaster_incidents,id',
            'metadata' => 'nullable|array',
        ];
    }
}