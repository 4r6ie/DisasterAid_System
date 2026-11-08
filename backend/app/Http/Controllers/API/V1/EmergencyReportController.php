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
use App\Models\EmergencyReport;

class EmergencyReportController extends Controller
{
    public function __construct(
        protected EmergencyReportService $service
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', EmergencyReport::class);
        $filters = request()->only(['search', 'status', 'priority', 'emergency_type', 'disaster_incident_id', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = $this->perPage();
        
        $emergencies = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => (new EmergencyReportCollection($emergencies))->toArray(request()),
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
        $this->authorize('create', EmergencyReport::class);
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
        $this->authorize('view', $emergency);
        
        return response()->json([
            'success' => true,
            'data' => new EmergencyReportResource($emergency->load(['disasterIncident', 'reporter', 'assignedResponder'])),
        ]);
    }

    public function update(UpdateEmergencyReportRequest $request, int $id): JsonResponse
    {
        $emergency = $this->service->getByIdOrFail($id);
        $this->authorize('update', $emergency);
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
        $this->authorize('delete', $emergency);
        $this->service->delete($emergency);
        
        return response()->json([
            'success' => true,
            'message' => 'Emergency report deleted successfully',
        ]);
    }

    public function assignResponder(AssignResponderRequest $request, int $id): JsonResponse
    {
        $emergency = $this->service->getByIdOrFail($id);
        $this->authorize('assignResponder', $emergency);
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
        $this->authorize('updateStatus', $emergency);
        $emergency = $this->service->updateStatus($emergency, $request->status);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => new EmergencyReportResource($emergency),
        ]);
    }

    public function nearby(): JsonResponse
    {
        $this->authorize('viewAny', EmergencyReport::class);
        $validated = request()->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:500',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $emergencies = $this->service->getNearby(
            (float) $validated['lat'],
            (float) $validated['lng'],
            (float) ($validated['radius'] ?? 50),
            (int) ($validated['limit'] ?? 10)
        );
        
        return response()->json([
            'success' => true,
            'data' => EmergencyReportResource::collection($emergencies),
        ]);
    }

    public function statistics(): JsonResponse
    {
        $this->authorize('viewAny', EmergencyReport::class);
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }
}