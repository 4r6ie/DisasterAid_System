<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreAffectedPersonRequest;
use App\Http\Requests\API\V1\UpdateAffectedPersonRequest;
use App\Http\Requests\API\V1\UpdateConditionRequest;
use App\Http\Requests\API\V1\AssignEvacuationCenterRequest;
use App\Http\Resources\API\V1\AffectedPersonResource;
use App\Http\Resources\API\V1\AffectedPersonCollection;
use App\Services\AffectedPersonService;
use Illuminate\Http\JsonResponse;

class AffectedPersonController extends Controller
{
    public function __construct(
        protected AffectedPersonService $service
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['search', 'disaster_incident_id', 'condition', 'evacuation_center_id', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);
        
        $persons = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new AffectedPersonCollection($persons),
            'meta' => [
                'current_page' => $persons->currentPage(),
                'last_page' => $persons->lastPage(),
                'per_page' => $persons->perPage(),
                'total' => $persons->total(),
            ],
        ]);
    }

    public function store(StoreAffectedPersonRequest $request): JsonResponse
    {
        $person = $this->service->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Affected person registered successfully',
            'data' => new AffectedPersonResource($person->load(['disasterIncident', 'evacuationCenter', 'registrar'])),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $person = $this->service->getByIdOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new AffectedPersonResource($person->load(['disasterIncident', 'evacuationCenter', 'registrar'])),
        ]);
    }

    public function update(UpdateAffectedPersonRequest $request, int $id): JsonResponse
    {
        $person = $this->service->getByIdOrFail($id);
        $person = $this->service->update($person, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Affected person updated successfully',
            'data' => new AffectedPersonResource($person->load(['disasterIncident', 'evacuationCenter', 'registrar'])),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $person = $this->service->getByIdOrFail($id);
        $this->service->delete($person);
        
        return response()->json([
            'success' => true,
            'message' => 'Affected person deleted successfully',
        ]);
    }

    public function updateCondition(UpdateConditionRequest $request, int $id): JsonResponse
    {
        $person = $this->service->getByIdOrFail($id);
        $person = $this->service->updateCondition($person, $request->condition);
        
        return response()->json([
            'success' => true,
            'message' => 'Condition updated successfully',
            'data' => new AffectedPersonResource($person),
        ]);
    }

    public function assignEvacuationCenter(AssignEvacuationCenterRequest $request, int $id): JsonResponse
    {
        $person = $this->service->getByIdOrFail($id);
        $person = $this->service->assignEvacuationCenter($person, $request->evacuation_center_id);
        
        return response()->json([
            'success' => true,
            'message' => 'Evacuation center assigned successfully',
            'data' => new AffectedPersonResource($person->load('evacuationCenter')),
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