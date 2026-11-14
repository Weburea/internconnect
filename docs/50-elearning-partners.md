# E-Learning Partners (public directory, referrals, admin)

> Updated 2026-11-11. Source of truth for `/elearning`, the partner details modal and the admin "Learning Partners" card.

## 1. Model

InternConnect sends students to partner platforms (ALX Africa, Coursera, DataCamp, freeCodeCamp, Google Career Certificates, CFI) to upskill. The partners send job-ready graduates back. Students add their certificate to their profile, which raises their Match Score and is shown to employers.

## 2. Data (`backend/Services/ElearningService.php`)

| Table | Holds |
|---|---|
| `elearning_partners` | slug, name, tier (badge), website, logo, brand colour, about, hubs, `services_json` [{name, desc}], is_active, sort_order |
| `elearning_programs` | partner_id, title, category (tech, design, data, business, free), duration, delivery, level, description, highlights, outcomes, perk, perk_code, external_url (https only), is_active |
| `elearning_referrals` | program_id, partner_id, candidate_id (null for visitors), created_at |

Tables are created on first use and seeded with the six launch partners and programs (previously hard-coded in the page). MySQL DDL: `database/schema_mysql.sql` section 14.

**Logos**: bundled official marks in `public/assets/partners/*.png`. An admin upload replaces one (stored privately as UploadStorage kind `logo`, served by `GET /elearning/logo/{partnerId}`).

## 3. Public page (`/elearning`)

- Cards come from `ElearningService::catalog()`. Hidden partners and programs are left out.
- Each card shows the partner logo, platform, tier, title, description, duration, delivery, perk and level.
- **View Details** modal, top to bottom:
  - logo with platform name, tier and category
  - program title and description
  - About the academy and hub locations
  - **Services offered by {platform}** (two-column list)
  - student advantage with a copyable voucher code
  - key curriculum
  - target internship outcomes
  - **How it works with InternConnect** (3 steps)
  - About {platform} link, Close, and **Visit Platform & Enroll**
- **Visit Platform & Enroll** goes through `GET /elearning/go/{programId}`, which records a referral and redirects to the partner's https URL.

## 4. Admin (`/admin/elearning` → Learning Partners)

- One card per partner: logo, name, badge, Visible/Hidden, program count, referrals in the last 30 days and all time.
- **Edit partner** modal fields:
  - logo upload with preview (PNG, JPG or WebP, 5MB)
  - name, badge, website (https), hubs, about
  - services list (add or remove, up to 12)
  - "Show on E-Learning" toggle

  Saved with `POST /admin/elearning/partners/{id}`.
- The existing Course Moderation shelf (mentor courses) is unchanged.

## 5. Gaps

- Program editing (title, perk, curriculum) is not in the admin UI yet; programs are seeded and can be edited in the database.
- Partner referral attribution relies on the partner accepting the voucher code; there is no partner-side API callback yet.

Tests: `tests/Unit/BillingAndApplicationsTest.php` (e-learning section).
