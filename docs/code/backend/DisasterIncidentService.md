---
tags: [code, backend, backend-service]
---

# DisasterIncidentService

`backend/app/Services/DisasterIncidentService.php`

**Layer:** service · **Module:** DisasterIncident

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected DisasterIncidentRepository $repository` | `` |
| public | `getAll` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getById` | `int $id` | `?DisasterIncident` |
| public | `getByIdOrFail` | `int $id` | `DisasterIncident` |
| public | `create` | `array $data` | `DisasterIncident` |
| public | `update` | `DisasterIncident $incident, array $data` | `DisasterIncident` |
| public | `delete` | `DisasterIncident $incident` | `bool` |
| public | `updateStatus` | `DisasterIncident $incident, string $status` | `DisasterIncident` |
| public | `updateCasualties` | `DisasterIncident $incident, array $casualties` | `DisasterIncident` |
| public | `assignResponder` | `DisasterIncident $incident, int $responderId` | `DisasterIncident` |
| public | `getNearby` | `float $lat, float $lng, float $radiusKm = 50, int $limit = 10` | `Collection` |
| public | `getStatistics` | `` | `array` |
| public | `getTrends` | `int $days = 30` | `array` |
| protected | `generateIncidentId` | `` | `string` |

## References

- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/DisasterIncidentRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
