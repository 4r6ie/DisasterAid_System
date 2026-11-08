---
tags: [code, backend, backend-model]
---

# AssistanceRequest

`backend/app/Models/AssistanceRequest.php`

**Layer:** model · **Module:** AssistanceRequest

Extends `Model`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `getActivitylogOptions` | `` | `LogOptions` |
| public | `scopeByStatus` | `$query, string $status` | `` |
| public | `scopeByUrgency` | `$query, string $urgency` | `` |
| public | `disasterIncident` | `` | `` |
| public | `requester` | `` | `` |
| public | `approver` | `` | `` |
| public | `dispatcher` | `` | `` |

## References

- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
