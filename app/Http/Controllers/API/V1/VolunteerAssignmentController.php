<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreVolunteerAssignmentRequest;
use App\Http\Requests\API\V1\UpdateVolunteerAssignmentRequest;
use App\Http\Resources\API\V1\VolunteerAssignmentResource;
use App\Http\Resources\API\V1\VolunteerAssignmentCollection;
use App\Services\VolunteerAssignmentService;
use Illuminate\Http\JsonResponse;

class VolunteerAssignmentController extends Controller
{
    public function __construct(
        protected VolunteerAssignmentService $service
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['volunteer_id', 'disaster_incident_id', 'status', 'role', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);
        
        $assignments = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new VolunteerAssignmentCollection($assignments),
            'meta' => [
                'current_page' => $assignments->currentPage(),
                'last_page' => $assignments->lastPage(),
                'per_page' => $assignments->perPage(),
                'total' => $assignments->total(),
            ],
        ]);
    }

    public function store(StoreVolunteerAssignmentRequest $request): JsonResponse
    {
        $assignment = $this->service->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Volunteer assigned successfully',
            'data' => new VolunteerAssignmentResource($assignment->load(['volunteer', 'disasterIncident', 'assigner'])),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $assignment = $this->service->getByIdOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new VolunteerAssignmentResource($assignment->load(['volunteer', 'disasterIncident', 'assigner'])),
        ]);
    }

    public function update(UpdateVolunteerAssignmentRequest $request, int $id): JsonResponse
    {
        $assignment = $this->service->getByIdOrFail($id);
        $assignment = $this->service->update($assignment, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Assignment updated successfully',
            'data' => new VolunteerAssignmentResource($assignment->load(['volunteer', 'disasterIncident', 'assigner'])),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $assignment = $this->service->getByIdOrFail($id);
        $this->service->delete($assignment);
        
        return response()->json([
            'success' => true,
            'message' => 'Assignment deleted successfully',
        ]);
    }
}