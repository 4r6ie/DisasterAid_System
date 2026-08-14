<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreAnnouncementRequest;
use App\Http\Requests\API\V1\UpdateAnnouncementRequest;
use App\Http\Resources\API\V1\AnnouncementResource;
use App\Http\Resources\API\V1\AnnouncementCollection;
use App\Services\AnnouncementService;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends Controller
{
    public function __construct(
        protected AnnouncementService $service
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['search', 'type', 'priority', 'audience', 'is_published', 'disaster_incident_id', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);
        
        $announcements = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new AnnouncementCollection($announcements),
            'meta' => [
                'current_page' => $announcements->currentPage(),
                'last_page' => $announcements->lastPage(),
                'per_page' => $announcements->perPage(),
                'total' => $announcements->total(),
            ],
        ]);
    }

    public function store(StoreAnnouncementRequest $request): JsonResponse
    {
        $announcement = $this->service->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully',
            'data' => new AnnouncementResource($announcement->load(['disasterIncident', 'creator'])),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $announcement = $this->service->getByIdOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new AnnouncementResource($announcement->load(['disasterIncident', 'creator'])),
        ]);
    }

    public function update(UpdateAnnouncementRequest $request, int $id): JsonResponse
    {
        $announcement = $this->service->getByIdOrFail($id);
        $announcement = $this->service->update($announcement, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Announcement updated successfully',
            'data' => new AnnouncementResource($announcement->load(['disasterIncident', 'creator'])),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $announcement = $this->service->getByIdOrFail($id);
        $this->service->delete($announcement);
        
        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully',
        ]);
    }

    public function publish(int $id): JsonResponse
    {
        $announcement = $this->service->getByIdOrFail($id);
        $announcement = $this->service->publish($announcement);
        
        return response()->json([
            'success' => true,
            'message' => 'Announcement published successfully',
            'data' => new AnnouncementResource($announcement),
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