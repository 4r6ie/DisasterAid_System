---
tags: [code, frontend, frontend-page, tsx]
---

# AuditLogsPage

`frontend/src/pages/AuditLogsPage.tsx`

**Layer:** page

## Exports

| Kind | Name |
| --- | --- |
| function | `AuditLogsPage` |

## Imports

- `{ useCallback, useEffect, useState }` from `react`
- `{ Card, Input, PageHeader, Select, Spinner }` from `@/components/ui`
- `{ Column, DataTable }` from `@/components/shared/DataTable`
- `{ Pagination }` from `@/components/shared/Pagination`
- `{ Modal }` from `@/components/shared/Modal`
- `{ Badge }` from `@/components/ui`
- `{ auditLogService }` from `@/services/resources`
- `{ formatDateTime }` from `@/utils/format`
- `{ AuditLog, PaginatedMeta, QueryParams }` from `@/types`

## References

- [[docs/code/backend/AuditLogService]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/DataTable]]
- [[docs/code/frontend/Modal]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/format]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/frontend]]
