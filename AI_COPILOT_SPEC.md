# Davethan CRM AI Copilot — Product and Engineering Specification

Status: proposed implementation contract  
Repository: `davethantech/davethancrm`  
Application: Laravel/PHP under `script/`

## Goal

Let an authorised CRM user describe a new feature in natural language and have the copilot take that request through repository analysis, implementation, verification, and a reviewable result. The copilot must not merely write an assessment report or return detached code snippets.

## User experience

1. The user opens **AI Copilot → Build a feature**.
2. The user describes the desired feature and its expected behaviour. Example: “Add a follow-up reminder to leads, visible on the lead timeline and dashboard.”
3. The copilot resolves the user’s tenant, role, permissions, relevant modules, routes, models, migrations, UI conventions, and tests.
4. It produces a concise implementation plan with impacted files, schema changes, permissions, acceptance criteria, risks, and tests.
5. It creates an isolated workspace/branch and implements the feature in small changes.
6. It runs the allowed validation commands, reviews failures, and iterates within configured limits.
7. It presents a diff, test/build results, affected files, migration notes, and any unresolved items.
8. It opens a reviewable change or pull request. Deployment happens only through the authorised deployment workflow.

## Required architecture

- **Request/API layer:** authenticated endpoints with tenant scoping, validation, rate limits, request status, and cancellation.
- **Orchestrator:** durable job/state machine with statuses such as queued, analysing, planning, implementing, testing, needs-review, failed, and completed.
- **Repository context service:** reads only the authorised repository and selected branch; retrieves relevant files, symbols, tests, dependency manifests, and project conventions. Never ingest `.env`, credentials, private keys, production dumps, or unrelated repositories.
- **Planning service:** converts the request into bounded tasks, acceptance criteria, impacted files, migration plan, and a test plan.
- **Code generation service:** edits files in an isolated worktree or temporary branch. No direct edits to the running production application.
- **Execution runner:** runs an allowlisted set of commands in a disposable, resource-limited environment with timeouts and no production credentials. Network access is denied by default and enabled only for explicitly approved dependency operations.
- **Verification service:** runs formatting, static checks, tests, migrations against a disposable database, and the production frontend build where configured.
- **Review interface:** shows the plan, diff, test logs, risk flags, and failure details; supports approve, request changes, retry, and discard.
- **Git integration:** commits changes to a feature branch and opens a pull request only after checks; never pushes directly to `main`.
- **Audit trail:** records request ID, requesting user, tenant, model/provider, changed paths, commands, test outcomes, approvals, and deployment identity. Do not log secrets or full sensitive CRM records.
- **Provider abstraction:** model provider and credentials come from server-side environment/secret management, never browser code. Support a configurable provider without tying core CRM boot to a single vendor.

## Safety and permission model

- A user may request only changes within their role and tenant permissions.
- The copilot must not elevate the requester’s permissions or silently broaden access.
- Read and write scopes must be explicit. Repository writes are confined to the isolated worktree/feature branch.
- Never execute arbitrary shell commands supplied by the model without policy validation. Use a command allowlist, isolated runner, timeouts, output-size limits, and resource limits.
- No access to production database credentials, production filesystem writes, environment files, secrets, or unrelated VPS sites.
- Destructive schema changes, permission/security changes, external messages, billing/payment actions, and deployment require an explicit human approval gate.
- Generated code must not disable authentication, authorisation, tenant isolation, audit logging, or security controls to make tests pass.
- Preserve the human-readable diff and logs for every generated change.

## Acceptance criteria

1. A request creates a durable job with visible progress and can be retried safely.
2. The system retrieves relevant source context and identifies the exact files/modules it intends to change.
3. The generated plan contains testable acceptance criteria.
4. Code changes exist on a feature branch and are shown as a diff; no “complete” status is allowed if no source change was made.
5. The runner reports actual command exit codes and logs. Missing tools or skipped tests are marked NOT RUN, never PASS.
6. Failed tests return actionable diagnostics; the copilot can retry within a bounded budget and then surface the blocker.
7. Tenant and role checks are tested on every copilot endpoint and on repository/job access.
8. Secrets and `.env` files are excluded from retrieval, prompts, diffs, logs, and commits.
9. The copilot cannot write to production or deploy without the configured human approval.
10. The final result lists changed files, migrations, checks run, pass/fail/not-run outcomes, and remaining risks.

## Suggested implementation increments

### Increment 1 — Safe foundation
- Copilot request/job tables, model/provider interface, job status API, audit events, permission policies, and a simple request/status UI.
- No repository writes yet; prove tenant isolation, persistence, cancellation, and status transitions.

### Increment 2 — Read-only repository analysis
- Branch-aware repository retrieval, secret exclusion, context selection, feature plan, impacted-file list, and test plan.
- Verify the copilot cannot retrieve protected environment files.

### Increment 3 — Isolated code changes
- Disposable worktree/branch, patch generation, diff viewer, command allowlist, test runner, output capture, retry limits, and discard/revert path.

### Increment 4 — Git review workflow
- Commit to a feature branch, create pull request, attach test results, and collect human review.

### Increment 5 — Controlled deployment
- Connect only to the approved CI/CD workflow. Require a human approval, verified backup, staging checks, health checks, and rollback instructions before production release.

## Explicit non-goals

- Do not allow an LLM to write directly into the live application or production database.
- Do not claim that a feature has been built when only a plan/report was generated.
- Do not automatically deploy unreviewed AI changes to production.
- Do not make browser-side calls using provider API keys.
- Do not weaken licensing, authentication, permissions, tenant isolation, or security to bypass an existing error.
