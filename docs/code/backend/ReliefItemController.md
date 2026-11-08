---
tags: [code, backend, backend-controller]
---

# ReliefItemController

`backend/app/Http/Controllers/API/V1/ReliefItemController.php`

**Layer:** controller · **Module:** ReliefItem

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected ReliefItemService $service` | `` |
| public | `index` | `` | `JsonResponse` |
| public | `store` | `StoreReliefItemRequest $request` | `JsonResponse` |
| public | `show` | `int $id` | `JsonResponse` |
| public | `update` | `UpdateReliefItemRequest $request, int $id` | `JsonResponse` |
| public | `destroy` | `int $id` | `JsonResponse` |
| public | `stockIn` | `StockInRequest $request, int $id` | `JsonResponse` |
| public | `stockOut` | `StockOutRequest $request, int $id` | `JsonResponse` |
| public | `distribute` | `DistributeRequest $request, int $id` | `JsonResponse` |
| public | `transactions` | `int $id` | `JsonResponse` |
| public | `statistics` | `` | `JsonResponse` |
| public | `lowStock` | `` | `JsonResponse` |
| public | `expiringSoon` | `` | `JsonResponse` |

## References

- [[docs/code/backend/Controller]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/DistributeRequest]]
- [[docs/code/backend/EvacuationCenter]]
- [[docs/code/backend/InventoryTransactionCollection]]
- [[docs/code/backend/InventoryTransactionResource]]
- [[docs/code/backend/ReliefItem]]
- [[docs/code/backend/ReliefItemCollection]]
- [[docs/code/backend/ReliefItemResource]]
- [[docs/code/backend/ReliefItemService]]
- [[docs/code/backend/StockInRequest]]
- [[docs/code/backend/StockOutRequest]]
- [[docs/code/backend/StoreReliefItemRequest]]
- [[docs/code/backend/UpdateReliefItemRequest]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
