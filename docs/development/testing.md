---
tags: [development, testing]
---

# Testing and quality checks

Related notes: [[README]] · [[docs/setup/installation]] ·
[[docs/operations/docker]]

## Backend

The suite uses [Pest](https://pestphp.com) and runs against an **in-memory
SQLite** database, so it never touches your development data.

```bash
cd backend

php artisan test        # runs the whole suite
./vendor/bin/pest       # same, directly
composer test           # composer script alias
```

Run a single file or filter by name:

```bash
php artisan test --filter=DisasterIncidentTest
```

Feature tests cover authentication, authorization, disaster incident
management, the assistance-request workflow and the dashboard endpoints.

`RolesAndPermissionsTest` pins the seeder: it asserts the 54-name permission
catalogue, the exact permission set of all five roles, the role hierarchy, and
that every permission reaches at least one role. It also checks the audit-log
endpoints as `admin` (allowed) and `user` (forbidden), so the role wiring is
verified end to end rather than only in the database.

### Code style

`composer.json` ships Laravel Pint:

```bash
cd backend
./vendor/bin/pint --test   # report only
./vendor/bin/pint          # fix
```

## Frontend

```bash
cd frontend

npm run lint              # ESLint (flat config), fails on any warning
npx tsc --noEmit          # TypeScript, no output means clean
npm run build             # tsc + production Vite build
```

## Full verification

Run these in order before committing:

```bash
cd backend && php artisan test && cd ..
cd frontend && npx tsc --noEmit && npm run lint && npm run build && cd ..
```

Expected results:

| Check | Expected |
| --- | --- |
| `php artisan test` | `Tests: 33 passed (99 assertions)` |
| `npx tsc --noEmit` | no output |
| `npm run lint` | no output |
| `npm run build` | `✓ built in ...` |

## Notes

- `phpunit.xml` pins `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:` with
  `force="true"`. The `<server>` entries are required as well — `<env>` alone
  does not overwrite `$_SERVER`, and Docker Compose sets `DB_*` there.
- Do not run `php artisan test` against the development database; if the
  `users` table comes back empty afterwards, the SQLite override has been lost.
- The suite regenerates `backend/bootstrap/cache/packages.php` and
  `services.php`; both are generated files and safe to delete.
