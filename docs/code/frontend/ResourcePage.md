---
tags: [code, frontend, frontend-component, tsx]
---

# ResourcePage

`frontend/src/components/shared/ResourcePage.tsx`

**Layer:** component

## Exports

| Kind | Name |
| --- | --- |
| interface | `ResourceField` |
| interface | `ResourceFilter` |
| interface | `ResourceConfig` |
| function | `ResourcePage` |

## Imports

- `{ ReactNode, useCallback, useEffect, useMemo, useState }` from `react`
- `{ Pencil, Plus, Search, Trash2 }` from `lucide-react`
- `{
  Button,
  Card,
  Field,
  Input,
  PageHeader,
  Select,
  Textarea,
}` from `@/components/ui`
- `{ Modal }` from `@/components/shared/Modal`
- `{ Column, DataTable }` from `@/components/shared/DataTable`
- `{ Pagination }` from `@/components/shared/Pagination`
- `{ usePaginated }` from `@/hooks/usePaginated`
- `{ useToast }` from `@/hooks/useToast`
- `{ apiErrorMessage, validationErrors }` from `@/services/http`
- `{ PaginatedResponse, QueryParams }` from `@/types`

## References

- [[docs/code/frontend/DataTable]]
- [[docs/code/frontend/Modal]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/Toast]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/usePaginated]]
- [[docs/code/frontend/useToast]]

## Hubs

- [[Code Map]] · [[docs/code/frontend]]
