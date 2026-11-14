# Audit: Public Site, Candidate Area, Plans, and Training Partners (Jigide)

> 2026-11-12. Codebase audit requested before building. **Status: implemented the same day.** See docs/53 (plans), 54 (e-mail, newsletter, contact, legal, receipt, offer letter), 55 (Jenny on Gemini, search assist), 56 (location, consent), 57-58 (training partners and partner API), 59 (MySQL). Deliberately not built: a category field in the home hero search (it would change the approved hero design).

## 1. Summary

| Area | Today | Verdict |
|---|---|---|
| Newsletter (home) | Form only changes the button text to "Subscribed". No storage, no admin, no unsubscribe, no email. | Build |
| Contact page | Fake: waits 0.8s and says "Message sent". Its toast never shows (`window.$dispatch` does not exist). | Build |
| Legal pages | Name **DeepSeek** as the AI provider (it is Gemini). Do not mention video interviews (Daily.co), location (MaxMind), partner data sharing, CV parsing, or the API. "Last updated Oct 20, 2026". | Rewrite content |
| Footer | Links work (12 routes); layout to be reviewed with the new pages (newsletter preferences, partners, API/status). | Polish |
| Jenny chatbot (public + dashboard) | 1,681-line scripted demo: canned replies on timers, simulated upload progress, persona switch Job Seeker/Recruiter. **Not connected to Gemini.** The search page's "Chat with Jenny" sends `open-jenny-chat`, which nothing listens to (only `open-jenny-ai` works). | Rebuild logic on Gemini, keep design |
| Admin Jenny chat | UI with a "history" list that is static sample data. No Gemini, no stored conversations. | Wire to Gemini + stored history |
| Visitor tracking / live map | Admin "Live visitors" map (MapLibre + OpenFreeMap, no key needed) shows **hard-coded sample cities**. Only video interviews record real locations (MaxMind). | Build real visitor/session tracking |
| Home search (title + location) | Plain GET to `/search`. No geolocation, no category, no Jenny. | Extend |
| Search page `/search` | Keeps its **own copy** of 20 listings (separate from `backend/Data/internships.php` used by Find Jobs and the API). | Unify data, add AI search |
| Plan enforcement | Paid features read `$_SESSION['candidate_plan']`, **default "pro"**, and **`?plan=premium` in any AI page URL unlocks Premium**. Billing (Paystack) records plans in `billing_subscriptions` but nothing reads them for access. | Fix first: one plan engine |
| Pricing compare | Pro vs Premium mostly "✓ vs ∞"; differences are not concrete. Prices in code: Pro **₦2,499**, Premium **₦4,999**. | Redesign logic + table |
| Candidate dashboard AI | "Pro insights" blur toggles client-side (`isPro`), recommendations not plan-gated. | Gate by real plan |
| Offer letter "Download PDF" | `window.print()` (browser print dialog). | Real PDF (jsPDF, already in project) |
| AI Assistant (Pro) prompt box | Input + send button with **no handler**. | Chat workspace like admin Jenny + attachments + quotas |
| Billing receipt | Printable HTML page. | Downloadable teller-style receipt PDF (+ A4 invoice) |
| Email | **No mail system** (no PHPMailer, no SMTP settings). Needed by newsletter, contact, training results. | Add PHPMailer + SMTP (cPanel mailbox) |
| Training partners (Jigide) | Not present. Related pieces exist (see section 8). Student dashboard has **no training area** (the brief assumes one). | Build after decisions |

## 2. Public site

### 2.1 Newsletter
- **Data**: a `newsletter_subscribers` table with:
  - email (unique) and status (`pending`, `active`, `unsubscribed`)
  - source (home, footer, signup), a hashed IP, a `token` for confirm and unsubscribe links
  - timestamps for subscribed, confirmed and unsubscribed
- **Flow**: subscribe goes through `POST /newsletter/subscribe` with a CSRF check, a rate limit per IP and an e-mail syntax and MX check. The existing card shows the result.
  - Double opt-in when mail is configured (NDPA-friendly). Otherwise the address is active immediately.
- **Unsubscribe**: `/newsletter/unsubscribe/{token}` (one click, no login) and a footer link. Every newsletter e-mail carries the link and a `List-Unsubscribe` header.
- **Admin** (new "Newsletter" page under Platform):
  - counts (active, pending, unsubscribed, growth)
  - searchable, paginated list
  - CSV export, manual unsubscribe, resubscribe on request
  - compose and send (batched by cron to respect cPanel mail limits), with a sent-campaign history
