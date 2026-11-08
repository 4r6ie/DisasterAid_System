---
tags: [code, backend, backend-controller]
---

# EmergencyReportController

`backend/app/Http/Controllers/API/V1/EmergencyReportController.php`

**Layer:** controller · **Module:** EmergencyReport

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected EmergencyReportService $service` | `` |
| public | `index` | `` | `JsonResponse` |
| public | `store` | `StoreEmergencyReportRequest $request` | `JsonResponse` |
| public | `show` | `int $id` | `JsonResponse` |
| public | `update` | `UpdateEmergencyReportRequest $request, int $id` | `JsonResponse` |
| public | `destroy` | `int $id` | `JsonResponse` |
| public | `assignResponder` | `AssignResponderRequest $request, int $id` | `JsonResponse` |
| public | `updateStatus` | `UpdateEmergencyStatusRequest $request, int $id` | `JsonResponse` |
| public | `nearby` | `` | `JsonResponse` |
| public | `statistics` | `` | `JsonResponse` |

## References

- [[docs/code/backend/AssignResponderRequest]]
- [[docs/code/backend/Controller]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/EmergencyReport]]
- [[docs/code/backend/EmergencyReportCollection]]
- [[docs/code/backend/EmergencyReportResource]]
- [[docs/code/backend/EmergencyReportService]]
- [[docs/code/backend/Responder]]
- [[docs/code/backend/StoreEmergencyReportRequest]]
- [[docs/code/backend/UpdateEmergencyReportRequest]]
- [[docs/code/backend/UpdateEmergencyStatusRequest]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
