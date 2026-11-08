<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreAnnouncementRequest;
use App\Http\Requests\API\V1\UpdateAnnouncementRequest;
use App\Http\Resources\API\V1\AnnouncementResource;
use App\Http\Resources\API\V1\AnnouncementCollection;
use App\Services\AnnouncementService;
use Illuminate\Http\JsonResponse;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function __construct(
        protected AnnouncementService $service
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Announcement::class);
        $filters = request()->only(['search', 'type', 'priority', 'audience', 'is_published', 'disaster_incident_id', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = $this->perPage();
        
        $announcements = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => (new AnnouncementCollection($announcements))->toArray(request()),
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
        $this->authorize('create', Announcement::class);
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
        $this->authorize('view', $announcement);
        
        return response()->json([
            'success' => true,
            'data' => new AnnouncementResource($announcement->load(['disasterIncident', 'creator'])),
        ]);
    }

    public function update(UpdateAnnouncementRequest $request, int $id): JsonResponse
    {
        $announcement = $this->service->getByIdOrFail($id);
        $this->authorize('update', $announcement);
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
        $this->authorize('delete', $announcement);
        $this->service->delete($announcement);
        
        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully',
        ]);
    }

    public function publish(int $id): JsonResponse
    {
        $announcement = $this->service->getByIdOrFail($id);
        $this->authorize('publish', $announcement);
        $announcement = $this->service->publish($announcement);
        
        return response()->json([
            'success' => true,
            'message' => 'Announcement published successfully',
            'data' => new AnnouncementResource($announcement),
        ]);
    }

    public function statistics(): JsonResponse
    {
        $this->authorize('viewAny', Announcement::class);
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }
}