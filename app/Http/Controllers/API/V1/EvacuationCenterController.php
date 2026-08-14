<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreEvacuationCenterRequest;
use App\Http\Requests\API\V1\UpdateEvacuationCenterRequest;
use App\Http\Requests\API\V1\UpdateOccupancyRequest;
use App\Http\Resources\API\V1\EvacuationCenterResource;
use App\Http\Resources\API\V1\EvacuationCenterCollection;
use App\Services\EvacuationCenterService;
use Illuminate\Http\JsonResponse;

class EvacuationCenterController extends Controller
{
    public function __construct(
        protected EvacuationCenterService $service
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['search', 'status', 'has_availability', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);
        
        $centers = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new EvacuationCenterCollection($centers),
            'meta' => [
                'current_page' => $centers->currentPage(),
                'last_page' => $centers->lastPage(),
                'per_page' => $centers->perPage(),
                'total' => $centers->total(),
            ],
        ]);
    }

    public function store(StoreEvacuationCenterRequest $request): JsonResponse
    {
        $center = $this->service->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Evacuation center created successfully',
            'data' => new EvacuationCenterResource($center->load(['manager', 'affectedPersons'])),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $center = $this->service->getByIdOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new EvacuationCenterResource($center->load(['manager', 'affectedPersons', 'inventoryTransactions'])),
        ]);
    }

    public function update(UpdateEvacuationCenterRequest $request, int $id): JsonResponse
    {
        $center = $this->service->getByIdOrFail($id);
        $center = $this->service->update($center, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Evacuation center updated successfully',
            'data' => new EvacuationCenterResource($center->load(['manager', 'affectedPersons'])),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $center = $this->service->getByIdOrFail($id);
        $this->service->delete($center);
        
        return response()->json([
            'success' => true,
            'message' => 'Evacuation center deleted successfully',
        ]);
    }

    public function updateOccupancy(UpdateOccupancyRequest $request, int $id): JsonResponse
    {
        $center = $this->service->getByIdOrFail($id);
        $center = $this->service->updateOccupancy($center, $request->occupancy);
        
        return response()->json([
            'success' => true,
            'message' => 'Occupancy updated successfully',
            'data' => new EvacuationCenterResource($center),
        ]);
    }

    public function nearby(): JsonResponse
    {
        $lat = request()->get('lat');
        $lng = request()->get('lng');
        $radius = request()->get('radius', 50);
        $limit = request()->get('limit', 10);

        if (!$lat || !$lng) {
            return response()->json([
                'success' => false,
                'message' => 'Latitude and longitude are required',
            ], 400);
        }

        $centers = $this->service->getNearby((float) $lat, (float) $lng, (float) $radius, (int) $limit);
        
        return response()->json([
            'success' => true,
            'data' => EvacuationCenterResource::collection($centers),
        ]);
    }

    public function statistics(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }
}