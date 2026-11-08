<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AffectedPersonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'disaster_incident' => $this->whenLoaded('disasterIncident', fn () => new DisasterIncidentResource($this->disasterIncident)),
            'name' => $this->name,
            'age' => $this->age,
            'gender' => $this->gender,
            'contact_phone' => $this->contact_phone,
            'address' => $this->address,
            'condition' => $this->condition,
            'condition_label' => $this->condition_label,
            'medical_notes' => $this->medical_notes,
            'family_contact_name' => $this->family_contact_name,
            'family_contact_phone' => $this->family_contact_phone,
            'evacuation_center' => $this->whenLoaded('evacuationCenter', fn () => new EvacuationCenterResource($this->evacuationCenter)),
            'evacuated_at' => $this->evacuated_at?->toISOString(),
            'registrar' => $this->whenLoaded('registrar', fn () => new UserResource($this->registrar)),
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}