- **Candidates**: a newsletter toggle in profile notification preferences.

### 2.2 Contact page
- `POST /contact` stores the message in `contact_messages` (name, email, topic, message, status, assigned_to), with CSRF, a rate limit and a honeypot field.
- It sends an e-mail to support and an automatic acknowledgement to the sender (when mail is configured).
- Admin gets a support inbox under Settings or Support: list, read, mark resolved, reply via e-mail. Customer Care can read and reply.
- Fix the toast bug.

### 2.3 Legal pages (content update)
Rewrite Privacy, Terms, Cookies and Refund for what the platform actually does now:
- **AI**: Gemini (Google), CV parsing and summaries, AI scoring as assistance only. Remove DeepSeek.
- **Video interviews**: Daily.co; not recorded; chat and files kept for the interview report; who sees what.
- **Location**: approximate city from IP (MaxMind), masked IPs; precise location only when the user taps "Near me".
- **Payments**: Paystack. Plans are not auto-renewing; refunds per the Refund policy.
- **Partners**: what is shared with training partners such as Jigide (only the reference token and e-mail the student chooses to send by clicking Apply) and what comes back (score, certificate).
- **Other**: the API (mobile apps); data retention periods; NDPA 2023 rights (access, correction, deletion, objection); contact for the Data Protection Officer.
- **Cookies**: the actual inventory: session (`PHPSESSID`, HttpOnly/SameSite), `admin_trusted_device`, local-storage preferences, Paystack, Daily, map tiles. Analytics only after consent.
- Set new "Last updated" dates.

### 2.4 Footer
Keep the design. Add: Newsletter preferences, Training Partners, Status/Help, and a "Report a problem" link to Contact. Check spacing at 360/390/440 and on tablet.

## 3. Jenny AI

### 3.1 Public and dashboard chatbot (bottom right)
- **Engine**: Gemini through a new `POST /jenny/chat` endpoint (CSRF, rate limited). It sends the conversation history (last ~12 turns), the persona (job seeker or recruiter), the page context and the plan.
  - Answers stream as text.
  - Actions come back as structured JSON: show internships, open search with filters, open pricing, start CV upload, schedule-interview help for recruiters.
  - Without Gemini, the current scripted answers stay as the fallback.
- **Attachments**: PDF, DOCX and images up to 10MB, using the UploadStorage checks. Gemini reads PDFs and images directly; DOCX text is extracted. CVs dropped in chat are parsed (CvService) and offered as "Use as my CV".
- **Voice** ("Jenny can speak"): browser speech-to-text (mic button) and text-to-speech (speaker toggle) via the Web Speech API. No extra key, nothing exposed.
  - Gemini Live (real-time audio) would need a server-side relay. It is not needed for this.
- **Persona switch** (Job Seeker / Recruiter): redesign it as a clear two-option segmented control in the header. Each side gets its own suggestions. Signed-in employers start as Recruiter.
- **Events**: one event name for "open Jenny" (`open-jenny-ai`) used by every page and card.
- **Plans** (candidate): Free 10 messages/day, Pro 40, Premium 150 (configurable). Recruiters follow the employer plan when it exists.

### 3.2 Admin Jenny chat
- Same Gemini endpoint with an **admin persona** that can query platform data (read-only summaries: signups, applications, interviews, payments, partner results).
- **Conversation history** stored per admin (`ai_conversations`, `ai_messages`): recall, rename, delete.
- Attachments as above.

### 3.3 Search assist (Pro and Premium)
- On `/search` and Find Jobs: "Ask Jenny" turns a sentence into filters (Gemini JSON), for example "remote design internships in Lagos paying over 200k". The filters are applied to the same list and shown as chips the user can remove.
- Free users see the box with an upgrade prompt.
- The Jenny card on the search page opens the chatbot already in search mode.

## 4. Location (geolocation)

- **Recommended engine**: the **MaxMind GeoLite2 City database file**, read locally (`geoip2/geoip2`).
  - Free and unlimited, no per-lookup quota, no network call.
  - Refreshed weekly by cron with your MaxMind licence key.
  - The current web-service lookup (1,000/day free) stays as a fallback.
- **Visitor tracking**: a `visit_sessions` table (session hash, role, masked IP, city, country, lat/lng, device, first and last seen, pages viewed). It is updated on each page view, throttled to one write per minute per session.
  - The admin "Live visitors" map and counts switch from sample data to this table (active in the last 5 minutes).
  - The admin overview gets "who signed in", with session location and device.
