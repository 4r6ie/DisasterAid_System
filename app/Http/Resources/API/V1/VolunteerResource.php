<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'date_of_birth' => $this->date_of_birth?->toISOString(),
            'gender' => $this->gender,
            'skills' => $this->skills,
            'availability' => $this->availability,
            'status' => $this->status,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'notes' => $this->notes,
            'joined_at' => $this->joined_at?->toISOString(),
            'assignments_count' => $this->whenCounted('assignments'),
            'active_assignments_count' => $this->whenCounted('activeAssignments'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}