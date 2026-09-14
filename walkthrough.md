# InternConnect Walkthrough & Navigation Guide

This walkthrough file contains direct navigation links to all views and pages created in the **InternConnect** project. You can click on the file paths to open the files in your editor, or click the localhost URLs to view the pages in your browser (assuming your Apache/XAMPP server is running).

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
| **Register Student** | [`auth/register-student.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/register-student.php) | `/register/student` | [Open](http://localhost/InternConnect/register/student) | [Open](http://localhost/InternConnect/public/register/student) | Candidate registration page. |
| **Register Employer** | [`auth/register-employer.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/register-employer.php) | `/register/employer` | [Open](http://localhost/InternConnect/register/employer) | [Open](http://localhost/InternConnect/public/register/employer) | Employer registration page. |
| **Forgot Password** | [`auth/forgot-password.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/forgot-password.php) | `/forgot-password` | [Open](http://localhost/InternConnect/forgot-password) | [Open](http://localhost/InternConnect/public/forgot-password) | Password recovery request form. |
| **Verify Reset Code** | [`auth/verify-code.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/verify-code.php) | `/verify-code` | [Open](http://localhost/InternConnect/verify-code) | [Open](http://localhost/InternConnect/public/verify-code) | Input screen for 6-digit verification code. |
| **Reset Password** | [`auth/reset-password.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/reset-password.php) | `/reset-password` | [Open](http://localhost/InternConnect/reset-password) | [Open](http://localhost/InternConnect/public/reset-password) | Form to set new account password with strength validation. |
| **Password Reset Success** | [`auth/password-reset-success.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/password-reset-success.php) | `/password-reset-success` | [Open](http://localhost/InternConnect/password-reset-success) | [Open](http://localhost/InternConnect/public/password-reset-success) | Confirmation screen after password reset completion. |
| **Welcome (Student Onboarding)** | [`auth/welcome.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/welcome.php) | `/welcome` | [Open](http://localhost/InternConnect/welcome) | [Open](http://localhost/InternConnect/public/welcome) | Step-by-step onboarding wizard for candidates. |
| **Welcome Success** | [`auth/welcome_success.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/welcome_success.php) | `/welcome/success` | [Open](http://localhost/InternConnect/welcome/success) | [Open](http://localhost/InternConnect/public/welcome/success) | Celebration state of the candidate onboarding wizard. |
| **Welcome Employer** | [`auth/welcome-employer.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/welcome-employer.php) | `/welcome/employer` | [Open](http://localhost/InternConnect/welcome/employer) | [Open](http://localhost/InternConnect/public/welcome/employer) | Onboarding wizard and profile activation for employers. |
| **Welcome Back** | [`auth/welcome-back.php`](file:///c:/xampp/htdocs/InternConnect/frontend/auth/welcome-back.php) | `/welcome-back` | [Open](http://localhost/InternConnect/welcome-back) | [Open](http://localhost/InternConnect/public/welcome-back) | Login success / role redirection welcome screen. |
| **Candidate Dashboard** | [`candidate/dashboard/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/dashboard/index.php) | `/candidate/dashboard` | [Open](http://localhost/InternConnect/candidate/dashboard) | [Open](http://localhost/InternConnect/public/candidate/dashboard) | Candidate portal home dashboard view with new topbar and sidebar. |
| **Candidate Saved Internships** | [`candidate/saved/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/saved/index.php) | `/candidate/saved` | [Open](http://localhost/InternConnect/candidate/saved) | [Open](http://localhost/InternConnect/public/candidate/saved) | Bookmarked/saved internships listing for candidates. |
| **Candidate My Applications** | [`candidate/applications/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php) | `/candidate/applications` or `/applications` or `/candidate` | [Open](http://localhost/InternConnect/candidate/applications) | [Open](http://localhost/InternConnect/public/candidate/applications) | Application tracking journey view with 5 KPI metrics, 2 urgent action banners, search & filter controls, and 6 diverse application stage cards. |
| **Candidate View Interview Invite** | [`candidate/interview/invite.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/interview/invite.php) | `/candidate/interview` or `/candidate/interview/invite` or `/interview` | [Open](http://localhost/InternConnect/candidate/interview) | [Open](http://localhost/InternConnect/public/candidate/interview) | Upcoming Interview Invite page: hero countdown banner (DAYS/HOURS/MINS), Vanguard Design role card, Google Meet joining box with copy/join actions, interviewer & format cards, **"What you submitted" drawer** (PDF documents with preview, Figma & portfolio links, candidate QA responses, cover letter quote), meeting details, calendar/reschedule triggers, and quick tips card. |
| **Candidate Review Offer** | [`candidate/offers/review.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/offers/review.php) | `/candidate/offer` or `/candidate/offers` or `/offer` | [Open](http://localhost/InternConnect/candidate/offer) | [Open](http://localhost/InternConnect/public/candidate/offer) | Formal Offer Review page: banner with green pill & congratulatory title, top summary card (3-dots removed), 2-col layout with formal Offer Letter (PDF download) & Job Description details, and action footer bar (Accept, Reject, inline Message button, and reassurance note). |
| **Candidate Offer Accepted** | [`candidate/offers/accepted.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/offers/accepted.php) | `/candidate/offer/accepted` or `/candidate/offer/success` | [Open](http://localhost/InternConnect/candidate/offer/accepted) | [Open](http://localhost/InternConnect/public/candidate/offer/accepted) | Celebration page after accepting an offer: "You're going to Stellar Systems, Sarah", summary card with `Placement Secured` badge, "What's next?" card, and action cards (Update profile, Share win, View application). |
| **Candidate Offer Declined** | [`candidate/offers/declined.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/offers/declined.php) | `/candidate/offer/declined` or `/candidate/offer/rejected` | [Open](http://localhost/InternConnect/candidate/offer/declined) | [Open](http://localhost/InternConnect/public/candidate/offer/declined) | Confirmation page after declining an offer: "Offer Declined" banner, summary card with `Offer Declined` status, "What happened?" explanation, and action cards (Continue job search, View other applications, Update job preferences). |
| **Candidate Profile Settings** | [`candidate/profile.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/profile.php) | `/candidate/profile` | [Open](http://localhost/InternConnect/candidate/profile) | [Open](http://localhost/InternConnect/public/candidate/profile) | Candidate profile settings & custom identity builder. |
| **Employer Dashboard** | [`employer/dashboard/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/dashboard/index.php) | `/employer/dashboard` | [Open](http://localhost/InternConnect/employer/dashboard) | [Open](http://localhost/InternConnect/public/employer/dashboard) | Employer portal talent management dashboard view with top KPI cards. |
| **Employer Company Profile** | [`employer/company/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/company/index.php) | `/employer/company` or `/companies` | [Open](http://localhost/InternConnect/employer/company) | [Open](http://localhost/InternConnect/public/employer/company) | Company Profile & Identity showcase with hero banner, HK logo, About, Culture, Gallery, Reviews, Profile Insights, and Contact Information on a clean white background. |
| **Log Out (Auth State Reset)** | [`backend/Controllers/AuthController.php`](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/AuthController.php) | `/logout` | [Open](http://localhost/InternConnect/logout) | [Open](http://localhost/InternConnect/public/logout) | Logs out the current user, switches the navbar to logged-out state, and redirects to home. |

---

## 2. Core Architecture & UI Components

### 2.0 Candidate Portal Navigation Components
- **Modular Components**:
  - **Shared Candidate Sidebar** ([`candidate-sidebar.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/candidate-sidebar.php)):
    - Standard brand logo with optimal height & width (`Logo_dark_dx00tg.svg`).
    - Candidate profile block with round avatar, pen edit button, candidate name (`Sarah Peters`), and role title (`Student Applicant`).
    - 8 Navigation items with generous vertical spacing and dedicated icons:
      1. `Dashboard` (`/candidate/dashboard`)
      2. `Saved Internships` (`/candidate/saved`)
      3. `My Application` (`/candidate/applications`)
      4. `Browse Opportunities` (`/internships`)
      5. `Profile Settings` (`/candidate/profile`)
      6. `Billings & Subscription` (`/pricing`)
      7. `Help Center` (`/help`)
      8. `Logout` (`/logout`)
    - Bottom Dark Promo Card (`db-promo-dark`): Dark navy card with "Go Further" headline, "Unlock AI interview prep and priority application reviews." description, and solid blue "Upgrade Now" CTA button (`/payments?plan=pro`).
  - **Shared Candidate Top Navbar** ([`candidate-topnav.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/candidate-topnav.php)):
    - Left brand logo (`Logo_dark_dx00tg.svg`) & mobile hamburger trigger.
    - Center links: `Find Internships`, `How it Works`, `E-Learning`, `Payments`, `Success Stories`.
    - Right section: `Search Internships` input box with search icon, Notification bell with unread red badge dot and dropdown drawer, and Candidate profile avatar with dropdown menu.
- **Scope Boundary**: The Employer portal navigation was left untouched as requested.

### 2.1 Reusable Pagination Component & Function
- **Helper Function**: [`render_pagination(...)`](file:///c:/xampp/htdocs/InternConnect/backend/Core/helpers.php)
  - Accessible, responsive pagination renderer supporting both standard link routing (`?page=N`) and reactive Alpine.js client-side pagination.
  - Generates "Showing X of Y [items]" on the left and previous chevron (`<`), active/inactive numbered buttons, and next chevron (`>`) on the right.
- **Shared View Component**: [`pagination.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/pagination.php)
  - Can be included via `include $sharedDir . '/pagination.php'` with parameters `$currentPage`, `$totalItems`, `$perPage`, `$itemName`, `$baseUrl`, and `$queryParams`.
- **Integrated in Candidate Applications**: [`applications/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php)
  - Responsive client-side interactive pagination displaying 6 items per page from the total 12 opportunities.
  - Automatically recalculates item count, active page, and total pages when switching status tabs (`All`, `Applied`, `In Review`, `Interview`, `Offer`, `Rejected`) or entering search keywords.
  - Features smooth scroll restoration on page change.
- **Mobile Responsiveness**: On mobile screens (`<=640px`), the pagination controls stack vertically and center-align for optimal touch targets.

### 2.1 Reusable Header / Navbar ([`navbar.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/navbar.php))
- **Dynamic Auth State Switching**:
  - **Logged-In State**: Displays the Search Internships input, notification bell with unread indicator dot, and the user profile avatar with dropdown menu containing links to Dashboard, Saved Internships, Applications, Help Center, Profile Settings, and **Log Out**.
  - **Logged-Out State**: Automatically hides search, notification bell, and user avatar dropdown, presenting clean **Log In** (`/login`) and **Sign Up** (`/register`) action buttons.
  - **Seamless Logout Flow**: Clicking "Log Out" invokes `/logout`, resets the session authentication flag, and redirects the user with the navbar immediately reflected in the logged-out state.
  - **Mobile Drawer Support**: The responsive mobile drawer also adjusts based on authentication state, providing mobile-friendly search and user profile options when authenticated, or Login / Sign Up CTA buttons when unauthenticated.

### 2.2 Payment & Upgrade Checkout View ([`payment.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment.php))
- **Visual Branding Card (Left)**: Uses Cloudinary-hosted visual banner (`picing_side_aq52aw.jpg`) with a rounded container, feature highlight badges, and overlay copy.
- **Selection Card (Right)**:
  - Plan type selection (Pro Plan vs Premium Plan) via query parameters or default.
  - Interactive radio cards for **Monthly Billing** vs **Annual Billing** (with 20% discount tag and "Best Value" highlight badge).
  - Dynamic price calculations and payment summary itemization.
  - **Paystack Redirection Popup Modal**: Clean modal dialog with animated progress bar, lock & shield security trust badges, and automatic redirect transition to Paystack's hosted payment gateway.

### 2.3 Payment Result Screens
1. **Payment Successful** ([`payment-success.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-success.php)):
   - Left side: Cloudinary asset (`pricing_success_n0cbzk.png`) featuring smiling candidate.
   - Right side: Animated blue checkmark ripple badge, "Payment Successful", "Your account is upgraded. You are now on **Pro** plan", itemized receipt with divider lines (Plan, Amount paid, Billing frequency, Next renewal), and "Go to Dashboard →" button.
2. **Payment Declined** ([`payment-declined.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-declined.php)):
   - Left side: Cloudinary asset (`payment_declined_jyso8t.png`) featuring concerned candidate.
   - Right side: Animated red X ripple badge, "Payment Declined", decline reason card with divider lines, "Try Again" & "Choose another plan" buttons.
3. **Payment Cancelled** ([`payment-cancelled.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-cancelled.php)):
   - Left side: Cloudinary asset (`payment_cancelled_dnvsmc.png`) featuring stressed candidate.
   - Right side: Animated red X ripple badge, "Payment Cancelled", cancellation status summary card with divider lines, "Try Again" & "Choose another plan" buttons.

---

## 3. Paystack Test Mode Suite & Simulation Guide

This section contains all official Paystack test credentials, failure test cards, and simulated payment methods for end-to-end testing in development.

### 3.1 Direct Preview URLs
You can view and test each result screen directly with custom query parameters:

| Result State | URL | Query Parameters Supported |
| :--- | :--- | :--- |
| **Payment Successful** | [http://localhost/InternConnect/payment/success](http://localhost/InternConnect/payment/success) | `?plan=pro` or `?plan=premium`<br>`&cycle=monthly` or `&cycle=annual` |
| **Payment Declined** | [http://localhost/InternConnect/payment/declined](http://localhost/InternConnect/payment/declined) | `?plan=pro&reason=Card+declined+by+issuing+bank`<br>`?reason=Insufficient+funds` |
| **Payment Cancelled** | [http://localhost/InternConnect/payment/cancelled](http://localhost/InternConnect/payment/cancelled) | `?plan=pro&cycle=monthly` |

---

### 3.2 Card Payment Testing

When prompted on Paystack test checkout, use the following standard input values:
- **Expiry Date**: Any valid future date (e.g. `12/28`)
- **CVV**: Any 3 digits (e.g. `123`)
- **PIN (if prompted)**: `1234`
- **OTP (if prompted)**: `123456`

#### A. Success Test Cards
| Card Network | Card Number | Verification Prompt | Outcome |
| :--- | :--- | :--- | :--- |
| **Visa (Standard)** | `4084 0840 8408 4081` | None | Direct Success $\rightarrow$ Redirects to **Payment Successful** |
| **Mastercard** | `5123 4567 8901 2345` | None | Direct Success $\rightarrow$ Redirects to **Payment Successful** |
| **Verve** | `5061 0000 0000 0000` | None | Direct Success $\rightarrow$ Redirects to **Payment Successful** |
| **Visa (3DS / OTP)** | `4084 0840 8408 4082` | Enter OTP: `123456` | Success $\rightarrow$ Redirects to **Payment Successful** |
| **Visa (PIN + OTP)** | `4084 0840 8408 4083` | Enter PIN: `1234`<br>Enter OTP: `123456` | Success $\rightarrow$ Redirects to **Payment Successful** |

#### B. Failure / Declined Test Cards
| Test Scenario | Card Number | Paystack Message | Outcome |
| :--- | :--- | :--- | :--- |
| **Card Declined** | `4084 0840 8408 4084` | *Card declined by issuing bank* | Redirects to **[Payment Declined](http://localhost/InternConnect/payment/declined)** |
| **Insufficient Funds** | `4084 0840 8408 4085` | *Insufficient funds* | Redirects to **[Payment Declined](http://localhost/InternConnect/payment/declined)** |
| **Incorrect PIN** | `4084 0840 8408 4086` | *Incorrect PIN* | Redirects to **[Payment Declined](http://localhost/InternConnect/payment/declined)** |
| **Expired Card** | `4084 0840 8408 4087` | *Card expired* | Redirects to **[Payment Declined](http://localhost/InternConnect/payment/declined)** |

---

### 3.3 Alternative Payment Methods Testing

#### A. Bank Transfer Simulation
1. On Paystack checkout, select the **Transfer** tab.
2. Paystack displays a test virtual account number.
3. Click **"I have sent the money"**.
4. In the simulation dialog:
   - Click **"Simulate Success"** $\rightarrow$ Triggers callback and redirects to **Payment Successful**.
   - Click **"Simulate Failure"** $\rightarrow$ Triggers callback and redirects to **Payment Declined**.

#### B. Pay with Bank Simulation
1. Select **Pay with Bank**.
2. Select **Test Bank (NG)** from the bank list.
3. Enter any test account number (e.g. `0123456789`) and authenticate.
4. Select **Authorize** (Success) or **Decline** (Declined).

#### C. USSD Simulation
1. Select **USSD**.
2. Choose any test bank (e.g. *GTBank `*737#`* or *Zenith Bank `*966#`*).
3. In the simulated USSD screen, click **"Simulate Success"** or **"Simulate Failure"**.

#### D. User Cancellation
- On Paystack checkout, clicking the **"$\times$" (Close/Cancel)** button cancels the payment flow and redirects the browser back to **[Payment Cancelled](http://localhost/InternConnect/payment/cancelled)**.

---

## 4. Candidate Offer Review Flow (`/candidate/offer`)

- **Trigger**: Click the **"Review Offer"** button on the green urgent action banner or the application card in [`candidate/applications/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php).
- **Direct Link**: **[http://localhost/InternConnect/candidate/offer](http://localhost/InternConnect/candidate/offer)**
- **Features**:
  1. **Header Banner**: `OFFER RECEIVED` badge pill, "You've received an offer, Sarah.", and congratulatory subtitle.
  2. **Top Application Summary Card**: Company avatar, verified badge, job title, location/type pills, applied status, current stage, applied date. (3-dots menu removed as specified).
  3. **Left Column - Formal Offer Letter**:
     - Header with document icon, offer received pill, and **"Download PDF"** trigger.
     - Full letter formatted to Sarah Peters from Stellar Systems, commencing date, responsibilities, compensation/growth note, acceptance deadline, and recruiter signature (Akinyele Okoro).
  4. **Right Column - Job Description Card**:
     - Position, location, type, duration (6 months).
     - Key Responsibilities checklist.
     - Required skill badges.
     - "About the Company" highlight box.
  5. **Action Footer Bar & Interactive Modals**:
     - Solid Blue **"Accept Offer"** button $\rightarrow$ opens spacious Acceptance modal with position/company/job-type recap card, and "Confirm & Accept" trigger which redirects to the **[Offer Accepted](http://localhost/InternConnect/candidate/offer/accepted)** page.
     - Outlined **"Reject Offer"** button $\rightarrow$ opens Decline modal with a custom-styled interactive select dropdown, optional personal reason textarea, and "Confirm Decline" trigger which redirects to the **[Offer Declined](http://localhost/InternConnect/candidate/offer/declined)** page.
     - Inline **"Message Recruiter"** button $\rightarrow$ opens polished Message modal with recruiter avatar & title card (Akinyele Okoro), clean subject/message focus ring inputs, and "Send Message" action.
     - Right reassurance note: *"You can still reject this offer if you change your mind. No worries!"* with security shield icon.

---

### 4.1 Offer Accepted Celebration Page (`/candidate/offer/accepted`)
- **Direct Link**: **[http://localhost/InternConnect/candidate/offer/accepted](http://localhost/InternConnect/candidate/offer/accepted)**
- **Features**:
  - Green circular checkmark badge with headline *"You're going to Stellar Systems, Sarah."*
  - Top Summary card with company name, role pill, location, job type, commencement date, and `Placement Secured` badge.
  - "What's next?" card detailing email onboarding next steps.
  - 3 Action Cards:
    - **Update your profile** $\rightarrow$ navigates to [`/candidate/profile`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/profile.php).
    - **Share your win** $\rightarrow$ interactive celebration text copy to clipboard with toast notification.
    - **View Application** $\rightarrow$ navigates to [`/candidate/applications`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php).
  - Central **"Go to Dashboard →"** CTA button $\rightarrow$ [`/candidate/dashboard`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/dashboard/index.php).

---

### 4.2 Offer Declined Confirmation Page (`/candidate/offer/declined`)
- **Direct Link**: **[http://localhost/InternConnect/candidate/offer/declined](http://localhost/InternConnect/candidate/offer/declined)**
- **Features**:
  - Red circular cross badge with headline *"Offer Declined"* and supportive message.
  - Top Summary card with company name, role pill, location, job type, start date, and `Offer Declined` status pill.
  - "What happened?" explanation card.
  - "Here's what you can do" section with 3 action cards:
    - **Continue Job Search** $\rightarrow$ navigates to [`/internships`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/internships.php).
    - **View Other Applications** $\rightarrow$ navigates to [`/candidate/applications`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php).
    - **Update Job Preferences** $\rightarrow$ navigates to [`/candidate/profile`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/profile.php).
  - Central **"Go to Dashboard →"** CTA button $\rightarrow$ [`/candidate/dashboard`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/dashboard/index.php).

---

## 5. Candidate Upcoming Interview Flow (`/candidate/interview`)

- **Trigger**: Click the **"View Invite"** button on the Vanguard Design interview banner in [`candidate/applications/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php) or `/candidate/dashboard`.
- **Direct Link**: **[http://localhost/InternConnect/candidate/interview](http://localhost/InternConnect/candidate/interview)**
- **Features & Sections**:
  1. **Breadcrumb**: *"Back to Applications"* with left chevron arrow.
  2. **Page Header**: *"Upcoming Interview"* & *"Your Interview with Vanguard Design"*.
  3. **Countdown Banner (TIME REMAINING)**:
     - Sapphire gradient header banner with clock circle icon.
     - 3 live countdown tiles (`DAYS`, `HOURS`, `MINS`) displaying remaining time until the session.
  4. **Left Column - Interview & Submission Dossier**:
     - **Company & Role Card**: Vanguard Design `V` purple avatar, role title (`Product Design Intern • Paid • Remote`), `Confirmed` green pill.
     - **Joining Instructions Box**: Google Meet code input with 1-click clipboard copy button (with checkmark transition and toast feedback) and solid *"Join Google Meet"* button.
     - **About this Interview Grid**: 2 subcards featuring interviewer profile (`Alex Chen, Senior Product Designer`) and format outline (`Portfolio Walkthrough (30m) + Q&A (15m)`).
     - **"What you submitted" Accordion Drawer (Inspired by [`/apply/senior-product-design-intern`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/application.php))**:
       - Smooth toggle accordion with chevron rotation.
       - **Resume Document Card**: `Resume_Sarah_Peters_V2.pdf` (2.4 MB) with PDF badge and interactive **"View"** modal preview trigger.
       - **Portfolio Case Study Card**: `Portfolio_Case_Study_FinTech.pdf` (8.1 MB) with pink PDF badge and **"View"** modal preview trigger.
       - **Design Artifact Links**: External link cards for **Figma Design Spec** (`figma.com/@sarahp/vanguard-design-spec`) and **Live Portfolio URL** (`sarahpeters.design/work/fintech-case-study`).
       - **Questionnaire Responses**: Detailed answers to employer questions (e.g. *"Why Vanguard Design?"* and *"Design Methodology"*).
       - **Cover Letter Block**: Italicized quote container displaying the candidate's custom cover note.
  5. **Right Column - Logistics & Actions**:
     - **Meeting Details Card**: Date (`Oct 24, 2026`), Time (`11:00 AM - 12:00 PM`), Platform (`Google Meet` with teal badge), and Timezone (`GMT+1`).
     - **Manage Interview Stack**:
       - Solid indigo **"Add to Calendar"** button (generates Google Calendar event with pre-filled Meet URL and interviewer details).
       - Outlined **"Request Reschedule"** button $\rightarrow$ opens interactive Reschedule modal with date picker, time slot selector, reason textarea, and toast notification confirmation.
     - **Quick Tips Card**: Sapphire purple header with bulb icon, structured bulleted tips (`Background Matters`, `Design Reasoning`, `Prepare Questions`), and link to the interview guide.
  6. **Interactive Modals**:
     - **Submission Document Preview Modal**: Displays simulated PDF viewer dialog with file size, category badge, and "Open Full Document" action.
     - **Reschedule Request Modal**: Allows proposing new time slots with seamless client-side submission and toast feedback.



