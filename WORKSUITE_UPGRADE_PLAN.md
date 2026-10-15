# Davethan CRM / Worksuite — Recovery and Upgrade Plan

## Correction
The previous work diverged by creating a separate Next.js/Supabase CRM instead of treating `davethantech/davethancrm` as the source project. That separate rebuild is not the authoritative implementation and must not replace or be deployed over this project.

## What the repository currently shows
- The project is a PHP/Laravel application stored under `script/`.
- `script/composer.json` identifies Laravel Framework `^10.0`, PHP `^8.1`, Laravel Modules, and Froiden/Worksuite-related packages, including `froiden/envato`.
- The repository documentation identifies the application as Worksuite HR, CRM and Project Management.
- The current dependency manifest contains Worksuite/vendor-specific integration points. Before changing activation or vendor-dependent code, establish that the deployment has a valid license/source entitlement. Do not patch out purchase verification or bypass vendor licensing.
- Do not print, copy, or commit values from any environment files. Review tracked environment files for exposed credentials and rotate any real secrets found.

## Delivery rules
1. Work only in a feature branch; keep `main` unchanged until changes are reviewed and tested.
2. Preserve the existing Laravel application and its database schema wherever possible. No new standalone CRM foundation.
3. Take a database and file backup before any schema change or VPS deployment. Never run destructive migrations against production as a first test.
4. Build a feature inventory from routes, controllers, models, migrations, modules, views, and permissions. Record which modules work, fail, or need replacement.
5. Add custom functionality through isolated Laravel modules/services, migrations, policies, tests, and UI components. Avoid editing vendor code when a supported extension point exists.
6. If the current Worksuite source is not properly licensed, do not modify or bypass its protected activation/vendor code. Instead, rebuild the needed functionality using independently authored code in a clearly separated implementation, based only on lawful requirements and data schemas—not copied proprietary source.
7. Test on staging first: dependency install, app boot, authentication/roles, CRUD flows, file uploads, email/queue/cron, reports, and migrations. Deploy only after checks pass and rollback is documented.

## Phases
### Phase 0 — Safety and entitlement
- Confirm the Worksuite edition/version and valid license/source entitlement.
- Inspect Git history and deployment configuration without exposing secrets.
- Identify any tracked credentials; remove them from future commits and rotate them if real.
- Record the PHP, database, Node/build, queue, cache, storage, and cron requirements.

### Phase 1 — Read-only architecture audit
- Inventory Laravel routes, controllers, models, migrations, policies/permissions, modules, frontend assets, tests, and dependencies.
- Map core workflows: leads/clients, projects/tasks/time logs, proposals/estimates/invoices/payments, tickets, employees/attendance/leaves, settings, notifications, and reports.
- Run safe static checks and test commands where an isolated environment is available.
- Produce a prioritised gap/bug list with file paths and acceptance criteria.

### Phase 2 — Stabilise the existing app
- Fix reproducible boot, dependency, configuration, security, and database issues first.
- Add regression tests around existing workflows before refactoring.
- Update framework/dependencies only as a separately tested upgrade—not as an unreviewed bulk version jump.

### Phase 3 — Advanced CRM enhancements
Prioritise business value and deliver in small, tested increments:
1. CRM pipeline: lead sources, stages, ownership, follow-up reminders, activity timeline, duplicate detection, and conversion tracking.
2. Sales operations: configurable pipelines, deals/opportunities, quotes/proposals, customer communications, and revenue forecasting.
3. Workflow automation: configurable triggers/actions, due-date reminders, assignment rules, email templates, and audit trail.
4. Dashboards and reporting: sales funnel, ageing, conversion, revenue, project profitability, and exportable reports.
5. Integrations and AI: only after permission controls, audit logging, and data handling are defined; use explicit user approval for external actions.
6. UI/UX: improve navigation, responsive views, search, filters, saved views, bulk actions, and accessible forms without breaking current screens.

### Phase 4 — Verification and deployment
- Run automated tests and smoke-test the highest-value workflows.
- Deploy to a staging path/database first; preserve existing websites, Nginx configuration, and unrelated VPS services.
- Back up, apply reviewed migrations, verify health checks/logs, and keep a rollback path.
- Document deployment and operations. Do not claim production deployment until it has actually been executed and verified.

## Immediate next action
Complete the Phase 0/1 audit and publish findings before making functional changes. No production deployment or changes to `main` are authorised by this plan.
