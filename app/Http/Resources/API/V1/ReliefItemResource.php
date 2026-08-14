<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReliefItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'min_stock' => $this->min_stock,
            'unit_cost' => $this->unit_cost ? (float) $this->unit_cost : null,
            'location' => $this->location,
            'expiry_date' => $this->expiry_date?->toISOString(),
            'batch_number' => $this->batch_number,
            'description' => $this->description,
            'status' => $this->status,
            'is_low_stock' => $this->is_low_stock,
            'is_expiring_soon' => $this->is_expiring_soon,
            'total_value' => $this->total_value,
            'transactions_count' => $this->whenCounted('transactions'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}