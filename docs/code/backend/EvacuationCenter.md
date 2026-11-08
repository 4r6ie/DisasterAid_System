---
tags: [code, backend, backend-model]
---

# EvacuationCenter

`backend/app/Models/EvacuationCenter.php`

**Layer:** model · **Module:** EvacuationCenter

Extends `Model`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `getActivitylogOptions` | `` | `LogOptions` |
| public | `scopeNearby` | `$query, float $lat, float $lng, float $radiusKm = 50` | `` |
| public | `scopeByStatus` | `$query, string $status` | `` |
| public | `scopeWithAvailability` | `$query` | `` |
| public | `manager` | `` | `` |
| public | `affectedPersons` | `` | `` |
| public | `inventoryTransactions` | `` | `` |
| public | `getOccupancyPercentageAttribute` | `` | `float` |
| public | `getAvailableCapacityAttribute` | `` | `int` |

## References

- [[docs/code/backend/AffectedPerson]]
- [[docs/code/backend/InventoryTransaction]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
