# InternConnect Master Walkthrough & System Guide (`md/general.md`)

This master walkthrough document details all platform views, route paths, architecture components, and direct navigation links for the **InternConnect** project.

---

## 1. Directory Navigation Table

| Page Name | Local File Path (Click to open code) | Route / URI | Localhost URL (Normal) | Localhost URL (with `/public/`) | Description |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Landing / Homepage** | [`landing/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/index.php) | `/` | [Open](http://localhost/InternConnect/) | [Open](http://localhost/InternConnect/public/) | Main landing page of InternConnect featuring hero, trusted companies, categories, feature showcase, testimonials, and 5-column footer. |
| **Pricing & Plans** | [`landing/pricing.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/pricing.php) | `/pricing` | [Open](http://localhost/InternConnect/pricing) | [Open](http://localhost/InternConnect/public/pricing) | Tiered pricing plans (Free, Pro, Enterprise), billing toggle, comparison grid, and "Ready to Find Your Next Opportunity?" CTA banner. |
| **Payment & Checkout Upgrade** | [`landing/payment.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment.php) | `/payments` or `/pricing/upgrade` or `/checkout` | [Open](http://localhost/InternConnect/payments) | [Open](http://localhost/InternConnect/public/payments) | 2-column checkout page with left visual card (`picing_side_aq52aw.jpg`), plan selection (Pro / Premium), billing cycle toggle (Monthly / Annual with 20% discount), and Paystack popup modal. |
| **Payment Successful** | [`landing/payment-success.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-success.php) | `/payment/success` | [Open](http://localhost/InternConnect/payment/success) | [Open](http://localhost/InternConnect/public/payment/success) | 2-column celebration screen with smiling candidate photo (`pricing_success_n0cbzk.png`), animated blue check badge, plan summary, renewal date, and "Go to Dashboard" CTA. |
| **Payment Declined** | [`landing/payment-declined.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-declined.php) | `/payment/declined` | [Open](http://localhost/InternConnect/payment/declined) | [Open](http://localhost/InternConnect/public/payment/declined) | 2-column declined screen with concerned candidate photo (`payment_declined_jyso8t.png`), animated red X badge, failure reason card, "Try Again" & "Choose another plan" buttons. |
| **Payment Cancelled** | [`landing/payment-cancelled.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-cancelled.php) | `/payment/cancelled` | [Open](http://localhost/InternConnect/payment/cancelled) | [Open](http://localhost/InternConnect/public/payment/cancelled) | 2-column cancellation screen with candidate photo (`payment_cancelled_dnvsmc.png`), animated red X badge, cancellation status summary, and retry buttons. |
| **Browse Internships** | [`landing/internships.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/internships.php) | `/internships` | [Open](http://localhost/InternConnect/internships) | [Open](http://localhost/InternConnect/public/internships) | List of all available internship listings with filtering by category, location, and type. |
| **Internship Detail (Product Design)** | [`landing/internship-detail.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/internship-detail.php) | `/internship/senior-product-design-intern` | [Open](http://localhost/InternConnect/internship/senior-product-design-intern) | [Open](http://localhost/InternConnect/public/internship/senior-product-design-intern) | Detail page for the Senior Product Design Intern role. |
| **Internship Detail (Software Engineering)** | [`landing/internship-detail.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/internship-detail.php) | `/internship/software-engineer-intern` | [Open](http://localhost/InternConnect/internship/software-engineer-intern) | [Open](http://localhost/InternConnect/public/internship/software-engineer-intern) | Detail page for the Software Engineering Intern role. |
| **Apply to Internship (Product Design)** | [`candidate/application.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/application.php) | `/apply/senior-product-design-intern` | [Open](http://localhost/InternConnect/apply/senior-product-design-intern) | [Open](http://localhost/InternConnect/public/apply/senior-product-design-intern) | Candidate application form with resume upload and cover note. |
| **Apply to Internship (Software Engineering)** | [`candidate/application.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/application.php) | `/apply/software-engineer-intern` | [Open](http://localhost/InternConnect/apply/software-engineer-intern) | [Open](http://localhost/InternConnect/public/apply/software-engineer-intern) | Candidate application form for the engineering position. |
| **Application Success** | [`candidate/application-success.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/application-success.php) | N/A (POST response) | N/A | N/A | Confirmation page rendered after successfully submitting an application. |
| **Login** | [`auth/login.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/login.php) | `/login` | [Open](http://localhost/InternConnect/login) | [Open](http://localhost/InternConnect/public/login) | User login portal supporting email & password or OAuth sign-in. |
| **Register (Role Selection)** | [`auth/register.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/register.php) | `/register` | [Open](http://localhost/InternConnect/register) | [Open](http://localhost/InternConnect/public/register) | Account type selection (Candidate or Employer). |
| **Register Student** | [`auth/register-student.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/register-student.php) | `/register/student` | [Open](http://localhost/InternConnect/register/student) | [Open](http://localhost/InternConnect/public/register/student) | Candidate registration page with custom dropdowns. |
| **Register Employer** | [`auth/register-employer.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/register-employer.php) | `/register/employer` | [Open](http://localhost/InternConnect/register/employer) | [Open](http://localhost/InternConnect/public/register/employer) | Employer registration page with custom dropdowns. |
| **Forgot Password** | [`auth/forgot-password.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/forgot-password.php) | `/forgot-password` | [Open](http://localhost/InternConnect/forgot-password) | [Open](http://localhost/InternConnect/public/forgot-password) | Password recovery request form. |
| **Verify Reset Code** | [`auth/verify-code.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/verify-code.php) | `/verify-code` | [Open](http://localhost/InternConnect/verify-code) | [Open](http://localhost/InternConnect/public/verify-code) | Input screen for 6-digit verification code. |
| **Reset Password** | [`auth/reset-password.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/reset-password.php) | `/reset-password` | [Open](http://localhost/InternConnect/reset-password) | [Open](http://localhost/InternConnect/public/reset-password) | Form to set new account password with strength validation. |
| **Password Reset Success** | [`auth/password-reset-success.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/password-reset-success.php) | `/password-reset-success` | [Open](http://localhost/InternConnect/password-reset-success) | [Open](http://localhost/InternConnect/public/password-reset-success) | Confirmation screen after password reset completion. |
| **Welcome (Student Onboarding)** | [`auth/welcome.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/welcome.php) | `/welcome` | [Open](http://localhost/InternConnect/welcome) | [Open](http://localhost/InternConnect/public/welcome) | Step-by-step onboarding wizard for candidates. |
| **Welcome Success** | [`auth/welcome_success.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/welcome_success.php) | `/welcome/success` | [Open](http://localhost/InternConnect/welcome/success) | [Open](http://localhost/InternConnect/public/welcome/success) | Celebration state of the candidate onboarding wizard. |
| **Welcome Employer** | [`auth/welcome-employer.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/welcome-employer.php) | `/welcome/employer` | [Open](http://localhost/InternConnect/welcome/employer) | [Open](http://localhost/InternConnect/public/welcome/employer) | Onboarding wizard and profile activation for employers. |
| **Welcome Back** | [`auth/welcome-back.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/welcome-back.php) | `/welcome-back` | [Open](http://localhost/InternConnect/welcome-back) | [Open](http://localhost/InternConnect/public/welcome-back) | Login success / role redirection welcome screen. |
| **Candidate Dashboard** | [`candidate/dashboard/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/dashboard/index.php) | `/candidate/dashboard` | [Open](http://localhost/InternConnect/candidate/dashboard) | [Open](http://localhost/InternConnect/public/candidate/dashboard) | Candidate portal home dashboard view with topbar, sidebar, and floating Jenny AI. |
| **Candidate Saved Internships** | [`candidate/saved/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/saved/index.php) | `/candidate/saved` | [Open](http://localhost/InternConnect/candidate/saved) | [Open](http://localhost/InternConnect/public/candidate/saved) | Bookmarked/saved internships listing for candidates. |
| **Candidate My Applications** | [`candidate/applications/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php) | `/candidate/applications` or `/applications` | [Open](http://localhost/InternConnect/candidate/applications) | [Open](http://localhost/InternConnect/public/candidate/applications) | Application tracking journey view with 5 KPI metrics, 2 urgent action banners, search & filter controls, and pagination. |
| **Candidate View Interview Invite** | [`candidate/interview/invite.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/interview/invite.php) | `/candidate/interview` or `/interview` | [Open](http://localhost/InternConnect/candidate/interview) | [Open](http://localhost/InternConnect/public/candidate/interview) | Upcoming Interview Invite page: hero countdown banner, Vanguard Design role card, Google Meet joining box, interviewer & format cards, submission dossier drawer, meeting details, and reschedule triggers. |
| **Candidate Review Offer** | [`candidate/offers/review.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/offers/review.php) | `/candidate/offer` or `/candidate/offers` | [Open](http://localhost/InternConnect/candidate/offer) | [Open](http://localhost/InternConnect/public/candidate/offer) | Formal Offer Review page: banner with green pill & title, summary card, 2-col layout with formal Offer Letter & Job Description, and action footer bar (Accept, Reject, Message). |
| **Candidate Offer Accepted** | [`candidate/offers/accepted.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/offers/accepted.php) | `/candidate/offer/accepted` | [Open](http://localhost/InternConnect/candidate/offer/accepted) | [Open](http://localhost/InternConnect/public/candidate/offer/accepted) | Celebration page after accepting an offer: "You're going to Stellar Systems, Sarah", summary card with `Placement Secured` badge, and action cards. |
| **Candidate Offer Declined** | [`candidate/offers/declined.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/offers/declined.php) | `/candidate/offer/declined` | [Open](http://localhost/InternConnect/candidate/offer/declined) | [Open](http://localhost/InternConnect/public/candidate/offer/declined) | Confirmation page after declining an offer: "Offer Declined" banner, summary card with `Offer Declined` status, explanation, and action cards. |
| **Candidate Profile Settings** | [`candidate/profile.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/profile.php) | `/candidate/profile` | [Open](http://localhost/InternConnect/candidate/profile) | [Open](http://localhost/InternConnect/public/candidate/profile) | Candidate profile settings with custom Alpine dropdowns. |
| **Employer Dashboard** | [`employer/dashboard/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/dashboard/index.php) | `/employer/dashboard` | [Open](http://localhost/InternConnect/employer/dashboard) | [Open](http://localhost/InternConnect/public/employer/dashboard) | Employer portal talent management dashboard view with top KPI cards. |
| **Employer Company Profile** | [`employer/company/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/company/index.php) | `/employer/company` or `/companies` | [Open](http://localhost/InternConnect/employer/company) | [Open](http://localhost/InternConnect/public/employer/company) | Company Profile & Identity showcase with hero banner, HK logo, About, Culture, Gallery, Reviews, and Contact Info. |
| **Employer Profile Settings** | [`employer/profile/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/profile/index.php) | `/employer/profile` | [Open](http://localhost/InternConnect/employer/profile) | [Open](http://localhost/InternConnect/public/employer/profile) | Complete Employer Organization settings: Cover/Logo management, CAC verification, Company Details, Department multi-select pills, and Notification Switches. |
| **Candidate Help Center Hub** | [`candidate/help/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/help/index.php) | `/help` or `/candidate/help` | [Open](http://localhost/InternConnect/help) | [Open](http://localhost/InternConnect/public/help) | Purple hero search banner, suggestion chips, 6 knowledge categories, Popular FAQs accordion, and Jenny AI Support Chatbot. |
| **Help Center Search** | [`candidate/help/search.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/help/search.php) | `/help/search` | [Open](http://localhost/InternConnect/help/search?q=interview) | [Open](http://localhost/InternConnect/public/help/search?q=interview) | Knowledge base search results view with highlighted `<mark>` keywords and category badges. |
| **Help Center Article Detail** | [`candidate/help/article.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/help/article.php) | `/help/article/how-to-prepare-for-interview` | [Open](http://localhost/InternConnect/help/article/how-to-prepare-for-interview) | [Open](http://localhost/InternConnect/public/help/article/how-to-prepare-for-interview) | Structured article content, reading time, feedback card ("Was this helpful?"), and related articles. |
| **Log Out (Auth State Reset)** | [`backend/Controllers/AuthController.php`](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/AuthController.php) | `/logout` | [Open](http://localhost/InternConnect/logout) | [Open](http://localhost/InternConnect/public/logout) | Logs out the current user, switches the navbar to logged-out state, and redirects to home. |

---

## 2. Core Architecture & UI Components

### 2.0 Candidate Portal Navigation Components
- **Modular Components**:
  - **Shared Candidate Sidebar** ([`candidate-sidebar.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/candidate-sidebar.php)):
    - Standard brand logo with optimal height & width (`Logo_dark_dx00tg.svg`).
    - Candidate profile block with round avatar, candidate name (`Sarah Peters`), and role title (`Student Applicant`).
    - 8 Navigation items: `Dashboard`, `Saved Internships`, `My Application`, `Browse Opportunities`, `Profile Settings`, `Billings & Subscription`, `Help Center`, `Logout`.
    - Bottom Dark Promo Card (`db-promo-dark`): Dark navy card with "Go Further" headline and solid blue "Upgrade Now" CTA.
  - **Shared Candidate Top Navbar** ([`candidate-topnav.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/candidate-topnav.php)):
    - Left brand logo & mobile hamburger trigger.
    - Center links: `Find Internships`, `How it Works`, `E-Learning`, `Payments`, `Success Stories`.
    - Right section: `Search Internships` input box, Notification bell with unread red badge dot, and Candidate profile avatar with dropdown.

### 2.1 Reusable Pagination Component & Function
- **Helper Function**: [`render_pagination(...)`](file:///c:/xampp/htdocs/InternConnect/backend/Core/helpers.php)
  - Accessible, responsive pagination renderer supporting both standard link routing (`?page=N`) and reactive Alpine.js client-side pagination.
- **Shared View Component**: [`pagination.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/pagination.php)
- **Integrated in Candidate Applications**: [`applications/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php)

### 2.2 Payment & Upgrade Checkout Suite
- **Checkout View** ([`payment.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment.php)): 2-column layout with visual branding card and plan selection.
- **Result Views**:
  - Payment Successful ([`payment-success.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-success.php))
  - Payment Declined ([`payment-declined.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-declined.php))
  - Payment Cancelled ([`payment-cancelled.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-cancelled.php))

---

## 3. Candidate Offer Review Flow (`/candidate/offer`)

- **Trigger**: Click "Review Offer" on [`candidate/applications/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php).
- **Direct Link**: **[http://localhost/InternConnect/candidate/offer](http://localhost/InternConnect/candidate/offer)**
- **Features**: Formal Offer Letter with PDF download, Job Description recap, and action modals (Accept, Reject, Message Recruiter).

---

## 4. Candidate Upcoming Interview Flow (`/candidate/interview`)

- **Trigger**: Click "View Invite" on [`candidate/applications/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php).
- **Direct Link**: **[http://localhost/InternConnect/candidate/interview](http://localhost/InternConnect/candidate/interview)**
- **Features**: Live countdown banner (`DAYS`, `HOURS`, `MINS`), Vanguard Design role card, Google Meet joining box with clipboard copy, "What you submitted" dossier drawer (PDF previews, Figma & portfolio links, candidate QA responses), and reschedule request modal.

---

## 5. Help Center Knowledge Base Portal (`/help`)

- **Direct Link**: **[http://localhost/InternConnect/help](http://localhost/InternConnect/help)**
- **Features**:
  1. **Purple Hero Search Banner**: Gradient background with search input and suggestion chips (`Quick Apply`, `Resume building`, `Interview tips`).
  2. **6 Knowledge Categories**: Getting Started, Applying for Internships, Profile & Settings, Saved Internships, Notifications & Alerts, Account & Privacy.
  3. **Popular FAQs Accordion**: Smooth interactive Alpine.js accordion with direct links to full articles.
  4. **Keyword Search Results** (`/help/search?q=...`): Real-time keyword filtering with soft-yellow `<mark>` badge highlights.
  5. **Article Detail View** (`/help/article/{slug}`): Breadcrumb navigation, reading time metadata, structured article content, feedback card (*"Was this helpful?"*), and related articles grid.

---

## 6. Global Jenny AI Assistant Robot Widget

- **Component**: [`frontend/shared/components/chatbot/jenny-ai.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/chatbot/jenny-ai.php)
- **Detailed Robotics Guide**: See [`md/robotics.md`](file:///c:/xampp/htdocs/InternConnect/md/robotics.md) for full architecture and technical breakdown.
- **Key Features**:
  - Exact brand colors: Navy `#000623`, Indigo `#4F48E6`, Light Grey `#F2F2F2`, White `#FFFFFF`.
  - True 1:1 circular floating launcher badge (`62px × 62px`, `object-fit: contain`).
  - Zero-emoji design: clean, bounded inline SVGs for all roles, calendars, avatars, and checkmarks.
  - Single-line guaranteed action buttons (`white-space: nowrap !important; flex-shrink: 0 !important;`).
  - Dual persona intelligence: Job Seeker (`Adaeze`) vs. Recruiter (`Tunde`) with dynamic first-name targeting.
  - Interactive flows: simulated CV upload, 78% radial review card, before/after comparison preview, candidate shortlisting, and redesigned online video interview scheduling card.
