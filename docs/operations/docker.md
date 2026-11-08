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
| `postgres` | — | PostgreSQL 16, data in the `postgres_data` volume |
| `backend` | http://localhost:8000 | PHP 8.4 built from `infrastructure/docker/php/Dockerfile` |
| `frontend` | http://localhost:5173 | Vite dev server, proxies `/api` to `backend:8000` |

Check status:

```bash
docker compose ps
docker compose logs -f backend
```

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
