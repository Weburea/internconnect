# Plans, limits and grants

> Added 2026-11-12. Code: `backend/Services/PlanService.php`. Admin: Subscriptions > **Plans & limits** (`/admin/subscriptions/plans`).

## 1. One source of truth

`PlanService::effectivePlan($type, $id)` = the **highest** of:
- the active paid subscription (`billing_subscriptions`, Paystack), and
- any active **plan grant** (`plan_grants`: admin grants, training-partner results).

Nothing reads the plan from the session or the URL any more. The old `?plan=premium` switch, which unlocked Premium for anyone, is gone. So is the dashboard "Free/Pro preview" toggle.

## 2. What each plan includes (defaults, editable by admin)

`-1` = unlimited, `0` = not included. Admin edits apply immediately and are never overwritten by a deploy.

| Feature (`plan_features.feature_key`) | Period | Free | Pro | Premium |
|---|---|---|---|---|
| Internship applications (`applications`) | month | 5 | 15 | unlimited |
| Premium listings (`premium_listings`) | flag | no | no | yes |
| Early access to new listings (`early_access_hours`) | text | — | — | 48 hours |
| Jenny search assist (`ai_search`) | day | 0 | 10 | unlimited |
| AI document create/edit: cover letters, CV analysis (`ai_documents`) | day | 0 | 5 | 15 |
| Jenny Coins (`jenny_coins`) | wallet | 5 | 20 | 60 |
| Jenny Coins refill after (`jenny_refill_hours`) | hours | 24 | 12 | 5 |
| AI Assistant chats with attachments (`assistant_chats`) | day | 0 | 5 | 15 |
| AI mock interviews (`mock_interviews`) | week | 0 | 1 | unlimited |
| Recruiter visibility (`recruiter_visibility`) | text | Standard | Boosted | Priority + Featured badge |
| Career insights (`career_insights`) | text | Profile strength | Match %, skill gaps | Salary benchmarks, market demand, readiness |

**Premium listing rule** (`plan_settings`): monthly stipend ≥ **₦300,000** and the role is hybrid or remote (7 of the 18 listings today).
- Non-Premium viewers see the listing with "Pay shown to Premium" and an **Unlock** button.
- The pay is removed server-side (page source, API and Jenny), not just hidden visually.
- Opening a locked listing redirects to Pricing.

The Pricing page compare table and plan cards are generated from these values, so they always match what is enforced.

## 3. Where limits are enforced

| Action | Feature | Behaviour when used up |
|---|---|---|
| Submit an application | `applications` | Form error with an upgrade hint; counted only after the submission is saved |
| AI cover letter (web and `POST /api/v1/ai/cover-letter`) | `ai_documents` | JSON 403 (not included) / 429 (used up) with `upgrade_url`; the page shows a toast |
| CV analysis | `ai_documents` | Redirect to Pricing |
| Career roadmap | 1 Jenny Coin | JSON 429 `no_coins` |
| Mock interview (first evaluated answer per role per day = one session) | `mock_interviews` | JSON 403/429 |
| Jenny widget / AI Assistant / search assist | see docs/55 | JSON with upgrade link |

Usage counters: `usage_counters` (account, feature, period key such as `2026-11-12`, `2026-W46`, `2026-11`).

## 4. Grants

`PlanService::grant($type, $id, $plan, $days, $source, ...)`. A grant is never a downgrade and never wastes paid days:
- **Same plan** as an active paid plan or grant: starts when that one ends (stacks).
- **Higher plan**: starts now; the paid plan keeps running underneath.
- **Lower plan**: starts when the higher plan ends.

Admin grants: Plans & limits > Grant a plan (account, plan, days, reason shown to the account). Revoking ends a grant now and never touches paid plans.

The demo candidate (#1) has a **Premium "Testing access" grant until 10 Feb 2027**, so the paid features can be tested. Revoke it in the same screen to test the Free experience.

## 5. Cron (daily)

`php bin/cron.php daily`:
- closes expired grants;
- e-mails paid accounts **3 days before their plan ends**. Plans never renew automatically, as the Terms say.

## Jenny Coins (2026-11-13)

Jenny chat is paid for with **Jenny Coins** instead of a daily message count. Each account has a wallet (`jenny_wallets`: account_type, account_id, spent, cycle_started_at).

| Plan | Coins | Refill after |
|---|---|---|
| Free | 5 | 24 hours |
| Pro | 20 | 12 hours |
| Premium | 60 | 5 hours |

Both values are editable in Admin > Subscriptions > Plans (`jenny_coins`, `jenny_refill_hours`, 1 to 168 hours).

- The refill clock starts at the **first coin spent** after a full wallet. When it runs out, the wallet is full again (the reset happens on the next read).
- Cost: 1 coin per Gemini answer, 2 when Jenny reads an attached file, 1 per career roadmap. Rule-based fallback answers are free.
- Visitors get a Free wallet keyed by IP (`visitor`, crc32 of the IP). Admins are never charged.
- With no coins left, the API returns 429 with code `no_coins` (`visitor_limit` for visitors) plus the coin state. The chat shows when the wallet refills and links to Pricing.
- `PlanService::coins()`, `canSpendCoins()`, `spendCoins()` and `refillCoins()`. `JennyService::coinState()` shapes the data for the browser.
- UI: the animated coin (`public/assets/images/jenny-coin.svg`, served locally with `url()`, not through Cloudinary), the dashboard card (`frontend/shared/components/jenny-coins-card.php`), the coin chip in the Jenny widget and the coin row in the AI Assistant chat.

**Grant form (2026-11-13):** pick the account by searching name, e-mail or number (`GET /admin/subscriptions/plans/accounts?q=`, `AccountDirectory`). Recent grants show the person's name and e-mail. Revoked or expired grants can be **reactivated**: a revoked grant with time left continues to its original end date; otherwise a new grant of the same plan and length starts now.
