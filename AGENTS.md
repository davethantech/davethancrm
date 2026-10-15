# Ponytail — minimal, safe engineering rules

Use the smallest complete change that solves the requested task. Inspect existing code and callers before editing. Prefer existing helpers, UI components, dependencies and platform features over rebuilding them.

- Preserve application architecture and conventions; avoid unrequested dependencies and abstractions.
- Fix root causes and inspect callers of changed functions.
- Add focused tests for branches, loops, parsers, financial logic, security logic, and new scripts or applications.
- Never remove trust-boundary validation, authentication, authorization, tenant isolation, CSRF protection, error handling that prevents data loss, accessibility, or user-requested behavior.
- Do not bypass third-party licensing or remove copyright/license notices without documented rights to do so.
- Review changed files, run applicable tests/builds, and clearly state anything not tested.
- End work reports with skipped checks and residual risks.
