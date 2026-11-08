<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'type' => 'sometimes|in:alert,advisory,notice,update,evacuation,weather',
            'priority' => 'sometimes|in:low,medium,high,critical',
            'audience' => 'sometimes|in:public,responders,staff,volunteers,affected',
            'is_published' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'disaster_incident_id' => 'nullable|exists:disaster_incidents,id',
            'metadata' => 'nullable|array',
        ];
    }
}