# Changelog

All notable changes to the InternConnect project will be documented in this file.

The format is based on Keep a Changelog, and this project adheres to Semantic Versioning.

---

## [1.0.0] - 2026-06-03

### Added

- Initial codebase structure under `app/`, `config/`, `database/`, and `public/`
- Authentication system with Role-Based Access Control (RBAC)
- Documentation files for roadmap, system architecture, database design, and features
- Deployment guide and git workflow principles
- DeepSeek AI system integration for resume optimization and recommendations
- Paystack payment processing system integration
- Log files configuration for auditing, security, and errors
- SQL file management policy rules directing generated SQL files to the `sql/` directory

---

## [Unreleased]

- **Employer Post Internship & Publishing Journey (`/employer/internships/create`, `/employer/internships/review`, `/employer/internships/success`)**:
  - **Create Opportunity Form (`frontend/employer/internships/create.php`)**: Built modern 2-column opportunity creation interface matching UI designs with zero emojis. Features Basic Information, Custom Alpine Category Dropdown with interactive keyword search and checkbox selection (Image 4 match), Segmented Internship Type control (Remote, Hybrid, On-site), Rich Text Area toolbars for Role Overview ("About the Internship", "What You'll Do", "What We're Looking For"), Interactive Alpine Skills Tag Manager with pill removal and inline addition, Custom Alpine Dropdowns for Education Level and Duration with "Apply" button triggers, Details & Duration controls, and Monthly Stipend / Perks selection.
  - **Jenny AI Mascot Mascot Card Branding**: Replaced generic icon in the "AI Internship Assistant" and "AI Optimization" cards with the high-resolution transparent waving Jenny chatbot mascot (`jenny_ai_hi.png` / Cloudinary `v1790296466`) inside a rounded avatar frame (`.emp-ai-avatar-wrap`).
  - **Review & Publish Internship (`frontend/employer/internships/review.php`)**: Implemented candidate-facing preview card with role title, location, salary pills, role overview, and **crucial structured `<li>` tag listings** with custom SVG checkmarks for "What You'll Do" and "What We're Looking For", plus detected skill tags and salary smart tip card. Right column features AI Optimization card with 85% score bar and Apply Suggestions trigger, Expected Performance metrics (120+ applicants, High visibility), Target Audience Match, and Publish Settings (Public visibility toggle, deadline edit trigger, auto-close listing).
  - **"Your role is live!" Animated Celebration Screen (`frontend/employer/internships/success.php`)**: Implemented celebratory confirmation view matching Image 3 with left rounded photo card featuring the smiling woman with laptop (`assets/images/welcome_smiling_woman.png`), "The next chapter of your career starts here." overlay, **smooth animated ripple success badge (`animation: successBadgeRipple 2.2s infinite`) and checkmark stroke drawing animation (`animation: checkDraw 2.5s infinite`)**, headline, "View Candidates" CTA, single-line "Post another role →" CTA, and My Internships management link.
  - **Single-Line Button Integrity & Mobile Responsiveness**: Fixed "Post another role →" button wrapping by enforcing `display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; white-space: nowrap !important; flex-shrink: 0 !important;` on both the button container and SVG arrow, with clean mobile stacking on devices ≤ 640px.
  - **Global Governance Rule Updates (`.agents/rules/rules.md`)**: Enshrined the Mandatory Animated Success Checkmark Rule, Single-Line Button Integrity & Non-Wrapping Action Icons Rule, and AI Feature & Assistant Card Mascot Branding Rule into project rules.



- **Jenny AI Assistant Chatbot Polish & Strict Zero-Emoji Compliance**:
  - **Ultra-Crisp 3D Launcher & Header Avatar**: Replaced header avatar and floating launcher badge with the new high-resolution 3D blue gradient badge (`jenny_icon.png` / Cloudinary `v1790300712`) featuring the crisp InternConnect student/book symbol.
  - **Guaranteed Brand Mark Rendering ("Powered by InternConnect AI")**: Replaced external image tag with pixel-perfect inline SVG brand mark (`#4F48E6`) to guarantee zero broken image frames across all network conditions and browsers.
  - **Strict Zero-Emoji Governance & Icon Modernization**: Fully purged all unicode emojis (`📅`, `💼`, `👤`, `✓`, etc.) from chat bubbles, persona bars, and action cards across the chatbot widget. Replaced with clean, bounded inline SVGs.
  - **Single-Line Button Integrity ("Applied")**: Enforced `white-space: nowrap !important;` and `flex-shrink: 0 !important;` along with an inline SVG checkmark on `.jenny-apply-btn` so "Applied" with checkmark never awkwardly wraps onto a second line.
  - **Dynamic Persona Header & Modern Pill Switcher**: Dynamically targets first names (`Job Seeker (Adaeze)` / `Recruiter (Tunde)` or active user first name) with clean SVG user/briefcase role icons and a modern rounded pill toggle button (`Switch to Recruiter` / `Switch to Job Seeker`).
  - **Premium Online Interview Scheduling Card**: Completely redesigned recruiter interview scheduling card with an inline SVG calendar icon, clean date & time zone display, duration pill (30 mins), responsive time slot pill grid (`9:00 AM`, `10:30 AM`, `2:00 PM`, `4:00 PM`, `5:30 PM`), full-width confirm CTA, and green confirmed state card.


- **Help Center Knowledge Base & Support Chatbot Integration (`/help`, `/help/search`, `/help/article/{slug}`)**:
  - **Main Help Center Hub (`frontend/candidate/help/index.php`)**: Built candidate-focused help center hub with purple hero search banner (`How can we help you, Sarah?`), live keyword search bar, popular suggestion chips (`Quick Apply`, `Resume building`, `Interview tips`), 6 category cards (*Getting Started*, *Applying for Internships*, *Profile & Settings*, *Saved Internships*, *Notifications & Alerts*, *Account & Privacy*), and smooth Alpine.js accordion for Popular FAQs with direct article links.
  - **Help Center Keyword Search (`frontend/candidate/help/search.php`)**: Implemented keyword search matching and highlighting using soft-yellow `<mark>` badges matching design specifications, displaying match counts, result snippets, and category tags.
  - **Article Detail View (`frontend/candidate/help/article.php`)**: Implemented full article view with multi-level breadcrumb navigation, category and reading time metadata, structured article content, related articles grid, interactive feedback card (*"Was this article helpful?"* with Yes/No actions), and a *"Still need help?"* CTA card triggering student support.
  - **HelpService Knowledge Provider (`backend/Services/HelpService.php`)**: Centralized repository of categories, articles, FAQs, search indexing, and keyword highlighting.
  - **Candidate Controller & Route Registration**: Registered `/help`, `/candidate/help`, `/help/search`, `/help/article`, `/help/article/{slug}`, and `/help/{category}/{slug}` in `backend/routes.php` and controller methods in `CandidateController.php`.
  - **Extracted Brand Logo & Chatbot Avatar Asset Generation**: Extracted standalone SVG mark `public/assets/images/logos/internconnect-icon.svg`, vector bot avatar `public/assets/images/chatbot/bot-avatar.svg`, and generated ultra-modern 3D AI assistant chatbot avatar `public/assets/images/chatbot/bot-avatar.jpg` with headphones and embedded InternConnect logo emblem.
  - **Modular Floating Support Chatbot Component (`frontend/shared/components/chatbot/widget.php`)**: Built floating student support launcher button and interactive popup chat dialog with agent avatar (*Alex from Student Success*), online status indicator, average wait time banner (*Avg. wait time: 2 mins*), message history, typing indicator, quick suggestions (*Syncing issues*, *Profile completion*, *Password reset*), and message input box.

- **Onboarding Wizard UX & UI Polish (Employer & Student)**:
  - **Compact Document Dropzones**: Reduced excessive height on upload dropzones (`py-4 px-4`) to make them clean, compact, and boxy.
  - **Filename Overflow Containment**: Enforced strict `overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100%` on uploaded document preview cards so long filenames never push or overflow out of the box.
  - **Optional Company Website**: Changed Employer Step 5 Website field to optional (`Website (Optional)`) and removed requirement from step validation.
  - **Enhanced Add Department Dropdown**: Added custom primary-colored scrollbar (`.custom-primary-scrollbar`) and smooth animated checkbox components.
  - **Redesigned Primary Internship Type (Step 6)**: Fixed edge spacing and vertical/horizontal padding with balanced `px-4 py-3.5` cards, brand-indigo icon containers, and smooth radio check indicators.
  - **Monochromatic & Professional Profile Preview Summary (Step 7)**: Redesigned the Program Overview & Verification summary grid into a cohesive, monochromatic corporate card layout with unified `bg-indigo-50 text-primary` icon tokens (Target Intake, Compensation Model, CAC RC Status, Company Size), eliminating clashy multi-colored badges.
  - **Monogram Logo Fallbacks (Step 5 & 7)**: Added automated company monogram avatars and `@error` image fallbacks to prevent broken image icon frames when logos are unset or draft blob URLs expire.

