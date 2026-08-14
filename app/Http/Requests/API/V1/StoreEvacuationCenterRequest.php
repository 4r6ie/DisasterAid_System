<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvacuationCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'capacity' => 'required|integer|min:1',
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