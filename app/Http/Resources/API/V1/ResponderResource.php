<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'specialization' => $this->specialization,
            'rank' => $this->rank,
            'status' => $this->status,
            'address' => $this->address,
            'date_of_birth' => $this->date_of_birth?->toISOString(),
            'certifications' => $this->certifications,
            'equipment' => $this->equipment,
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'assigned_disasters_count' => $this->whenCounted('assignedDisasters'),
            'assigned_emergencies_count' => $this->whenCounted('assignedEmergencies'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}