<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardMapResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $this->resource;
    }
}