<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:alert,advisory,notice,update,evacuation,weather',
            'priority' => 'required|in:low,medium,high,critical',
            'audience' => 'required|in:public,responders,staff,volunteers,affected',
            'is_published' => 'sometimes|boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'disaster_incident_id' => 'nullable|exists:disaster_incidents,id',
            'metadata' => 'nullable|array',
        ];
    }
}