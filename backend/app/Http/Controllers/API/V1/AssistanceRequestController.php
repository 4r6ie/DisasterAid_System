<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreAssistanceRequestRequest;
use App\Http\Requests\API\V1\UpdateAssistanceRequestRequest;
use App\Http\Requests\API\V1\RejectAssistanceRequestRequest;
use App\Http\Resources\API\V1\AssistanceRequestResource;
use App\Http\Resources\API\V1\AssistanceRequestCollection;
use App\Services\AssistanceRequestService;
use Illuminate\Http\JsonResponse;
use App\Models\AssistanceRequest;

class AssistanceRequestController extends Controller
{
    public function __construct(
        protected AssistanceRequestService $service
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', AssistanceRequest::class);
        $filters = request()->only(['search', 'status', 'urgency', 'assistance_type', 'disaster_incident_id', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = $this->perPage();
        
        $requests = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => (new AssistanceRequestCollection($requests))->toArray(request()),
            'meta' => [
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
                'per_page' => $requests->perPage(),
                'total' => $requests->total(),
            ],
        ]);
    }

    public function store(StoreAssistanceRequestRequest $request): JsonResponse
    {
        $this->authorize('create', AssistanceRequest::class);
        $assistanceRequest = $this->service->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Assistance request created successfully',
            'data' => new AssistanceRequestResource($assistanceRequest->load(['disasterIncident', 'requester', 'approver', 'dispatcher'])),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $assistanceRequest = $this->service->getByIdOrFail($id);
        $this->authorize('view', $assistanceRequest);
        
        return response()->json([
            'success' => true,
            'data' => new AssistanceRequestResource($assistanceRequest->load(['disasterIncident', 'requester', 'approver', 'dispatcher'])),
        ]);
    }

    public function update(UpdateAssistanceRequestRequest $request, int $id): JsonResponse
    {
        $assistanceRequest = $this->service->getByIdOrFail($id);
        $this->authorize('update', $assistanceRequest);
        $assistanceRequest = $this->service->update($assistanceRequest, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Assistance request updated successfully',
            'data' => new AssistanceRequestResource($assistanceRequest->load(['disasterIncident', 'requester', 'approver', 'dispatcher'])),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $assistanceRequest = $this->service->getByIdOrFail($id);
        $this->authorize('delete', $assistanceRequest);
        $this->service->delete($assistanceRequest);
        
        return response()->json([
            'success' => true,
            'message' => 'Assistance request deleted successfully',
        ]);
    }

    public function approve(int $id): JsonResponse
    {
        $assistanceRequest = $this->service->getByIdOrFail($id);
        $this->authorize('approve', $assistanceRequest);
        $assistanceRequest = $this->service->approve($assistanceRequest);
        
        return response()->json([
            'success' => true,
            'message' => 'Assistance request approved successfully',
            'data' => new AssistanceRequestResource($assistanceRequest),
        ]);
    }

    public function dispatch(int $id): JsonResponse
    {
        $assistanceRequest = $this->service->getByIdOrFail($id);
        $this->authorize('dispatch', $assistanceRequest);
        $assistanceRequest = $this->service->dispatch($assistanceRequest);
        
        return response()->json([
            'success' => true,
            'message' => 'Assistance request dispatched successfully',
            'data' => new AssistanceRequestResource($assistanceRequest),
        ]);
    }

    public function complete(int $id): JsonResponse
    {
        $assistanceRequest = $this->service->getByIdOrFail($id);
        $this->authorize('complete', $assistanceRequest);
        $assistanceRequest = $this->service->complete($assistanceRequest);
        
        return response()->json([
            'success' => true,
            'message' => 'Assistance request completed successfully',
            'data' => new AssistanceRequestResource($assistanceRequest),
        ]);
    }

    public function reject(RejectAssistanceRequestRequest $request, int $id): JsonResponse
    {
        $assistanceRequest = $this->service->getByIdOrFail($id);
        $this->authorize('reject', $assistanceRequest);
        $assistanceRequest = $this->service->reject($assistanceRequest, $request->rejection_reason);
        
        return response()->json([
            'success' => true,
            'message' => 'Assistance request rejected',
            'data' => new AssistanceRequestResource($assistanceRequest),
        ]);
    }

    public function statistics(): JsonResponse
    {
        $this->authorize('viewAny', AssistanceRequest::class);
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }
}