- **Precise location**: only when the user taps "Near me" in the search bar (browser geolocation permission). It is used for sorting by distance and not stored beyond the session.
- **Privacy**: IPs are masked; precise coordinates are never stored. A short cookie and analytics consent banner on first visit, plus the legal updates in 2.3.
- **Home search**: title + location + **category** (dropdown) + "Near me". A city typed in the location box (for example Lagos) filters by location; Jenny can refine it for Pro and Premium.

## 5. Plans: what really separates Free, Pro and Premium

**Positioning**: Free = find and apply. Pro = AI helps you apply better. Premium = AI plus first access to the better roles.

| Capability | Free | Pro (₦2,499) | Premium (₦4,999) |
|---|---|---|---|
| Applications per month | 5 | 15 | Unlimited |
| Internships you can see | Standard listings | Standard listings | Standard **+ Premium listings**: higher stipend band (e.g. ≥ ₦200,000), hybrid/remote flexibility, roles employers mark "Premium" |
| Early access | — | — | New listings **48 hours before** everyone else |
| Jenny search assist | — | 10 searches/day | Unlimited + "near me" ranking + salary insight per role |
| AI document create/edit (CV, cover letter) | — | **5 per day** | **15 per day** |
| Jenny chat messages | 10/day | 40/day | 150/day |
| AI mock interviews with feedback | — | 1 per week (trial) | Unlimited |
| Recruiter visibility | Standard | Boosted in employer search | **Priority**: shown first + "Featured" badge |
| Career insights | Profile strength | + match %, skill gaps | + salary benchmarks, market demand, interview readiness |
| Video interviews, tracking, notifications | Yes | Yes | Yes |

The example you gave (the same reception role in Lagos): Pro and Premium both see the ₦200,000 on-site posting. Premium additionally sees the **higher-paying hybrid version**, flagged as a Premium listing, and sees new ones 48 hours earlier.

Free users still *see that Premium listings exist* (title and company blurred, "Premium role" badge), so the value is visible without hiding the market, and search engines keep indexing public listings.

**Engine** (fixes the security hole in section 1):
- `PlanService::effectivePlan(candidateId)` is the single source of truth. It returns the highest of the active paid subscription (BillingService) and any active grant (training results, section 8).
  - It replaces `$_SESSION['candidate_plan']`.
  - The `?plan=` override is removed.
- `plan_features` (database/config) holds every number in the table above, so limits change without code.
- `UsageQuota` keeps per-day counters per account and feature. It returns "4 of 5 used today" for the UI and blocks with an upgrade prompt.

**Pricing page**: a comparison table by these themes (Apply, Discover, AI help, Visibility, Insights), with plain numbers instead of ✓/∞, plus a "Which plan is right for me" row and the training route ("Score 85%+ with a training partner and get Premium free for 6 months").

## 6. Candidate area

- **Dashboard**:
  - AI recommendations ranked by the real plan, with sort and filter for Pro and Premium
  - "Pro Career Insights" unlocked by the real plan (the client-side toggle is removed)
  - the checklist reads the real profile and CV state
  - a new **Training Programs** card (section 8)
- **Find Jobs / Search**: one data source (`backend/Data/internships.php`), AI search for paid plans, Premium listings and early access per section 5.
- **Applications → Review offer letter**: "Download PDF" builds a real A4 letter with jsPDF (already bundled):
  - letterhead, date, reference
  - bold headings, justified paragraphs
  - an offer summary table (role, stipend, start date, duration, location, reporting manager)
  - acceptance block, signature lines, page numbers
- **AI Assistant (Pro)**:
  - the working prompt box becomes a chat workspace with the same layout as the admin Jenny chat ("Hi Sarah", Jenny logo, suggestion cards, history panel)
  - attachments (CV, job description)
  - quick tools (rewrite CV, cover letter, interview prep, career plan)
  - quota counter per plan (Pro 5/day, Premium 15/day for document create/edit)
- **Billing & Subscription**:
  - per-payment **receipt PDF** in teller style (80 mm wide, monospace totals, reference, card ending, VAT line if needed, QR or verification code)
  - an **A4 invoice** alternative
  - a training-grant row ("Premium granted by Jigide, until …") in the history
  - the current plan card shows the source (Paid / Granted)

## 7. Email (needed by 2.1, 2.2 and 8)

- Add PHPMailer (`composer require phpmailer/phpmailer`) and SMTP settings in `.env` (cPanel mailbox: host, port 465/587, user, password, from address).
- Add a `mail_queue` table plus cron sending, so pages never wait on SMTP and cPanel hourly limits are respected.
- Branded templates: transactional (results, receipts, contact acknowledgement) and newsletter (with unsubscribe).

