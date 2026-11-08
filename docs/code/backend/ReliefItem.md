---
tags: [code, backend, backend-model]
---

# ReliefItem

`backend/app/Models/ReliefItem.php`

**Layer:** model · **Module:** ReliefItem

Extends `Model`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `getActivitylogOptions` | `` | `LogOptions` |
| public | `scopeByCategory` | `$query, string $category` | `` |
| public | `scopeLowStock` | `$query` | `` |
| public | `scopeExpiringSoon` | `$query, int $days = 30` | `` |
| public | `scopeActive` | `$query` | `` |
| public | `transactions` | `` | `` |
| public | `getIsLowStockAttribute` | `` | `bool` |
| public | `getIsExpiringSoonAttribute` | `` | `bool` |
| public | `getTotalValueAttribute` | `` | `float` |

## References

- [[docs/code/backend/InventoryTransaction]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
