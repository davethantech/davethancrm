# Davethan CRM — Rebuild and Product Delivery Plan

Status: active implementation plan  
Repository: `davethantech/davethancrm`  
Working branch: `worksuite-upgrade-audit`  
Application root: `script/`  
Target stack: existing Laravel/PHP application and its current database technology; retain the established stack unless a measured technical blocker requires a reviewed change.

## Product objective

Turn Davethan CRM into a maintainable, independently owned CRM and work-management product. The task is not limited to making the existing login page work. Rebuild fragile architecture, redesign the UI, preserve required business workflows, add CRM capabilities, and provide an AI copilot that can implement requested features against the repository.

The previous separate Next.js/Supabase implementation is not the source of truth and must not be deployed over this Laravel project.

## Non-negotiable delivery rules

1. Make changes on this feature branch. Keep `main` unchanged until reviewed and tested.
2. Do not deploy speculative changes to the production VPS. Keep existing websites, Nginx sites, databases, and services isolated.
3. Preserve data. No `migrate:fresh`, blind seeding, blanket dependency upgrades, or destructive database changes against production.
4. Never run the all-in-one database seeder in production: it regenerates `APP_KEY` and creates sample users with known default passwords.
5. Never expose or commit environment secrets. `script/.env.dev` is tracked and must be treated as potentially compromised; remove it from future commits and rotate any real credentials in it or its history.
6. Do not evade a third-party license check to activate or use proprietary code without entitlement. The target product must not depend on Worksuite/Froiden purchasing checks: replace vendor-specific functionality with independently authored Davethan implementations where it is lawful to do so. Do not treat deleting a check from proprietary code as a product rebuild.
7. Preserve the Laravel/PHP technology foundation and current database technology. Do not start a parallel standalone CRM.
8. A feature is not complete because a route or page loads. It needs permissions, validation, persistence, error handling, tests, and documented acceptance criteria.
9. No claim of successful build, test, or deployment without an actual recorded result.

## Target architecture

- **Application core:** Laravel routing, controllers, services/actions, jobs, events, policies, notifications, validation, logging, and migrations.
- **Tenant and identity layer:** explicit tenant/company ownership, authentication, roles, granular permissions, and tenant-isolation tests. Avoid hidden assumptions that one singleton global-settings row always exists.
- **Configuration:** environment/config defaults for deployment settings; tenant-scoped settings for organisation preferences; feature-specific settings in their owning domain. Missing optional settings must degrade safely and never cause a fatal boot or login error.
- **Business domains:** CRM leads/contacts/accounts/deals; sales pipelines; activities and follow-ups; projects/tasks/time; proposals/quotes/invoices/payments; support tickets; reporting; users/teams/permissions; notifications and integrations.
- **Integration boundary:** first-party interfaces and adapters for email, storage, AI providers, and external services. No core boot path should require a purchase-verification integration.
- **UI:** coherent Davethan design system, responsive navigation, accessible forms, consistent tables/filters/search, loading/empty/error states, and reusable components. Retain existing useful workflows while replacing dated screens incrementally.
- **AI copilot:** permission-aware feature-request intake, repository context retrieval, planning, isolated code changes, tests, review/diff, and controlled deployment. See `AI_COPILOT_SPEC.md`.

## Work phases

### Phase 0 — Protect the environment
- Keep the VPS stable; do not reboot it or alter unrelated sites as part of this rebuild.
- Back up the database and files before any deployment or schema mutation and verify that the backups are usable.
- Audit tracked environment files and Git history without printing secrets; rotate any real exposed credentials.
- Record PHP CLI and PHP-FPM versions, Composer/Node versions, database version, extensions, build scripts, queue, cron, storage, and deployment requirements.

