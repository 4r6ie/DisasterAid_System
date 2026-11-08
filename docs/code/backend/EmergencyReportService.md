---
tags: [code, backend, backend-service]
---

# EmergencyReportService

`backend/app/Services/EmergencyReportService.php`

**Layer:** service · **Module:** EmergencyReport

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected EmergencyReportRepository $repository` | `` |
| public | `getAll` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getById` | `int $id` | `?EmergencyReport` |
| public | `getByIdOrFail` | `int $id` | `EmergencyReport` |
| public | `create` | `array $data` | `EmergencyReport` |
| public | `update` | `EmergencyReport $report, array $data` | `EmergencyReport` |
| public | `delete` | `EmergencyReport $report` | `bool` |
| public | `updateStatus` | `EmergencyReport $report, string $status` | `EmergencyReport` |
| public | `assignResponder` | `EmergencyReport $report, int $responderId` | `EmergencyReport` |
| public | `getNearby` | `float $lat, float $lng, float $radiusKm = 50, int $limit = 10` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/EmergencyReport]]
- [[docs/code/backend/EmergencyReportRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
