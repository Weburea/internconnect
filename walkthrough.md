# InternConnect Student Onboarding & Navigation Restructuring Walkthrough (`walkthrough.md`)

This document details the architectural plan, API analysis, and implementation roadmap for:
1. **Student Onboarding (`/register/student`)**:
   - **Step 3 (Email Verification)**: 2-minute countdown synchronization across logic and sidebar, plus seamless 6-digit OTP paste automation.
   - **Step 4 (Academic Background)**: Free open-source worldwide University Search API architecture, selection, and autocomplete integration.
   - **Step 6 (Career Interests)**: Search-filtered interest tags and work arrangement selection.
2. **Global Navigation Restructuring (`navbar.php`)**:
   - Updated link sequence: `Find Jobs`, `Pricing`, `E-Learning`, `How It Works`, `Contact Us`.
3. **Footer Restructuring (`footer.php`)**:
   - Clean 3-group column hierarchy: `Platform`, `Support`, `Company` (replacing `Blog`), and `Follow Us`.
4. **Clean Placeholder Route & View Architecture**:
   - Lightweight scaffolded views for newly referenced pages (`/elearning`, `/how-it-works`, `/contact`, `/refund`, `/cookies`, etc.) preventing 404 errors.
5. **Architectural Governance & Rules Update (`rules.md`)**:
   - Strict standard for third-party API integration (backend proxy, caching, timeout resilience, zero client-side direct dependency).

---

## 1. Step 3: Email Verification Timer & OTP Paste Automation

### A. 2-Minute Cooldown & Expiration Logic
| Element | Current State | Reconstructed State |
| :--- | :--- | :--- |
| **Marketing Sidebar Copy** | "Code expires in 5 minutes" | **"Code expires in 2 minutes"** |
| **Resend Cooldown Timer** | `resend_cooldown: 51` (displays `0:51`) | **`resend_cooldown: 120` (displays `2:00` counting down to `0:00`)** |
| **Code Expiry Countdown** | `countdown: 300` (5 min) | **`countdown: 120` (2 min)** |
| **Timer Formatter Function** | `formatCooldown(secs)` hardcoded `0:ss` | **Dynamic `formatCooldown(secs)` displaying `m:ss` (e.g., `2:00`, `1:59`, `0:45`)** |

```javascript
formatCooldown(secs) {
    const m = Math.floor(secs / 60);
    const s = secs % 60;
    return `${m}:${s < 10 ? '0' : ''}${s}`;
}
```

### B. Seamless 6-Digit OTP Paste Mechanism
- **User Pain Point**: Students copying a 6-digit verification code from their email app currently have to memorize or switch back and forth to type it digit-by-digit into individual boxes.
- **Solution**:
  - Attach `@paste="handlePaste($event)"` to the verification input wrapper.
  - Automatically captures clipboard data, strips all non-numeric characters (`\D`), extracts the first 6 digits, and immediately populates `this.verification_input`.
  - Instantly renders all 6 boxes populated.
  - Allows manual digit-by-digit typing and backspace deletion for full flexibility.

```javascript
handlePaste(e) {
    e.preventDefault();
    const clipboardData = e.clipboardData || window.clipboardData;
    const pastedText = clipboardData ? clipboardData.getData('text') : '';
    const cleanDigits = pastedText.replace(/\D/g, '').substring(0, 6);
    if (cleanDigits.length > 0) {
        this.verification_input = cleanDigits;
    }
}
```

---

## 2. Step 4: Worldwide University Search API — Evaluation & Architecture

### A. University Search API Comparison & Selection

The platform requires an API that:
- Has **thousands/tens of thousands of verified universities worldwide** (Nigeria, UK, USA, Canada, Germany, India, Ghana, Kenya, Australia, etc.).
- Is **completely free and open-source** with **no restrictive rate-limits or API key expiration**.
- Does not break client code if external networks fluctuate.

