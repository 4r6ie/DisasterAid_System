---
tags: [home, moc]
---

# DisasterAid System

A disaster response and emergency assistance management platform. It gives
response agencies a single place to record disasters, triage emergency
reports, coordinate responders and volunteers, run evacuation centres, manage
relief inventory and publish public advisories.

The project is a monorepo:

| Directory | Contents |
| --- | --- |
| `backend/` | Laravel 13 JSON API — Sanctum authentication, Spatie roles and permissions, activity logging |
| `frontend/` | React 18 + TypeScript SPA — Vite, Tailwind CSS, Axios, Zustand, Recharts, Leaflet |
| `infrastructure/` | Docker images, compose stack and tooling |
| `docs/` | Architecture, database, API, setup, testing and operations documentation |

> **The repository is public.** It is not production-ready as shipped: set
> `APP_KEY`, change every credential and disable debug mode before exposing it
> to a network. See [Security](#security).

## Documentation

| Document | Contents |
| --- | --- |
| [Code map](docs/code/Code%20Map.md) | One note per source file, generated from the code |
| [Installation](docs/setup/installation.md) | Prerequisites, install, migrate, seed, run |
| [Configuration](docs/setup/configuration.md) | Every environment variable and config file |
| [Architecture](docs/architecture/overview.md) | Layering, request lifecycle, authorisation |
| [Database](docs/database/schema.md) | Tables, relationships, conventions |
| [API reference](docs/api/endpoints.md) | Endpoints, request bodies, permissions, workflows |
| [Testing](docs/development/testing.md) | Backend and frontend checks |
| [Docker operations](docs/operations/docker.md) | Compose commands and troubleshooting |

## Features

| Module | Capabilities |
| --- | --- |
| Dashboard | KPI cards, 30-day incident trends, severity and type charts, incident map |
| Disaster incidents | CRUD, severity and status workflow, casualty counts, responder assignment, nearby lookup |
| Emergency reports | CRUD, priority and status workflow, responder assignment, nearby lookup |
| Assistance requests | CRUD, approve / dispatch / complete / reject workflow, requester tracking |
| Evacuation centres | CRUD, capacity and occupancy, nearby lookup |
| Relief inventory | CRUD, stock in / stock out / distribute, low-stock and expiry tracking |
| Responders | CRUD, specialization and availability status |
| Volunteers | CRUD, skills and availability status |
| Volunteer assignments | Assign volunteers to incidents with a role and status |
| Affected persons | Registration, condition tracking, evacuation-centre assignment |
| Announcements | Publish advisories to a selected audience |
| Audit logs | Read-only activity trail with filtering |

## Requirements

| Requirement | Version |
| --- | --- |
| PHP | 8.4+ with `pdo_pgsql`, `mbstring`, `gd`, `intl`, `zip`, `bcmath` |
| Composer | 2.x |
| Node.js | 20+ |
| PostgreSQL | 14+ (16 recommended) |

A container runtime is sufficient if you would rather not install PHP or Node
locally.

## Getting started

### Docker

```bash
docker compose up -d --build
docker compose exec backend php artisan migrate --seed
```

Open http://localhost:5173 and sign in with the super administrator account
configured through `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env`.

### Local

```bash
cd backend
composer install
php artisan key:generate
php artisan migrate --seed

cd ../frontend
npm install
npm run dev
```

The API is served from http://localhost:8000 and the UI from
http://localhost:5173.

## Configuration

The application reads `backend/.env`; Docker Compose reads `.env` at the
repository root. Neither file is committed — both are created per machine,
and the full variable list lives in
[docs/setup/configuration.md](docs/setup/configuration.md).

| Variable | Where | Description |
| --- | --- | --- |
| `APP_KEY` | backend | Generated with `php artisan key:generate` |
| `APP_ENV` / `APP_DEBUG` | backend | `production` / `false` when deployed |
| `DB_*` | backend | PostgreSQL connection |
| `CACHE_STORE` | backend | Must be `file` |
| `ADMIN_EMAIL` / `ADMIN_PASSWORD` | backend | Super administrator account |
| `SEED_DEMO_USERS` / `DEMO_PASSWORD` | backend | Opt-in demo accounts |
| `CORS_ALLOWED_ORIGINS` | backend | Allowed browser origins; empty blocks all |
| `APP_PORT` / `DB_PORT` | root | Host ports used by Compose |

## API

A stateless JSON interface under `/api`, authenticated with Sanctum bearer
tokens. Every endpoint is permission-gated; the full reference — endpoints,
request bodies, validation rules, permissions and workflows — is in
[docs/api/endpoints.md](docs/api/endpoints.md).

## Security

- **Authentication** — Sanctum personal access tokens. Every route except
  registration, login and password reset requires `auth:sanctum`.
- **Authorisation** — permission-based. Controllers call `$this->authorize()`;
  policies map each ability to one of 54 permissions held by five roles.
- **Input validation** — every write goes through a form request; `per_page`
  is clamped to 100.
- **Error handling** — failures return a consistent envelope; stack traces are
  only exposed when `APP_DEBUG=true`.
- **Headers** — the `SecurityHeaders` middleware sets `X-Content-Type-Options`,
  `X-Frame-Options`, `Referrer-Policy`, `Cross-Origin-Resource-Policy`, a
  restrictive `Content-Security-Policy`, and HSTS over HTTPS.
- **Rate limiting** — 60 requests per minute per IP on the API group.
- **Passwords** — bcrypt. Reset tokens are stored server-side and expire.
- **Audit trail** — `spatie/laravel-activitylog` on the core models.

### Credential rules

| Variable | Rule |
| --- | --- |
| `backend/.env` | Never committed |
| `APP_KEY` | Generated locally; never committed |
| `ADMIN_PASSWORD` | Required in production — the seeder refuses to run without it |
| `DEMO_PASSWORD` | Required when `SEED_DEMO_USERS=true`; no default |
| Demo accounts | Never created in production |
| `APP_DEBUG` | `false` anywhere public |

### Deployment checklist

- [ ] `APP_ENV=production` and `APP_DEBUG=false`
- [ ] `APP_KEY` generated and stored securely
- [ ] `ADMIN_PASSWORD` and `DB_PASSWORD` changed
- [ ] `SEED_DEMO_USERS=false`
- [ ] `CORS_ALLOWED_ORIGINS` restricted to the frontend origin
- [ ] Served over HTTPS
- [ ] PostgreSQL not exposed to the internet
- [ ] `storage/` and `bootstrap/cache/` not web-accessible
- [ ] Database and `APP_KEY` backed up

## Testing

```bash
cd backend && php artisan test                                  # 33 tests, 99 assertions
cd frontend && npm run lint && npx tsc --noEmit && npm run build
```

`RolesAndPermissionsTest` pins the permission catalogue and the exact
permission set of every role. See
[docs/development/testing.md](docs/development/testing.md).

## Project structure

```
DisasterAid_System/
├── backend/                     Laravel 13 JSON API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/V1/  Controllers (authorise then delegate)
│   │   │   ├── Middleware/         SecurityHeaders
│   │   │   ├── Requests/        Form requests with validation rules
│   │   │   └── Resources/       API resources (response shaping)
│   │   ├── Models/              Eloquent models
│   │   ├── Policies/            Permission checks used by the controllers
│   │   ├── Providers/           Service providers
│   │   ├── Repositories/        Data access layer
│   │   └── Services/            Business logic and workflows
│   ├── bootstrap/app.php        Application configuration, middleware, API errors
│   ├── config/                  Configuration files (including cors.php)
│   ├── database/                Migrations, factories and seeders
│   ├── routes/                  api.php, web.php, console.php
│   ├── tests/                   Pest feature and unit tests
│   ├── composer.json
│   ├── phpunit.xml
│   └── router.php               Front-controller router for `php -S`
├── frontend/                    React 18 + TypeScript SPA
│   ├── src/
│   │   ├── components/          Design-system primitives and shared components
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
│   ├── docker/postgres/         PostgreSQL initialization
│   └── scripts/generate-code-map.py
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

## License

MIT
