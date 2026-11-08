---
tags: [code, backend, backend-model]
---

# Responder

`backend/app/Models/Responder.php`

**Layer:** model · **Module:** Responder

Extends `Model`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `getActivitylogOptions` | `` | `LogOptions` |
| public | `scopeByStatus` | `$query, string $status` | `` |
| public | `scopeBySpecialization` | `$query, string $specialization` | `` |
| public | `scopeAvailable` | `$query` | `` |
| public | `user` | `` | `` |
| public | `assignedDisasters` | `` | `` |
| public | `assignedEmergencies` | `` | `` |
| public | `hasCertification` | `string $certification` | `bool` |

## References

- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/EmergencyReport]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
