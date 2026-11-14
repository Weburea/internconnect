# Visitor location, live map, Near me, cookie consent

> Added 2026-11-12. Code: `backend/Services/VisitService.php`, `VisitController`, `public/assets/js/ic-visit.js`, `public/assets/js/ic-consent.js`, `frontend/shared/components/cookie-banner.php`.

## 1. Visits

- After each page load, `ic-visit.js` sends a beacon to `POST /t/visit`. CSRF is handled by `ic-csrf.js`.
- While the tab is visible it sends a heartbeat every minute, and nothing while hidden. The page never waits for it.
- `visit_sessions` holds one row per browser session:
  - masked IP;
  - city, country and coordinates (MaxMind GeoLite2 web service, cached 7 days per IP in `geo_cache`);
  - device, role (visitor, student, employer, staff), current page, page count, first and last seen.
- **Online** means seen in the last 5 minutes.
- **Returning visitors**: the `ic_vid` cookie (12 months) is set only if the visitor allowed analytics cookies.
- **Retention**: visit rows older than 90 days are removed by the daily cron. This is stated in the Privacy Policy.

## 2. Admin live map

`/admin/api/live-sessions`, used by the overview map, now returns real sessions instead of the hard-coded city list:
- `is_sample: false`
- per-city counts (students, employers);
- countries with share;
- the latest 50 sessions (masked IP, device, role, page, minutes ago).

## 3. Near me (Find Jobs)

In the location dropdown, **Near me**:
1. If the visitor allows it, uses the browser location to pick the nearest major city (Lagos, Abuja, Port Harcourt, Ibadan, Enugu, Kano, Benin City, Kaduna, Accra, Nairobi).
2. Otherwise uses the city from the IP address (`GET /geo/me`).

The exact position is never sent to or stored by the server.

## 4. Consent

- The banner appears until the visitor chooses "Essential only" or "Accept all".
- The cookie page has the toggles.
- Withdrawing analytics consent deletes `ic_vid` at once.
