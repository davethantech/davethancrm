# Worksuite Repository Audit — Initial Findings

Date: 2026-10-15  
Repository: `davethantech/davethancrm`  
Audit branch: `worksuite-upgrade-audit`

## Scope and limits

This is a read-only repository audit using the checked-in source. It is not a live VPS audit. No production files, database, or server configuration have been changed. The live URL has not been authenticated or inspected from the server side.

## Confirmed architecture

- The Laravel application lives in `script/`, not the repository root.
- `script/composer.json` declares Laravel Framework `^10.0`, PHP `^8.1`, Laravel Modules, and Worksuite/Froiden-related dependencies such as `froiden/envato` and `froiden/laravel-installer`.
- `script/package.json` uses Laravel Mix 6 / Webpack 5 to build the frontend assets.
- `script/routes/` contains web, public, settings, API, and channel route files.
- `script/.gitignore` excludes the normal `.env` file but does not exclude `.env.dev`.

## Priority findings

### P0 — Tracked environment configuration needs a security review

`script/.env.dev` is tracked in Git. Its variable names include database, application, JWT, mail, and third-party integration settings. A safe automated scan found non-empty, non-obvious-placeholder values in several sensitive/account-related fields. The values were deliberately not printed.

Actions:
1. Treat those values as potentially exposed until verified.
2. Rotate any real database passwords, application/JWT secrets, mail credentials, and third-party keys found in that file or its Git history.
3. Remove the environment file from future repository commits and replace it with a value-free example template.
4. If any real secret was committed, removing the current file is not enough: revoke/rotate the secret and review Git history and access logs.

Do not paste secrets into issues, chat, or commit messages.

### P1 — Production web root must be verified

The live URL currently includes `/public/login`. That does not prove the server is misconfigured, but it is a signal to inspect the virtual-host document root. Laravel's web root should point to `script/public` (or the equivalent deployed `public` directory); the project root must not be directly served. Preserve existing Nginx/Apache sites and other VPS services when changing this.

### P1 — Runtime compatibility is not yet established

The Composer manifest allows PHP `^8.1`, while the vendor's current installation guide states PHP `>=8.2`. Confirm the exact Worksuite release, installed dependency lock file, and actual PHP CLI/FPM versions before changing PHP or running Composer updates. Do not do a blanket dependency upgrade against production.

### P1 — Dependencies and runtime cannot yet be validated

The repository contains `script/composer.json` and `script/package-lock.json`; the current audit has not run Composer, npm, Laravel boot, tests, migrations, or database checks in an isolated runtime. No claim is made that the app currently passes these checks.

### P2 — Feature audit is next

Inventory routes/controllers/models/migrations/modules and map existing CRM workflows before adding features. Implement additions through isolated custom code, permissions/policies, migrations, and tests. Keep `main` unchanged until the branch is reviewed.

## Licensing boundary

This audit does not remove or bypass Worksuite/Froiden/Envato license or purchase verification. Deployment troubleshooting and independently authored extensions can proceed without altering that protection.

## VPS deployment gate

Before deployment:
- Create and verify a database backup and file backup.
- Confirm PHP CLI and PHP-FPM versions/extensions, Composer version, database version/connectivity, document root, TLS, storage/cache write permissions, cron/queue configuration, and Laravel logs.
- Keep `APP_DEBUG=false` in production and keep `.env` outside public web access.
- Test in staging and document rollback.
- Do not run migrations, replace files, or change web-server configuration until the target path, database, and rollback are confirmed.
