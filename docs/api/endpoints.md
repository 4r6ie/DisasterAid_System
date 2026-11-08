---
tags: [api]
---

# API reference

Base URL: `/api`

Related notes: [[docs/architecture/overview]] · [[docs/database/schema]] · [[README]]

The API is a stateless JSON interface served by the Laravel backend. The React
frontend consumes it through a single Axios instance; every rule below applies
to direct HTTP clients as well.

---

## 1. Conventions

| Item | Value |
| --- | --- |
| Base URL (development) | `http://localhost:8000/api` |
| Base URL (Vite dev server) | `http://localhost:5173/api` (proxied) |
| Media type | `application/json` |
| Authentication | Bearer personal access token (Laravel Sanctum) |
| Character encoding | UTF-8 |
| Timestamp format | ISO 8601 (`2026-01-15T08:30:00.000000Z`) |

### Required headers

```http
Accept: application/json
Content-Type: application/json          # for bodies
Authorization: Bearer <token>          # for authenticated routes
```

### Response envelope

Every response — success or failure — uses the same shape.

Success:

```json
{ "success": true, "message": "Login successful", "data": { } }
```

Failure:

```json
{ "success": false, "message": "The given data was invalid.", "errors": { "email": ["The provided credentials are incorrect."] } }
```

Collections return the list under `data` and pagination metadata under `meta`:

```json
{
  "success": true,
  "data": [ { } ],
  "meta": { "current_page": 1, "last_page": 4, "per_page": 15, "total": 52 }
}
```

### Status codes

| Code | Meaning |
| --- | --- |
| `200` | OK |
| `201` | Created (`POST` returning a resource) |
| `401` | Unauthenticated — missing or invalid token |
| `403` | Authenticated but lacking the required permission |
| `404` | Resource not found |
| `422` | Validation failed (see `errors`) |
| `429` | Too many requests (rate limit exceeded) |
| `500` | Server error |

### Error rendering

`bootstrap/app.php` maps exceptions to the envelope:

| Exception | Status | Message |
| --- | --- | --- |
| `ValidationException` | 422 | `The given data was invalid.` + `errors` |
| `AuthenticationException` | 401 | `Unauthenticated.` |
| `AuthorizationException` | 403 | Policy message, or `This action is unauthorized.` |
| `ModelNotFoundException` | 404 | `Resource not found.` |
| Other `HttpExceptionInterface` | its status | Exception message, or the status text |
| Anything else | 500 | `Server error.` (detail only when `APP_DEBUG=true`) |

---

## 2. Authentication

Authentication uses **Laravel Sanctum** personal access tokens. Tokens are
returned in the `data.token` field and must be sent as a Bearer credential.

### `POST /api/auth/register`

Create an account and receive a token.

| Field | Rules |
| --- | --- |
| `name` | required, string, max 255 |
| `email` | required, email, unique `users.email` |
| `password` | required, string, min 8, confirmed (`password_confirmation`) |
| `phone` | nullable, string, max 20 |
| `address` | nullable, string |

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H 'Accept: application/json' -H 'Content-Type: application/json' \
  -d '{"name":"Jane Doe","email":"jane@example.com","password":"password123","password_confirmation":"password123"}'
