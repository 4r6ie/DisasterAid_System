---
tags: [code, backend, backend-model]
---

# EmergencyReport

`backend/app/Models/EmergencyReport.php`

**Layer:** model · **Module:** EmergencyReport

Extends `Model`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `getActivitylogOptions` | `` | `LogOptions` |
| public | `scopeNearby` | `$query, float $lat, float $lng, float $radiusKm = 50` | `` |
| public | `scopeByStatus` | `$query, string $status` | `` |
| public | `scopeByPriority` | `$query, string $priority` | `` |
| public | `disasterIncident` | `` | `` |
| public | `reporter` | `` | `` |
| public | `assignedResponder` | `` | `` |

## References

- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/Responder]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