## 8. Training Partners (Jigide): audit per the brief

### 8.1 Corrections to the brief's assumptions
- **Stack**:
  - Routing is a custom PHP router (not Laravel).
  - Locally the database is SQLite and services create their tables on first use; production is MySQL (`database/schema_mysql.sql`).
  - Plain Alpine/Tailwind views.
  - PHPMailer is **not installed** (section 7).
- **Accounts**: there is **no live users table yet**.
  - Candidates are the demo account #1 and sign-in is a demo flow.
  - "Find the student by e-mail or InternConnect ID" only works reliably once accounts move to MySQL.
  - Matching by the **reference token** created at Apply time works now.
- **Student training area**: does not exist on the dashboard. Admin has an **E-Learning** section, containing course moderation (sample data) and the Learning Partners card built on 2026-11-11.
- **Plan prices**: the code has Pro **₦2,499** (the brief says ₦2,500) and Premium ₦4,999. Unchanged unless you decide otherwise.

### 8.2 What exists and can be reused

| Need | Existing piece |
|---|---|
| Partner record, logo, about, services, admin edit | `ElearningService` + `elearning_partners` (admin Learning Partners card, logo upload) |
| Redirect + intent tracking | `/elearning/go/{id}` + `elearning_referrals` (records click and candidate) |
| Versioned API, JSON envelope, errors, rate limits | `/api/v1` stack: `ApiController`, `ApiException`, `ApiResponse`, `RateLimiter` |
| HMAC verification pattern | Paystack webhook signature check |
| Plan history, current plan, receipts | `BillingService` (`billing_transactions`, `billing_subscriptions`) |
| Notifications | `CommunicationService::createNotification()` (in-app) |
| Certificate files | `UploadStorage` (private storage, signature checks) |
| Admin pages, roles | Admin layout, E-Learning section; staff roles exist (Platform Admin, Customer Care, Finance & Billing, Verification Lead) **but are labels only**: no real permission checks yet |
| API docs | Swagger UI at `/admin/api-docs`, spec contract-tested |
| Tests | PHPUnit unit + HTTP feature suites |

### 8.3 What is missing
- partner API keys and HMAC secrets
- applications with statuses and reference tokens
- results with score history and corrections
- configurable tier rules
- plan grants and the effective-plan logic (section 5)
- API audit log
- cron (none exists)
- email (section 7)
- student Training Programs UI
- "Certified by" badge and filter for recruiters
- admin pages for applications, results, rules, grants, logs and score distribution
- real role permissions (Customer Care read-only)

### 8.4 Proposed design
- **One partner table for both kinds of partner**: extend `elearning_partners` with `partner_type` (`directory` = referral only, e.g. Coursera; `certifying` = sends results, e.g. Jigide), plus `application_url`, `contact_email`, `api_key_hash`, `api_key_prefix`, `webhook_secret` (encrypted), `status`.
  - The public E-Learning page and the student Training Programs card read the same records.
  - This avoids two partner systems.
- **New tables**, matching the brief, with our naming:
  - `partner_applications`: user_id, partner_id, status, reference_token (random 32 hex), timestamps
  - `training_results`: score DECIMAL(5,2), certificate_url / certificate_file, certificate_reference UNIQUE per partner, track, issued_at, raw_payload, received_at, verified_by_admin, supersedes_id for corrections
  - `tier_rules` (seeded 85-100 → Premium 180 days, 65-84 → Pro 180, 50-64 → Pro 30, < 50 → none)
  - `subscription_grants`: user_id, training_result_id, plan, starts_at, expires_at, status, reason
  - `api_audit_log`: partner_id, endpoint, request_hash, status_code, ip, created_at
