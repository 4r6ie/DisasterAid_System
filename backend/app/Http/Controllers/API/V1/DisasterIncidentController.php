<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreDisasterIncidentRequest;
use App\Http\Requests\API\V1\UpdateDisasterIncidentRequest;
use App\Http\Requests\API\V1\AssignResponderRequest;
use App\Http\Requests\API\V1\UpdateDisasterStatusRequest;
use App\Http\Requests\API\V1\UpdateCasualtiesRequest;
use App\Http\Resources\API\V1\DisasterIncidentResource;
use App\Http\Resources\API\V1\DisasterIncidentCollection;
use App\Services\DisasterIncidentService;
use Illuminate\Http\JsonResponse;
use App\Models\DisasterIncident;

class DisasterIncidentController extends Controller
{
    public function __construct(
        protected DisasterIncidentService $service
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', DisasterIncident::class);
        $filters = request()->only(['search', 'status', 'severity', 'disaster_type', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = $this->perPage();
        
        $incidents = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => (new DisasterIncidentCollection($incidents))->toArray(request()),
            'meta' => [
                'current_page' => $incidents->currentPage(),
                'last_page' => $incidents->lastPage(),
                'per_page' => $incidents->perPage(),
                'total' => $incidents->total(),
            ],
        ]);
    }

    public function store(StoreDisasterIncidentRequest $request): JsonResponse
    {
        $this->authorize('create', DisasterIncident::class);
        $incident = $this->service->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Disaster incident created successfully',
            'data' => new DisasterIncidentResource($incident->load(['reporter', 'assignedResponder'])),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $incident = $this->service->getByIdOrFail($id);
        $this->authorize('view', $incident);
        
        return response()->json([
            'success' => true,
            'data' => new DisasterIncidentResource($incident->load(['reporter', 'assignedResponder', 'emergencies', 'assistanceRequests', 'affectedPersons', 'announcements'])),
        ]);
    }

    public function update(UpdateDisasterIncidentRequest $request, int $id): JsonResponse
    {
        $incident = $this->service->getByIdOrFail($id);
        $this->authorize('update', $incident);
        $incident = $this->service->update($incident, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Disaster incident updated successfully',
            'data' => new DisasterIncidentResource($incident->load(['reporter', 'assignedResponder'])),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $incident = $this->service->getByIdOrFail($id);
        $this->authorize('delete', $incident);
        $this->service->delete($incident);
        
        return response()->json([
            'success' => true,
            'message' => 'Disaster incident deleted successfully',
        ]);
    }

    public function assignResponder(AssignResponderRequest $request, int $id): JsonResponse
    {
        $incident = $this->service->getByIdOrFail($id);
        $this->authorize('assignResponder', $incident);
        $incident = $this->service->assignResponder($incident, $request->responder_id);
        
        return response()->json([
            'success' => true,
            'message' => 'Responder assigned successfully',
            'data' => new DisasterIncidentResource($incident->load('assignedResponder')),
        ]);
    }

    public function updateStatus(UpdateDisasterStatusRequest $request, int $id): JsonResponse
    {
        $incident = $this->service->getByIdOrFail($id);
        $this->authorize('updateStatus', $incident);
        $incident = $this->service->updateStatus($incident, $request->status);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => new DisasterIncidentResource($incident),
        ]);
    }

    public function updateCasualties(UpdateCasualtiesRequest $request, int $id): JsonResponse
    {
        $incident = $this->service->getByIdOrFail($id);
        $this->authorize('updateCasualties', $incident);
        $incident = $this->service->updateCasualties($incident, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Casualties updated successfully',
            'data' => new DisasterIncidentResource($incident),
        ]);
    }

    public function nearby(): JsonResponse
    {
        $this->authorize('viewAny', DisasterIncident::class);
        $validated = request()->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:500',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $incidents = $this->service->getNearby(
            (float) $validated['lat'],
            (float) $validated['lng'],
            (float) ($validated['radius'] ?? 50),
            (int) ($validated['limit'] ?? 10)
        );
        
        return response()->json([
            'success' => true,
            'data' => DisasterIncidentResource::collection($incidents),
        ]);
    }

    public function statistics(): JsonResponse
    {
        $this->authorize('viewAny', DisasterIncident::class);
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }
}