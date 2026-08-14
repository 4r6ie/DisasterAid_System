<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmergencyReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'emergency_type' => $this->emergency_type,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'address' => $this->address,
            'people_needing_help' => $this->people_needing_help,
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'disaster_incident' => $this->whenLoaded('disasterIncident', fn () => new DisasterIncidentResource($this->disasterIncident)),
            'reporter' => $this->whenLoaded('reporter', fn () => new UserResource($this->reporter)),
            'assigned_responder' => $this->whenLoaded('assignedResponder', fn () => new ResponderResource($this->assignedResponder)),
            'assigned_at' => $this->assigned_at?->toISOString(),
            'resolved_at' => $this->resolved_at?->toISOString(),
            'resolution_notes' => $this->resolution_notes,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}