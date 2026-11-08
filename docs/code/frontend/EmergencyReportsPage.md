---
tags: [code, frontend, frontend-page, tsx]
---

# EmergencyReportsPage

`frontend/src/pages/EmergencyReportsPage.tsx`

**Layer:** page

## Exports

| Kind | Name |
| --- | --- |
| function | `EmergencyReportsPage` |

## Imports

- `{ useEffect, useMemo, useState }` from `react`
- `{ ResourcePage, ResourceConfig }` from `@/components/shared/ResourcePage`
- `{ StatusBadge }` from `@/components/shared/StatusBadge`
- `{ disasterService, emergencyReportService }` from `@/services/resources`
- `{ EMERGENCY_STATUSES, EMERGENCY_TYPES, PRIORITIES, toOptions }` from `@/utils/constants`
- `{ formatDate, formatNumber }` from `@/utils/format`
- `{ EmergencyReport }` from `@/types`

## References

- [[docs/code/backend/EmergencyReport]]
- [[docs/code/backend/EmergencyReportService]]
- [[docs/code/frontend/ResourcePage]]
- [[docs/code/frontend/StatusBadge]]
- [[docs/code/frontend/constants]]
- [[docs/code/frontend/format]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/frontend]]
