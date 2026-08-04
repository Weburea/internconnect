# Release Checklist

All deployments to staging and production environments must pass this checklist.

---

## Pre-Release Tasks

- [ ] All automated unit and integration tests pass successfully
- [ ] No temporary debug code remains in the codebase (e.g. `var_dump`, `console.log`)
- [ ] Security scan completed with no critical vulnerabilities detected
- [ ] Coding standards checked (PSR-12 validation and CSS audit passes)
- [ ] Database migration scripts created, tested, and validated for rollbacks

---

## Deployment Steps

- [ ] Run backup script to capture database and media state
- [ ] Pull code from release branch to production server
- [ ] Run database migration scripts: `composer migration:migrate`
- [ ] Clear runtime, template, and API route caches
- [ ] Force compile and purge production assets

---

## Post-Release Verification

- [ ] Verify core endpoints return successful HTTP status codes
- [ ] Perform a test user login and candidate job discovery flow
- [ ] Verify logs directory contains no active application exceptions or PHP crashes
- [ ] Check system monitoring dashboards to verify CPU and database load targets
