<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreVolunteerRequest;
use App\Http\Requests\API\V1\UpdateVolunteerRequest;
use App\Http\Requests\API\V1\UpdateVolunteerStatusRequest;
use App\Http\Resources\API\V1\VolunteerResource;
use App\Http\Resources\API\V1\VolunteerCollection;
use App\Services\VolunteerService;
use Illuminate\Http\JsonResponse;

class VolunteerController extends Controller
{
    public function __construct(
        protected VolunteerService $service
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['search', 'status', 'skill', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);
        
        $volunteers = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new VolunteerCollection($volunteers),
            'meta' => [
                'current_page' => $volunteers->currentPage(),
                'last_page' => $volunteers->lastPage(),
                'per_page' => $volunteers->perPage(),
                'total' => $volunteers->total(),
            ],
        ]);
    }

    public function store(StoreVolunteerRequest $request): JsonResponse
    {
        $volunteer = $this->service->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Volunteer registered successfully',
            'data' => new VolunteerResource($volunteer),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $volunteer = $this->service->getByIdOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new VolunteerResource($volunteer->load('assignments')),
        ]);
    }

    public function update(UpdateVolunteerRequest $request, int $id): JsonResponse
    {
        $volunteer = $this->service->getByIdOrFail($id);
        $volunteer = $this->service->update($volunteer, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Volunteer updated successfully',
            'data' => new VolunteerResource($volunteer),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $volunteer = $this->service->getByIdOrFail($id);
        $this->service->delete($volunteer);
        
        return response()->json([
            'success' => true,
            'message' => 'Volunteer deleted successfully',
        ]);
    }

    public function updateStatus(UpdateVolunteerStatusRequest $request, int $id): JsonResponse
    {
        $volunteer = $this->service->getByIdOrFail($id);
        $volunteer = $this->service->updateStatus($volunteer, $request->status);
        
        return response()->json([
            'success' => true,
            'message' => 'Volunteer status updated successfully',
            'data' => new VolunteerResource($volunteer),
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