---
tags: [code, backend, backend-model]
---

# DisasterIncident

`backend/app/Models/DisasterIncident.php`

**Layer:** model · **Module:** DisasterIncident

Extends `Model`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `getActivitylogOptions` | `` | `LogOptions` |
| public | `scopeNearby` | `$query, float $lat, float $lng, float $radiusKm = 50` | `` |
| public | `scopeByStatus` | `$query, string $status` | `` |
| public | `scopeBySeverity` | `$query, string $severity` | `` |
| public | `reporter` | `` | `` |
| public | `assignedResponder` | `` | `` |
| public | `emergencies` | `` | `` |
| public | `assistanceRequests` | `` | `` |
| public | `affectedPersons` | `` | `` |
| public | `announcements` | `` | `` |
| public | `inventoryTransactions` | `` | `` |
| public | `volunteerAssignments` | `` | `` |
| public | `getTotalCasualtiesAttribute` | `` | `int` |

## References

- [[docs/code/backend/AffectedPerson]]
- [[docs/code/backend/Announcement]]
- [[docs/code/backend/AssistanceRequest]]
- [[docs/code/backend/EmergencyReport]]
- [[docs/code/backend/InventoryTransaction]]
- [[docs/code/backend/Responder]]
- [[docs/code/backend/User]]
- [[docs/code/backend/VolunteerAssignment]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
