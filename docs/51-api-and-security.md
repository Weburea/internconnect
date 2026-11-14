# Testing (PHPUnit), REST API v1, Swagger and Security

> Updated 2026-11-12. Implements docs/45 sections 1-4. Source of truth for running tests, the `/api/v1` contract, the API reference and the platform's security controls.

## 1. Testing

| Command | Runs |
|---|---|
| `composer test` | Every PHPUnit suite (PHPUnit 11.5, `phpunit.xml`) |
| `composer test:unit` | `tests/PHPUnit/Unit`: the 80 original tests (via `LegacySuiteTest`), API auth/JWT/rate limiter, API controllers, OpenAPI contract. No web server needed. |
| `composer test:feature` | `tests/PHPUnit/Feature`: HTTP tests against `TEST_BASE_URL` (default `http://localhost/InternConnect`). Skipped automatically if the site is not running. |
| `composer test:legacy` | The original runner, `php tests/run_tests.php` |

- **Isolation**: `tests/bootstrap.php` copies `database/database.sqlite` to `storage/testing/test-<pid>.sqlite` for each run and deletes it afterwards. Tests never write to the development database. No `.env` is loaded, so Gemini, Daily and Paystack use their offline fallbacks.
- **Route crawler** (`RouteCrawlerAuditTest`): requests every GET route, fails on 404, 5xx, PHP errors, missing views or self-redirects, and writes `docs/broken-links-audit.md`. Last run: 181 routes, 160 OK, 3 redirects, 0 broken, 18 skipped (file streams and state-changing GETs).
- **Security** (`SecurityTest`): private files blocked, headers and cookie flags, CSRF, login rate limit.
- **API** (`ApiV1Test`): envelope, JSON errors, sign-in, refresh rotation, logout, roles, ownership, CSRF exemption.

## 2. REST API v1 (`/api/v1`)

- **Envelope**: `{success, code, message, data, meta?}`. Errors add `error` (machine code) and `errors` (`{field: [messages]}`).
  - 404 and 405 (with `Allow`) are JSON too.
  - Unexpected failures return 500 `server_error` with no internals; details go to the PHP error log.
- **Auth**:
  - `POST /auth/login` returns `access_token`: an HS256 JWT signed with `JWT_SECRET`, valid 15 minutes, with claims `sub, role, jti, iat, nbf, exp, iss`.
  - It also returns `refresh_token`: random, 30 days, stored only as a SHA-256 hash.
  - Every refresh rotates both tokens. Re-using a spent refresh token revokes the whole sign-in family, which detects stolen tokens.
  - `POST /auth/logout` revokes the family and denylists the access token `jti`.
  - Accounts (until users move to MySQL): `candidate@internconnect.ng`, `employer@internconnect.ng` and `admin@internconnect.ng`, all with the password in `API_DEMO_PASSWORD`. Without `JWT_SECRET` (at least 32 characters) or that password, sign-in returns 503.
- **Limits**:
  - 120 requests per minute per account (per IP when anonymous)
  - AI endpoints: 10 per minute; room tokens: 10 per minute
  - Login: 5 per minute per e-mail and IP, and 20 per 15 minutes per IP
  - Over a limit: 429 with `Retry-After`
- **CORS**: browser origins listed in `API_CORS_ORIGINS`; preflight answered. Mobile apps need no CORS.
- **Ownership**: candidates and employers only reach their own interviews and applications. Anything else returns 404, so the endpoint does not reveal what exists.

| Area | Endpoints |
|---|---|
| System | `GET /health` |
| Auth | `POST /auth/login`, `POST /auth/refresh-token`, `POST /auth/logout`, `GET /auth/me` |
| Catalog (public) | `GET /internships` (q, category, location, remote, page, per_page), `GET /internships/{id}`, `GET /elearning/programs` |
| Candidate | `GET /candidate/profile`, `GET /candidate/applications`, `GET /candidate/applications/{id}` |
| Account | `GET /billing`, `GET /notifications` (candidate or employer) |
| Employer | `GET /employer/candidates/{id}/application`, `POST /employer/candidates/{id}/stage` |
| Interviews | `GET /interviews`, `GET /interviews/{id}/status`, `POST /interviews/{id}/token` (Daily room URL + meeting token; candidates wait for the join window) |
| Jenny AI | `POST /ai/cv/parse` (multipart `resume`), `POST /ai/cover-letter`, `POST /ai/interview-prep/questions` |