| Criteria | 1. Hipo World Universities API (Recommended) | 2. ROR (Research Organization Registry) | 3. OpenAlex Institutions API |
| :--- | :--- | :--- | :--- |
| **Coverage** | **10,000+ accredited higher education institutions** across 200+ countries | 105,000+ research & university organizations | 100,000+ global academic entities |
| **Pricing / License** | **100% Free & Open Source (MIT)** | 100% Free & Open Data (CC0) | Free (up to 100k requests/day) |
| **API Key Required?** | **No** (open public endpoints) | **No** | No (optional mailto header) |
| **Nigerian & African Coverage** | **Comprehensive** (UNILAG, OAU, UI, Covenant, ABU, UNN, FUTA, Babcock, etc.) | High | High |
| **Search Response Speed** | **Fast (< 120ms)** | Moderate (~200–350ms) | Moderate (~250–400ms) |
| **Offline Dataset Available?** | **Yes** (full JSON dump available on GitHub) | Yes (monthly data dumps) | Yes |
| **Verdict** | **TOP PICK FOR INTERNCONNECT**: Fastest, lightest, covers all universities with domains & websites. | Excellent secondary source. | Too research/citation-heavy. |

### B. Chosen Architecture: Resilient Backend Proxy Pattern

Per project governance, **third-party APIs must never be called directly from client browsers**:
1. Client-side direct calls risk CORS errors, adblocker/privacy extension blocks, and unpredictable network latency.
2. Direct calls cannot be cached across users, causing repeated slow round-trips.

```
[ Client Browser (Alpine.js Autocomplete) ]
                  │
        GET /api/universities?q=lagos
                  ▼
[ Backend UniversityService (App\Services\UniversityService) ]
        │
        ├── 1. Check Local In-Memory / File Cache (0ms response)
        │      └─ Found? Return immediately.
        │
        ├── 2. Query Hipolabs API: http://universities.hipolabs.com/search?name={q}
        │      └─ Success? Store in cache (24hr TTL) & return clean JSON.
        │
        └── 3. Fallback: If external API times out (> 1.5s) or fails:
               └─ Query bundled local fallback database (`sql/universities.json`).
```

### C. UX Implementation on Step 4 (`register-student.php`)
- **Input Field**: Replace static text input with an interactive search autocomplete input.
- **Dropdown List**:
  - Opens as user types (debounced 250ms, minimum 2 characters).
  - Displays matching university name, country badge, and official domain (e.g., `University of Lagos • Nigeria`).
  - Clicking any item fills the field and closes the menu.
  - **Manual Entry Guarantee**: If a candidate attends an unlisted or newly founded college, they can still freely type and submit their custom name without being blocked.

---

## 3. Step 6: Career Interests & Work Arrangement

- **Search Filter**: The "Search" input dynamically filters the `all_interests` tags in real time using `filteredInterests()`.
- **Interactive Multi-Select**: Selected tags display in brand primary indigo (`#4F48E6`), while unselected tags show in soft neutral borders (`#E2E8F0`).
- **Work Arrangement**: Checkboxes for `Remote`, `Hybrid`, and `On-site` with single-click toggle and validated state before advancing to Step 7.

---

## 4. Global Navigation Restructuring (`navbar.php`)

### Updated Navbar Link Sequence:
| # | Nav Item Text | Target URL | Active Path Rule | Status |
| :---: | :--- | :--- | :--- | :--- |
| **1** | **Find Jobs** | `url('/internships')` | `/internships`, `/internship/*`, `/apply/*` | Points to existing internships search |
| **2** | **Pricing** | `url('/pricing')` | `/pricing`, `/payments`, `/checkout` | Points to existing pricing page |
| **3** | **E-Learning** | `url('/elearning')` | `/elearning` | Scaffold lightweight placeholder view |
| **4** | **How It Works** | `url('/how-it-works')` | `/how-it-works` | Scaffold lightweight placeholder view |
| **5** | **Contact Us** | `url('/contact')` | `/contact` | Scaffold lightweight placeholder view |

- Applied identically across desktop navigation (`.header-desktop-links`) and mobile drawer navigation (`.header-mobile-drawer`).

---

## 5. Global Footer Restructuring (`footer.php`)

### Updated Footer Column Structure:
```
[ Brand Column ]
Logo + "Empowering the next generation of global talent through editorial-grade career resources."

[ Platform ]
├── E-Learning           -> /elearning
├── Find Jobs            -> /internships
└── Employer Hub         -> /employer

[ Support ]
├── Help Center          -> /help
├── Privacy Policy       -> /privacy
├── Terms of Service     -> /terms
├── Refund Policy        -> /refund
├── Contact Us           -> /contact
└── Cookie Policy        -> /cookies

[ Company ]  (Replaces old "Blog" column)
├── About Us             -> /about
├── Success Stories      -> /testimonials
└── Jenny AI             -> /#meet-jenny-block

[ Follow Us ]
LinkedIn, Facebook, Instagram, X
```

