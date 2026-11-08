---
tags: [architecture]
---

# Architecture

Related notes: [[README]] · [[docs/database/schema]] · [[docs/api/endpoints]]

## Overview

DisasterAid System is a monorepo with two decoupled applications that talk
over a JSON API.

```
┌────────────────────┐        /api/*        ┌────────────────────┐
│   frontend/        │ ───────────────────► │   backend/         │
│   React 18 + Vite  │  Authorization:      │   Laravel 13       │
│   TypeScript       │  Bearer <sanctum>    │   PostgreSQL       │
└────────────────────┘                      └────────────────────┘
                                                     │
                                                     ▼
                                              ┌──────────────┐
                                              │  PostgreSQL  │
                                              └──────────────┘
```

The frontend is a single-page application. During development Vite proxies
`/api` to the backend; in production the built assets are served by any static
web server and the API is exposed separately.

## Backend layering

The backend is organised as a thin HTTP layer over a domain layer:

```
Controller  →  Service  →  Repository  →  Model
     │            │
     │            └── sets ownership, timestamps, generated reference numbers
     └── authorises with the matching Policy, then delegates
```

- **Controllers** (`app/Http/Controllers/Api/V1`) validate input through form
  requests, call `$this->authorize()` so the matching policy runs, and return
  API resources. They contain no business logic.
- **Requests** (`app/Http/Requests/Api/V1`) hold validation rules and
  authorization stubs.
- **Resources** (`app/Http/Resources/Api/V1`) shape responses so the API
  contract is stable regardless of the underlying schema.
- **Services** (`app/Services`) own workflows: generating reference numbers,
  stamping ownership (`reported_by`, `requested_by`), and orchestrating
  repositories.
- **Repositories** (`app/Repositories`) provide data access. `BaseRepository`
  offers the shared primitives; each repository adds its filtered queries.
- **Policies** (`app/Policies`) map abilities to Spatie permissions.

## Request lifecycle

1. `routes/api.php` applies the `api` middleware group and `auth:sanctum`.
2. `bootstrap/app.php` registers the middleware aliases and a JSON exception
   renderer so every error shares the response envelope.
3. The controller runs the form request, authorizes the action against a
   policy, and calls a service.
4. The service mutates the model through a repository and returns it.
5. The controller wraps the result in a resource and returns it.

## Response envelope

Every response — success or failure — uses the same shape:

```json
{ "success": true, "data": { } }
{ "success": false, "message": "The given data was invalid.", "errors": { } }
```

List endpoints add a `meta` object with `current_page`, `last_page`,
`per_page` and `total`.

## Authentication and authorization

- Authentication is stateless using **Laravel Sanctum** personal access tokens.
  Clients send `Authorization: Bearer <token>`.
- Authorization is permission-based. Roles are collections of permissions
  seeded by `database/seeders/RolesAndPermissionsSeeder.php`:
  `super_admin`, `admin`, `responder`, `volunteer`, `user`.
- Controllers call `$this->authorize('ability', $model)`; the matching policy
  checks `$user->can('<permission>')`.
- Model-less abilities (`view dashboard`, `view audit logs`) are Gate
  definitions registered in `AppServiceProvider`.

## Frontend organization

```
src/
├── components/ui/       Design-system primitives (Button, Input, Card, ...)
├── components/shared/   Reusable components (DataTable, Modal, Toast, ...)
├── hooks/               usePaginated (data fetching), useAuthorization
├── layouts/             Application shell with sidebar navigation
├── pages/               One screen per route
├── routes/              Router definition and route guards
├── services/            Axios instance and typed resource services
├── stores/              Zustand auth store
├── types/               Shared TypeScript contracts
└── utils/               Formatting helpers and option constants
```

`ResourcePage` is a configuration-driven CRUD screen. Each module supplies a
config (columns, filters, form fields, service calls) and the component
provides search, filtering, pagination, sorting, create/edit modals and delete
confirmation. Modules with bespoke workflows (assistance requests, inventory)
build on the same primitives.

## Cross-cutting concerns

- **Validation errors** are rendered by `bootstrap/app.php` into the shared
  envelope with a `422` status.
- **Audit trail**: `spatie/laravel-activitylog` records changes on the core
  models; `AuditLogController` exposes a read-only view.
- **Pagination safety**: `per_page` is clamped to 100 by `Controller::perPage()`.