- **Custom Form Dropdowns Standardization & Company Overview Polish**:
  - **Eliminated Native Boxy HTML Selects Across All Portals**: Replaced all native `<select>` elements in Candidate Profile, Student Registration Wizard (`/register/student`), Employer Registration Wizard (`/register/employer`), and Employer Profile Settings (`/employer/profile`) with custom rounded Alpine.js dropdown components (`.db-dropdown-wrap`, `.db-dropdown-btn`, `.db-dropdown-menu`, `.db-dropdown-item`) matching the employer dashboard filter style.
  - **Single Tick Selection & Escaping Fix**: Fixed Degree Type and Graduation Year multi-tick bug by encoding options with `json_encode()` and using single-variable strict equality (`degree_level === dOpt` / `graduation_year === yOpt`).
  - **Company Overview Navigation & Detail Sync**:
    - Renamed sidebar navigation item from "Companies" to "**Company Overview**" (`/employer/company`).
    - Removed hero banner "Change Banner" button and avatar pencil edit icon from Company Overview (edits now properly routed exclusively to Profile Settings).
    - Linked "Edit Profile" directly to `/employer/profile`.
    - Added verified CAC RC Registration badge and dynamic target department tags from onboarding session.
  - **Master Governance Rules**: Enforced mandatory custom form dropdown rules in `.agents/rules/agent_mistakes_preventions.md` and `.agents/rules/rules.md`.

- **Employer Profile Settings & Organization Management (`/employer/profile`, `/employer/settings`)**:
  - **Employer Profile Settings View**: Built complete organization profile settings matching the candidate profile UX architecture:
    - **Brand Visual Assets**: Company Cover Banner and Logo / Avatar uploaders with drag-and-drop, live image preview, recommended aspect ratio guidelines, and instant replacement.
    - **Organization Verification & CAC**: CAC Registration / RC number input with business incorporation certificate upload card, full-screen document preview modal, and verified status indicator.
    - **Company Profile Information**: Company Name, Slogan/Tagline, Industry selection, Company Size, Overview Bio, and Culture & Values descriptions.
    - **Internship Program & Focus**: Multi-tag hiring department selector (Engineering, Product Design, Marketing, Data Science, etc.), expected intern volume, and program compensation type.
    - **Contact Details & Social Links**: Careers email, phone number, headquarters location, website URL, and social media handles (LinkedIn, X/Twitter, etc.).
    - **Notification Preferences**: Application alerts, interview RSVPs, and platform announcements switches.
    - **Danger Zone**: Organization account deactivation modal protected by company name verification input.
    - **Authentication & Security**: Primary login email (Verified), Google Workspace OAuth (Connected), Password & Security change form with visibility toggles, and Active Devices & Sessions management.
  - **Sidebar Navigation Refactoring**:
    - Removed `Saved`, `Resources`, and redundant Quick Access links from the employer sidebar.
    - Added dedicated **Profile Settings** link with active route indicators.
    - Restyled and polished the **Build your future** promo card at the bottom of the sidebar with proper spacing and gradient aesthetics.
  - **UI/UX Refinements & Bug Fixes**:
    - **Single Arrow Select Dropdowns**: Fixed repeating `v v v v v` background arrow artifacts on `<select>` inputs by enforcing `appearance: none !important;` and `background-repeat: no-repeat !important; background-position: right 14px center !important; background-size: 16px 16px !important;` across normal, hover, and focus states.
    - **Target Internship Departments Pills**: Restructured department tags into pre-rendered `.emp-tag-pill` elements inside flex-wrapping `.emp-tags-wrapper` with explicit SVG checkmarks and seamless Alpine.js state synchronization.
    - **Clean File Upload Dropzone Default**: Configured Business Verification (CAC) dropzone to initialize in an interactive empty state by default, rendering uploaded file cards only when actual documents exist.
    - **Base Controller Redirection**: Added `redirect(string $path): void` to `App\Core\Controller` to handle post-submission redirects without fatal method exceptions.
    - **Rule System Updates**: Updated `.agents/rules/agent_mistakes_preventions.md` and `.agents/rules/rules.md` with strict rules for select arrows, interactive tag pills, form redirects, and empty dropzone states.

