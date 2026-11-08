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
use App\Models\ReliefItem;

class ReliefItemController extends Controller
{
    public function __construct(
        protected ReliefItemService $service
    ) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', ReliefItem::class);
        $filters = request()->only(['search', 'category', 'status', 'low_stock', 'expiring_soon', 'date_from', 'date_to', 'sort_by', 'sort_dir']);
        $perPage = $this->perPage();
        
        $items = $this->service->getAllItems($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => (new ReliefItemCollection($items))->toArray(request()),
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
        $this->authorize('create', ReliefItem::class);
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
        $this->authorize('view', $item);
        
        return response()->json([
            'success' => true,
            'data' => new ReliefItemResource($item->load('transactions')),
        ]);
    }

    public function update(UpdateReliefItemRequest $request, int $id): JsonResponse
    {
        $item = $this->service->getItemByIdOrFail($id);
        $this->authorize('update', $item);
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
        $this->authorize('delete', $item);
        $this->service->deleteItem($item);
        
        return response()->json([
            'success' => true,
            'message' => 'Relief item deleted successfully',
        ]);
    }

    public function stockIn(StockInRequest $request, int $id): JsonResponse
    {
        $item = $this->service->getItemByIdOrFail($id);
        $this->authorize('stockIn', $item);
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
        $this->authorize('stockOut', $item);
        
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
        $this->authorize('distribute', $item);
        
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
        $this->authorize('view', $item);
        $filters = request()->only(['type', 'evacuation_center_id', 'disaster_incident_id', 'date_from', 'date_to']);
        $perPage = $this->perPage();
        
        $transactions = $this->service->getTransactions($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => (new InventoryTransactionCollection($transactions))->toArray(request()),
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
        $this->authorize('viewAny', ReliefItem::class);
        return response()->json([
            'success' => true,
            'data' => $this->service->getStatistics(),
        ]);
    }

    public function lowStock(): JsonResponse
    {
        $this->authorize('viewAny', ReliefItem::class);
        $items = $this->service->getLowStock();
        
        return response()->json([
            'success' => true,
            'data' => ReliefItemResource::collection($items),
        ]);
    }

    public function expiringSoon(): JsonResponse
    {
        $this->authorize('viewAny', ReliefItem::class);
        $validated = request()->validate(['days' => 'nullable|integer|min:1|max:365']);
        $items = $this->service->getExpiringSoon($validated['days'] ?? 30);
        
        return response()->json([
            'success' => true,
            'data' => ReliefItemResource::collection($items),
        ]);
    }
}