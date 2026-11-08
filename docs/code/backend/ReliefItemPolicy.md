---
tags: [code, backend, backend-policy]
---

# ReliefItemPolicy

`backend/app/Policies/ReliefItemPolicy.php`

**Layer:** policy · **Module:** ReliefItem

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `viewAny` | `User $user` | `bool` |
| public | `view` | `User $user, ReliefItem $item` | `bool` |
| public | `create` | `User $user` | `bool` |
| public | `update` | `User $user, ReliefItem $item` | `bool` |
| public | `delete` | `User $user, ReliefItem $item` | `bool` |
| public | `stockIn` | `User $user, ReliefItem $item` | `bool` |
| public | `stockOut` | `User $user, ReliefItem $item` | `bool` |
| public | `distribute` | `User $user, ReliefItem $item` | `bool` |

## References

- [[docs/code/backend/ReliefItem]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
