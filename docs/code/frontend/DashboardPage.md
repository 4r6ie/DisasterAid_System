---
tags: [code, frontend, frontend-page, tsx]
---

# DashboardPage

`frontend/src/pages/DashboardPage.tsx`

**Layer:** page

## Exports

| Kind | Name |
| --- | --- |
| function | `DashboardPage` |

## Imports

- `{ useEffect, useMemo, useState }` from `react`
- `{
  Area,
  AreaChart,
  Bar,
  BarChart,
  CartesianGrid,
  Cell,
  Legend,
  Pie,
  PieChart,
  ResponsiveContainer,
  Tooltip,
  XAxis,
  YAxis,
}` from `recharts`
- `{ CircleMarker, MapContainer, Popup, TileLayer }` from `react-leaflet`
- `{ AlertTriangle, Building2, HandHelping, Siren }` from `lucide-react`
- `{
  Card,
  CardBody,
  CardHeader,
  PageHeader,
  Spinner,
  StatCard,
}` from `@/components/ui`
- `{ dashboardService }` from `@/services/resources`
- `{ apiErrorMessage }` from `@/services/http`
- `{ formatNumber, titleCase }` from `@/utils/format`
- `{ DashboardCharts, DashboardStats, MapPoint }` from `@/types`

## References

- [[docs/code/backend/DashboardService]]
- [[docs/code/frontend/Layout]]
- [[docs/code/frontend/format]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/frontend]]
