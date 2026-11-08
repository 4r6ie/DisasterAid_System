---
tags: [code, frontend, frontend-page, tsx]
---

# AssistanceRequestsPage

`frontend/src/pages/AssistanceRequestsPage.tsx`

**Layer:** page

## Exports

| Kind | Name |
| --- | --- |
| function | `AssistanceRequestsPage` |

## Imports

- `{ useMemo, useRef, useState }` from `react`
- `{ ResourcePage, ResourceConfig }` from `@/components/shared/ResourcePage`
- `{ StatusBadge }` from `@/components/shared/StatusBadge`
- `{ Button, Field, Textarea }` from `@/components/ui`
- `{ Modal }` from `@/components/shared/Modal`
- `{ assistanceRequestService }` from `@/services/resources`
- `{ useToast }` from `@/hooks/useToast`
- `{ apiErrorMessage }` from `@/services/http`
- `{ ASSISTANCE_STATUSES, ASSISTANCE_TYPES, URGENCIES, toOptions }` from `@/utils/constants`
- `{ formatDate, formatNumber }` from `@/utils/format`
- `{ AssistanceRequest }` from `@/types`

## References

- [[docs/code/backend/AssistanceRequest]]
- [[docs/code/backend/AssistanceRequestService]]
- [[docs/code/frontend/Modal]]
- [[docs/code/frontend/ResourcePage]]
- [[docs/code/frontend/StatusBadge]]
- [[docs/code/frontend/Toast]]
- [[docs/code/frontend/constants]]
- [[docs/code/frontend/format]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]
- [[docs/code/frontend/useToast]]

## Hubs

- [[Code Map]] · [[docs/code/frontend]]
