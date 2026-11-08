---
tags: [code, backend, backend-service]
---

# ReliefItemService

`backend/app/Services/ReliefItemService.php`

**Layer:** service · **Module:** ReliefItem

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected ReliefItemRepository $itemRepository, protected InventoryTransactionRepository $transactionRepository` | `` |
| public | `getAllItems` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getItemById` | `int $id` | `?ReliefItem` |
| public | `getItemByIdOrFail` | `int $id` | `ReliefItem` |
| public | `createItem` | `array $data` | `ReliefItem` |
| public | `updateItem` | `ReliefItem $item, array $data` | `ReliefItem` |
| public | `deleteItem` | `ReliefItem $item` | `bool` |
| public | `stockIn` | `ReliefItem $item, array $data` | `InventoryTransaction` |
| public | `stockOut` | `ReliefItem $item, array $data` | `InventoryTransaction` |
| public | `distribute` | `ReliefItem $item, array $data` | `InventoryTransaction` |
| public | `getTransactions` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getItemTransactions` | `int $itemId, int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |
| public | `getLowStock` | `int $limit = 50` | `Collection` |
| public | `getExpiringSoon` | `int $days = 30, int $limit = 50` | `Collection` |

## References

- [[docs/code/backend/InventoryTransaction]]
- [[docs/code/backend/InventoryTransactionRepository]]
- [[docs/code/backend/ReliefItem]]
- [[docs/code/backend/ReliefItemRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
