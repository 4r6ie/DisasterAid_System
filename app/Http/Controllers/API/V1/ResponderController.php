<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreResponderRequest;
use App\Http\Requests\API\V1\UpdateResponderRequest;
use App\Http\Requests\API\V1\UpdateResponderStatusRequest;
use App\Http\Resources\API\V1\ResponderResource;
use App\Http\Resources\API\V1\ResponderCollection;
use App\Services\ResponderService;
use Illuminate\Http\JsonResponse;

class ResponderController extends Controller
{
    public function __construct(
        protected ResponderService $service
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['search', 'status', 'specialization', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);
        
        $responders = $this->service->getAll($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new ResponderCollection($responders),
            'meta' => [
                'current_page' => $responders->currentPage(),
                'last_page' => $responders->lastPage(),
                'per_page' => $responders->perPage(),
                'total' => $responders->total(),
            ],
        ]);
    }

    public function store(StoreResponderRequest $request): JsonResponse
    {
        $responder = $this->service->create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Responder created successfully',
            'data' => new ResponderResource($responder),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $responder = $this->service->getByIdOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new ResponderResource($responder->load('user')),
        ]);
    }

    public function update(UpdateResponderRequest $request, int $id): JsonResponse
    {
        $responder = $this->service->getByIdOrFail($id);
        $responder = $this->service->update($responder, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Responder updated successfully',
            'data' => new ResponderResource($responder),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $responder = $this->service->getByIdOrFail($id);
        $this->service->delete($responder);
        
        return response()->json([
            'success' => true,
            'message' => 'Responder deleted successfully',
        ]);
    }

    public function updateStatus(UpdateResponderStatusRequest $request, int $id): JsonResponse
    {
        $responder = $this->service->getByIdOrFail($id);
        $responder = $this->service->updateStatus($responder, $request->status);
        
        return response()->json([
            'success' => true,
            'message' => 'Responder status updated successfully',
            'data' => new ResponderResource($responder),
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