---
tags: [code, backend, backend-service]
---

# DashboardService

`backend/app/Services/DashboardService.php`

**Layer:** service · **Module:** Dashboard

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `getStats` | `` | `array` |
| public | `getCharts` | `` | `array` |
| public | `getMapData` | `` | `array` |
| protected | `getDisasterTrends` | `int $days` | `array` |
| protected | `getDisasterByType` | `` | `array` |
| protected | `getDisasterBySeverity` | `` | `array` |
| protected | `getEmergenciesByPriority` | `` | `array` |
| protected | `getAssistanceByType` | `` | `array` |
| protected | `getOccupancyTrend` | `int $days` | `array` |

## References

- [[docs/code/backend/AffectedPerson]]
- [[docs/code/backend/Announcement]]
- [[docs/code/backend/AssistanceRequest]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/EmergencyReport]]
- [[docs/code/backend/EvacuationCenter]]
- [[docs/code/backend/ReliefItem]]
- [[docs/code/backend/Responder]]
- [[docs/code/backend/Volunteer]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/format]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