Code: `backend/Api/` (ApiAuth, Jwt, ApiResponse, ApiException, OpenApiSpec), `backend/Controllers/Api/V1/`, routes at the end of `backend/routes.php`.

**Planned** (needs MySQL accounts, backend phase):
- register and OTP verification
- applying to and saving internships
- employer job posting and dashboard counters
- the CV in-place editor (`apply-edits`), the job-description generator, match explanations
- the Daily webhook

The internship list itself already comes from `backend/Data/internships.php`, shared by `/internships` and the API.

## 3. Swagger / OpenAPI

- `GET /admin/api-docs`: Swagger UI (swagger-ui-dist 5.17.14), admin only. Use Authorize with an access token to try endpoints.
- `GET /admin/api-docs/openapi.json`: OpenAPI 3.0.3 spec built by `App\Api\OpenApiSpec`.
- `ApiControllersTest` fails if a `/api/v1` route is undocumented, if a documented path has no route, or if a `$ref` or path parameter is missing, so the spec cannot drift.

## 4. Security controls

| Control | Where |
|---|---|
| **Private files** never served: database, source, docs, tests, vendor, storage, config files; only `index.php` runs as PHP | `.htaccess` (+ `FilesMatch` fallback). Before 2026-11-12 the SQLite database, docs and the test runner were downloadable. |
| **CSRF** on every web POST (header `X-CSRF-Token`, field `_csrf`, or `?_csrf=` for beacons). Exempt: `/payment/webhook` (HMAC-signed) and `/api/*` (Bearer tokens). | `App\Core\Security::verifyCsrf()` in the router; token in `<meta name="csrf-token">` of all layouts; `public/assets/js/ic-csrf.js` adds it to fetch, XHR, forms (including `form.submit()`) and `sendBeacon`. Failure: 403 with code `csrf_mismatch` (JSON for fetch, a "Page expired" page for forms). |
| **Session**: HttpOnly, SameSite=Lax, Secure on HTTPS, strict mode, new id after every sign-in | `Security::startSession()`, `regenerateSession()` |
| **Headers**: nosniff, X-Frame-Options SAMEORIGIN, Referrer-Policy, Permissions-Policy (camera, microphone and screen capture only for this site), COOP, HSTS on HTTPS, no X-Powered-By | `Security::sendHeaders()` in `public/index.php` |
| **Rate limits**: web login, admin login (by IP, besides the session lockout), API, AI | `App\Services\RateLimiter` (database-backed, keys hashed) |
| **Admin**: localhost auto sign-in only when `APP_ENV=local`; "trusted device" cookie is an HMAC with `APP_KEY`/`JWT_SECRET` (it was a hash with a fixed salt and could be forged); Secure flag on HTTPS | `AdminController` |
| **Ownership**: interview rooms, join/leave/end, files, chat, scorecards, AI suggestions and reports only for the interview's candidate/employer (or admin) | `VideoInterviewService::accessibleBy()`, `InterviewController::boot()` |
| Uploads: extension + size + MIME + signature checks, private storage, streamed by controllers | `UploadStorage` (unchanged) |
| Payments: server-side prices, amount check, signed webhook, idempotent by reference | `BillingService`, `PaymentController` |

**Still open**:
- Candidate and employer sign-in are demo flows without passwords; the API demo password is shared. Both are replaced by real accounts in the MySQL phase.
- Employer-to-candidate ownership on `/employer/candidates/*` (there is no employer-internship ownership data yet).
- A Content-Security-Policy (pages still use inline scripts and styles, so a CSP must start in report-only mode).
- Point the production document root at `public/` if the host allows it.