- **Migrations**: `database/migrations/2026_11_xx_training_partners.sql` (MySQL DDL, additive only) and the same tables created by the service locally. They are also added to `schema_mysql.sql` section 15.
- **API** (`/api/v1/partners/...`, documented in Swagger and as a PDF/Markdown pack for Jigide's developers):
  - Auth: `X-Partner-Key` header, plus `X-Signature: sha256=<HMAC of raw body with webhook secret>`, plus `X-Timestamp` (rejected if older than 5 minutes: replay protection), plus an optional `Idempotency-Key`.
  - `POST /api/v1/partners/results`: validate the score (0-100), the active partner and a known student (reference token, then e-mail when accounts exist).
    - Store the result and apply the tier rule; e-mail plus dashboard notification.
    - The same `certificate_reference` returns the original response (no duplicates).
    - A changed score creates a correction row, recalculates the grant and keeps the history.
  - `POST /api/v1/partners/applications/status`: in_training, certified, rejected, withdrawn.
  - `GET /api/v1/partners/students/{reference}`: confirms the reference is valid (returns only masked name and status, no personal data).
  - Every call is logged in `api_audit_log` and rate limited per partner.
- **Apply flow**: the student taps Apply on Jigide, a `partner_applications` row is created (status `redirected`), and the student is redirected to `application_url?ic_ref=<reference_token>&ic_email=<email>`. The e-mail is only sent when the student ticks "Share my e-mail with Jigide".
- **Grants vs paid plans** (proposal; you asked how it would be handled):
  - Never downgrade.
  - **Same plan**: the grant's days are added after the paid period (stacked).
  - **Higher plan** (paid Pro, granted Premium): Premium applies immediately for its duration. The unused paid Pro days are **paused and resume after** the grant ends, so nothing paid is lost.
  - **Lower plan** (paid Premium, granted Pro): the grant is recorded and starts after the paid Premium ends.
  - Grants never touch Paystack and are shown as "Granted by Jigide" in billing history.
- **Cron** (cPanel, once a day): `php /home/<user>/public_html/InternConnect/bin/cron.php daily`. It expires grants and paid plans, resumes paused plans, sends queued e-mail and refreshes GeoLite2 weekly. Plus an every-5-minutes entry for the mail queue.
- **Admin (inside the existing E-Learning design)**: tabs Partners (add/edit, activate, generate/rotate API key shown once), Applications, Results (verify, manual add), Tier rules, Grants (revoke), API logs, Score distribution per partner (histogram plus a mean/median alert for inflated scoring). Customer Care: read-only Applications and Results.
- **Student**:
  - a dashboard **Training Programs** card: partners, Apply, status per partner
  - a result card: score, certificate download, plan unlocked and expiry
  - the "Certified by Jigide" badge on the profile
- **Recruiter**: "Certified by [Partner]" badge plus score band (85+, 65-84, 50-64) on candidate cards and the profile page, a filter in candidate search, and a "certified match" notification.
- **Out of scope** (per the brief): lessons, quizzes, co-branded certificates, revenue share, collecting partner applicant data.

### 8.5 E-Learning content strategy (public page and dashboard)
- **Message**: "Train with our partners. Get certified. Get hired." InternConnect stays a recruitment platform, and training is the route to better jobs.
- **Page structure**:
  1. Hero: "Get job-ready with InternConnect training partners".
  2. Certification rewards: the score-to-plan table, e.g. "85%+ unlocks Premium free for 6 months".
  3. How it works, in 4 steps: Apply on the partner's site, train and get scored, the result is sent to InternConnect, then the badge and plan are unlocked.
  4. Certifying partners (Jigide first) with the "Results go straight to your profile" label.
  5. Learning partners (Coursera, DataCamp, …): referral only, add the certificate to your profile manually.
  6. What employers see (the badge).
  7. FAQ: cost, data sharing, how long results take, what happens to my paid plan.
- **Dashboard card copy**: "Boost your profile with a certified training program", plus status chips (Redirected, In training, Certified).

## 9. Decisions needed

1. **Order**: recommended order:
   1. plan engine + pricing (fixes the `?plan=` hole)
   2. email
   3. newsletter, contact, legal and footer
   4. offer letter and receipt PDFs
   5. Jenny on Gemini (chatbot, AI Assistant page, search assist, admin chat)
   6. visitor tracking + location
   7. training partners (Jigide)
2. **Accounts**: build Jigide now on reference tokens only, or first move accounts to MySQL (real sign-up and login) so e-mail and ID matching work? Recommendation: build now on reference tokens; e-mail matching switches on with accounts.
3. **One partner table** (extend `elearning_partners`, section 8.4) or a separate `training_partners` table as the brief names it?
4. **Grant vs paid plan policy** in 8.4: approve, or prefer something else?
5. **Price**: keep Pro at ₦2,499 or change it to ₦2,500?
6. **Plan numbers** in section 5 (applications, AI quotas, Premium stipend threshold ₦200,000, 48-hour early access): approve or adjust.
7. **SMTP**: which mailbox should send (e.g. no-reply@yourdomain via cPanel)? You add the password to `.env`.
8. **Location**: OK to use the MaxMind GeoLite2 database file (free, uses your existing licence key) and to show a consent banner on first visit?