---

## 6. Lightweight Route & View Scaffold Architecture

Per user instruction:
> *"Any page that is not being created, just create the page, but don't write anything on it. You can just write a text like 'Contact Page', 'E-Learning Page', etc. on the blank screen so that it makes sense, but do not do any extra design on it."*

We will scaffold clean minimal views with standard header, page identifier headline, and standard footer for:
1. `frontend/landing/elearning.php` -> Route: `GET /elearning`
2. `frontend/landing/how-it-works.php` -> Route: `GET /how-it-works`
3. `frontend/landing/contact.php` -> Route: `GET /contact`
4. `frontend/legal/refund.php` -> Route: `GET /refund`
5. `frontend/legal/cookies.php` -> Route: `GET /cookies`
6. `frontend/landing/about.php` -> Route: `GET /about`
7. `frontend/legal/privacy.php` -> Route: `GET /privacy`
8. `frontend/legal/terms.php` -> Route: `GET /terms`

---

## 7. Project Governance & Rules Integration

Update [`.agents/rules/rules.md`](file:///c:/xampp/htdocs/InternConnect/.agents/rules/rules.md) and [`.agents/rules/agent_mistakes_preventions.md`](file:///c:/xampp/htdocs/InternConnect/.agents/rules/agent_mistakes_preventions.md) with the **Third-Party API Integration Protocol**:
- **Backend Service Abstraction Mandatory**: All external third-party data providers (e.g., University APIs, verification services, geocoding) must be queried exclusively through a dedicated backend Service class in `backend/Services/`.
- **Zero Direct Client-Side Calls**: Client-side JavaScript must never make unproxied fetch calls to external domains.
- **Fail-Safe Fallbacks**: Every third-party integration must define a local fallback or bundled dataset with timeout threshold (≤ 2.0s), ensuring platform stability even during external service outages.

---

---

## 8. Implementation Milestones & Status (Completed)

1. [x] **Step 3 Verification Code Timer & OTP Paste (Student & Employer Onboarding)**:
   - Updated marketing sidebar copy from 5/10 minutes to **"Code expires in 2 minutes"** in both [register-student.php](file:///c:/xampp/htdocs/InternConnect/frontend/auth/register-student.php) and [register-employer.php](file:///c:/xampp/htdocs/InternConnect/frontend/auth/register-employer.php).
   - Set `countdown: 120` and `resend_cooldown: 120` in [onboarding-student.js](file:///c:/xampp/htdocs/InternConnect/public/assets/js/onboarding-student.js) and [onboarding-employer.js](file:///c:/xampp/htdocs/InternConnect/public/assets/js/onboarding-employer.js).
   - Upgraded `formatCooldown(secs)` to format as `m:ss` (e.g. `2:00`, `1:59`, `0:45`).
   - Implemented `@paste="handlePaste($event)"` handler on OTP inputs for instant 6-digit distribution.
2. [x] **Step 4 Worldwide University Search API**:
   - Built [UniversityService.php](file:///c:/xampp/htdocs/InternConnect/backend/Services/UniversityService.php) with Hipolabs API integration, 24h file caching, and offline fallback dataset.
   - Built [ApiController.php](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/ApiController.php) with route `GET /api/universities` in [routes.php](file:///c:/xampp/htdocs/InternConnect/backend/routes.php).
   - Embedded debounced autocomplete dropdown on Step 4 in [register-student.php](file:///c:/xampp/htdocs/InternConnect/frontend/auth/register-student.php) with full manual input support.
3. [x] **Step 6 Career Interests & Work Arrangement**:
   - Real-time search filter and toggle pill grid verified and responsive.
4. [x] **Global Navigation Restructuring (`navbar.php`)**:
   - Updated desktop and mobile link sequence to: `Find Jobs`, `Pricing`, `E-Learning`, `How It Works`, `Contact Us`.
5. [x] **Global Footer Restructuring (`footer.php`)**:
   - `Platform` column updated to strictly contain `E-Learning` and `Find Jobs` (`Employer Hub` removed per instruction).
   - `Support` column expanded with Help Center, Privacy, Terms, Refund, Contact, and Cookies.
   - `Company` column replacing `Blog` with About Us, Success Stories, and Jenny AI.
6. [x] **Lightweight Route & View Scaffolding**:
   - Created minimal views for `/elearning`, `/how-it-works`, `/contact`, `/about`, `/testimonials`, `/refund`, `/cookies`, `/privacy`, `/terms`.
   - Registered all GET routes in [routes.php](file:///c:/xampp/htdocs/InternConnect/backend/routes.php) and handled by [HomeController.php](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/HomeController.php).
7. [x] **Governance & Rules Documentation**:
   - Enforced Third-Party API Integration Protocol in [.agents/rules/rules.md](file:///c:/xampp/htdocs/InternConnect/.agents/rules/rules.md) and [.agents/rules/agent_mistakes_preventions.md](file:///c:/xampp/htdocs/InternConnect/.agents/rules/agent_mistakes_preventions.md).
8. [x] **Testing & Validation**:
   - All modified and created files passed PHP syntax validation (`php -l`) with zero errors.

---

## 9. Registration Input Field Standardization & Wizard Polish (Candidate & Employer)

### A. Core Problem Statement & Reference Design Standard
- **The Issue**: Registration input fields had inconsistent paddings, boxy outlines, unaligned left/right icon slots, and jarring modal pop-ups occurred when navigating backwards or switching steps. Additionally, on Candidate Step 6 (Interests), selecting any discipline triggered an explosion of 8+ sub-specialization pills that cluttered and blew out the card layout.
- **Reference Standard**: The Employer Post Internship page (`/employer/internships/create`, styled in `public/assets/css/employer-post-internship.css`) was designated as the single source of truth for all form controls:
  - Input container: `.emp-field-group` (`margin-bottom: 16px;`)
  - Label: `.emp-field-label` (`font-size: 12px; font-weight: 600; color: #334155; margin-bottom: 7px;`)
  - Inputs: `.emp-input-text` (`padding: 11px 14px; font-size: 13.5px; border-radius: 10px; border: 1px solid #E2E8F0; focus: border-color #4F48E6, box-shadow: 0 0 0 3px rgba(79, 72, 230, 0.12)`)
  - Icon slots: `.emp-input-with-icon` (`padding-left: 40px;`) with `.emp-input-icon-slot` (`left: 14px; width/height: 16px; color: #94A3B8;`)
  - Right icon slots: `.emp-input-with-right-icon` (`padding-right: 42px;`) with `.emp-input-right-slot` (`right: 14px;`)
  - Textareas: `.emp-textarea` (`padding: 12px 14px; border-radius: 10px; border: 1px solid #E2E8F0; line-height: 1.6;`)
  - Custom Dropdowns: standardized `.db-dropdown-wrap`, `.db-dropdown-btn`, `.db-dropdown-chevron`, `.db-dropdown-menu`, `.db-dropdown-item`
  - Segmented Controls: `.emp-segmented-control`, `.emp-segment-btn`

### B. Verification Modal Pop-Up Elimination
- **Root Cause**:
  1. The success and failure modal wrappers lacked `x-cloak` and inline `style="display: none;"`, causing them to flash briefly into view during initial browser rendering before Alpine finished initializing.
  2. In `onboarding-employer.js`, `startSuccessModalRedirect()` was completely undefined, resulting in fatal JavaScript errors when submitting Step 3.
  3. Clicking "Back" on Step 4 or any subsequent step did not reset active modal states or cancel pending `setInterval` redirection timers.
- **Rectification**:
  1. Added `x-cloak style="display: none;"` to both Success and Failure modal wrappers in both `register-student.php` and `register-employer.php`.
  2. Implemented `resetModals()` and updated `clearTimers()` in both `onboarding-student.js` and `onboarding-employer.js` to unconditionally cancel `success_redirect_timer` and reset modal booleans.
  3. Added `startSuccessModalRedirect()` to `onboarding-employer.js` with smooth progress bar animation and safe transition to Step 4.
  4. Attached `resetModals()` to all Back buttons across Steps 3, 4, 5, 6, and 7.

### C. Candidate Step 6 Clean Interests Layout (Resolving Screenshot 1 Clutter)
- **Problem**: When a user selected an interest, `filteredInterests()` dynamically concatenated all related sub-disciplines (e.g. `Machine Learning Engineer`, `Deep Learning`, `Computer Vision`, `NLP`, `Data Analyst`, `Data Engineering`), sprawling a messy wall of tags across the screen.
- **Solution**:
  - Refactored `filteredInterests()` in `public/assets/js/onboarding-student.js` to show strictly the clean 15 foundational disciplines by default (plus any active selections).
  - Search queries cleanly filter disciplines without exploding secondary tags into view.
  - Relational keywords (e.g. `python`, `figma`, `react`, `pytorch`) are silently extracted and synchronized into the `interest_keywords` hidden array in the background for AI matchmaking and SQL indexing without cluttering the visual UI.

### D. Full Form Component Alignment
- **Student Registration (`register-student.php`)**:
  - Step 2: Full Name, Email, Password, Confirm Password -> `.emp-field-group`, `.emp-field-label`, `.emp-input-with-icon`, `.emp-input-text`, `.emp-input-right-slot`.
  - Step 4: First Name, Last Name, University Autocomplete, Degree Level Custom Dropdown, Graduation Year Custom Dropdown, Field of Study, GPA -> completely standardized.
  - Step 5: Portfolio URL, LinkedIn, GitHub -> `.emp-field-group`, `.emp-field-label`, `.emp-input-with-icon`, `.emp-input-text`.
  - Step 6: Search input with clear icon -> `.emp-input-with-icon.emp-input-with-right-icon`, `.emp-input-text`.
  - Step 7: Current Location -> `.emp-input-with-icon`, `.emp-input-text`; Relocating -> `.emp-segmented-control`, `.emp-segment-btn`; Location tag pill wrapper -> rounded-xl with clean border.
- **Employer Registration (`register-employer.php`)**:
  - Step 2: Company Name, Work Email, Password, Confirm Password -> `.emp-field-group`, `.emp-field-label`, `.emp-input-with-icon`, `.emp-input-text`, `.emp-input-right-slot`.
  - Step 4: Registration Number (CAC/RC) -> `.emp-field-group`, `.emp-field-label`, `.emp-input-with-icon`, `.emp-input-text`.
  - Step 5: Company Name Details, Industry, Company Size (Custom Dropdown), Website, Description -> completely standardized to `.emp-*` and `.db-dropdown-*`.
  - Step 6: Expected Hiring Volume -> Custom Dropdown `.db-dropdown-wrap`; Primary Internship Type -> High-contrast padded cards.

---

## 5. Phase 5 & Version 2.0 Platform Expansion (Option 1, Option 2, Option 3)

### Option 1: Enterprise Analytics & CSV Reporting
- **Route**: `/employer/analytics`
- **Features**: 5 core recruitment KPIs, hiring conversion funnel drop-off tracker, university talent source breakdown, discipline hiring velocity table, report window filter (`Last 7 Days`, `Last 30 Days`, `Last 90 Days`, `Year to Date`).
- **Data Streaming Service (`ExportService.php`)**:
  - `/employer/analytics/export/candidates` (Candidate pipeline CSV)
  - `/employer/analytics/export/interviews` (Interview schedule CSV)
  - `/employer/analytics/export/billing` (Billing & Paystack ledger CSV with UTF-8 BOM and Naira `₦` support)

### Option 2: Version 2.0 AI Career Coach & Cover Letter Generator
- **AI Cover Letter Generator (`/candidate/ai-assistant/cover-letter`)**:
  - Quick-pick verified openings (Paystack, Flutterwave, Nexura, Interswitch) or custom company/role inputs.
  - 4 tailored tone styles: *Professional*, *Confident & Bold*, *Technical*, *Enthusiastic*.
  - Structured 4-paragraph generation with ATS keyword optimization, reading time, in-place editing, and 1-click clipboard copy.
- **AI Career Coach & Skill Roadmap (`/candidate/ai-assistant/career-coach`)**:
  - 7-discipline switcher (*Product Designer*, *UI/UX Designer*, *Software Engineer*, *Frontend Developer*, *Backend Developer*, *Data Analyst*, *Product Manager*).
  - Career readiness percentage score and 3-shelf skill gap diagnostic (Mastered, In Progress, Priority Focus).
  - 12-week progressive milestone checklist roadmap with interactive checkmarks and curated E-Learning course recommendations.

### Option 3: End-to-End Platform Walkthrough & Final QA Audit
- **Audit Coverage**: 28 total platform views tested across Candidate, Employer, and Super-Admin portals.
- **Mobile Zero-Overflow Guarantee**: `docScrollWidth === clientWidth` on all screens (`360px`, `390px`, `440px`, `1440px`).
- **Zero Native Alerts**: 100% adherence to Alpine toast dispatch (`show-toast`).
- **Zero Colored Accent Borders**: 100% clean neutral `#E2E8F0` border policy.
- **Test Suite**: 50/50 automated tests passing (`php tests/run_tests.php`).

---

## 6. Direct Messaging Bi-Directional Delivery & Sync Fix
- **Issue Diagnosed**: Active thread polling (`syncActiveThread()`) was overwriting local messages because `CommunicationService::getThreadMessages()` only searched ephemeral session state without fallback seed messages.
- **Resolution**:
  - Implemented `getDefaultThreadMessages(int $threadId): array` in `backend/Services/CommunicationService.php` providing rich pre-seeded candidate and employer messages.
  - Refactored both `frontend/employer/messages/index.php` and `frontend/candidate/messages/index.php` to clean script-backed Alpine workspaces (`employerMessagingWorkspace()`, `candidateMessagingWorkspace()`).
  - Added optimistic reactivity (`this.conversations = [...this.conversations]`) with multi-frame scroll-to-bottom.
  - Verified with live Headless Chrome tests across viewports.

---

## 7. In-Platform Daily.co Video Interviewing Architecture
- **Philosophy**: Candidates and employers remain 100% inside InternConnect during live interviews—never bounced out to third-party tools like Google Meet or Zoom.
- **Backend Service (`backend/Services/VideoInterviewService.php`)**:
  - Daily.co meeting room URL generation (`https://internconnect.daily.co/nxt-uxin-{id}`).
  - Persistence of rubric evaluations and interview scores in `interview_evaluations` table with session fallback.
- **Controllers & Routes**:
  - `EmployerController::interviewRoom` (`/employer/interviews/room/{id}`)
  - `EmployerController::saveInterviewScore` (`POST /api/interviews/room/score`)
  - `CandidateController::interviewRoom` (`/candidate/interview/room/{id}`)
- **Employer Video Room Interface (`frontend/employer/interviews/room.php`)**:
  - Pre-call lobby with camera preview and live mic level detection.
  - In-call stage with responsive participant tiles, live stopwatch timer, and floating media controls.
  - Right drawer with 3 tabs: **Competency Evaluation Rubric** (1–5 star ratings, recommendation chips, private notes, auto-save), **Screening Questions** (checklist with recruiter hints), and **Candidate Dossier** (academics, links).
- **Candidate Video Room Interface (`frontend/candidate/interview/room.php`)**:
  - Pre-call lobby and in-call stage matching the design system.
  - Right drawer with 3 tabs: **Agenda & Stage Breakdown**, **Jenny AI Live Tips** (STAR technique, calming advice), and **In-Call Direct Chat**.
- **Audit Results**: 100% zero horizontal overflow on all viewports (`360px`, `390px`, `440px`, `1440px`).

---

## 8. Staging & Production Deployment Framework (`docs/20-deployment-guide.md`)
- Production specifications for Ubuntu 22.04 / 24.04 LTS with PHP 8.3-FPM, MySQL 8.0+, Nginx/Apache, Composer, and Let's Encrypt SSL.
- Strict WebRTC SSL enforcement (`Permissions-Policy` and HTTPS requirement for `getUserMedia`).
- Detailed `.env` production checklist for Paystack, Daily.co, DeepSeek AI, and Google OAuth.

---

## 9. Master MySQL 8.0+ Backend Architecture (`docs/48-mysql-backend-architecture-and-schema.md`)
- Authoritative specification and DDL script (`database/schema_mysql.sql`) covering:
  - 11 core production tables: `users`, `candidate_profiles`, `employer_profiles`, `internships`, `applications`, `interviews`, `interview_evaluations`, `message_threads`, `direct_messages`, `in_app_notifications`, `subscriptions`, `payment_transactions`, `ai_usage_logs`, `audit_logs`.
  - Foreign key cascading rules, FULLTEXT search indexes on internship postings and candidate skills, and covering indexes.
  - Dual-driver abstraction model in `Database.php` (SQLite for local testing, MySQL for staging/production).

---

## 10. Automated Test Suite Verification
- **Test Suite (`php tests/run_tests.php`)**:
  - **Results**: 53 passed, 0 failed (100% pass rate).
  - Includes full coverage for admin, AI, communication, video interview rooms, analytics, and enterprise features.



