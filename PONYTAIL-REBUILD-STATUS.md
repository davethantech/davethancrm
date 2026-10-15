# Davethan CRM — Ponytail rebuild

## Scope of this branch
- Adds the project-level Ponytail engineering rules in `/AGENTS.md`.
- Adds low-risk, shared UI refinements in `script/resources/scss/ponytail-refinements.scss`, imported by `script/resources/scss/main.scss`.
- Refines the shared admin dashboard header spacing, tab overflow on small screens, widget menu sizing, form feedback, and refresh behavior in `script/resources/views/dashboard/admin.blade.php`.

## Licensing boundary
This source tree contains `froiden/envato`, purchase-verification views and configuration, and product-specific verification logic. This branch does not bypass purchase-code verification, remove proprietary notices, or relicense upstream Worksuite code. Before removing the purchase gate, confirm the repository owner has rights to modify and redistribute that commercial source without purchase verification. A lawful clean-room replacement is an alternative if those rights are unavailable.

Third-party authentication, authorization/roles, tenant isolation, CSRF protection and data-integrity safeguards must remain intact.

## Verification
Changes were made through GitHub source operations only. The PHP application, database, and browser build could not be executed in this environment. Run the documented checks locally before deployment:
1. `composer install` and `php artisan test` from `script/`.
2. `npm ci` and `npm run production` from `script/`.
3. Check route registration and test dashboard load for admin, employee, and client roles with a non-production database.
4. Verify dark mode, RTL, narrow viewports, date-range changes, and widget preferences.

Do not deploy this branch as production-ready until those checks pass.
