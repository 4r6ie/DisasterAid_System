<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreEmergencyReportRequest;
use App\Http\Requests\API\V1\UpdateEmergencyReportRequest;
use App\Http\Requests\API\V1\AssignResponderRequest;
use App\Http\Requests\API\V1\UpdateEmergencyStatusRequest;
use App\Http\Resources\API\V1\EmergencyReportResource;
use App\Http\Resources\API\V1\EmergencyReportCollection;
use App\Services\EmergencyReportService;
use Illuminate\Http\JsonResponse;

class EmergencyReportController extends Controller
{
    public function __construct(
        protected EmergencyReportService $service
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['search', 'status', 'priority', 'emergency_type', 'disaster_incident_id', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);
        
        $emergencies = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new EmergencyReportCollection($emergencies),
            'meta' => [
                'current_page' => $emergencies->currentPage(),
                'last_page' => $emergencies->lastPage(),
                'per_page' => $emergencies->perPage(),
                'total' => $emergencies->total(),
            ],
        ]);
    }

    public function store(StoreEmergencyReportRequest $request): JsonResponse
    {
        $emergency = $this->service->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Emergency report created successfully',
            'data' => new EmergencyReportResource($emergency->load(['disasterIncident', 'reporter', 'assignedResponder'])),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $emergency = $this->service->getByIdOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new EmergencyReportResource($emergency->load(['disasterIncident', 'reporter', 'assignedResponder'])),
        ]);
    }

    public function update(UpdateEmergencyReportRequest $request, int $id): JsonResponse
    {
        $emergency = $this->service->getByIdOrFail($id);
        $emergency = $this->service->update($emergency, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Emergency report updated successfully',
            'data' => new EmergencyReportResource($emergency->load(['disasterIncident', 'reporter', 'assignedResponder'])),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $emergency = $this->service->getByIdOrFail($id);
        $this->service->delete($emergency);
        
        return response()->json([
            'success' => true,
            'message' => 'Emergency report deleted successfully',
        ]);
    }

    public function assignResponder(AssignResponderRequest $request, int $id): JsonResponse
    {
        $emergency = $this->service->getByIdOrFail($id);
        $emergency = $this->service->assignResponder($emergency, $request->responder_id);
        
        return response()->json([
            'success' => true,
            'message' => 'Responder assigned successfully',
            'data' => new EmergencyReportResource($emergency->load('assignedResponder')),
        ]);
    }

    public function updateStatus(UpdateEmergencyStatusRequest $request, int $id): JsonResponse
    {
        $emergency = $this->service->getByIdOrFail($id);
        $emergency = $this->service->updateStatus($emergency, $request->status);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => new EmergencyReportResource($emergency),
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

        $emergencies = $this->service->getNearby((float) $lat, (float) $lng, (float) $radius, (int) $limit);
        
        return response()->json([
            'success' => true,
            'data' => EmergencyReportResource::collection($emergencies),
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