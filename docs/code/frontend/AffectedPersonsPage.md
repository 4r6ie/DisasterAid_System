---
tags: [code, frontend, frontend-page, tsx]
---

# AffectedPersonsPage

`frontend/src/pages/AffectedPersonsPage.tsx`

**Layer:** page

## Exports

| Kind | Name |
| --- | --- |
| function | `AffectedPersonsPage` |

## Imports

- `{ useEffect, useMemo, useState }` from `react`
- `{ ResourcePage, ResourceConfig }` from `@/components/shared/ResourcePage`
- `{ StatusBadge }` from `@/components/shared/StatusBadge`
- `{ affectedPersonService, disasterService, evacuationCenterService }` from `@/services/resources`
- `{ CONDITIONS, GENDERS, toOptions }` from `@/utils/constants`
- `{ formatDate }` from `@/utils/format`
- `{ AffectedPerson }` from `@/types`

## References

- [[docs/code/backend/AffectedPerson]]
- [[docs/code/backend/AffectedPersonService]]
- [[docs/code/backend/EvacuationCenterService]]
- [[docs/code/frontend/ResourcePage]]
- [[docs/code/frontend/StatusBadge]]
- [[docs/code/frontend/constants]]
- [[docs/code/frontend/format]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/frontend]]
