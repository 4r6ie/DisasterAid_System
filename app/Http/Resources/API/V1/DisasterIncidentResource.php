<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisasterIncidentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'incident_id' => $this->incident_id,
            'disaster_type' => $this->disaster_type,
            'title' => $this->title,
            'description' => $this->description,
            'severity' => $this->severity,
            'status' => $this->status,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'affected_count' => $this->affected_count,
            'injured_count' => $this->injured_count,
            'missing_count' => $this->missing_count,
            'deceased_count' => $this->deceased_count,
            'rescued_count' => $this->rescued_count,
            'total_casualties' => $this->total_casualties,
            'occurred_at' => $this->occurred_at?->toISOString(),
            'resolved_at' => $this->resolved_at?->toISOString(),
            'reporter' => $this->whenLoaded('reporter', fn () => new UserResource($this->reporter)),
            'assigned_responder' => $this->whenLoaded('assignedResponder', fn () => new ResponderResource($this->assignedResponder)),
            'emergencies_count' => $this->whenCounted('emergencies'),
            'assistance_requests_count' => $this->whenCounted('assistanceRequests'),
            'affected_persons_count' => $this->whenCounted('affectedPersons'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}