### Phase 1 — Repository inventory and dependency map
- Inventory routes, controllers, models, migrations, policies, role/permission checks, modules, Blade/JS/CSS assets, tests, package dependencies, and scheduled commands.
- Map each current workflow to its UI, route, controller/service, model/table, permissions, and tests.
- Find every dependency on `GlobalSetting`, `global_settings`, `purchase_code`, Froiden Envato boot traits, installation markers, and vendor-specific licensing/purchasing packages.
- Classify each dependency: remove from Davethan-owned code, replace with a first-party implementation, migrate data, or retain only in a lawful isolated legacy adapter.
- Mark each module as keep, refactor, replace, or retire. Identify unused/duplicate routes and null-unsafe boot paths.
- Record findings and acceptance criteria before changing behaviour.

### Phase 2 — First-party boot and installation
- Define a first-party setup/upgrade process that does not depend on a vendor installer or purchase check.
- Use idempotent migrations and explicit setup commands. Separate schema migration, initial organisation setup, administrator creation, and optional demo data.
- Never create default-password accounts. Create the first administrator through a one-time setup flow or secure CLI command that requires a supplied email and password.
- Replace singleton assumptions with safe configuration and tenant-aware settings services. Missing optional settings should return validated defaults.
- Remove purchase-specific dependencies from the rebuilt first-party application only after all call sites and data requirements have been mapped and replacement paths are tested.
- Add tests for a clean database, an existing database with no settings rows, missing optional settings, and repeated setup attempts.

### Phase 3 — Core product and UI redesign
- Preserve and validate the required CRM and work-management workflows.
- Build a consistent Davethan UI system and migrate screens in small increments.
- Prioritise dashboard/navigation, leads and contacts, deals/pipelines, activity timeline, follow-ups, customers, projects/tasks, proposals/invoices, reports, user administration, and tenant settings.
- Add CRM pipeline stages, ownership, lead sources, duplicate detection, assignment rules, reminders, revenue forecasts, saved views, exports, audit trails, and workflow automation.
- Keep feature-specific configuration in its domain; avoid rebuilding a giant mandatory global-settings record.

### Phase 4 — AI feature-development copilot
- Implement the feature workflow in `AI_COPILOT_SPEC.md`.
- The copilot must work against the actual Laravel repository and current branch, not generate detached snippets or a report pretending a feature was implemented.
- Require a reviewable diff, tests, build results, and explicit deployment gate. Do not let AI silently alter production data, secrets, permissions, or unrelated sites.

### Phase 5 — Quality gates
- Unit/feature tests for authentication, roles, tenant isolation, CRUD, validation, settings defaults, and AI-generated changes.
- Static analysis and formatting where configured; Composer install from lock file; frontend production build.
- Smoke tests for login/logout, navigation, lead-to-deal workflow, customer/project/task operations, proposals/invoices, uploads, notifications, and reports.
- Test migrations on a disposable database and on a staging copy before production.
- Verify error logs, queue/cron, cache, storage permissions, and rollback.
- Record each check as PASS/FAIL/NOT RUN with its command and output summary.

### Phase 6 — Staging and production rollout
- Deploy only after the branch passes the agreed quality gates and the deployment has a verified backup and rollback procedure.
- Keep staging database and storage separate from production.
- Preserve all unrelated VPS services and existing Nginx virtual hosts.
- After deployment, verify the exact health endpoint and critical user flows. If verification fails, roll back rather than patching production ad hoc.

## Immediate implementation order

1. Finish the dependency and workflow inventory.
2. Create a call-site map for global settings and purchase/licensing dependencies.
3. Define the first-party configuration, tenant settings, and installation contracts.
4. Add tests for missing settings and fresh setup before replacing call sites.
5. Implement the UI redesign and core CRM improvements incrementally.
6. Build the AI copilot behind explicit permissions and a controlled execution pipeline.
7. Test and deploy through staging; do not change `main` until review.

## Completion definition

The rebuild is complete only when the application boots without vendor purchase dependencies, setup is repeatable and secure, required CRM workflows persist correctly, tenant/role boundaries are tested, the redesigned UI builds, the AI copilot can produce tested reviewable code changes, and staging/production checks are recorded. Removing a single error or making `/login` return HTTP 200 is not completion.
