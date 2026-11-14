# Retention: keeping members after they get hired

> 2026-11-12. Research and proposals for InternConnect's leadership. Nothing here is built yet; section 4 lists what already exists to build on.

## 1. The problem, stated precisely

Out of 100 Pro members, 30 get an internship and stop paying. Two facts make this different from ordinary churn:

1. **This churn is success.** They leave because the product worked. Treating them like unhappy customers (discounts, guilt) is wrong. They are the best marketing we have.
2. **Our plans never auto-renew** (Terms, docs/53). "Churn" is really *not renewing*. The question is not "how do we stop cancellations" but "what reason does a hired person have to come back next month?"

There is a third fact specific to us: **internships end.** A 3–6 month internship means most "hired" members will be job-hunting again soon. That churn is *temporary*, if we are there when they come back.

## 2. What the market does (research)

| Company | What they do | What we take |
|---|---|---|
| **LinkedIn Premium** | Treats churn as the start of a relationship and wins people back ("boomerang"), offering *multiple* free trials over a member's life instead of one. Plans and paywalls are matched to the member's current intent. Premium-feature usage, not payment, is the early warning for churn. Cancel flow asks for a reason and offers to switch plans. [RevenueCat interview with LinkedIn's VP Product](https://www.revenuecat.com/blog/growth/ora-levit-linkedin-sub-club-2025/), [Sub Club episode summary](https://metacast.app/podcast/sub-club-by-revenuecat/DlWjuiPD/value-driven-growth-linkedin-s-billion-dollar-subscription-strategy-ora-levit-linkedin/Tk35uc1f), [playbook summary (secondary)](https://www.betterlaunch.co/playbooks/ora-levit-linkedin-billion-9), [offboarding teardown](https://raaft.io/blog/linkedin-offboarding-teardown), [Premium revenue $1.7B](https://news.linkedin.com/2024/march/LinkedIn-Premium-subscription-tops-1-7B-in-revenue-fueled-by-ai-features) | Re-entry offers at the right moment; usage-based churn alerts; intent-based plans |
| **Handshake** | Graduates keep lifetime access; alumni connect with peers and employers who hire alumni; schools track first destinations. A critique: the focus stays on current students. [University of Arizona guide](https://career.arizona.edu/blog/2025/05/14/using-handshake-after-graduation/), [Handshake help](https://support.joinhandshake.com/hc/en-gb/articles/115007119887-Using-Handshake-After-Graduation), [Talent Engagement Suite](https://joinhandshake.com/blog/employers/introducing-handshakes-talent-engagement-suite), [critique (directory)](https://us.fitgap.com/products/handshake-for-education-partners) | An alumni mode that keeps the profile alive and useful to employers |
| **Indeed** | Upload CV, preview, confirm; it is parsed into a structured profile, and recommendations follow history and behaviour. The profile shows skills and preferences beyond the CV. [Indeed help](https://support.indeed.com/hc/en-us/articles/4408783727629), [Smart Sourcing release](https://www.indeed.com/news/releases/indeed-launches-ai-powered-smart-sourcing-to-make-hiring-faster-by-matching-and-connecting-people-with-relevant-jobs) | We already do this (CV autofill, docs/49). Keep value before the paywall |
| **Jobberman (Nigeria)** | Free soft-skills training funded by Mastercard Foundation; certificates presented to employer partners; employers hire trained candidates at discounted rates; NYSC partnership; 280,000+ trained, 130,000+ placed (reported). [Jobberman soft skills](https://www.jobberman.com/youth-engagement/about-us/), [BusinessDay](https://businessday.ng/?p=609984), [iMocha case study](https://imocha.io/customers/jobberman-case-study-how-to-upskill-candidates-in-job-relevant-soft-skills) | Training plus certificates is credible in Nigeria. Revenue can come from employers, not only students |
| **Teal, Huntr, Jobscan** (AI job-search tools) | Short fixed terms (Teal: 7, 30 or 90 days), free tiers with limits. No public churn data. [Teal](https://www.tealhq.com/post/resume-coach-review), [Huntr pricing](https://api.huntr.co/pricing), [comparison (competitor blog)](https://jobshinobi.com/compare/jobscan-resume-scanner-subscription-cost-vs-value) | The category accepts "pay only while searching". Our edge must be life after the search |
| **Subscriptions in general** | In a Recurly / Hanover Research survey of 1,000 consumers, only 37% said their services offer a pause, and 25% of subscribers chose pause over cancel where it existed. "Not using it enough" is the top cancel reason (51%). Paused members come back far more often than cancelled ones, but vendor figures vary widely, so treat them as direction only. [Recurly](https://recurly.com/blog/the-pause-button-your-best-retention-tool), [Recurly docs](https://docs.recurly.com/recurly-subscriptions/docs/navigate-retain-pause-subscriptions-benefits), [Airbridge](https://airbridge.io/blog/why-subscription-churn-happens-top-5-cancellation-reasons-forecast-for-mobile-apps-in-2026), [Cleeng](https://blog.cleeng.com/how-to-use-subscription-pauses-to-reduce-churn-and-boost-retention) | A fixed-length pause with an automatic resume date beats a hard stop |

**Honest gap.** No company publishes churn among members who got hired. The ideas below are designed to be measured by us (section 5) rather than resting on borrowed numbers.

## 3. Proposals: what would make InternConnect different

### A. "Hired" is a plan change, not a goodbye (highest impact)

When an application reaches the **Hired** stage (we already track stages), or a member says "I got a job", we run a celebration flow:
- a congratulations page and a shareable "Hired through InternConnect" card;
- the member chooses one of:
  1. **Career mode** (₦999/month, or free for the first month as a thank-you): everything below in B–E, with job search switched off;
  2. **Pause until my internship ends**: free and silent until the end date, then we come back with roles (C);
  3. **Leave**, with one question (why) and no guilt.

### B. First-90-days coach (Jenny)

Interns are graded during the internship, and the goal of most interns is a **return offer**. Jenny becomes a weekly coach:
- "What did you ship this week?" builds an **evidence log**, which turns into a performance-review summary;
- prep for check-ins with the manager;
- workplace questions ("how do I ask for feedback?").

This is valuable *because* they are hired, and only we have their offer, role and dates.

### C. Contract-end radar (unique to internships)

We know the start date and duration from the offer letter. **Four weeks before the end** we say: "Your internship at Stellar Systems ends 1 Aug. Here are 12 full-time roles for someone with your new experience." Their profile is refreshed automatically with the internship.

This turns temporary churn into a predictable **re-entry moment**, the "multiple trials over a lifetime" idea LinkedIn uses, but triggered by a date we actually know.

### D. Verified Career Passport

When an internship ends, the employer confirms completion and gives a short rating (one click, from the employer dashboard). Together with partner certificates (docs/57), this builds a **verified work record**:
- a shareable link; recruiters can search "verified alumni";
- its value grows with every role, so the account becomes an asset people keep;
- it is a credential, not a subscription.

Employers get value too: verified references save them screening time, so they will fill it in.

### E. Give-to-get salary intelligence

Hired members contribute their stipend or salary anonymously and receive a free month or the full **Premium salary benchmarks** in return. This creates the data behind Premium's "salary benchmarks" (docs/53) that no Nigerian competitor has at internship level, and every hired person adds to it.

### F. Pay it forward: alumni mentors and referrers

Hired members can become **mentors** for students from their university (15-minute chats, CV reviews) and **refer** candidates to roles at their own company. They earn plan credit through grants (docs/53 §4: `PlanService::grant` already supports this), plus a "Mentor" badge.

This builds community, and the strongest acquisition channel in Nigeria: people trust "someone from my school who works there".

### G. Employer-paid seats (move the revenue)

Companies buy **"Intern Success" seats**: Premium for their interns during the internship (B and the training partners), plus a dashboard of intern progress. When the student stops paying because they were hired, the employer starts paying for the same person. Jobberman shows employer-side money works in this market.

### H. Smarter paywall and pricing

- **Value before payment.** Show personalised matches from the uploaded CV before asking for money (we already parse the CV at sign-up).
- **Intent-based offers**, like LinkedIn's matched paywalls:
  - searching: Pro or Premium;
  - interview scheduled: a one-week "Interview Sprint" pass (mock interviews, company brief);
  - hired: Career mode.
- **Short passes** that fit how students pay: 7-day and 30-day passes, plus a "until I'm hired" bundle. Payment by transfer and USSD through Paystack.
- **Win-back**: a free 7-day trial at re-entry moments (C), not once per lifetime.

### I. Early-warning dashboard (admin)

Track usage per paid member (applications, Jenny, mock interviews in the last 14 days). A paid member with no usage is at risk *before* the plan ends. LinkedIn's leading indicator is feature use, not payment. Trigger a Jenny nudge with something concrete ("3 new roles match your CV").

## 4. Already built that these ideas use

- Plan engine with editable limits, grants and expiry reminders (docs/53): Career mode, credits for mentors and give-to-get are grants and new `plan_features` rows.
- Application stages including Hired, and offer letters with dates: triggers for A and C.
- Jenny on Gemini with stored conversations (docs/55): the coach in B.
- Training partners, certificates and "Certified by" badges (docs/57): the start of D.
- Newsletter and e-mail queue (docs/54): every nudge and re-entry message.
- Employer dashboard: completion and rating for D, seats for G.

## 5. How to measure (so we know it works)

- Split churn into **success churn** (hired) and **dissatisfaction churn** (with reasons). Report them separately.
- Track:
  - **Career-mode take-up** of hired members (target: 30%);
  - **Re-entry reactivation** within 60 days of an internship end date;
  - **Passport completion** by employers;
  - **Mentor and referral activity**;
  - **Employer seats sold**.
- Run A/B tests where possible: pause versus no pause in the end-of-plan e-mail, and Career mode free first month versus paid.

## 6. Suggested order

1. **Quick wins (2–3 weeks):** A (hired flow plus pause), C (contract-end radar), I (risk dashboard), and the hired exit question.
2. **Next (1–2 months):** B (90-day coach), E (salary give-to-get), H (passes and intent offers).
3. **Strategic (quarter):** D (verified passport), F (mentors and referrals), G (employer seats).
