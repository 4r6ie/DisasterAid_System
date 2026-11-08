---
tags: [code, frontend, frontend-page, tsx]
---

# AnnouncementsPage

`frontend/src/pages/AnnouncementsPage.tsx`

**Layer:** page

## Exports

| Kind | Name |
| --- | --- |
| function | `AnnouncementsPage` |

## Imports

- `{ useEffect, useMemo, useState }` from `react`
- `{ ResourcePage, ResourceConfig }` from `@/components/shared/ResourcePage`
- `{ StatusBadge }` from `@/components/shared/StatusBadge`
- `{ announcementService, disasterService }` from `@/services/resources`
- `{ ANNOUNCEMENT_TYPES, AUDIENCES, PRIORITIES, toOptions }` from `@/utils/constants`
- `{ formatDateTime }` from `@/utils/format`
- `{ Announcement }` from `@/types`

## References

- [[docs/code/backend/Announcement]]
- [[docs/code/backend/AnnouncementService]]
- [[docs/code/frontend/ResourcePage]]
- [[docs/code/frontend/StatusBadge]]
- [[docs/code/frontend/constants]]
- [[docs/code/frontend/format]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/frontend]]
