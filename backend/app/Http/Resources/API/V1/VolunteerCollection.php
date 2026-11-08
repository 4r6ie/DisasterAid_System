<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VolunteerCollection extends ResourceCollection
{
    /**
     * Return the wrapped list of resources. The controller is responsible for
     * the response envelope, so the collection itself must not add a "data"
     * wrapper of its own.
     */
    public function toArray(Request $request): array
    {
        return $this->collection
            ->map(fn ($item) => (new VolunteerResource($item))->resolve($request))
            ->values()
            ->all();
    }
}
