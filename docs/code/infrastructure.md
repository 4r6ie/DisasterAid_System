---
tags: [code, infrastructure, operations]
---

# Infrastructure code map

Related notes: [[Code Map]] · [[docs/operations/docker]] · [[docs/setup/configuration]]

## Docker images

| File | Purpose |
| --- | --- |
| `infrastructure/docker/php/Dockerfile` | PHP 8.4 CLI + composer + pdo_pgsql, builds `backend/` |
| `infrastructure/docker/node/Dockerfile` | Node 20 + `npm ci`, runs the Vite dev server |
| `infrastructure/docker/nginx/` | Nginx configuration (placeholder) |
| `infrastructure/docker/postgres/` | PostgreSQL initialization (placeholder) |

## Orchestration

| File | Purpose |
| --- | --- |
| `docker-compose.yml` | postgres + backend + frontend on one network |
| `backend/router.php` | Front-controller router used by `php -S` |
| `backend/bootstrap/app.php` | Middleware, rate limit, JSON error envelope |
| `frontend/vite.config.ts` | Dev server and `/api` proxy |
| `frontend/tailwind.config.js` | Theme and content paths |
| `.dockerignore` | Keeps node_modules, vendor and .git out of the build context |
| `.gitignore` | Ignores dependencies, env files, build output |
