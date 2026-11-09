---
tags: [operations, docker]
---

# Docker operations

Related notes: [[README]] · [[docs/setup/installation]] ·
[[docs/setup/configuration]]

Everything runs through `docker-compose.yml` at the repository root. No local
PHP or Node install is required.

## Start the stack

```bash
docker compose up -d --build
```

| Service | URL | Notes |
| --- | --- | --- |
| `nginx` | http://localhost | Public gateway — proxies `/` to the frontend and `/api` to the backend |
| `adminer` | http://localhost:8080 | Browser database admin (Adminer) for PostgreSQL |
| `postgres` | — | PostgreSQL 16, data in the `postgres_data` volume |
| `backend` | http://localhost:8000 | PHP 8.4 built from `infrastructure/docker/php/Dockerfile` |
| `frontend` | http://localhost:5173 | Vite dev server, proxies `/api` to `backend:8000` |

The nginx gateway is the single entry point: open
[http://localhost](http://localhost) and the SPA and API are served from the
same origin, so no CORS or mixed-port issues arise. The backend and frontend
ports remain available for direct access during development.

Check status:

```bash
docker compose ps
docker compose logs -f backend
```

## Viewing the database

Adminer is included in the stack and is the easiest way to inspect the
database — no `psql` or desktop client needed:

```bash
docker compose up -d adminer
```

Open http://localhost:8080 and log in with:

| Field | Value |
| --- | --- |
| System | PostgreSQL |
| Server | `postgres` (prefilled) |
| Username | `disasteraid` |
| Password | `disasteraid` |
| Database | `disasteraid` |

The server and database are prefilled through `ADMINER_DEFAULT_SERVER` and
`ADMINER_DEFAULT_DB`, so only the credentials need entering.

Any PostgreSQL client works too, since the container publishes port 5432:

| Field | Value |
| --- | --- |
| Host | `localhost` |
| Port | `5432` |
| Username | `disasteraid` |
| Password | `disasteraid` |
| Database | `disasteraid` |

Change `DB_PASSWORD` in the root `.env` before exposing the database to any
network other than localhost.

## Migrate and seed

```bash
docker compose exec backend php artisan migrate --seed
```

The seed account is `admin@disasteraid.test` / `password`.

## Rebuild after a dependency change

```bash
docker compose up -d --build
```

If the frontend fails with `MODULE_NOT_FOUND` from `rollup/dist/native.js`,
the named `node_modules` volume is stale — remove it and rebuild:

```bash
docker compose down
docker volume rm disasteraid_system_frontend_node_modules
docker compose up -d --build
```

## Stop and clean up

```bash
docker compose down                    # keep data
docker compose down -v                 # also drop the database volume
```

## Volumes

| Volume | Contents |
| --- | --- |
| `postgres_data` | PostgreSQL data files |
| `frontend_node_modules` | The frontend's own Linux `node_modules` |

The backend image carries its own `vendor/`; the frontend image its own
`node_modules/`. Neither is taken from the host, because npm and Composer
install platform-specific binaries.

## Ports

| Variable | Default | Override |
| --- | --- | --- |
| `WEB_PORT` | `80` | in the root `.env` — host port for the nginx gateway |
| `ADMINER_PORT` | `8080` | in the root `.env` — host port for the Adminer DB UI |
| `APP_PORT` | `8000` | in the root `.env` |
| `DB_PORT` | `5432` | in the root `.env` |

A `Bind for 0.0.0.0:5432 failed: port is already allocated` error means
another PostgreSQL (often a leftover `docker run` container) holds the port.
Remove it first:

```bash
docker ps -a --format "{{.Names}} {{.Ports}}"
docker rm -f <name>
```

## Serving in production

The dev stack uses `php -S` with `backend/router.php` and the Vite dev server.
For production, build the frontend (`npm run build` → `frontend/dist`) and
serve it from a static web server such as Nginx, then point `/api` at the
Laravel application.

## Troubleshooting

| Symptom | Cause and fix |
| --- | --- |
| `could not translate host name "postgres"` | A stale container is not on the compose network — run `docker compose down` then `up -d` |
| `Conflict. The container name ... is already in use` | Remove the orphaned container, or run `up -d --remove-orphans` |
| Build fails with `Could not open input file: artisan` | The PHP Dockerfile must use `composer install --no-scripts` |
| `MODULE_NOT_FOUND` from rollup | Stale `node_modules` volume — see above |
| Edits to `frontend/` are not reflected by the dev server | Docker Desktop on Windows does not forward inotify events from bind mounts, so Vite never sees host-side edits. `frontend/vite.config.ts` enables polling to work around this — after changing that file, restart the container: `docker compose restart frontend` |
