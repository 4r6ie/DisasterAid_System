---
tags: [code, frontend, frontend-page, tsx]
---

# ReliefItemsPage

`frontend/src/pages/ReliefItemsPage.tsx`

**Layer:** page

## Exports

| Kind | Name |
| --- | --- |
| function | `ReliefItemsPage` |

## Imports

- `{ useMemo, useRef, useState }` from `react`
- `{ ResourcePage, ResourceConfig }` from `@/components/shared/ResourcePage`
- `{ StatusBadge }` from `@/components/shared/StatusBadge`
- `{ Button, Field, Input, Select, Textarea }` from `@/components/ui`
- `{ Modal }` from `@/components/shared/Modal`
- `{ reliefItemService }` from `@/services/resources`
- `{ useToast }` from `@/hooks/useToast`
- `{ apiErrorMessage }` from `@/services/http`
- `{
  INVENTORY_CATEGORIES,
  INVENTORY_STATUSES,
  STOCK_IN_REFERENCES,
  STOCK_OUT_REFERENCES,
  toOptions,
}` from `@/utils/constants`
- `{ formatDate, formatNumber }` from `@/utils/format`
- `{ ReliefItem }` from `@/types`

## References

- [[docs/code/backend/ReliefItem]]
- [[docs/code/backend/ReliefItemService]]
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
