---
tags: [home, moc]
---

# DisasterAid System

A disaster response and emergency assistance management platform. It gives
response agencies a single place to record disasters, triage emergency
reports, coordinate responders and volunteers, run evacuation centres, manage
relief inventory and publish public advisories.

The repository is a monorepo containing two applications plus their
infrastructure and documentation:

- **`backend/`** — a Laravel 13 JSON API (Sanctum token authentication, Spatie
  roles & permissions, activity logging).
- **`frontend/`** — a React 18 + TypeScript single-page application (Vite,
  Tailwind CSS, Axios data layer, Zustand, Recharts and Leaflet).
- **`infrastructure/`** — Docker images and server configuration.
- **`docs/`** — the documentation notes indexed below.

---

## Documentation

| Note | Covers |
| --- | --- |
| [[Code Map]] | **Code map** — one note per source file, with its functions |
| [[docs/setup/installation]] | Prerequisites, install, migrate, seed, run locally |
| [[docs/setup/configuration]] | Every environment variable and config file |
| [[docs/architecture/overview]] | Layering, request lifecycle, authentication, frontend organization |
| [[docs/database/schema]] | Tables, relationships, conventions, seeders |
| [[docs/api/endpoints]] | Complete API reference — endpoints, bodies, permissions |
| [[docs/development/testing]] | Backend and frontend checks, expected results |
| [[docs/operations/docker]] | Docker Compose operations and troubleshooting |

---

## Quick start

With Docker (recommended):

```bash
docker compose up -d --build
docker compose exec backend php artisan migrate --seed
```

Then open http://localhost:5173 and sign in as `admin@disasteraid.test` /
`password`. See [[docs/operations/docker]] for details.

Without Docker:

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

cd ../frontend
npm install
npm run dev
```

See [[docs/setup/installation]] for the full walkthrough and troubleshooting.

---

## Feature overview

| Module | Capabilities |
| --- | --- |
| Dashboard | KPI cards, 30-day incident trends, severity/type charts, incident map |
| Disaster incidents | CRUD, severity & status workflow, casualty counts, responder assignment, nearby lookup |
| Emergency reports | CRUD, priority & status workflow, responder assignment, nearby lookup |
| Assistance requests | CRUD, approve / dispatch / complete / reject workflow, requester tracking |
| Evacuation centres | CRUD, capacity & occupancy, nearby lookup |
| Relief inventory | CRUD, stock in / stock out / distribute, low-stock and expiry tracking |
| Responders | CRUD, specialization and availability status |
| Volunteers | CRUD, skills and availability status |
| Volunteer assignments | Assign volunteers to incidents with a role and status |
| Affected persons | Registration, condition tracking, evacuation-centre assignment |
| Announcements | Publish advisories to a chosen audience |
| Audit logs | Read-only activity trail with filtering |

Every endpoint is permission-gated. The permission names enforced by the API
are exactly the ones granted by `backend/database/seeders/RolesAndPermissionsSeeder.php`.

---

## Project structure

```
DisasterAid_System/
├── backend/                     Laravel 13 JSON API
│   ├── app/
│   │   ├── Actions/             Single-action classes (reserved)
│   │   ├── Enums/               Domain enumerations (reserved)
│   │   ├── Events/              Domain events (reserved)
│   │   ├── Exceptions/          Custom exceptions (reserved)
│   │   ├── Http/
│   │   │   ├── Controllers/Api/V1/  Controllers (authorise then delegate)
│   │   │   ├── Requests/        Form requests with validation rules
│   │   │   └── Resources/       API resources (response shaping)
│   │   ├── Jobs/                Queue jobs (reserved)
│   │   ├── Listeners/           Event listeners (reserved)
│   │   ├── Models/              Eloquent models
│   │   ├── Policies/            Permission checks used by the controllers
│   │   ├── Providers/           Service providers
│   │   ├── Repositories/        Data access layer
│   │   └── Services/            Business logic and workflows
│   ├── bootstrap/app.php        Application configuration, middleware, API errors
│   ├── config/                  Configuration files
│   ├── database/                Migrations, factories and seeders
│   ├── routes/                  api.php, web.php, console.php
│   ├── tests/                   Pest feature and unit tests
│   ├── composer.json
│   ├── phpunit.xml
│   └── router.php               Front-controller router for `php -S`
├── frontend/                    React 18 + TypeScript SPA
│   ├── src/
│   │   ├── components/ui/       Design system primitives
│   │   ├── components/shared/   Reusable feature components
│   │   ├── hooks/               Data-fetching and authorization hooks
│   │   ├── layouts/             Application shell
│   │   ├── pages/               Route-level screens
│   │   ├── routes/              Router definition
│   │   ├── services/            API client and resource services
│   │   ├── stores/              Zustand state
│   │   ├── types/               Shared TypeScript types
│   │   └── utils/               Formatting and constants
│   ├── package.json
│   └── vite.config.ts
├── infrastructure/
│   ├── docker/php/Dockerfile    PHP 8.4 CLI image (composer + pdo_pgsql)
│   ├── docker/node/Dockerfile   Node 20 image for the Vite dev server
│   ├── docker/nginx/            Nginx configuration
│   └── docker/postgres/         PostgreSQL initialization
├── docs/
│   ├── code/                    Generated code map — one note per source file
│   ├── setup/                   Installation and configuration
│   ├── architecture/            System architecture
│   ├── database/                Data model
│   ├── api/                     API reference
│   ├── development/             Testing and quality checks
│   └── operations/              Docker and deployment
├── docker-compose.yml
└── README.md
```

---

## Security notes

- Permissions are enforced in the controllers via `$this->authorize()`; the
  policies in `app/Policies` map abilities to Spatie permissions.
- `per_page` is clamped to 100 to bound query cost.
- Passwords are hashed with bcrypt; tokens are Sanctum personal access tokens.
- Audit logging is provided by `spatie/laravel-activitylog`.
- Never commit `.env` — it is listed in `.gitignore`.

---

## License

MIT
