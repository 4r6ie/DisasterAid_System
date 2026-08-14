<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreReliefItemRequest;
use App\Http\Requests\API\V1\UpdateReliefItemRequest;
use App\Http\Requests\API\V1\StockInRequest;
use App\Http\Requests\API\V1\StockOutRequest;
use App\Http\Requests\API\V1\DistributeRequest;
use App\Http\Resources\API\V1\ReliefItemResource;
use App\Http\Resources\API\V1\ReliefItemCollection;
use App\Http\Resources\API\V1\InventoryTransactionResource;
use App\Http\Resources\API\V1\InventoryTransactionCollection;
use App\Services\ReliefItemService;
use Illuminate\Http\JsonResponse;

class ReliefItemController extends Controller
{
    public function __construct(
        protected ReliefItemService $service
    ) {}

    public function index(): JsonResponse
    {
        $filters = request()->only(['search', 'category', 'status', 'low_stock', 'expiring_soon', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = request()->get('per_page', 15);
        
        $items = $this->service->getAllItems($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new ReliefItemCollection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function store(StoreReliefItemRequest $request): JsonResponse
    {
        $item = $this->service->createItem($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Relief item created successfully',
            'data' => new ReliefItemResource($item),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $item = $this->service->getItemByIdOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => new ReliefItemResource($item->load('transactions')),
        ]);
    }

    public function update(UpdateReliefItemRequest $request, int $id): JsonResponse
    {
        $item = $this->service->getItemByIdOrFail($id);
        $item = $this->service->updateItem($item, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Relief item updated successfully',
            'data' => new ReliefItemResource($item),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = $this->service->getItemByIdOrFail($id);
        $this->service->deleteItem($item);
        
        return response()->json([
            'success' => true,
            'message' => 'Relief item deleted successfully',
        ]);
    }

    public function stockIn(StockInRequest $request, int $id): JsonResponse
    {
        $item = $this->service->getItemByIdOrFail($id);
        $transaction = $this->service->stockIn($item, $request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Stock added successfully',
            'data' => new InventoryTransactionResource($transaction->load(['reliefItem', 'processor'])),
        ]);
    }

    public function stockOut(StockOutRequest $request, int $id): JsonResponse
    {
        $item = $this->service->getItemByIdOrFail($id);
        
        try {
            $transaction = $this->service->stockOut($item, $request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Stock removed successfully',
            'data' => new InventoryTransactionResource($transaction->load(['reliefItem', 'processor'])),
        ]);
    }

    public function distribute(DistributeRequest $request, int $id): JsonResponse
    {
        $item = $this->service->getItemByIdOrFail($id);
        
        try {
            $transaction = $this->service->distribute($item, $request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Items distributed successfully',
            'data' => new InventoryTransactionResource($transaction->load(['reliefItem', 'processor', 'evacuationCenter', 'disasterIncident'])),
        ]);
    }

    public function transactions(int $id): JsonResponse
    {
        $item = $this->service->getItemByIdOrFail($id);
        $filters = request()->only(['type', 'evacuation_center_id', 'disaster_incident_id', 'date_from', 'date_to']);
        $perPage = request()->get('per_page', 15);
        
        $transactions = $this->service->getTransactions($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => new InventoryTransactionCollection($transactions),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ],
        ]);
    }

    public function statistics(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }

    public function lowStock(): JsonResponse
    {
        $items = $this->service->getLowStock();
        
        return response()->json([
            'success' => true,
            'data' => ReliefItemResource::collection($items),
        ]);
    }

    public function expiringSoon(): JsonResponse
    {
        $days = request()->get('days', 30);
        $items = $this->service->getExpiringSoon($days);
        
        return response()->json([
            'success' => true,
            'data' => ReliefItemResource::collection($items),
        ]);
    }
}