<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReliefItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|in:food,water,medicine,shelter,clothing,hygiene,tools,other',
            'unit' => 'sometimes|string|max:50',
            'quantity' => 'sometimes|integer|min:0',
            'min_stock' => 'sometimes|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after_or_equal:today',
            'batch_number' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive,discontinued',
        ];
    }
}