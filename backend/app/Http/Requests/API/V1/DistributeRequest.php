<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class DistributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1',
            'reference_id' => 'nullable|integer',
            'reference_number' => 'nullable|string|max:100',
            'evacuation_center_id' => 'nullable|exists:evacuation_centers,id',
            'disaster_incident_id' => 'nullable|exists:disaster_incidents,id',
            'unit_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'transaction_date' => 'nullable|date',
        ];
    }
}