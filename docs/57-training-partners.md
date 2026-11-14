# Training partners (Jigide and any partner after)

> Added 2026-11-12. Code: `backend/Services/PartnerService.php`, `backend/Controllers/Api/V1/PartnerApiController.php`, `AdminPartnersController`, `TrainingController`. Admin: E-Learning > **Training partners** (`/admin/elearning/partners`). Partner developer guide: **docs/58-partner-api.md**.

InternConnect stays a recruitment product. Partners train; their results unlock free plan time and a badge recruiters can see.

## 1. Flow

1. A candidate presses **Start with {Partner}**, either in the "Certified training" section of `/elearning` or from the dashboard card.
2. `POST /training/start/{id}` creates or reuses a `partner_applications` row with a private reference such as `ICT3F9A1C2B7D4E`.
3. The student is redirected to the partner's application link with `ic_ref`, `ic_ts` and `ic_sig`. `ic_sig` is the HMAC of `reference.timestamp` with the partner's signing secret.
4. The partner reports progress (`/applications/status`) and the final score (`/results`) to `/api/v1/partners/*`, signed with HMAC.
5. The score becomes a `training_results` row. **Tier rules** turn it into a plan grant through `PlanService::grant` (never Paystack, never a downgrade of a paid plan; see docs/53 §4).
6. The candidate sees status, score, the plan unlocked and the certificate link on the dashboard card. Employers see **Certified by {Partner}** badges on the candidate page, and a **Certified only** filter on the candidates list.

No personal data is sent to partners: only the reference. Partners never send applicant data to us, only the result.

## 2. Everything is adjustable (admin)

| Setting | Where | Example |
|---|---|---|
| Pass mark per partner | Partners > Edit | 50% now, 70% later |
| Score ranges, plan, days | Pass mark & tiers | 85–100 Premium 180 days, 65–84.99 Pro 180 days, 50–64.99 Pro 30 days |
| Stop offering a plan | Pass mark & tiers | Set the band to "No plan (badge only)" or turn it off |
| Rules for one partner only | Pass mark & tiers, choose the partner | Jigide uses its own rules, others keep the global defaults |
| Status | Partners | Active or Paused (paused partners are hidden from students and their API calls are refused) |
| Application window | Partners | Unfinished applications expire after 120 days |
| Keys | Partners > Create key / Rotate key | Shown once; the old key stops immediately |

**History is safe.** Saving rules switches the old ones off rather than deleting them. Existing results keep the plan they earned, and new results use the new rules.

**Defaults** (the Jigide brief): 85–100 Premium 6 months; 65–84 Pro 6 months; 50–64 Pro 1 month; below 50 nothing.

**Jigide** is seeded as **Paused**. To go live:
1. Set the real application link and contact e-mail.
2. Create the key and send it securely, with docs/58, to their developers.
3. Set the partner to Active.

## 3. Corrections, revokes, manual results

- **A new score** for the same reference supersedes the old result (status `superseded`, linked by `supersedes_id`). The old grant is revoked and the new one issued.
- **Same score again** is reported as `unchanged`.
- **Admin > Results**: add or correct a result by hand (for partners without API access yet), or **Revoke** a result. Revoking ends its plan grant and removes the badge.
- **Customer Care** staff can view every tab but cannot change anything.

## 4. Data (additive tables)

- `training_partners`: partner details; key prefix, key hash, AES-256-GCM encrypted signing secret; pass mark; window; status.
- `partner_applications`: reference, candidate, partner, programme, status.
- `training_results`: score, passed, course, certificate, status, supersedes, rule, grant, plan and days, source.
- `tier_rules`: per-partner or global (`partner_id` NULL).
- `api_audit_log`: one row per API call, including refused calls, with masked IPs.
- `partner_idempotency`.
- Plan grants live in `plan_grants` (source `training`).

## 5. Ops

The daily cron expires stale applications and ended grants (`php bin/cron.php daily`).

## 6. Test checklist

Covered by `tests/PHPUnit/Unit/TrainingPartnerTest.php`:
- duplicate (Idempotency-Key replay, same score with a new key, key reused with another body gives 409);
- unknown student (404);
- invalid score (101, -1, text, missing give 422);
- paid user grant (paid Premium stays; free Pro starts after it);
- expiry (grants and applications);
- bad key, bad signature, stale timestamp, missing Idempotency-Key, rotated key, paused partner;
- corrections and revokes;
- pass mark and tier changes;
- another partner's reference (404);
- audit rows.

All pass.

Out of scope (per the brief): lessons and quizzes, co-branded certificates, revenue share, collecting partner applicant data.
