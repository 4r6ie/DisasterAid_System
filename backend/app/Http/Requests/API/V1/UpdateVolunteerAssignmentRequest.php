<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVolunteerAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'volunteer_id' => 'sometimes|exists:volunteers,id',
            'disaster_incident_id' => 'sometimes|exists:disaster_incidents,id',
            'role' => 'sometimes|string|in:team_leader,medical,logistics,rescue,communication,general',
            'status' => 'sometimes|in:assigned,active,completed,cancelled',
            'notes' => 'nullable|string',
        ];
    }
}