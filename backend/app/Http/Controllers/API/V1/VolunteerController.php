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
use App\Models\Volunteer;

class VolunteerController extends Controller
{
    public function __construct(
        protected VolunteerService $service
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Volunteer::class);
        $filters = request()->only(['search', 'status', 'skill', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = $this->perPage();
        
        $volunteers = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => (new VolunteerCollection($volunteers))->toArray(request()),
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
        $this->authorize('create', Volunteer::class);
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
        $this->authorize('view', $volunteer);
        
        return response()->json([
            'success' => true,
            'data' => new VolunteerResource($volunteer->load('assignments')),
        ]);
    }

    public function update(UpdateVolunteerRequest $request, int $id): JsonResponse
    {
        $volunteer = $this->service->getByIdOrFail($id);
        $this->authorize('update', $volunteer);
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
        $this->authorize('delete', $volunteer);
        $this->service->delete($volunteer);
        
        return response()->json([
            'success' => true,
            'message' => 'Volunteer deleted successfully',
        ]);
    }

    public function updateStatus(UpdateVolunteerStatusRequest $request, int $id): JsonResponse
    {
        $volunteer = $this->service->getByIdOrFail($id);
        $this->authorize('updateStatus', $volunteer);
        $volunteer = $this->service->updateStatus($volunteer, $request->status);
        
        return response()->json([
            'success' => true,
            'message' => 'Volunteer status updated successfully',
            'data' => new VolunteerResource($volunteer),
        ]);
    }

    public function statistics(): JsonResponse
    {
        $this->authorize('viewAny', Volunteer::class);
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }
}