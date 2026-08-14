<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvacuationCenterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'capacity' => $this->capacity,
            'current_occupancy' => $this->current_occupancy,
            'occupancy_percentage' => $this->occupancy_percentage,
            'available_capacity' => $this->available_capacity,
            'status' => $this->status,
            'facilities' => $this->facilities,
            'contact_person' => $this->contact_person,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'manager' => $this->whenLoaded('manager', fn () => new UserResource($this->manager)),
            'affected_persons_count' => $this->whenCounted('affectedPersons'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}