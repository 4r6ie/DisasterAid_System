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
use App\Models\EvacuationCenter;

class EvacuationCenterController extends Controller
{
    public function __construct(
        protected EvacuationCenterService $service
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', EvacuationCenter::class);
        $filters = request()->only(['search', 'status', 'has_availability', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = $this->perPage();
        
        $centers = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => (new EvacuationCenterCollection($centers))->toArray(request()),
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
        $this->authorize('create', EvacuationCenter::class);
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
        $this->authorize('view', $center);
        
        return response()->json([
            'success' => true,
            'data' => new EvacuationCenterResource($center->load(['manager', 'affectedPersons', 'inventoryTransactions'])),
        ]);
    }

    public function update(UpdateEvacuationCenterRequest $request, int $id): JsonResponse
    {
        $center = $this->service->getByIdOrFail($id);
        $this->authorize('update', $center);
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
        $this->authorize('delete', $center);
        $this->service->delete($center);
        
        return response()->json([
            'success' => true,
            'message' => 'Evacuation center deleted successfully',
        ]);
    }

    public function updateOccupancy(UpdateOccupancyRequest $request, int $id): JsonResponse
    {
        $center = $this->service->getByIdOrFail($id);
        $this->authorize('updateOccupancy', $center);
        $center = $this->service->updateOccupancy($center, $request->occupancy);
        
        return response()->json([
            'success' => true,
            'message' => 'Occupancy updated successfully',
            'data' => new EvacuationCenterResource($center),
        ]);
    }

    public function nearby(): JsonResponse
    {
        $this->authorize('viewAny', EvacuationCenter::class);
        $validated = request()->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:500',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $centers = $this->service->getNearby(
            (float) $validated['lat'],
            (float) $validated['lng'],
            (float) ($validated['radius'] ?? 50),
            (int) ($validated['limit'] ?? 10)
        );
        
        return response()->json([
            'success' => true,
            'data' => EvacuationCenterResource::collection($centers),
        ]);
    }

    public function statistics(): JsonResponse
    {
        $this->authorize('viewAny', EvacuationCenter::class);
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }
}