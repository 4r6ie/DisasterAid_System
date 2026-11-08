<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class StockOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1',
            'reference_type' => 'nullable|string|in:usage,transfer,expired,damaged,adjustment',
            'reference_id' => 'nullable|integer',
            'reference_number' => 'nullable|string|max:100',
            'unit_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'transaction_date' => 'nullable|date',
        ];
    }
}