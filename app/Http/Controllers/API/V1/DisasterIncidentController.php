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

class DisasterIncidentController extends Controller
{
    public function __construct(
        protected DisasterIncidentService $service
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['search', 'status', 'severity', 'disaster_type', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);
        
        $incidents = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new DisasterIncidentCollection($incidents),
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
        
        return response()->json([
            'success' => true,
            'data' => new DisasterIncidentResource($incident->load(['reporter', 'assignedResponder', 'emergencies', 'assistanceRequests', 'affectedPersons', 'announcements'])),
        ]);
    }

    public function update(UpdateDisasterIncidentRequest $request, int $id): JsonResponse
    {
        $incident = $this->service->getByIdOrFail($id);
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
        $this->service->delete($incident);
        
        return response()->json([
            'success' => true,
            'message' => 'Disaster incident deleted successfully',
        ]);
    }

    public function assignResponder(AssignResponderRequest $request, int $id): JsonResponse
    {
        $incident = $this->service->getByIdOrFail($id);
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
        $incident = $this->service->updateCasualties($incident, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Casualties updated successfully',
            'data' => new DisasterIncidentResource($incident),
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

        $incidents = $this->service->getNearby((float) $lat, (float) $lng, (float) $radius, (int) $limit);
        
        return response()->json([
            'success' => true,
            'data' => DisasterIncidentResource::collection($incidents),
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