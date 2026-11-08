<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreAffectedPersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'disaster_incident_id' => 'required|exists:disaster_incidents,id',
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'condition' => 'sometimes|in:safe,injured,critical,missing,deceased,rescued',
            'medical_notes' => 'nullable|string',
            'family_contact_name' => 'nullable|string|max:255',
            'family_contact_phone' => 'nullable|string|max:20',
            'evacuation_center_id' => 'nullable|exists:evacuation_centers,id',
            'notes' => 'nullable|string',
        ];
    }
}