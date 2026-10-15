# Davethan CRM — First-party Core

A clean Laravel application boundary, separate from the Worksuite application in `script/`. It contains no Worksuite source, Froiden Envato verifier, vendor installer, purchase-code fields, or licence bypasses.

## Included in this scaffold

- Laravel/PHP foundation
- First-run administrator creation, disabled after the first account exists
- Session login/logout, CSRF protection and throttled login/setup routes
- Dashboard and first-party Leads CRUD with search, pagination and validation
- Dedicated users/leads migrations and a responsive starter UI

## Deployment checklist

1. Use a separate, empty database. Never point this core at the existing Worksuite database.
2. Copy `.env.example` to `.env`; set a strong database password and production APP_URL.
3. Run `composer install --no-dev --optimize-autoloader`, `php artisan key:generate`, and `php artisan migrate --force`.
4. Ensure `storage/` and `bootstrap/cache/` are writable by PHP.
5. Point a separate staging vhost/document root at `core/public`; use HTTPS before opening `/setup`.
6. Create the first admin once. After the first account exists, `/setup` returns 404.

## Status

This is a scaffold milestone and has not yet been run through Composer, migrations or HTTP smoke tests. Do not call it deployment-ready until those checks pass. AI copilot, multi-tenancy, and remaining CRM modules are not yet implemented. The original app and production database are intentionally untouched.