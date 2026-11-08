<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'relief_item' => $this->whenLoaded('reliefItem', fn () => new ReliefItemResource($this->reliefItem)),
            'type' => $this->type,
            'type_label' => $this->type_label,
            'quantity' => $this->quantity,
            'reference_type' => $this->reference_type,
            'reference_id' => $this->reference_id,
            'reference_number' => $this->reference_number,
            'unit_cost' => $this->unit_cost ? (float) $this->unit_cost : null,
            'total_cost' => $this->total_cost ? (float) $this->total_cost : null,
            'processor' => $this->whenLoaded('processor', fn () => new UserResource($this->processor)),
            'evacuation_center' => $this->whenLoaded('evacuationCenter', fn () => new EvacuationCenterResource($this->evacuationCenter)),
            'disaster_incident' => $this->whenLoaded('disasterIncident', fn () => new DisasterIncidentResource($this->disasterIncident)),
            'notes' => $this->notes,
            'transaction_date' => $this->transaction_date?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}