```

Response `201` — `data.user` is a user resource and `data.token` is the
plain-text token.

### `POST /api/auth/login`

| Field | Rules |
| --- | --- |
| `email` | required, email |
| `password` | required, string |
| `remember` | boolean |

Invalid credentials return `422` with `errors.email = ["The provided
credentials are incorrect."]`.

### `POST /api/auth/forgot-password`

| Field | Rules |
| --- | --- |
| `email` | required, email, must exist in `users.email` |

Always returns `200` to avoid account enumeration.

### `POST /api/auth/reset-password`

| Field | Rules |
| --- | --- |
| `token` | required, string |
| `email` | required, email |
| `password` | required, string, min 8, confirmed |

### `POST /api/auth/logout`

Revokes the token used for the request.

### `GET /api/auth/me`

Returns the authenticated user.

### `PUT /api/auth/profile`

| Field | Rules |
| --- | --- |
| `name` | sometimes, string, max 255 |
| `phone` | nullable, string, max 20 |
| `address` | nullable, string |

### `POST /api/auth/change-password`

| Field | Rules |
| --- | --- |
| `current_password` | required, string |
| `new_password` | required, string, min 8, confirmed |

---

## 3. Authorization

Authorization is **permission-based**, not role-based. Controllers call
`$this->authorize('ability', $model)`; the matching policy checks
`$user->can('<permission>')`. Roles are only a convenience bundle of
permissions.

Model-less abilities (`view dashboard`, `view audit logs`) are Gate definitions
registered in `AppServiceProvider`.

### Roles

| Role | Permission groups |
| --- | --- |
| `super_admin` | every group (including `audit`) |
| `admin` | dashboard, disasters, emergencies, assistance, evacuation, inventory, responders, volunteers, affected, announcements, audit |
| `responder` | dashboard, disasters, emergencies, assistance, evacuation, inventory, affected, announcements |
| `volunteer` | dashboard, disasters, assistance, evacuation, inventory, announcements |
| `user` | dashboard, disasters, assistance, announcements |

### Permissions catalogue

| Group | Permissions |
| --- | --- |
| dashboard | `view dashboard` |
| disasters | `view disasters`, `create disasters`, `edit disasters`, `delete disasters`, `assign responders`, `update disaster status`, `update casualties` |
| emergencies | `view emergencies`, `create emergencies`, `edit emergencies`, `delete emergencies`, `update emergency status` |
| assistance | `view assistance requests`, `create assistance requests`, `edit assistance requests`, `delete assistance requests`, `approve assistance`, `dispatch assistance`, `complete assistance`, `reject assistance` |
| evacuation | `view evacuation centers`, `create evacuation centers`, `edit evacuation centers`, `delete evacuation centers`, `update occupancy` |
| inventory | `view relief inventory`, `create relief items`, `edit relief items`, `delete relief items`, `manage inventory`, `distribute relief` |
| responders | `view responders`, `create responders`, `edit responders`, `delete responders`, `update responder status` |
| volunteers | `view volunteers`, `create volunteers`, `edit volunteers`, `delete volunteers`, `update volunteer status` |
| affected | `view affected persons`, `create affected persons`, `edit affected persons`, `delete affected persons`, `update condition`, `assign evacuation center` |
| announcements | `view announcements`, `create announcements`, `edit announcements`, `delete announcements`, `publish announcements` |
| audit | `view audit logs` |

The full catalogue is seeded by
`backend/database/seeders/RolesAndPermissionsSeeder.php`.

---

## 4. Pagination, sorting and filtering

List endpoints accept these query parameters:

| Parameter | Default | Notes |
| --- | --- | --- |
| `page` | `1` | Page number |
| `per_page` | `15` | Clamped to the range 1–100 by `Controller::perPage()` |
| `search` | — | Case-insensitive match on the module's searchable columns |
| `sort_by` | `created_at` | Column to sort by |
| `sort_dir` | `desc` | `asc` or `desc` |
| module filters | — | e.g. `status`, `severity`, `disaster_type`, `date_from`, `date_to` |

Pagination metadata:

```json
{ "current_page": 1, "last_page": 4, "per_page": 15, "total": 52 }
```

---

## 5. Rate limiting

The `api` middleware group applies a throttle of **60 requests per minute per
IP** (`$middleware->throttleApi('60,1')`). Exceeding it returns `429` with
`{ "success": false, "message": "Too Many Attempts." }`.

---

## 6. Endpoint reference

### 6.1 Dashboard

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/dashboard/stats` | `view dashboard` |
| `GET` | `/api/dashboard/charts` | `view dashboard` |
| `GET` | `/api/dashboard/map` | `view dashboard` |

`stats` returns per-module counters (`disasters`, `emergencies`, `assistance`,
`evacuation_centers`, `relief_items`, `responders`, `volunteers`,
`affected_persons`, `announcements`), each with totals and status breakdowns.
`charts` returns the series used by the dashboard graphs; `map` returns the
geo points used by the Leaflet view.

### 6.2 Disaster incidents

| Method | Endpoint | Permission | Purpose |
| --- | --- | --- | --- |
| `GET` | `/api/disasters` | `view disasters` | Paginated list |
| `POST` | `/api/disasters` | `create disasters` | Create a record |
| `GET` | `/api/disasters/{id}` | `view disasters` | Show one |
| `PUT` | `/api/disasters/{id}` | `edit disasters` | Update |
| `DELETE` | `/api/disasters/{id}` | `delete disasters` | Soft delete |
| `GET` | `/api/disasters/nearby` | `view disasters` | Distance search |
| `GET` | `/api/disasters/statistics` | `view disasters` | Aggregates |
| `POST` | `/api/disasters/{id}/assign-responder` | `assign responders` | Attach a responder |
| `PATCH` | `/api/disasters/{id}/status` | `update disaster status` | Change status |
| `PATCH` | `/api/disasters/{id}/casualties` | `update casualties` | Update counters |

**Create body**

| Field | Rules | Notes |
| --- | --- | --- |
| `disaster_type` | required, in `earthquake, flood, typhoon, fire, landslide, volcanic, tsunami, other` | |
| `title` | required, string, max 255 | |
| `description` | nullable, string | |
| `severity` | required, in `low, moderate, high, critical` | |
| `status` | sometimes, in `reported, verified, active, responding, contained, resolved` | |
| `latitude` | required, numeric, between -90 and 90 | |
| `longitude` | required, numeric, between -180 and 180 | |
| `address` | nullable, string | |
| `city` | nullable, string, max 255 | |
| `province` | nullable, string, max 255 | |
| `occurred_at` | nullable, date | |
| `metadata` | nullable, array | Free-form key/value payload |

`incident_id` is generated by the service as `DIS-YYYY-NNNN` and is not
accepted from the client.

**Nearby query**: `lat` (required, -90..90), `lng` (required, -180..180),
`radius` (km, default 10), `limit` (default 20).

**Response**

```json
{
  "id": 1,
  "incident_id": "DIS-2026-0001",
  "disaster_type": "flood",
  "title": "Flash flood in Barangay San Jose",
  "description": null,
  "severity": "high",
  "status": "active",
  "latitude": 14.5995,
  "longitude": 120.9842,
  "address": "San Jose, Malabon",
  "city": "Malabon",
  "province": "Metro Manila",
  "affected_count": 120,
  "injured_count": 8,
  "missing_count": 2,
  "deceased_count": 0,
  "rescued_count": 90,
  "total_casualties": 130,
  "occurred_at": "2026-01-15T08:30:00.000000Z",
  "resolved_at": null,
  "reporter": { "id": 1, "name": "System Administrator" },
  "assigned_responder": null,
  "emergencies_count": 3,
  "assistance_requests_count": 5,
  "affected_persons_count": 12,
  "created_at": "2026-01-15T08:31:00.000000Z",
  "updated_at": "2026-01-15T08:31:00.000000Z"
}
```

`reporter` and `assigned_responder` appear only when eager-loaded; the
`*_count` fields appear only when requested with `with_count`.

### 6.3 Emergency reports

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/emergency-reports` | `view emergencies` |
| `POST` | `/api/emergency-reports` | `create emergencies` |
| `GET` | `/api/emergency-reports/{id}` | `view emergencies` |
| `PUT` | `/api/emergency-reports/{id}` | `edit emergencies` |
| `DELETE` | `/api/emergency-reports/{id}` | `delete emergencies` |
| `GET` | `/api/emergency-reports/nearby` | `view emergencies` |
| `GET` | `/api/emergency-reports/statistics` | `view emergencies` |
| `POST` | `/api/emergency-reports/{id}/assign-responder` | `assign responders` |
| `PATCH` | `/api/emergency-reports/{id}/status` | `update emergency status` |

**Create body**

| Field | Rules |
| --- | --- |
| `emergency_type` | required, in `medical, fire, rescue, evacuation, other` |
| `description` | required, string |
| `priority` | required, in `low, medium, high, critical` |
| `status` | sometimes, in `pending, assigned, in_progress, resolved, cancelled` |
| `latitude` | required, numeric, between -90 and 90 |
| `longitude` | required, numeric, between -180 and 180 |
| `address` | nullable, string |
| `people_needing_help` | required, integer, min 1 |
| `contact_name` | nullable, string, max 255 |
| `contact_phone` | nullable, string, max 20 |
| `disaster_incident_id` | nullable, exists `disaster_incidents.id` |
| `metadata` | nullable, array |

### 6.4 Assistance requests

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/assistance-requests` | `view assistance requests` |
| `POST` | `/api/assistance-requests` | `create assistance requests` |
| `GET` | `/api/assistance-requests/{id}` | `view assistance requests` |
| `PUT` | `/api/assistance-requests/{id}` | `edit assistance requests` |
| `DELETE` | `/api/assistance-requests/{id}` | `delete assistance requests` |
| `GET` | `/api/assistance-requests/statistics` | `view assistance requests` |
| `POST` | `/api/assistance-requests/{id}/approve` | `approve assistance` |
| `POST` | `/api/assistance-requests/{id}/dispatch` | `dispatch assistance` |
| `POST` | `/api/assistance-requests/{id}/complete` | `complete assistance` |
| `POST` | `/api/assistance-requests/{id}/reject` | `reject assistance` |

**Create body**

| Field | Rules |
| --- | --- |
| `assistance_type` | required, in `food, water, medicine, shelter, clothing, other` |
| `quantity` | required, integer, min 1 |
| `unit` | required, string, max 50 |
| `urgency` | required, in `low, medium, high, critical` |
| `status` | sometimes, in `pending, approved, dispatched, completed, rejected, cancelled` |
| `description` | nullable, string |
| `latitude` | nullable, numeric, between -90 and 90 |
| `longitude` | nullable, numeric, between -180 and 180 |
| `address` | nullable, string |
| `contact_name` | nullable, string, max 255 |
| `contact_phone` | nullable, string, max 20 |
| `disaster_incident_id` | nullable, exists `disaster_incidents.id` |
| `metadata` | nullable, array |

**`reject` body**: `rejection_reason` (required, string).

The reference number is generated as `REQ-YYYY-NNNN`.

### 6.5 Evacuation centers

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/evacuation-centers` | `view evacuation centers` |
| `POST` | `/api/evacuation-centers` | `create evacuation centers` |
| `GET` | `/api/evacuation-centers/{id}` | `view evacuation centers` |
| `PUT` | `/api/evacuation-centers/{id}` | `edit evacuation centers` |
| `DELETE` | `/api/evacuation-centers/{id}` | `delete evacuation centers` |
| `GET` | `/api/evacuation-centers/nearby` | `view evacuation centers` |
| `GET` | `/api/evacuation-centers/statistics` | `view evacuation centers` |
| `PATCH` | `/api/evacuation-centers/{id}/occupancy` | `update occupancy` |

**Create body**

| Field | Rules |
| --- | --- |
| `name` | required, string, max 255 |
| `address` | required, string |
| `latitude` | required, numeric, between -90 and 90 |
| `longitude` | required, numeric, between -180 and 180 |
| `capacity` | required, integer, min 1 |
| `current_occupancy` | sometimes, integer, min 0 |
| `status` | sometimes, in `active, inactive, full, maintenance` |
| `facilities` | nullable, array of strings |
| `contact_person` | nullable, string, max 255 |
| `contact_phone` | nullable, string, max 20 |
| `contact_email` | nullable, email, max 255 |
| `managed_by` | nullable, exists `users.id` |
| `notes` | nullable, string |

**`occupancy` body**: `occupancy` (required, integer, min 0).

### 6.6 Relief inventory

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/relief-items` | `view relief inventory` |
| `POST` | `/api/relief-items` | `create relief items` |
| `GET` | `/api/relief-items/{id}` | `view relief inventory` |
| `PUT` | `/api/relief-items/{id}` | `edit relief items` |
| `DELETE` | `/api/relief-items/{id}` | `delete relief items` |
| `GET` | `/api/relief-items/statistics` | `view relief inventory` |
| `GET` | `/api/relief-items/low-stock` | `view relief inventory` |
| `GET` | `/api/relief-items/expiring-soon` | `view relief inventory` |
| `POST` | `/api/relief-items/{id}/stock-in` | `manage inventory` |
| `POST` | `/api/relief-items/{id}/stock-out` | `manage inventory` |
| `POST` | `/api/relief-items/{id}/distribute` | `distribute relief` |
| `GET` | `/api/relief-items/{id}/transactions` | `view relief inventory` |

**Create body**

| Field | Rules |
| --- | --- |
| `name` | required, string, max 255 |
| `category` | required, in `food, water, medicine, shelter, clothing, hygiene, tools, other` |
| `unit` | required, string, max 50 |
| `quantity` | required, integer, min 0 |
| `min_stock` | required, integer, min 0 |
| `unit_cost` | nullable, numeric, min 0 |
| `location` | nullable, string, max 255 |
| `expiry_date` | nullable, date, after or equal to today |
| `batch_number` | nullable, string, max 100 |
| `description` | nullable, string |
| `status` | sometimes, in `active, inactive, discontinued` |

**`stock-in` body**

| Field | Rules |
| --- | --- |
| `quantity` | required, integer, min 1 |
| `reference_type` | nullable, in `purchase, donation, transfer, return` |
| `reference_id` | nullable, integer |
| `reference_number` | nullable, string, max 100 |
| `unit_cost` | nullable, numeric, min 0 |
| `notes` | nullable, string |
| `transaction_date` | nullable, date |

**`stock-out` body**: same shape, with `reference_type` in
`usage, transfer, expired, damaged, adjustment`.

**`distribute` body**

| Field | Rules |
| --- | --- |
| `quantity` | required, integer, min 1 |
| `reference_id` | nullable, integer |
| `reference_number` | nullable, string, max 100 |
| `evacuation_center_id` | nullable, exists `evacuation_centers.id` |
| `disaster_incident_id` | nullable, exists `disaster_incidents.id` |
| `unit_cost` | nullable, numeric, min 0 |
| `notes` | nullable, string |
| `transaction_date` | nullable, date |

`expiring-soon` accepts `days` (default 30).

Every movement writes an `inventory_transactions` row and adjusts
`relief_items.quantity`; stock-out and distribute refuse quantities above the
available stock.

### 6.7 Responders

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/responders` | `view responders` |
| `POST` | `/api/responders` | `create responders` |
| `GET` | `/api/responders/{id}` | `view responders` |
| `PUT` | `/api/responders/{id}` | `edit responders` |
| `DELETE` | `/api/responders/{id}` | `delete responders` |
| `GET` | `/api/responders/statistics` | `view responders` |
| `PATCH` | `/api/responders/{id}/status` | `update responder status` |

**Create body**

| Field | Rules |
| --- | --- |
| `employee_id` | required, string, max 50, unique `responders.employee_id` |
| `name` | required, string, max 255 |
| `email` | required, email, unique `responders.email` |
| `phone` | nullable, string, max 20 |
| `specialization` | required, in `medical, fire, rescue, police, logistics, coordination, communications` |
| `rank` | nullable, string, max 50 |
| `status` | sometimes, in `available, on_duty, off_duty, on_leave, inactive` |
| `address` | nullable, string |
| `date_of_birth` | nullable, date |
| `certifications` | nullable, array of strings |
| `equipment` | nullable, array of strings |
| `user_id` | nullable, exists `users.id` |
| `hired_at` | nullable, date |

**`status` body**: `status` (required, in `available, on_duty, off_duty, on_leave, inactive`).

### 6.8 Volunteers

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/volunteers` | `view volunteers` |
| `POST` | `/api/volunteers` | `create volunteers` |
| `GET` | `/api/volunteers/{id}` | `view volunteers` |
| `PUT` | `/api/volunteers/{id}` | `edit volunteers` |
| `DELETE` | `/api/volunteers/{id}` | `delete volunteers` |
| `GET` | `/api/volunteers/statistics` | `view volunteers` |
| `PATCH` | `/api/volunteers/{id}/status` | `update volunteer status` |

**Create body**

| Field | Rules |
| --- | --- |
| `name` | required, string, max 255 |
| `email` | required, email, unique `volunteers.email` |
| `phone` | nullable, string, max 20 |
| `address` | nullable, string |
| `date_of_birth` | nullable, date |
| `gender` | nullable, in `male, female, other` |
| `skills` | nullable, array of strings |
| `availability` | nullable, array |
| `emergency_contact_name` | nullable, string, max 255 |
| `emergency_contact_phone` | nullable, string, max 20 |
| `notes` | nullable, string |

**`status` body**: `status` (required, in `available, busy, inactive, on_leave`).

### 6.9 Volunteer assignments

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/volunteer-assignments` | `view volunteers` |
| `POST` | `/api/volunteer-assignments` | `create volunteers` |
| `GET` | `/api/volunteer-assignments/{id}` | `view volunteers` |
| `PUT` | `/api/volunteer-assignments/{id}` | `edit volunteers` |
| `DELETE` | `/api/volunteer-assignments/{id}` | `delete volunteers` |

**Create body**

| Field | Rules |
| --- | --- |
| `volunteer_id` | required, exists `volunteers.id` |
| `disaster_incident_id` | required, exists `disaster_incidents.id` |
| `role` | required, in `team_leader, medical, logistics, rescue, communication, general` |
| `status` | sometimes, in `assigned, active, completed, cancelled` |
| `notes` | nullable, string |

### 6.10 Affected persons

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/affected-persons` | `view affected persons` |
| `POST` | `/api/affected-persons` | `create affected persons` |
| `GET` | `/api/affected-persons/{id}` | `view affected persons` |
| `PUT` | `/api/affected-persons/{id}` | `edit affected persons` |
| `DELETE` | `/api/affected-persons/{id}` | `delete affected persons` |
| `GET` | `/api/affected-persons/statistics` | `view affected persons` |
| `PATCH` | `/api/affected-persons/{id}/condition` | `update condition` |
| `POST` | `/api/affected-persons/{id}/assign-center` | `assign evacuation center` |

**Create body**

| Field | Rules |
| --- | --- |
| `disaster_incident_id` | required, exists `disaster_incidents.id` |
| `name` | required, string, max 255 |
| `age` | nullable, integer, 0–150 |
| `gender` | nullable, in `male, female, other` |
| `contact_phone` | nullable, string, max 20 |
| `address` | nullable, string |
| `condition` | sometimes, in `safe, injured, critical, missing, deceased, rescued` |
| `medical_notes` | nullable, string |
| `family_contact_name` | nullable, string, max 255 |
| `family_contact_phone` | nullable, string, max 20 |
| `evacuation_center_id` | nullable, exists `evacuation_centers.id` |
| `notes` | nullable, string |

**`condition` body**: `condition` (required, same enum).
**`assign-center` body**: `evacuation_center_id` (required, exists
`evacuation_centers.id`).

### 6.11 Announcements

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/announcements` | `view announcements` |
| `POST` | `/api/announcements` | `create announcements` |
| `GET` | `/api/announcements/{id}` | `view announcements` |
| `PUT` | `/api/announcements/{id}` | `edit announcements` |
| `DELETE` | `/api/announcements/{id}` | `delete announcements` |
| `GET` | `/api/announcements/statistics` | `view announcements` |
| `POST` | `/api/announcements/{id}/publish` | `publish announcements` |

**Create body**

| Field | Rules |
| --- | --- |
| `title` | required, string, max 255 |
| `content` | required, string |
| `type` | required, in `alert, advisory, notice, update, evacuation, weather` |
| `priority` | required, in `low, medium, high, critical` |
| `audience` | required, in `public, responders, staff, volunteers, affected` |
| `is_published` | sometimes, boolean |
| `published_at` | nullable, date |
| `expires_at` | nullable, date, after `published_at` |
| `disaster_incident_id` | nullable, exists `disaster_incidents.id` |
| `metadata` | nullable, array |

### 6.12 Audit logs

| Method | Endpoint | Permission |
| --- | --- | --- |
| `GET` | `/api/audit-logs` | `view audit logs` |
| `GET` | `/api/audit-logs/statistics` | `view audit logs` |

Read-only view over `spatie/laravel-activitylog` entries: `description`,
`subject_type`, `subject_id`, `causer`, `properties`, `created_at`.

---

## 7. Workflows

### Assistance request

```
pending ──approve──► approved ──dispatch──► dispatched ──complete──► completed
   │                     │
   └──reject──► rejected └──reject──► rejected
```

`cancelled` is reachable from `pending` and `approved` through a general update.

### Disaster incident status

```
reported ► verified ► active ► responding ► contained ► resolved
```

### Emergency report status

```
pending ► assigned ► in_progress ► resolved
```

`cancelled` is reachable from `pending` and `assigned`.

### Responder / volunteer status

Responders: `available`, `on_duty`, `off_duty`, `on_leave`, `inactive`.
Volunteers: `available`, `busy`, `inactive`, `on_leave`.

---

## 8. Quick start

```bash
# 1. Log in
TOKEN=$(curl -s -X POST http://localhost:8000/api/auth/login \
  -H 'Accept: application/json' -H 'Content-Type: application/json' \
  -d '{"email":"admin@disasteraid.test","password":"password"}' \
  | python3 -c 'import sys,json;print(json.load(sys.stdin)["data"]["token"])')

# 2. Create a disaster incident
curl -X POST http://localhost:8000/api/disasters \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"disaster_type":"flood","title":"Flash flood","severity":"high",
       "latitude":14.5995,"longitude":120.9842}'

# 3. List with a filter
curl "http://localhost:8000/api/disasters?status=active&per_page=10" \
  -H "Accept: application/json" -H "Authorization: Bearer $TOKEN"
```

---

## 9. Implementation map

| Concern | Location |
| --- | --- |
| Routes | `backend/routes/api.php` |
| Controllers | `backend/app/Http/Controllers/API/V1/` |
| Form requests (validation) | `backend/app/Http/Requests/API/V1/` |
| Response shaping | `backend/app/Http/Resources/API/V1/` |
| Business logic | `backend/app/Services/` |
| Data access | `backend/app/Repositories/` |
| Authorization | `backend/app/Policies/`, `AppServiceProvider` |
| Error envelope | `backend/bootstrap/app.php` |
| Permissions and roles | `backend/database/seeders/RolesAndPermissionsSeeder.php` |
| Frontend client | `frontend/src/services/` |
