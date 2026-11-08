---
tags: [setup, installation]
---

# Installation

Related notes: [[README]] · [[docs/setup/configuration]] ·
[[docs/development/testing]] · [[docs/operations/docker]]

## Prerequisites

| Requirement | Version | Notes |
| --- | --- | --- |
| PHP | 8.4+ | with `pdo_pgsql`, `mbstring`, `gd`, `intl`, `zip`, `bcmath` |
| Composer | 2.x | |
| Node.js | 20+ | with npm |
| PostgreSQL | 14+ | 16 recommended |

Alternatively, skip the local toolchain and use
[[docs/operations/docker]] — a container runtime is enough.

## 1. Clone the repository

```bash
git clone https://github.com/4r6ie/DisasterAid_System.git
cd DisasterAid_System
```

## 2. Install dependencies

```bash
cd backend && composer install && cd ..
cd frontend && npm install && cd ..
```

## 3. Create the environment file

```bash
cp backend/.env.example backend/.env
```

Point `DB_*` at your PostgreSQL server and set `CACHE_STORE=file`
(Laravel 11+ defaults the cache store to `database`, which has no table here).
See [[docs/setup/configuration]] for every variable.

## 4. Generate the application key

```bash
cd backend && php artisan key:generate && cd ..
```

## 5. Create the database

```bash
psql -U postgres -c "CREATE DATABASE disasteraid;"
```

Any PostgreSQL client works; the database name must match `DB_DATABASE`.

## 6. Migrate and seed

```bash
cd backend
php artisan migrate --seed
```

The seeder creates five roles (`super_admin`, `admin`, `responder`,
`volunteer`, `user`), the full permission catalogue, and an administrator
account from `ADMIN_EMAIL` / `ADMIN_PASSWORD` (defaults
`admin@disasteraid.test` / `password`).

To rebuild from scratch:

```bash
php artisan migrate:fresh --seed
```

## 7. Run the API

```bash
cd backend
php artisan serve
```

The API is served from `http://localhost:8000/api`.

## 8. Run the frontend

```bash
cd frontend
npm run dev
```

The UI is served from `http://localhost:5173`. Vite proxies `/api` to
`http://localhost:8000` by default; set `VITE_API_TARGET` to override.

## Seeded accounts

The seeder creates one account per role so every permission path can be
exercised by hand.

| Email | Password | Role | Can |
| --- | --- | --- | --- |
| `admin@disasteraid.test` | `password` | `super_admin` | everything |
| `officer@disasteraid.test` | `password` | `admin` | everything except user management |
| `responder@disasteraid.test` | `password` | `responder` | dashboard, disasters, emergencies, assistance, evacuation, inventory, affected persons, announcements |
| `volunteer@disasteraid.test` | `password` | `volunteer` | dashboard, disasters, assistance, evacuation, inventory, announcements |
| `resident@disasteraid.test` | `password` | `user` | dashboard, disasters, assistance, announcements |

The super administrator uses `ADMIN_EMAIL` / `ADMIN_PASSWORD`; the four demo
accounts use `DEMO_PASSWORD` and are skipped in production unless
`SEED_DEMO_USERS=true`.

Change every password immediately in any real deployment.

## Troubleshooting

| Symptom | Cause and fix |
| --- | --- |
| `could not find driver` | `pdo_pgsql` is missing — enable it in `php.ini` |
| `relation "cache" does not exist` | Set `CACHE_STORE=file` in `backend/.env` |
| `SQLSTATE[08006] could not translate host name` | `DB_HOST` is wrong or PostgreSQL is not running |
| `419 Page Expired` | `APP_KEY` is missing — run `php artisan key:generate` |
| Vite proxy fails | Set `VITE_API_TARGET` to the backend URL |
