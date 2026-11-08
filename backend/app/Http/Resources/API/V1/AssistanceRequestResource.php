<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssistanceRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'request_number' => $this->request_number,
            'assistance_type' => $this->assistance_type,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'urgency' => $this->urgency,
            'status' => $this->status,
            'description' => $this->description,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'address' => $this->address,
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'disaster_incident' => $this->whenLoaded('disasterIncident', fn () => new DisasterIncidentResource($this->disasterIncident)),
            'requester' => $this->whenLoaded('requester', fn () => new UserResource($this->requester)),
            'approver' => $this->whenLoaded('approver', fn () => new UserResource($this->approver)),
            'dispatcher' => $this->whenLoaded('dispatcher', fn () => new UserResource($this->dispatcher)),
            'approved_at' => $this->approved_at?->toISOString(),
            'dispatched_at' => $this->dispatched_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
            'rejection_reason' => $this->rejection_reason,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}