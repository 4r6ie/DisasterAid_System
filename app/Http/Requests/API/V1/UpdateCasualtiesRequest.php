<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCasualtiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'affected_count' => 'sometimes|integer|min:0',
            'injured_count' => 'sometimes|integer|min:0',
            'missing_count' => 'sometimes|integer|min:0',
            'deceased_count' => 'sometimes|integer|min:0',
            'rescued_count' => 'sometimes|integer|min:0',
        ];
    }
}