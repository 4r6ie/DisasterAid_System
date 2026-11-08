---
tags: [code, backend, backend-repository]
---

# InventoryTransactionRepository

`backend/app/Repositories/InventoryTransactionRepository.php`

**Layer:** repository · **Module:** InventoryTransaction

Extends `BaseRepository`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `InventoryTransaction $model` | `` |
| public | `getFiltered` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getByReliefItem` | `int $reliefItemId, int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/BaseRepository]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/EvacuationCenter]]
- [[docs/code/backend/InventoryTransaction]]
- [[docs/code/backend/ReliefItem]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
