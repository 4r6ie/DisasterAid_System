<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'priority' => $this->priority,
            'audience' => $this->audience,
            'is_published' => $this->is_published,
            'is_active' => $this->is_active,
            'published_at' => $this->published_at?->toISOString(),
            'expires_at' => $this->expires_at?->toISOString(),
            'disaster_incident' => $this->whenLoaded('disasterIncident', fn () => new DisasterIncidentResource($this->disasterIncident)),
            'creator' => $this->whenLoaded('creator', fn () => new UserResource($this->creator)),
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}