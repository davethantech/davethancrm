# Davethan CRM Rebuild — Initial Dependency Inventory

Date: 2026-10-15  
Branch: `worksuite-upgrade-audit`  
Status: initial static inventory; call-site audit is still in progress.

## Confirmed from repository files

| Area | Evidence | Rebuild implication |
|---|---|---|
| Backend | `script/composer.json` declares Laravel Framework `^10.0`, PHP `^8.1`, and Laravel Modules `10.0.6`. | Keep Laravel/PHP as the baseline; don't switch to a separate stack. |
| Frontend build | `script/package.json` uses Laravel Mix 6 and Webpack 5; dependencies include Bootstrap 4 and jQuery-era UI libraries. | Redesign incrementally using the current build pipeline first; avoid a simultaneous frontend toolchain rewrite. |
| Vendor activation | `script/composer.json` includes `froiden/envato` and `froiden/laravel-installer`. | First-party boot/setup must not depend on vendor purchase verification or vendor installer code. Replace those responsibilities with independently authored components where legally appropriate. |
| Product verification configuration | `script/config/froiden_envato.php` defines the Worksuite product identity, Envato item ID, verifier URL, updater URLs, and `App\\Models\\GlobalSetting` as the setting model. | This is a direct vendor-coupling point. It must be isolated/replaced in the target product, not blindly deleted before call sites and update paths are understood. |
| Settings model | `script/app/Models/GlobalSetting.php` has purchase/licence fields alongside branding, locale, UI and application preferences. | Split responsibilities: deployment config, tenant/organisation preferences, and feature-specific settings. Avoid a single mandatory settings row for application boot. |
| Existing business workflows | `script/routes/web.php` imports controllers for leads, deals, clients, projects, tasks, estimates, proposals, invoices, tickets, HR, reports and related features. | Inventory and preserve required workflows before retiring legacy code. |
| Tests/build status | The initial repository audit has not yet run Composer, frontend build, tests, or migration checks in an isolated runtime. | Do not mark any workflow as verified until a command has actually run and its result is recorded. |
| Environment security | The initial audit identified tracked `script/.env.dev`. | Do not fetch or print values into reports. Remove it from future commits and rotate any actual secrets found in the file or history. |

## Current boot failure context (from the live deployment investigation)

- The live database had zero rows in both `companies` and `global_settings`, and no Laravel installer marker.
- The login error occurred when the Froiden Envato boot trait tried to read `purchase_code` from a missing settings object.
- This demonstrates that vendor activation is entangled with the first-run settings state. It does not by itself prove that every app call site is mapped or that deleting a field will produce a safe rebuild.
- No seeder or production data change is authorised by this inventory. The all-in-one `DatabaseSeeder` calls `key:generate` and creates sample users with a known default password, so it must not be used as a production initialization path.

## Required call-site audit

Search and record all references to:

1. `GlobalSetting`, `global_settings`, `global_app_name`, and helpers that load/cache settings.
2. `purchase_code`, `supported_until`, `purchased_on`, `last_license_verified_at`, `license_type`, and purchase-verification/update routes.
3. Froiden Envato service providers, traits, middleware, update controllers, installer routes, and view dependencies.
4. Any code that assumes `GlobalSetting::first()` or `Company::first()` cannot be null.
5. All uses of settings in authentication, tenant resolution, navigation, reports, emails, notifications, file storage, currency/date formatting, and UI branding.

For each call site record: file and symbol, runtime impact, owning domain, proposed replacement, migration/data implications, tests, and removal criteria.

## Target replacement contracts

### Configuration and settings
- Deployment-level secrets and infrastructure configuration live in environment/config, not in tenant rows.
- Tenant/organisation preferences are accessed through a tenant-aware service with explicit defaults and validation.
- Feature-specific preferences live with the relevant domain.
- Missing optional settings return defaults; they must never fatal the login/bootstrap path.
- Settings reads/writes are tenant-scoped and covered by authorization and tenant-isolation tests.

### First-party setup
- Provide separate idempotent commands/flows for schema migration, first organisation creation, and first administrator creation.
- Never regenerate an existing `APP_KEY` during normal setup or upgrades.
- Never create known default-password users.
- Require an explicitly supplied admin email and strong password; hash it with Laravel's password hasher.
- Support repeat execution safely and test the clean-install and partially initialized cases.

### Product/vendor independence
- The target Davethan product must not call the Worksuite purchase-verification service as a prerequisite for app boot, login, or core CRM use.
- Remove vendor-specific code only through a lawful replacement implementation; do not patch a proprietary binary/source distribution solely to evade its entitlement requirements.
- Provide first-party versioning, release/update and health-check mechanisms as separate product features rather than retaining hidden coupling to a vendor update service.

## Next audit deliverable

Produce a file-by-file call-site matrix before code removal. Then implement the first-party settings/boot abstraction and its regression tests in isolation. Only after those tests pass should old call sites be migrated in small, reviewable groups.