- **Employer Company Profile View & Navigation (`/employer/company`, `/companies`, `/company`)**:
  - **Asset Integration via Cloudinary**: Uploaded and integrated the circular HK logo (`hk_logo.jpg`) and horizontal banner (`hk_banner.png`) under Cloudinary folder `internConnect/images/`.
  - **Hero Card**: High-resolution banner background with overlay circular avatar badge, verified status checkmark, company name (`TechFlow Systems`), slogan, metadata pills (`Software Development`, `Lagos, Nigeria`, `201–500 employees`), and action buttons (`Follow`, `Apply Now` / `Post Opening`).
  - **Sidebar Navigation Updates**:
    - Replaced the previous "Analytics" item with "Companies" pointing to `/employer/company`.
    - Added "Saved", "Resources", and a "Quick Access" sub-section (`Recommended for you`, `Saved companies`, `Upcoming interviews`).
    - Added bottom promo card ("Build your future with the right opportunities").
  - **Layout & Content Hierarchy**:
    - Single unified `Overview` tab without cumbersome modal switching.
    - **Left Column**: "About Company" card with tag pills, "Culture & Values" card with tag pills, "Company Gallery" (4-photo grid with `+5` overflow badge), and "Candidate & Intern Reviews" (4.9★ rating badge, verified reviewer cards, and recommendations).
    - **Right Column**: "Profile Insights" (78% strength meter + actionable checklist + "Improve Profile" button), "Contact Information" (email, phone, social links), and "Company Details" (Industry, Size, Location, Website).
    - Strictly omitted "Similar Companies" per explicit user directive.
    - Clean white background / light theme dashboard aesthetics in compliance with the **Emoji Prohibition Rule** and design standards.
  - Redesigned the 5 top metric cards (`ACTIVE POSTS`, `APPLICATIONS`, `SHORTLISTED`, `INTERVIEWS`, `HIRED`) in [frontend/employer/dashboard/index.php](file:///c:/xampp/htdocs/InternConnect/frontend/employer/dashboard/index.php) to strictly match the layout and design of the candidate "Your Internship Journey" KPI cards.
  - Structure: Left-aligned info column (Title $\rightarrow$ Big Bold Metric Number $\rightarrow$ Subtitle) with vertically-centered 44x44px rounded square icon box (`.emp-kpi-icon-wrap`) on the right.
  - Card Dimensions & Spacing: Unified with 18px 20px padding, 16px grid gap, 16px border-radius, subtle shadow, and smooth hover lift animations.

- **Candidate Profile Settings Redesign (`/candidate/profile`, `/profile`)**:
  - Reorganized profile sections to strictly follow the desired layout hierarchy: **Personal Information** $\rightarrow$ **Academic Information** $\rightarrow$ **Experience Assets** $\rightarrow$ **Notification Preferences**.
  - **Academic Information Card**:
    - Added comprehensive academic credential fields: `University Name` (`db-input`), `Expected Graduation Year/ Graduation Year` (styled custom `.db-select` dropdown spanning 2018–2030), `Degree Type` (styled custom `.db-select` dropdown), `Field of Study` (`db-input`), and `GPA/ Grade (Optional)` (`db-input`).
  - **Experience Assets Card**:
    - Renamed from "Resume & Portfolio" to "Experience Assets".
    - Preserved high-fidelity resume upload drag-and-drop zone (`.db-upload-zone`), uploaded resume pill (`.db-file-card`), and live full-screen modal preview.
    - Added dedicated, high-contrast input fields for **Portfolio Website URL**, **LinkedIn Profile**, and **GitHub Profile** in a responsive 6x6 two-column layout with SVG prefix badges.
  - **Security & Authentication Cards (Right Column)**:
    - **Authentication Card**: Fixed alignment and spacing on `Verified` and `Connected` status badges with `justify-content: space-between;`, `gap: 16px;`, and `white-space: nowrap;` so badges never crowd against email addresses.
    - **Password & Security Card**: Implemented Current Password, New Password, and Confirm Password fields with interactive eye toggles and recovery link to `/forgot-password`.
    - **Active Devices & Sessions Card**: Standardized device titles to clean names (e.g., `Windows PC`, `iPhone 15 Pro`), fixed single-line alignment for the `This Device` badge (`white-space: nowrap; flex-shrink: 0;`), and preserved location/IP metadata cleanly below.
  - **Account Management & Safe Deactivation Card (`.db-danger-card`)**:
    - Replaced the floating text button with a clean, dedicated **Account Management** card featuring dark typography and a structured action button.
    - Integrated a high-fidelity deactivation confirmation modal requiring the candidate to spell their exact full name before enabling the destructive action button, preventing accidental account deactivations.
  - **Project Governance & Anti-AI-Sloppiness Rules**:
    - Updated `.agents/rules/rules.md` and `.agents/rules/agent_mistakes_preventions.md` with strict rules enforcing pixel-perfect badge alignment, single-line badge integrity (`white-space: nowrap`), clean entity labeling, and strict SVG bounding to prevent AI-generated design artifacts.
  - **Mobile Hamburger Sidebar Fix**:
    - Implemented a resilient, global event-delegated click listener in [candidate-sidebar.php](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/candidate-sidebar.php) and [employer-sidebar.php](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/employer-sidebar.php) ensuring the mobile hamburger button (`#db-hamburger`) opens the sidebar drawer reliably across all candidate and employer views on mobile viewports.
- **Candidate Interview Preparation Guide (`/candidate/interview/guide`, `/interview/guide`)**:
  - Completely redesigned [frontend/candidate/interview/guide.php](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/interview/guide.php) matching the new design layout (Image 2):
    - **Header**: Breadcrumb navigation (`← My Application / Interview Preparation`), page title, and subtitle.
    - **Overview Readiness Strip**: Target badge icon, dynamic progress bar with percentage readout (`60%`), section count (`5 sections`), checklist count (`7 checklist items`), and estimated reading time (`~15 min read`).
    - **5 Interactive Phase Accordion Cards**: Pre-Interview Preparation, STAR Framework, Portfolio/Code Walkthrough, Smart Questions to Ask, and Post-Interview Follow-Up with individual task completion badges (`Done` / `Start`) and collapsible sections.
    - **Right Sidebar Widgets**: Interactive **Interview Readiness Checklist** synced dynamically with preparation tasks and **Quick tips** card with SVG accents.
    - Zero emojis used across UI components in compliance with the **Emoji Prohibition Rule**.
- **Interview Invite Page & Flatpickr Refinements (`/candidate/interview`)**:
  - Enhanced **Flatpickr Time Picker** popover styling in [dashboard.css](file:///c:/xampp/htdocs/InternConnect/public/assets/css/dashboard.css) with high-contrast borders (`1.5px solid #CBD5E1`), pronounced shadow depth, styled number steppers, and vibrant AM/PM toggles to eliminate any washed-out appearance on white modal backgrounds.
  - Standardized submitted file rows (`Resume` and `Portfolio Case Study`): unified PDF badge colors, removed file size numbers, and removed unnecessary `"View"` action links to keep the drawer strictly as a clean submission summary matching the Cover Letter row pattern.
- **Project Governance Updates (`.agents/rules/rules.md`)**:
  - Added strict **Emoji Prohibition Rule** forbidding emojis in UI text, cards, headings, and text platform-wide (requiring clean SVG icons instead).
  - Added **Modal Design & Pattern Rule** enforcing unified backdrop, dialog dimensions, recruiter profile boxes, and styled pickers.
- **Candidate Applications Journey Tracking Portal (`/candidate`, `/candidate/applications`, `/candidate/application`, `/applications`, `/my-applications`)**:
  - Implemented the candidate application tracking portal view matching design specifications in [applications/index.php](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/applications/index.php).
  - Configured layout using the unified candidate dashboard shell (`db-shell`, `db-sidebar`, `db-main`, `db-topnav`, `db-content`, `db-footer.php`) matching `/candidate/dashboard` and `/candidate/saved`.
  - Added `applications()` controller action in [CandidateController.php](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/CandidateController.php) providing live mock state for metrics, urgent alert banners, and 6 diverse application stages with full year dates (`Sep 28, 2024`, `Sep 25, 2024`, `Sep 20, 2024`, `Sep 15, 2024`, `Sep 12, 2024`, `Sep 08, 2024`).
  - Registered route endpoints `/candidate`, `/candidate/applications`, `/candidate/application`, `/applications`, and `/my-applications` in [routes.php](file:///c:/xampp/htdocs/InternConnect/backend/routes.php).
  - Features:
    - **Candidate Dashboard Sidebar**: Candidate profile block with photo edit dropdown, navigation items (`Dashboard`, `Saved Internships`, `My Applications` with active state, `Browse Opportunities`, `Help Center`, `Profile Settings`), and bottom "Find More Opportunities" promo card with "Subscribe Now" CTA.
    - **Top Dashboard Bar**: Mobile drawer trigger, search input, notifications dropdown with real-time updates, and profile settings menu.
    - **Page Header**: Added "Your Internship Journey" with personalized subtitle count (`"Stay organized and ahead of the curve, Sarah. 12 active opportunities found."`) positioned prominently before the KPI cards.
    - **5 Top Metric Cards**: `Total Applications` (24), `In Progress` (5), `Interviews` (3), `Offers` (1), `Rejected` (15).
    - **Urgent Action Banners**:
      - **Offer Extended Banner (Mint Green)**: `"Stellar Systems extended an offer!"` with subtext, `"Respond by Oct 15"`, and solid green `"Review Offer"` CTA button.
      - **Upcoming Interview Banner (Lavender/Indigo)**: `"Upcoming Interview: Vanguard Design"` with `"Oct 5, 2:00 PM"` and `"View Invite"` CTA button.
    - **All Applications Section**: Header with increased 24px bold typography, search input (`Search companies...`), and status filter dropdown.
    - **6 Application Cards**: Built with company initial avatars, verified checkmark badges, compensation & work type tags (`Remote`, `Internship`, `Paid`, `Hybrid`, `On-site`), status & stage texts, customized colored next step badge boxes (`Wait for feedback`, `Prepare for interview` + `Oct 5, 2024`, `Review offer`, `Application closed`), full year submission dates, and 3-dots action dropdown menus featuring icons for "View Listing" (navigating to role details) and single-line "Withdraw Application" modal action.
    - **Mobile Responsiveness**: Implemented a structured CSS grid-area card layout for mobile viewports (e.g. iPhone 17 Pro Max 440px) ensuring avatar, company metadata, top-right 3-dots menu, dual-column status & stage, full-width action boxes, and bottom date alignment render cleanly.
  - Added dedicated styling rules strictly in external [style.css](file:///c:/xampp/htdocs/InternConnect/public/assets/css/style.css).

### Changed
- **Payment & Upgrade Selection Checkout Flow**:
  - Connected live Paystack Test Mode integration via [PaystackService.php](file:///c:/xampp/htdocs/InternConnect/backend/Services/PaystackService.php) and [PaymentController.php](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/PaymentController.php).
  - Configured test API credentials in [.env](file:///c:/xampp/htdocs/InternConnect/.env) (`PAYSTACK_PUBLIC_KEY` and `PAYSTACK_SECRET_KEY`).
  - Added `absolute_url()` helper in [helpers.php](file:///c:/xampp/htdocs/InternConnect/backend/Core/helpers.php) to ensure Paystack receives fully qualified `http://localhost/...` callback endpoints for automatic post-payment redirection.
  - Implemented 3 dedicated status result screens matching design:
    - **Payment Successful** in [payment-success.php](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-success.php): 2-column layout with smiling candidate photo (`pricing_success_n0cbzk.png`), animated blue checkmark ripple badge, plan activation summary with row dividers, next renewal date, and "Go to Dashboard →" CTA.
    - **Payment Declined** in [payment-declined.php](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-declined.php): 2-column layout with concerned candidate photo (`payment_declined_jyso8t.png`), animated red X ripple badge, decline reason card with row dividers, "Try Again" & "Choose another plan" buttons.
    - **Payment Cancelled** in [payment-cancelled.php](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment-cancelled.php): 2-column layout with stressed candidate photo (`payment_cancelled_dnvsmc.png`), animated red X ripple badge, cancelled status summary with row dividers, "Try Again" & "Choose another plan" buttons.
  - Added direct preview routes (`/payment/success`, `/payment/declined`, `/payment/cancelled`) in [routes.php](file:///c:/xampp/htdocs/InternConnect/backend/routes.php).
  - Implemented the standalone upgrade / checkout selection view in [payment.php](file:///c:/xampp/htdocs/InternConnect/frontend/landing/payment.php) accessible via `/payments`, `/pricing/upgrade`, and `/checkout`.
  - Built a 2-column layout matching the design:
    - **Left Column**: Visual image card rendering Cloudinary asset (`picing_side_aq52aw.jpg`) with dark gradient overlay, displaying `"Invest in your potential."` and explanatory subtext.
    - **Right Column**: Upgrade selection card featuring a purple lightning bolt badge ⚡, dynamic title (`Elevate Your Career with Pro` / `Premium`), interactive selectable Monthly and Annual billing radio cards with live price toggles (`₦2,499/mo` vs `₦24,999/yr` with "Best Value" savings pill badge), 3 feature checkmarks, cancellation disclaimer, and "Continue to payment" & "Back to plans" buttons.
    - **Paystack Redirection Popup Modal**: Added animated modal triggered on "Continue to payment" with blurred backdrop, custom payment card icon badge, `"Redirecting you to Paystack"`, `"Secured by Paystack"` badge, trust security indicators (`256-bit encryption`, `PCI-DSS compliant`), and an animated indeterminate progress loading track.
  - Linked the "Upgrade to Pro" (`/payments?plan=pro`) and "Go Premium" (`/payments?plan=premium`) buttons on [pricing.php](file:///c:/xampp/htdocs/InternConnect/frontend/landing/pricing.php) and [components/pricing.php](file:///c:/xampp/htdocs/InternConnect/frontend/landing/components/pricing.php) to seamlessly launch the checkout selection flow.
  - Added dedicated styling rules strictly in external [style.css](file:///c:/xampp/htdocs/InternConnect/public/assets/css/style.css).
- **Shared Navbar Layout, Auth State Switching & User Profile Dropdown**:
  - Implemented dynamic auth state switching in [navbar.php](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/navbar.php):
    - **Logged-In State**: Displays search input (`Search Internships`), notification bell with red unread indicator, and avatar dropdown (Dashboard, Saved Internships, Applications, Help Center, Profile Settings, and Log Out).
    - **Logged-Out State**: Automatically hides search input, notification bell, and avatar dropdown, rendering clean **Log In** (`/login`) and **Sign Up** (`/register`) CTA buttons.
    - **Log Out Handler & Flow**: Added `/logout` route in [routes.php](file:///c:/xampp/htdocs/InternConnect/backend/routes.php) and `logout()` method in [AuthController.php](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/AuthController.php) resetting the `is_logged_in` session state and redirecting to the homepage with the navbar immediately rendered in the logged-out state.
    - **Mobile Drawer Support**: Adapts mobile drawer navigation seamlessly between logged-in user profile sublinks and logged-out action buttons.
  - Rearranged the desktop and mobile navigation links in [navbar.php](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/navbar.php) to: **Find Internships**, **How it Works**, **E-Learning**, **Payments**, and **Success Stories**.
  - Placed all supporting styles into [style.css](file:///c:/xampp/htdocs/InternConnect/public/assets/css/style.css).
- **Pricing Page Visual & Feature Showcase Refresh**: 
  - Promoted the Section Header with billing switcher to act as the primary banner on the pricing page ([pricing.php](file:///c:/xampp/htdocs/InternConnect/frontend/landing/pricing.php)), removing the previous 2-column hero banner.
  - Replaced the 4-column "Why Thousands of Candidates Choose Pro" section with a visual showcase consisting of a focused candidate image (`Woman_working_focused_sqvdeg.png`) on the left and 4 staggered floating feature cards on the right. Formatted **AI Recommendations** and **Interview Preparation** cards to overlap directly on top of the image boundary on desktop while offsetting **Resume Optimization** and **Priority Application** to the right, centered the overall layout horizontally, and made it stack smoothly to full-width cards on mobile viewports.
  - Redesigned the "Ready to Find Your Next Opportunity?" CTA banner section on the pricing page using the Cloudinary photograph background (`Celebrating_success_ouxlqf.png`) with a 72% dark overlay, centered white typography, and a single "Get Started For Free" white CTA button.
- **Global Footer 5-Column Redesign & Styling**: Updated [footer.php](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/footer.php) to feature a spacious 5-column horizontal grid layout with expanded max-width container (`max-width: 1280px`). Restored the dark SVG brand logo (`Logo_dark_dx00tg.svg`). Configured the 5 columns in a single row across desktop/laptop screens: **Brand** (logo + description), **Platform** ("About Us", "Employer Hub", "Applicant Resources"), **Support** ("Contact Support", "Privacy Policy", "Terms of Service"), **Blog** ("Latest Article", "News & Updates", "Interview Prep"), and **Follow Us** positioned on the far right with styled rounded square social icon buttons (LinkedIn, Facebook, Instagram, X). Consolidated all styling rules inside external [style.css](file:///c:/xampp/htdocs/InternConnect/public/assets/css/style.css).
- **Auth Page Right-Side Background Image**: Updated the right-side presentation background on login (`login.php`), forgot-password (`forgot-password.php`), verify-code (`verify-code.php`), and reset-password (`reset-password.php`) pages to render the new Cloudinary visual identity asset (`Visual_Identity_7_ptgohs.png`). The asset is dynamically served via the `asset()` helper, aligned to the left (`bg-left`), zoomed in slightly (`scale-120` with a custom `transform-origin: left 80px`), and blended at 95% opacity on top of a dark gradient backdrop to keep the text crisp, highly legible, and visually clean.
- **Application Cover Letter Validation & Switching Guards**: Updated the application wizard ([application.php](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/application.php)) to enforce a minimum of 100 characters and a maximum of 2,000 characters for typed statements of intent. Implemented premium, primary-branded confirmation/inner alert warnings preventing direct mode switching if data exists in the active tab (requiring users to explicitly click custom styled switch buttons to clear text or remove files). Removed the redundant "Save for later" buttons from the application wizard steps.
- **Application Wizard Fields & Navigation Rules**: Updated the application wizard ([application.php](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/application.php)) and [CandidateController.php](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/CandidateController.php):
  - Renamed the portfolio dropzone upload to "Attach NYSC credentials (optional)" and extended file type support to include `.pdf`, `.png`, `.jpg`, and `.jpeg`.
  - Added a new text input field for "Link for Portfolio" before the Figma Link input.
  - Simplified the Figma Link input field label to "Figma Link".
  - Implemented an Alpine.js watcher for the salary expectation input that dynamically formats numbers with commas (e.g., `3,999` or `40,000`) as the user types.
  - Added navigation validation guards on the progress sidebar stepper links, preventing users from skipping steps and dispatching the system's toast alert component on invalid navigation attempts.
  - Redesigned the Step 4 preview layout for "Your Answers" and "Attachments & Links" using custom card layouts, icon indicators, doc extension badges, and a grid system.
  - Stepper Spacing Optimization: reduced discrete vertical segment connector lines (`.stepper-connector` in `application.css`) height to 24px and shifted offset top to 52px to create clean spacing from step circles. (Later restored to its original spacious `height: 48px` and `top: 40px` styling based on user preference).
- **Employer Dashboard Layout & Spacing Refinements**: Fixed layout alignment discrepancies in the employer dashboard ([index.php](file:///c:/xampp/htdocs/InternConnect/frontend/employer/dashboard/index.php)) by introducing a custom local `<style>` block. Converted invalid inline style utility properties (Tailwind classes mixed into inline styles) to standard valid CSS rules, correcting the KPI stats row into a 5-column grid, aligning the welcome banner horizontally, ensuring bar charts rise from the bottom baseline, adding a `Live` badge with a pulsating dot to the interviews header, and wrapping match rate badges neatly. Added:
  - Custom **Applications Performance Period Dropdown**: Styled the period selector dropdown to match the candidate Filter dropdown design (`db-filter-btn` trigger and light-mode floating options card), eliminating all template text-squishing bugs.
  - Mobile responsive optimization for the **Review Queue** table, converting rows into beautiful blocks with left-aligned details indented by 48px to prevent button squishing, aligning the role and date elements in a single row next to each other, and styling **Review Now** as a full-width block button.
  - Increased chart bar widths to 24px on desktop while dynamically restoring slim 12px widths on mobile device viewports.
  - CSS grow-up keyframe animations for the performance chart bars.
  - Interactive CSS-driven pop-up tooltips showing department-specific Goal vs. Actual details above the bar columns on hover.
  - Purple/lavender styling matching the mockup for the match rate pill badges.
  - Styled AI candidate status badges with left-aligned status dots (emerald for "Available Now", amber for "Interviewed").
  - Redesigned topbar controls on employer navigation with circular icon containers (`db-topnav-icon`) and Alpine.js dropdown menus for **Notification Bell** (showing recent alerts and unread badge dot) and **Settings Cog** (showing Company Profile, Security & Permissions, Notification Settings), matching the candidate navbar behavior down to the pixel.
  - Redesigned topbar **Sign Out** button on employer navigation using `db-signout`, displaying red icon + red text on desktop, and automatically converting to a clean circular red icon button (`38px x 38px`) on mobile screens (`< 900px`) matching the candidate navbar.
  - Increased graph width share to `2.4fr` in the grid.
  - Adjusted **Recent Activity** container layout to use clean `gap: 20px` list spacing while positioning card footer action cleanly at the bottom.
- **Candidate Prefix Route Mapping & Chart Tooltips**: Registered `/candidate/dashboard`, `/candidate/saved`, and `/candidate/profile` in [routes.php](file:///c:/xampp/htdocs/InternConnect/backend/routes.php) to mirror employer routing architecture (`/employer/dashboard`), with automated 302 redirects from `/dashboard` to `/candidate/dashboard` in [CandidateController.php](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/CandidateController.php). Integrated floating dark pop-up tooltips (`cand-chart-tooltip`) showing stage-specific volumes on Candidate Dashboard performance chart bar hover. Updated all links across candidate views, welcome-back screens, sidebar navigation, saved internships, profile settings, and application success screens.


### Added
- **Index Page Hero & Cloudinary Showcase Assets**: Updated the homepage hero section ([hero.php](file:///c:/xampp/htdocs/InternConnect/frontend/landing/components/hero.php)) with updated subheadline, Bento Grid layout lines, and official Cloudinary showcase assets:
  - Right-side showcase image: `https://res.cloudinary.com/weburea/image/upload/v1785645677/internConnect/images/homepage_feature_showcase.png`
  - Stacked avatar icon assets rendered directly inside the card overlay:
    1. `homepage_icon_star.png` (`v1785645709`)
    2. `homepage_icon_margin_alt.png` (`v1785645701`)
    3. `homepage_icon_margin.png` (`v1785645693`)
- **Updated Cloudinary Brand Assets**: Updated navbar logo, footer logo, and favicon URLs across the application to reflect updated Cloudinary brand assets:
  - **Navbar Logo**: `https://res.cloudinary.com/weburea/image/upload/v1785517289/Logo_dark_dx00tg.svg` (`Logo_dark_dx00tg.svg`)
  - **Footer Logo**: `https://res.cloudinary.com/weburea/image/upload/v1785562552/logo_black_b_z9uhv6.svg` (`logo_black_b_z9uhv6.svg`)
  - **Favicon**: `https://res.cloudinary.com/weburea/image/upload/v1785517512/Logo_single_dark_dj6vhf.png` (`Logo_single_dark_dj6vhf.png`)
  - Updated layout templates (`app.php`, `auth.php`, `dashboard.php`), navbar component (`navbar.php`), footer component (`footer.php`), auth views, candidate dashboard views, local asset fallbacks, the `asset()` helper function in `backend/Core/helpers.php`, and updated the dynamic vector paths and viewBox coordinates in `public/assets/js/favicon-animator.js` to draw and animate the new brand mark.
- **Cloudinary UI Image Migration**: Migrated all local UI and branding image assets to Cloudinary CDN under the `internConnect/images/` path.
  - Logo assets are uploaded as vector/image assets to `internConnect/images/logo/`.
  - Homepage and UI assets are uploaded to `internConnect/images/`.
  - Updated the global `asset()` helper in `backend/Core/helpers.php` to dynamically serve image assets from the Cloudinary CDN when `CLOUDINARY_CLOUD_NAME` is configured, while preserving local serving for stylesheets and scripts.
  - Added `CLOUDINARY_CLOUD_NAME` template variables in `.env` and `.env.example`.

## [0.9.8] - 2026-06-23

### Added
- **Profile Settings Page**: Created a high-fidelity Candidate Profile Settings page (`frontend/candidate/profile.php`) matching the design mockup and design system.
  - Implemented form layouts for Personal Information (Name, Email, Role, Phone, Professional Bio) with dynamic session state preservation and validation.
  - Built a Resume & Portfolio block featuring interactive file upload drop zone and drag-and-drop file processing with visual feedback.
  - Designed Quick Links block supporting Portfolio and LinkedIn URL inline updating using Alpine.js.
  - Incorporated toggle switches for Job Recommendations, Application Status, and Platform Announcements.
  - Implemented account deactivation confirmation dialog with clean Alpine.js modal overlay.
- **Profile Routing & Controller Handlers**: Registered `GET /profile` and `POST /profile` routes in `backend/routes.php` and defined controller logic in `CandidateController.php`.
- **Form and Toggle CSS Styles**: Added comprehensive CSS styling rules for forms, inputs, custom toggle switches, file drag zones, and layout configurations to `public/assets/css/dashboard.css`.
- **Reusable Toast Notifications**: Developed a high-fidelity, reusable global Toast component (`frontend/shared/components/toast.php`) supporting 4 premium colored notification styles (`success`, `warning`, `danger`, `info`) positioned at the **top right** of the viewport.
- **Redesigned Resume Uploader File Pill**: Rebuilt the file list card using the high-fidelity design from the internship apply uploader, featuring a simulated PDF file card, custom detail metadata, status badges, and action buttons ("View File" and "Delete").

### Fixed
- **Profile Page Footer Error**: Fixed a path traversal bug in the profile template footer inclusion by updating the path to `__DIR__ . '/../shared/components/footer.php'`.

## [0.9.7] - 2026-06-21

### Fixed
- **Form Submission Silent Failure**: The "Submit Application" button was silently blocked by hidden `required` HTML5 inputs in Step 1 when the form was submitted from Step 3. Removed all HTML5 `required` attributes from `job_title`, `experience_company`, and `salary_expectation` inputs. Validation now exclusively happens server-side in `CandidateController::submitApplication()`.
- **Server-Side Validation Coverage**: Expanded `submitApplication()` to validate `job_title`, `experience_company`, and `salary_expectation` in addition to `phone`. Missing fields now return descriptive error messages in the error banner.
- **Error Re-render with Correct Internship**: On validation failure, the form now re-renders with the correct internship details (title, company) resolved from the `$slug` parameter instead of a generic fallback.
- **Alpine.js Data Attribute Parser Conflict**: Refactored the monolithic `x-data="{ ... }"` inline attribute in `application.php` into a clean `Alpine.data('applicationForm', ...)` script block. This eliminates HTML parser conflicts caused by JSON characters (`>`, `"`) inside HTML attribute values.
- **Form State Preservation on Errors**: After a failed POST submission, all Step 1 field values (phone, job title, company, salary expectation, cover letter) are restored via PHP `$oldInput` variables injected into Alpine.js initial state, so users do not lose their input.
- **Initial Step on Validation Failure**: When the form re-renders with server-side errors, Alpine now starts on Step 3 (the submit step) rather than Step 1, positioning the user near the error banner.
- **Mobile Toggle Button Alignment (Step 3)**: Fixed the "Type Letter" / "Upload File" toggle buttons on mobile by switching the wrapper from `inline-flex` to `flex w-full sm:inline-flex sm:w-auto`, ensuring buttons fill the full width on small screens. Replaced Tailwind `class="mt-6"` with inline `style="margin-top: 24px"` to guarantee spacing is applied regardless of Tailwind compilation.

## [0.9.6] - 2026-06-21

### Added
- **Saved Internships Page**: Created a high-fidelity Saved Internships view for candidates (`frontend/candidate/saved/index.php`).
  - Rendered a grid showing 4 mock saved internships: Product Design Intern (Google), Data Science Fellow (Spotify), Marketing Strategy Intern (Airbnb), and Software Engineering Intern (Microsoft) with respective logo SVGs, bookmark states, details, tags, Quick Apply, and Share actions.
  - Implemented widgets for **Resume Optimization** and **Get Alerts** under a new section "Based on your saved items" to match the Stitch design mockup.
  - Wired the new sidebar active states and global layouts.
- **Saved Routing & Controller Handlers**: Registered a `GET /saved` endpoint in `backend/routes.php` and defined the corresponding `savedInternships()` method inside `CandidateController` returning mock data.
- **Custom Saved Page Styles**: Integrated styles for the saved internships grid layout, badges, card layouts, optimization banner, and alerts card into `public/assets/css/dashboard.css`.

### Fixed
- **Mobile Filename Text Overflow**: Resolved styling bugs where long uploaded filenames in the candidate application form (`frontend/candidate/application.php`) for both Resume and Cover Letter cards would overflow horizontally on narrow mobile screens. Fixed by applying `min-w-0 flex-1` to details containers and `break-all` to filename text blocks.

## [0.9.5] - 2026-06-20

### Added
- **Inline Validation warnings**: Added visual error message blocks below Resume and Cover Letter drag-and-drop boxes in the candidate application form (`frontend/candidate/application.php`).
- **Custom Warning Alert Modal**: Built a reusable, custom warning Modal component with a blurred backdrop (`backdrop-blur-sm`), warning icon, heading, and CTA "OK" button using Alpine.js.
- **Image & Animation Polish**:
  - Linked `password_image.jpg` on the Password Reset Success view and added a pulsing ripple animation for the green checkmark badge.
  - Linked `appy_image.png` on the Application Success view and optimized the status indicator icons block alignment and spacing in `application-success.css` to prevent overlaps.

### Changed
- **Form Layout**: Restricted the Cover Letter toggle tabs button wrapper width to auto-fit its buttons rather than stretching full-width, and removed the card's top animated accent bar to keep the UI clean.
- **Alert Dialogs**: Replaced standard browser `alert()` popups with the new custom warning Modal component and inline alerts.
- **Progress Stepper Track**: Replaced the full vertical line progress track (which bled through and inside transparent step circles) with discrete vertical segment connector lines (`.stepper-connector`) defined in `application.css` that align exactly between circle edges.

---

## [0.9.4] - 2026-06-20

### Added
- **Forgot Password Feature**: Implemented a high-fidelity Forgot Password module (`frontend/auth/forgot-password.php`):
  - Created a 50/50 split-screen layout with an interactive left form section and right presentation card panel.
  - Custom brand-accented circular icon combining a counter-clockwise arrow and a padlock.
  - Handled the sent-state (`?sent=1`) to show a clean confirmation card instead of the form.
  - Handled the error-state (`?error=invalid`) to render a warning banner above the inputs.
  - Fully wired with the backend routing and `AuthController` handler.
  - Styled the submit button as a standard primary CTA matching the login form.
- **Verification Code Feature**: Implemented a 6-digit Code Verification screen (`frontend/auth/verify-code.php`) and flow:
  - Form layout containing 6 individual square text boxes with automatic forward focus, backspace backward focus, and full clipboard copy-paste detection.
  - Interactive 60-second countdown timer that transitions into a "Resend Code" link on expiration.
  - Setup routing rules (`GET /verify-code` & `POST /verify-code`) in the router and backend controllers.
  - Redirection from Forgot Password module on valid email submission to the Code Verification screen.
- **Reset Password Feature**: Implemented a Create New Password screen (`frontend/auth/reset-password.php`) and flow:
  - Form containing fields for new password and confirm password with visibility toggle (eye icon) and padlock icons.
  - Real-time password requirement checklist (At least 8 characters, One special character, One number) dynamically updating as the user types (via Alpine.js).
  - Submit button disabled until all criteria are satisfied.
  - Linked the verification code submission success to redirect to `/reset-password`.
- **Password Reset Success Feature**: Created a Password Reset Success screen (`frontend/auth/password-reset-success.php`) based on the welcoming page theme:
  - Renders a top header with navigation links, left-side illustration card (secured caption), and right-side green checkmark badge, title, and CTA buttons linking to dashboard and internships.
- **Design Fidelity Rules**: Added Rule 18 to the project principles (`docs/23-project-principles-rules.md`) and updated workspace global rules (`docs/39-workspace-global-rules.md`) to enforce strict first-pass visual fidelity checking when design mockups or screenshots are provided to prevent unnecessary layout iterations and token waste.

### Changed
- **Loader Timing**: Reduced the global page loader timeout duration from 8 seconds (`8000ms`) to 4 seconds (`4000ms`) in `loader.php` for faster user transitions while preserving the logo bounce animations.

### Fixed
- **Dashboard Settings Tag Mismatch**: Corrected swapped tags on the dashboard "Recommended for You" section where "Digital Marketing" was showing technical tags and "Backend Developer" was showing marketing tags.
- **Dashboard Layout closing tag**: Added the missing closing `</div>` to the settings topnav icon dropdown container in `frontend/candidate/dashboard/index.php`.

---

## [0.9.3] - 2026-06-16

### Added
- Created external customized CSS files in `public/assets/css/` to hold scoped styles for:
  - [dashboard.css](file:///c:/xampp/htdocs/InterConnect/public/assets/css/dashboard.css) (Extracted from candidate dashboard view and layout)
  - [application.css](file:///c:/xampp/htdocs/InterConnect/public/assets/css/application.css) (Extracted from candidate application view)
  - [application-success.css](file:///c:/xampp/htdocs/InterConnect/public/assets/css/application-success.css) (Extracted from candidate application success view)
  - [welcome.css](file:///c:/xampp/htdocs/InterConnect/public/assets/css/welcome.css) (Extracted from authentication welcome view)
  - [welcome_pro.css](file:///c:/xampp/htdocs/InterConnect/public/assets/css/welcome_pro.css) (Extracted from authentication welcome pro view)
- Implemented dynamic stylesheet loader support (`$extraCss`) in layouts [app.php](file:///c:/xampp/htdocs/InterConnect/frontend/shared/layouts/app.php) and [auth.php](file:///c:/xampp/htdocs/InterConnect/frontend/shared/layouts/auth.php) to link external CSS pages inside the `<head>` of templates.

### Fixed
- **Mobile Topnav Usability**: Optimized candidate dashboard top header layout for narrow viewports (max-width: 900px):
  - Hidden the `.db-topnav-spacer` to prevent layout spacing competition.
  - Allowed `.db-search` input to stretch to full available width.
  - Wrapped "Sign Out" text in a span (`.db-signout-text`) and hid it on mobile.
  - Formatted the "Sign Out" button into a clean `38px` circular icon button, matching the settings and notification circular icons to prevent text wrapping/breaking.
- **Desktop Topnav Refinements**:
  - Widened the search input `.db-search` max-width to `480px` (from `340px`) to make it look lengthy and balanced.
  - Increased `.db-topnav-inner` flex gap to `16px` (from `12px`) for better element spacing.
  - Applied `white-space: nowrap` to `.db-signout` to prevent "Sign Out" text from wrapping to a second line.

### Planned

- Candidate job matching engine optimization
- Real-time messaging notification upgrades using WebSockets
- Automated interview calendar integration
- Multi-currency billing support

---

## [0.1.0] - 2026-06-06

### Removed
- Laravel framework and all Laravel-specific skeleton directories (`app/`, `bootstrap/`, `config/`, `routes/`, `storage/`, `tests/`, `vendor/`, `resources/`).
- Laravel-specific config files (`artisan`, `phpunit.xml`, `vite.config.js`, `composer.lock`, `package-lock.json`).

### Changed
- Updated `composer.json` and `.env`/`.env.example` to support a minimal, pure PHP environment.
- Restructured the project layout under Rule 15 (`design/`, `frontend/`, `backend/`, `api/`, `public/assets/`).

---

## [0.2.0] - 2026-06-06

### Added
- Plain PHP MVC core architecture (`backend/Core/` containing Database, Router, Controller, Model).
- Reusable layouts (`app.php` and `auth.php` under `frontend/shared/layouts/`).
- Reusable component templates (`navbar.php`, `footer.php`, `card.php` under `frontend/shared/components/`).
- Page view structures and controllers for Home (`index.php`), Internship Listing (`internships.php`), Login (`login.php`), and Register (`register.php`).
- Tailwind CSS configuration (`tailwind.config.js` and entry stylesheet `input.css`).
- Organized and renamed homepage asset images (`homepage_hero_illustration.png`, `homepage_feature_showcase.png`, and badge/margin icons).

---

## [0.3.0] - 2026-06-06

### Added
- Implemented production-ready landing page view (`frontend/landing/index.php`) and mapped it to `HomeController`.
- Created modular, reusable layout sections under `frontend/landing/components/`:
  - `hero.php`: Implemented typography tokens, responsive buttons, and hero image.
  - `statistics.php`: Created structured active statistics banner.
  - `features.php`: Built a two-column detail section with Cap/Briefcase/Users icon list.
  - `opportunities.php`: Designed 3-column interactive role cards (UX, Digital Marketing, Backend).
  - `testimonials.php`: Styled success stories section with star ratings and user info.
  - `pricing.php`: Created three-tier subscription plan cards (Free, Pro, Premium).
  - `cta.php`: Implemented global footer call-to-action banner.
- Upgraded shared layout components:
  - `navbar.php`: Integrated Alpine.js responsive hamburger toggle menu.
  - `footer.php`: Restructured with multi-column legal/resources links and social indicators.
- Downloaded and saved target design schema (`landing_page_design.png`) locally for QA comparison.

---

## [0.4.0] - 2026-06-07

### Changed
- Replaced the primary website theme color with a premium blue (`#2563eb`) in `tailwind.config.js`.
- Cleaned and replaced all orange styling classes with matching blue classes across all components (`hero.php`, `features.php`, `opportunities.php`, `testimonials.php`, `pricing.php`, `cta.php`).
- Recompiled tailwind CSS styles to output directory, eliminating all residual orange styles.
- Updated `testimonials.php` layout to support a responsive 3-column grid layout on desktop, adding a new card for **Jonathan Davis** (initials **DJ**) with a custom blue rounded container, and styling other testimonial avatars to blue.
- Refined `pricing.php` subscription plan:
  - Expanded feature checkmark lists for Free, Pro, and Premium packages.
  - Set Free and Premium plan CTA buttons to outline blue styles.
  - Named the Pro plan action button "Upgrade to Pro" and styled it with a solid blue configuration.
  - Colored all checklist item icons blue (`text-blue-600`).

---

## [0.5.0] - 2026-06-07

### Added
- Implemented the production-ready Browse Internships page layout (`frontend/landing/internships.php`).
- Designed a responsive top search block with fields for Keyword/Role, Location, Category dropdown, and a solid blue "Search" button.
- Designed a side filter menu panel featuring Remote Preferences (Fully Remote, Hybrid) and Commitment types (Full-time, Part-time).
- Added an alert subscription card styled with a light blue banner and toggleable "Enable Alerts" button.
- Populated a 2x2 grid containing 4 mock internship cards (Nexura, Airbnb, WellSpring Care, BrightWave) equipped with categories, titles, tags, and apply hooks.
- Integrated Alpine.js for client-side search, location, dropdown, checkbox, and radio filter updates in real-time.
- Compiled all newly created stylesheet classes into the target CSS bundle.
## [0.5.1] - 2026-06-07

### Added
- Created a core utility file `backend/Core/helpers.php` defining `url()` and `asset()` helper functions.
- Integrated the helpers into the main controller router and entry point (`public/index.php`).

### Changed
- Updated all absolute layout paths, image paths, and routing links in `navbar.php`, `footer.php`, `hero.php`, `opportunities.php`, and `features.php` to use `url()` and `asset()` helper functions, enabling subdirectory hosting compatibility.

## [0.5.2] - 2026-06-08

### Added
- Added `.htaccess` in the `public/` directory for front controller URL rewriting, solving 404 routing errors on Apache under subdirectory environments.

### Changed
- Re-structured `navbar.php` and `footer.php` navigation links to match the target design structure ("Find Internships", "How It Works", "For Employers", "Success Story").
- Relabeled navbar button actions to "Login" and "Sign Up".
- Added dynamic PHP path matching in `navbar.php` to highlight active links (e.g. blue bottom border under "Find Internships" when browsing).
- Redesigned the search bar UI in `internships.php` with styled keyword, location inputs, and a custom Alpine.js category selector.

## [0.5.3] - 2026-06-08

### Fixed
- **Category dropdown clipping bug**: The dropdown panel on the Browse Internships page was being visually clipped by the grid container's implicit overflow boundary, causing only 2 of 5 options to be visible.
  - **Root cause**: The `absolute`-positioned dropdown was anchored inside a CSS Grid cell; the browser clamped its visible area to the cell's bounding box.
  - **Fix**: Switched the dropdown panel to `position: fixed` using a `getBoundingClientRect()`-based Alpine.js `toggle()` method to compute the correct viewport-relative coordinates on each open. This escapes all overflow and stacking-context boundaries entirely.
  - Also added `overflow: visible` inline to the search bar wrapper as a secondary safeguard.

## [0.6.0] - 2026-06-08

### Added
- **Internship Detail Page** (`frontend/landing/internship-detail.php`): Full detail view built from the Stitch reference design:
  - Breadcrumb trail (Internships › Category › Sub-category)
  - Job title, company, location, work-mode pill, and tag badges (REMOTE / FULL-TIME / INTERNSHIP)
  - Role Overview with multi-paragraph body text
  - Key Responsibilities block (blue checkmark list inside a gray-tinted card)
  - "What We're Looking For" 2×2 grid of requirement cards with SVG icons
  - Program Benefits list + Application Window side-by-side section
  - Sticky right sidebar: salary range, Apply Now CTA, Save for Later button, Quick Stats (Applicants / Posted / Level), Share Role icons (copy link, email, native share)
- **Route**: `GET /internship/{slug}` → `InternshipController::show()`.
- **Controller**: `InternshipController::show(array $params)` with mock datasets for `senior-product-design-intern` and `software-engineer-intern`.

### Changed
- Updated internship card Apply button on `/internships` listing from a static `<button>` to an Alpine.js-bound `<a>` linking to `/internship/{slug}`.
- Added `id` (slug) field to all four mock internship objects in the Alpine.js data array.

## [0.6.1] - 2026-06-08 — Hotfix

### Fixed
- **Internship detail page broken layout**: `lg:col-span-8` and `lg:col-span-4` are not present in the compiled `style.css`. Switched to `lg:col-span-9` (content) + `lg:col-span-3` (sidebar), which ARE confirmed in the compiled Tailwind output. Sidebar now renders correctly beside the main content column.
- **Sticky sidebar `top-24` missing**: `top-24` is not in compiled CSS. Replaced with `style="top: 5.5rem;"` inline to ensure sticky positioning works without Tailwind purge issues.
- **Apply button showing literal `\n` characters**: Previous edit escaped newlines as `\n` string literals inside a JSON `ReplacementContent` field, which wrote raw backslash-n characters into the PHP file. The template string was rewritten as proper multiline HTML, resolving the rendered `\n Apply\n` display bug.

## [0.6.2] - 2026-06-08 — UI Polish

### Changed
- **Company Logo**: Increased the size and prominence of the company initials logo in the Internship detail page header (`h-12 w-12 text-xl` from `h-7 w-7 text-sm`).
- **Location Redundancy**: Removed the redundant secondary location and work mode sub-row (`Lagos, NG (Hybrid)`) under the company name, as the tags section already accommodates these details.
- **Share Role Section**: Aligned the "Share Role" label horizontally with its social icon buttons using a flex row with `justify-between` to remove unnecessary vertical spacing and better match the design reference.

## [0.6.3] - 2026-06-08 — UI Tooltip Fix

### Changed
- **Share Tooltips**: Replaced the default, unstyled browser `title` attributes on the "Share Role" buttons (Copy Link, Email, Native Share) with professionally designed, custom CSS tooltips using Tailwind's `group-hover` and absolute positioning. Refined the tooltip design to use a light theme (`bg-white text-gray-700`) with fully rounded corners (`rounded-full`) and subtle shadows to ensure it looks highly professional and distinct from the dark/square native tooltips.

## [0.6.4] - 2026-06-08 — Tooltip Interaction Fix

### Fixed
- **Tooltip Visibility**: The custom Tailwind `group-hover` tooltips were failing to display because those specific utility classes were likely pruned from the compiled `style.css` during the build process since they were never previously used.
- **Dynamic Interaction**: Re-implemented the tooltips using Alpine.js (`x-data`, `@mouseenter`, `@mouseleave`, `x-show`) to guarantee visibility without relying on uncompiled CSS pseudo-classes. Additionally, added dynamic text feedback so the tooltip instantly switches to read "Copied!" when the user clicks the "Copy link" button.

## [0.6.5] - 2026-06-08 — Tooltip Typography Fix

### Fixed
- **Tooltip Text Wrapping**: Fixed an issue where the tooltip text (e.g., "Copy link") was wrapping onto multiple lines inside the circular boundary, creating an awkward shape. Added `whitespace-nowrap` to force single-line rendering.
- **Tooltip Alignment**: Added `left-1/2 -translate-x-1/2` to perfectly center the tooltips horizontally above their respective icons instead of being left-aligned by default.

## [0.6.6] - 2026-06-08 — Tooltip Positioning Fix

### Fixed
- **Tooltip Clustering**: Moved the tooltip position from the top (`bottom-full mb-2`) to the bottom (`top-full mt-2`) of the icons. This prevents the tooltips from overlapping and visually cluttering the 'Share Role' section's surrounding components when activated.

## [0.6.7] - 2026-06-09 — Brand and Content Polish

### Changed
- **Primary Color Update**: Updated `tailwind.config.js` to set the primary color utility to `#4F47E6` (Indigo 600) based on new design specs.
- **Global Theme Migration**: Automatically replaced all `blue-` color classes (e.g., `text-blue-600`, `bg-blue-600`, `hover:bg-blue-700`) across all frontend PHP files with their `indigo-` equivalents to establish the new `#4F47E6` primary brand color project-wide.
- **Internship Detail Static UI Data**: Refactored the work-type tags to a static configuration showing `Remote`, `Internship`, and `Full-time` at the top of the internship details.
- **Compensation Display**: Changed the compensation field to statically display `Monthly Compensation` and `$3,000` to simplify the UI block, according to instructions avoiding dynamic mock arrays for this block.

## [0.6.8] - 2026-06-09 — Footer & CTA Subscription Polish

### Changed
- **Footer Structure & Copy**: Set the copyright year to 2020 and styled the "InternConnect" brand name using the main `text-primary` color. Removed "Made in Nigeria" from the footer.
- **Footer Social Links**: Updated the social media suite to include X (formerly Twitter), LinkedIn, Instagram, and Facebook. Removed the GitHub icon, keeping only appropriate enterprise/social platforms.
- **CTA Section Rebuild**: Replaced the "Join as Student" / "Join as Employer" dual button layout in the main CTA block (`Ready to curate your future?`) with a unified email subscription form.
- **CTA Form Design**: Implemented a dark theme inline input form matching the design reference, featuring a transparent email input, white placeholder text, and a white `Subscribe` button. Added an anti-spam disclaimer underneath.

## [0.6.9] - 2026-06-09 — Authentication Page Polish

### Changed
- **Auth Page Polish**: Aligned Login and Register pages with design system typography (Inter) and high-fidelity mockups.
- **Split Screen Presentation**: Structured right-hand presentation panels on both login and register views to use a 50/50 desktop split screen.
- **Plant Visual Asset Integration**: Restructured styling in `login.php` and `register.php` right panels to render the plant image (`plant.png`) blended dynamically with a dark blue gradient backdrop overlay, matching the high-fidelity branding reference.

## [0.7.0] - 2026-06-09 — Application Flow Layout Fix

### Fixed
- **Application Form Redundant Layout**: Removed redundant `ob_start()` and direct `navbar` / `footer` `require` calls from `frontend/candidate/application.php`. The core layout wrapper now successfully inherits these elements globally via `Controller::renderView()`, preventing duplicated header and footer elements.
- **Application Form Theming**: Replaced custom `#435E8F` hex colors in the Candidate Application form with Tailwind's dynamically compiled `bg-primary`, `text-primary`, and `hover:bg-primary-hover` variables to match the Indigo `#4F47E6` global site theme. Corrected the navy text `#071A35` to use standard `text-primary-text` referencing the Tailwind config.

## [0.7.1] - 2026-06-10 — Tailwind CSS Safelist Fix

### Fixed
- **Missing Hover/Focus Utilities**: Added a `safelist` array to `tailwind.config.js` to prevent Tailwind's JIT purger from stripping custom brand color variants (`hover:bg-primary-hover`, `focus:ring-primary`, `hover:bg-primary-light`, `hover:text-primary`, `active:scale-95`, `disabled:opacity-50`, `disabled:cursor-not-allowed`, `group-hover:bg-primary/10`) that are used throughout the frontend but were absent from compiled `style.css`, causing button hover states and form focus rings to not render.
- **Tailwind Rebuild**: Re-ran `npx tailwindcss` build to compile all safelisted variants into the CSS output.

---

## [0.8.0] - 2026-06-10 — Application Module Backend Integration

### Added
- **`CandidateController`** (`backend/Controllers/CandidateController.php`): New controller with `submitApplication()` method handling `POST /apply/{slug}`. Includes full server-side validation (phone, cover letter length ≤ 2000 chars, resume file size ≤ 5MB, file type whitelist: PDF/DOCX/RTF). Documented with `TODO` markers for database, file storage, AI parsing, and email notification integration.
- **POST Route**: Registered `POST /apply/{slug}` → `CandidateController::submitApplication` in `backend/routes.php`.
- **`application-success.php`** (`frontend/candidate/application-success.php`): New animated confirmation screen rendered after successful application submission. Displays application summary (company, role, submitted timestamp, resume name, status badge), a 3-step "What happens next?" timeline, and CTA buttons to browse more roles or return home. Animated with Alpine.js entrance transitions and a pulsing success badge.
- **Form Wiring**: Wired `frontend/candidate/application.php` form to `POST` with `enctype="multipart/form-data"`. Added `name` attributes to all fields (`phone`, `email`, `full_name`, `cover_letter`, `resume`). Added a CSRF token placeholder hidden field for future session-based token integration.

### Changed
- **`InternshipController::apply()`**: Added `id`, `slug`, and `slug` variable to the view data passed to the application template so the form's `action` URL can be dynamically constructed.

---

## [0.8.1] - 2026-06-12 — Application Form Mobile & UX Fixes

### Fixed
- **Mobile Layout — "Next: Cover Letter" Button**: Changed button order on mobile using `order-1 sm:order-2` so the primary CTA appears first (top) and "Previous" appears last (bottom) on narrow screens, matching mobile-first UX patterns.
- **Salary Expectation Input Spacing**: Replaced `pl-10` + inline `padding-left` with a consistent `pl-12` Tailwind class to create correct spacing between the `$` symbol and the user's typed/placeholder text, matching the alignment of the Job Title and Company fields.
- **Salary Icon Display**: Fixed a broken SVG render caused by an invalid Tailwind size class (`h-4.5 w-4.5`). Changed to the standard `h-5 w-5` so the currency icon now renders correctly.
- **Employer Questions Divider Alignment**: Removed the leading `<div class="flex-grow h-px">` from the "Answer these questions from the employer" divider so the label left-aligns naturally with the form fields.
- **Placeholder Text Cleanup**: Removed the `e.g.` prefix from Job Title (`Frontend Developer Intern`), Company (`TechLabs Nigeria`), and Salary (`2,500`) placeholders for a cleaner, more direct input experience.
- **Button Text Wrapping**: Wrapped "View File" and "Delete" button text in `<span style="white-space: nowrap;">` and added `flex-shrink-0` to prevent text from breaking onto two lines on small screens.
- **Document Visualizer Height**: Constrained the Document Visualizer mock preview panel to `max-h-56` with `overflow-y-auto` so it scrolls internally and doesn't push the resume upload step layout.

### Changed
- **Cover Letter Drag-and-Drop**: Added `clDragOver` state variable and `handleCoverLetterDrop()` / `processCoverLetterFile()` methods to the Alpine.js `x-data` object, refactoring the cover letter file upload to support drag-and-drop natively, consistent with the resume drag-and-drop UX.
- **Final Statement Buttons — Equal Width on Mobile**: All three buttons on Step 3 (Previous, Save for Later, Submit Application) now use `w-full sm:w-auto` to ensure equal full-width display on mobile devices.

---

## [0.8.2] - 2026-06-12 — Multi-Page Resume Preview Modal

### Added
- **Full-Screen Preview Modal**: Rebuilt the document preview modal (`showPreviewModal`) on the Resume step into a full three-page scrollable resume experience:
  - **Page 1**: Candidate header, Professional Summary, Education, Professional Experience (2 roles).
  - **Page 2**: Technical Skills (4 categories), Projects (2), Certifications (3).
  - **Page 3**: Awards & Achievements, Extracurriculars, References, and a verified credential footer stamp.
- **"Scroll to view all pages"** hint in the modal footer and a **"3 pages"** indicator in the modal header for desktop screens.
- Each page is rendered as a separate white card with shadow, separated by a dark gray document-background container.

### Changed
- Modal height increased to `h-[90vh]` to provide a more immersive viewing area.
- Modal footer restructured to a `flex justify-between` layout to accommodate the new scroll hint alongside the Close button.

---

## [0.8.3] - 2026-06-12 — Brand Logo Integration

### Added
- **Favicon**: Added `logo_color.svg` as the `<link rel="icon">` in both `frontend/shared/layouts/app.php` and `frontend/shared/layouts/auth.php`, ensuring the brand icon appears consistently on all browser tabs across every page.

### Changed
- **Navbar Logo**: Replaced the plain text-based `InternConnect` wordmark in `frontend/shared/components/navbar.php` with the full `logo_color_name.svg` brand logo image, served via the `asset()` helper.
- **Footer Logo**: Updated `frontend/shared/components/footer.php` to render `logo_black_name.svg` instead of the text-based wordmark, providing a high-contrast brand treatment in the footer across all pages.

---

## [0.9.0] - 2026-06-12 — Global Page Loading Animation

### Added
- **Global Loading Screen** (`frontend/shared/components/loader.php`): Created a new self-contained PHP component that renders a full-screen animated page loader using the official `logo_color_loading.svg` brand graphic.
  - The loader uses an **animated SVG** that splits the unified brand logo path into individual interactive layers:
    - **Left Anchor Circle**: Scales up (`1.08×`) and glows brand violet (`#4F47E6`) when the bouncing ball makes contact.
    - **Bridge Path**: Static connecting curve matching the official brand logo geometry.
    - **Right Anchor Circle**: Scales up (`1.12×`) and flashes a success-green micro-interaction accent (`#10B981`) on ball impact.
    - **Bouncing Ball**: A vibrant indigo circle (`#6969F9`) that travels along a realistic parabolic arc between the two anchors, using `cubic-bezier` easing to simulate natural gravity (ease-out rising, ease-in falling).
  - An **"Loading InterConnect"** label with `animate-pulse` displays below the graphic.
  - A **vanilla JS `window.load` handler** fades the overlay to `opacity: 0` over `500ms` then sets `display: none`, with a `readyState` guard to safely dismiss the loader if the page is already loaded when the script executes.
- **Loader Injected Globally**: The `loader.php` component is included at the top of the `<body>` tag in both `frontend/shared/layouts/app.php` and `frontend/shared/layouts/auth.php`, covering all pages site-wide (landing, internship listing, detail, login, register, application form, etc.).

---

## [0.9.1] - 2026-06-12 — Loader Optimization & Timing Control

### Changed
- **Fixed Loading Duration**: Configured the global loading screen (`frontend/shared/components/loader.php`) to remain visible for exactly `8` seconds (`8000ms`) to allow the high-fidelity SVG brand animation to display fully and play out naturally.
- **Scroll Lock during Loading**: Added body scroll-lock (`overflow: hidden`) while the loader is active to prevent page scrolling behind the loading screen, restoring normal scrolling on dismissal.
- **Conditional Loader Inclusion**: Wrapped the loader include statement in `frontend/shared/layouts/app.php` and `frontend/shared/layouts/auth.php` with a check for `$showLoader ?? true`. This allows future back-end/dashboard pages using these layouts to opt-out of the loading animation by passing `'showLoader' => false` from the controller.

---

## [0.9.2] - 2026-06-12 — High-Fidelity Loader Color Animation & Styling Fixes

### Changed
- **Premium Loader Color Bounce**: Updated the global page loader's SVG animation to transition dynamically between the design system's primary purple (`#4F47E6`) and primary black (`#191C1D`).
  - **Left Anchor & Ball origin**: Set to primary brand purple (`#4F47E6`).
  - **Right Anchor**: Set to primary black (`#191C1D`).
  - **Dynamic Transition**: The bouncing ball and bridge path animate in sync, taking the brand purple color when the ball is at the left impact state, and transitioning to primary black when the ball reaches the right impact state.
- **Fixed Layout and Z-Index Conflicts**: Ensured inline `z-index: 9999999 !important;` prevents any layout elements, panels, or navbars from showing through the loading screen.
- **Unified Branding Elements**: Integrated favicons, responsive brand logos (`logo_color_name.svg` and `logo_black_name.svg`) across login, register, navbar, and footer pages, aligning the platform's layout with the design standards.

