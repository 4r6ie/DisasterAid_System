<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisasterIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'disaster_type' => 'required|string|in:earthquake,flood,typhoon,fire,landslide,volcanic,tsunami,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'required|in:low,moderate,high,critical',
            'status' => 'sometimes|in:reported,verified,active,responding,contained,resolved',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'occurred_at' => 'nullable|date',
            'metadata' => 'nullable|array',
        ];
    }
}