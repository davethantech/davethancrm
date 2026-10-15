# Davethan CRM — First-party Core

A clean Laravel application boundary, separate from the Worksuite application in `script/`. It contains no Worksuite source, Froiden Envato verifier, vendor installer, purchase-code fields, or licence bypasses.

## Included in this scaffold

- Laravel/PHP foundation
- First-run administrator creation, disabled after the first account exists
- Session login/logout, CSRF protection and throttled login/setup routes
- Dashboard, Leads CRUD, Companies directory CRUD, and Contacts CRUD linked to companies
- Search, pagination, validation, dedicated migrations, and responsive Blade views
- GitHub Actions CI validates Composer, route registration and PHPUnit feature tests

## Deployment checklist

1. Use a separate, empty database. Never point this core at the existing Worksuite database.
2. Copy `.env.example` to `.env`; set a strong database password and production APP_URL.
3. Run `composer install --no-dev --optimize-autoloader`, `php artisan key:generate`, and `php artisan migrate --force`.
4. Ensure `storage/` and `bootstrap/cache/` are writable by PHP.
5. Point a separate staging vhost/document root at `core/public`; use HTTPS before opening `/setup`.
6. Create the first admin once. After the first account exists, `/setup` returns 404.

## Status

The current branch passes GitHub Actions Composer validation, route registration and PHPUnit feature tests (11 tests, 19 assertions), including the Contacts module and its company relationship. This is still an early core milestone, not production-ready: it has not been deployed to staging, and multi-tenancy, roles/permissions, AI Copilot, and the remaining CRM modules are not implemented. The original app and production database are intentionally untouched.