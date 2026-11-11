# 10 - Admin System Specification

**InternConnect Operations & Platform Governance Console**  
**Design Reference:** `docs/admin-dashboard-design-system-and-ui-spec.md`  
**Current Phase:** Frontend UI/UX Architecture & Prototype Planning  

---

## 1. Overview & Operational Purpose

The InternConnect Admin System ("Mission Control") provides high-level platform oversight, compliance governance, and operational support. It enables the company to manage tens of thousands of student candidates, verify corporate employers, oversee Jenny AI matching models, and empower hired support staff ("Customer Care Representatives" / "Watching Staff") with role-gated tooling.

---

## 2. Administrative Role Hierarchy (RBAC)

To allow the company to safely hire support personnel without granting access to core financial data or system keys, access is segmented into 5 standard operational roles:

1. **Super Administrator (`Platform Admin` / `System Owner`)**:
   - Full unrestricted access across all modules, configuration settings, and database management.
   - Responsible for inviting new team members, assigning staff roles, and managing billing integrations.

2. **Trust & Compliance Lead (`Verification Officer`)**:
   - Responsible for reviewing and approving/declining Corporate Affairs Commission (CAC) incorporation certificates and business documents.
   - Monitors employer verification queues, flags suspicious job postings, and manages account suspensions.

3. **Customer Support Agent (`Customer Success Specialist` / "Watching Staff")**:
   - Frontline support tooling to inspect candidate and employer profiles, view application pipelines, assist with 2FA/password resets, and resolve user inquiries.
   - Read-only access to platform statistics; restricted from approving CAC documents, issuing refunds, or viewing API keys.

4. **AI & Curriculum Moderator (`AI Operations Specialist`)**:
   - Oversees the Jenny AI matching engine, reviews nightly scoring runs, audits flagged match anomalies, and monitors Gemini token usage.
   - Moderates mentor-submitted E-Learning courses and reviews curriculum content before publishing.

5. **Financial Controller (`Finance & Billing Specialist`)**:
   - Manages Paystack payment records, monthly recurring revenue (MRR) tracking, student Pro/Premium subscriptions, and verified refund authorizations.

---

## 3. Product Architecture & Screen Hierarchy (Dotted Tree Structure)

The following ASCII dotted-line tree maps the entire administrative workflow, screen routes, drawers, and modal dialogs for the product design team:

```text
===================================================================================================
INTERNCONNECT ADMIN OPERATIONS CONSOLE — ARCHITECTURE & SCREEN FLOW TREE
===================================================================================================
:
+-- [1.0] AUTHENTICATION & ACCESS CONTROL
:   :
:   +-- 1.1 /admin/login ................................. Staff Login Screen
:   :   ·-- Email & Password Credentials Input
:   :   ·-- Rate Limiting (5 Attempts Max / 15-min Lockout)
:   :   ·-- CSRF Protection & Encrypted Session Cookie
:   :
:   +-- 1.2 /admin/login/2fa ............................. Two-Factor Verification Challenge
:   :   ·-- 6-Digit Authenticator App (TOTP) Input Grid
:   :   ·-- Fallback: Dispatch One-Time Code via Verified Staff Email
:   :   ·-- Remember This Device (30-day Trusted Session Token)
:   :
:   +-- 1.3 /admin/forgot-password ....................... Password Recovery Request
:   :   ·-- Staff Work Email Submission
:   :   ·-- Cryptographically Signed 1-Hour Recovery Link Dispatch
:   :
:   \-- 1.4 /admin/reset-password ........................ Password Reset Execution
:       ·-- Password Strength Meter (Min 10 Chars, Uppercase, Number, Symbol)
:       ·-- Immediate Revocation of All Active Sessions on Reset
:
+-- [2.0] GLOBAL APPLICATION SHELL (LAYOUT FRAMEWORK)
:   :
:   +-- 2.1 Admin Sidebar (260px Fixed Desktop / Slide-out Mobile Drawer)
:   :   ·-- Platform Brand Header (InternConnect Logo Only, No Badge)
:   :   ·-- Navigation Menu Items with Live Counter Pills:
:   :   :   ·-- Overview (/admin/overview)
:   :   :   ·-- Interns Directory (/admin/interns) [Submenu: All, Active, Placed, Awaiting Review (37)]
:   :   :   ·-- Employers & CAC Desk (/admin/employers) [Badge: 14 Pending]
:   :   :   ·-- Jenny AI Operations (/admin/ai) [Badge: 6 Flagged]
:   :   :   ·-- E-Learning Moderation (/admin/elearning) [Badge: 3 Courses]
:   :   :   ·-- Subscriptions & Paystack (/admin/subscriptions)
:   :   :   \-- Settings & Watching Staff (/admin/settings/team)
:   :   \-- Footer Staff Profile Card:
:   :       ·-- Staff Avatar Initials + Full Name
:   :       ·-- Role Badge Pill ("Platform Admin" / "Watching Staff")
:   :       \-- Secure Logout Action Button
:   :
:   +-- 2.2 Top Navigation Header (64px Sticky White Bar)
:   :   ·-- Command Palette Trigger Search Box ("Ask Jenny or search interns...") [Ctrl+K]
:   :   ·-- Notification Bell Icon with Red Unread Counter (3 Pending Alerts)
:   :   \-- Staff Profile Quick Menu (Role Pill, My Profile, Audit Logs, Logout)
:   :
:   +-- 2.3 Global Command Palette Modal (Ctrl + K Overlay)
:   :   ·-- Search Input with Debounced Autocomplete
:   :   ·-- Grouped Instant Results:
:   :   :   ·-- Group 1: Interns (Name, Email, University, Profile Link)
:   :   :   ·-- Group 2: Employers (Company Name, Verification Status, Openings)
:   :   :   ·-- Group 3: Courses (Title, Instructor, Moderation State)
:   :   :   \-- Group 4: Quick Actions (Invite Staff, Verify CAC, Review AI Flags)
:   :   \-- Keyboard Navigation (Arrow Keys + Enter to Select + Escape to Close)
:   :
:   \-- 2.4 Floating Notification Drawer Panel (Triggered by Bell)
:       ·-- Header: "Notifications" + Unread Count + "Mark all as read"
:       ·-- Feed Items:
:       :   ·-- CAC Verification Alert (Nexura Technologies - 10 mins ago)
:       :   ·-- AI Match Flag Alert (Low confidence matches - 45 mins ago)
:       :   \-- Candidate Offer Acceptance (Sarah Peters @ DesignHub - 2 hours ago)
:       \-- Padded Footer: "View all platform audit logs ->" (Links to /admin/settings/team)
:
+-- [3.0] PLATFORM OVERVIEW (MISSION CONTROL COMMAND CENTER) — /admin/overview
:   :
:   +-- 3.1 Page Header & Controls
:   :   ·-- Title: "Overview" + Welcome Greeting ("Welcome back, Jayden.")
:   :   ·-- Date Range Indicator ("Last 30 days · to 15 Oct 2026")
:   :   \-- Segmented Time-Period Filter: [Week] | [Month] (Active) | [Quarter] | [Year]
:   :
:   +-- 3.2 KPI Metrics Grid (4 Dynamic Count-Up Cards with Top-Right Icon Chips)
:   :   ·-- Card 1: TOTAL INTERNS (12,480 | +312 this week | ^ 2.6% vs last month) [Blue Chip]
:   :   ·-- Card 2: ACTIVE EMPLOYERS (486 | +19 this month | ^ 4.1% vs last month) [Orange Chip]
:   :   ·-- Card 3: AI MATCH ACCEPTANCE (71% | +3 pts vs benchmark | ^ 3.2% vs last month) [Purple Chip]
:   :   \-- Card 4: ACTIVE LEARNERS (4,920 | +8% this month | ^ 8.0% vs last month) [Emerald Chip]
:   :
:   +-- 3.3 Main Split Analytics Section (8-Col Chart + 4-Col Needs Attention Queue)
:   :   +-- Column Left (66% Width): Applications & Placements Chart
:   :   :   ·-- Header: Volume Summary (Applications: 7,210 ^ 12.4% | Placements: 1,312 ^ 8.6%)
:   :   :   ·-- Smooth Dual-Line Chart.js Area Chart (Indigo = Apps, Emerald = Hires)
:   :   :   \-- Interactive Tooltip showing Week-by-Week volumes
:   :   :
:   :   \-- Column Right (33% Width): "Needs Your Attention" Action Shelf (4 Critical Items)
:   :       ·-- Item 1: 14 employers awaiting verification [Review ->] (CAC review)
:   :       ·-- Item 2: 6 AI matches flagged for review [Inspect ->] (Low confidence)
:   :       ·-- Item 3: 3 courses awaiting review [Review ->] (Mentor syllabus)
:   :       \-- Item 4: 2,620 incomplete intern profiles [Nudge ->] (Automated reminders)
:   :
:   +-- 3.4 Today Activity Strip (Above KPI Grid)
:   :   \-- New Sign-ups | Applications | Interviews Today (No-shows) | Offers Accepted
:   :
:   +-- 3.5 Live Visitors Map (Full-Width Card) [Feed: GET /admin/api/live-sessions]
:   :   ·-- Zoomable MapLibre Map with Clustered, Pulsing Session Markers
:   :   ·-- Region Shortcuts: World, Africa, Nigeria, North America, South America, Europe, Asia
:   :   ·-- Online Now Total + Student / Employer Split
:   :   ·-- Countries Tab: Online Count + Share Bar (Click to Zoom)
:   :   \-- Sessions Tab: City, Country, Masked IP, Device, Role, Current Page (Click to Zoom)
:   :
:   +-- 3.6 Insights Row (3 Cards)
:   :   ·-- Finance Performance: MRR, Monthly Histogram, Stream Progress List (Super Admin + Financial Controller Only)
:   :   ·-- Top Performers: Employers | Universities | Interns Tabs
:   :   \-- Marketplace Balance: Open Roles, Applicants per Role, Zero-Applicant Roles, Median Days to Hire, Roles by Category
:   :
:   +-- 3.7 Jenny AI Strategic Insights Card (Full-Width Banner)
:   :   ·-- Header: Jenny Mascot Icon Badge + "Jenny AI Strategic Insights"
:   :   ·-- Schedule Status Pill: "Cached daily · Next run 06:00 WAT"
:   :   ·-- 3 Structured Insight Columns:
:   :   :   ·-- Insight 1: 2,620 Incomplete Profiles (Drop rank by 44% -> Push auto-nudges)
:   :   :   ·-- Insight 2: 14 Zero-Applicant Roles (Generic titles -> Suggest title rewrite)
:   :   :   \-- Insight 3: Friday 2-5 PM Peak Show-Up (88% attendance vs 62% Monday)
:   :   \-- Action Button: "Ask Jenny about these numbers ->"
:   :
:   \-- 3.8 Bottom Split Section
:       ·-- Left: From Sign-up to Placement Funnel + System Health (Gemini, Nightly Scoring, Paystack Webhooks, Email Delivery)
:       \-- Right: Recent Activity Feed
:
+-- [4.0] INTERNS MANAGEMENT & PROFILE INSPECTOR — /admin/interns
:   :
:   +-- 4.1 Filter Tabs Bar & Search Controls
:   :   ·-- Category Filter Tabs: [All 12,480] | [Active] | [Placed] | [Awaiting review 37] | [Suspended]
:   :   ·-- Search Box: Filter candidates by name, email, university, skill
:   :   \-- Filter Button: Modal trigger for University, Graduation Year, CGPA
:   :
:   +-- 4.2 Interns Master Data Table (Responsive 6px Custom Scrollbar When Overflowing)
:   :   ·-- Col 1: Multi-select Checkbox
:   :   ·-- Col 2: Intern Identity (Avatar Initials, Full Name, University Email)
:   :   ·-- Col 3: University Name (e.g. University of Lagos, Covenant University)
:   :   ·-- Col 4: Applications Count (e.g. 18 Apps)
:   :   ·-- Col 5: AI Match Score Pill (Color-Coded: 90+ Purple, 80-89 Blue, 70-79 Slate, <70 Amber)
:   :   ·-- Col 6: Profile Completeness Bar (Track + Progress Fill + Percentage Text)
:   :   ·-- Col 7: Placement Status Badge (`Placed`, `Active`, `Awaiting Interview`, `Suspended`)
:   :   \-- Col 8: Row 3-Dots Action Menu (Single-active state dropdown with icons: Inspect, Message, Copy Link, Suspend)
:   :
:   +-- 4.3 Intern Profile Inspector (420px Fixed Slide-Over Drawer)
:   :   ·-- Header: Close Button (X), Candidate Avatar, Full Name, University
:   :   ·-- Verified Skills Cloud (Figma, User Research, Prototyping, Wireframing)
:   :   ·-- Performance Summary Grid: [18 Applications] | [5 Interviews] | [2 Offers]
:   :   ·-- Jenny AI Fit Analysis Card: AI fit breakdown and suggested next learning course
:   :   ·-- Recent Applications Timeline: Company Name, Role, Status Dot, Application Date
:   :   \-- Drawer Action Footer:
:   :       ·-- Primary Button: "Open Profile ->" (Navigates to deep candidate dossier)
:   :       \-- Secondary Danger Soft Button: "Suspend account"
:   :
:   \-- 4.4 Suspend Intern Account Modal (Clean Dialog with Inset Textarea)
:       ·-- Modal Backdrop (Blur 8px + Slate Dark tint)
:       ·-- Header: Warning Shield Icon + Target Intern Name
:       ·-- Reason Radio Group:
:       :   ·-- [x] Terms of Service Violation (Unsolicited spamming or unprofessional conduct)
:       :   ·-- [ ] Suspected Fraudulent Activity (Identity impersonation or duplicate accounts)
:       :   \-- [ ] Inaccurate Academic Credentials (Falsified enrollment or graduation proof)
:       ·-- Additional Staff Notes Textarea (Padded px-3.5 py-3, Inset Placeholder, 85px Height)
:       \-- Modal Actions: [Cancel Button] | [Confirm Suspension (Rose Button)]
:
+-- [5.0] EMPLOYERS & CAC VERIFICATION DESK — /admin/employers
:   :
:   +-- 5.1 Verification Queue Shelf (Top Highlight Banner)
:   :   ·-- Section Header: "VERIFICATION QUEUE (14 PENDING)"
:   :   ·-- Queue Summary: "14 companies awaiting business document & CAC incorporation approval"
:   :   ·-- Queue Cards:
:   :   :   ·-- Company Logo, Legal Entity Name, RC/BN Registration Number
:   :   :   ·-- Work Email Domain Validation Pill (Checked vs Official Domain)
:   :   :   ·-- Submission Age ("Submitted 3 days ago")
:   :   :   \-- Queue Action Buttons: [Review Document] | [One-Click Approve]
:   :
:   +-- 5.2 CAC Certificate Preview Modal (Side-by-Side Review Dialog)
:   :   ·-- Left Pane: High-Resolution PDF/Image Preview of CAC Incorporation Certificate
:   :   ·-- Right Pane: Verification Checklist:
:   :   :   ·-- [x] RC Number matches Corporate Affairs Commission registry
:   :   :   ·-- [x] Registered business address matches official domain WHOIS
:   :   :   ·-- [x] Authorized director / recruiter identity confirmed
:   :   \-- Decision Actions: [Reject with Reason...] | [Approve & Activate Company]
:   :
:   \-- 5.3 Employer Directory Table & Company Inspector Drawer
:       ·-- Search, Industry Filters (Fintech, Healthtech, Agritech, Media)
:       ·-- Metrics: Active Openings, Total Applicants, Placed Interns, Verification State
:       \-- Drawer: Corporate profile, hiring team members, active job listings
:
+-- [6.0] JENNY AI OPERATIONS CONSOLE — /admin/ai
:   :
:   +-- 6.1 Nightly Match Throughput & Engine Health Gauges
:   :   ·-- Model Status: Google Gemini 1.5 Pro / Flash (Live / 99.98% Uptime)
:   :   ·-- Nightly Batch Scoring Run: 1,904 candidate-internship pairs processed
:   :   \-- Overall Match Acceptance Rate: 71.4% (Industry target > 65%)
:   :
:   +-- 6.2 Flagged Match Discrepancy Queue (6 Pending Items)
:   :   ·-- Candidate vs Role Comparison View (Confidence score < 65% or skill mismatch)
:   :   ·-- AI Match Rationale Summary Explaining Why Flagged with distinct candidate typography
:   :   \-- Administrative Overrides: [Confirm Match] | [Suppress Match]
:   :
:   +-- 6.3 Token Consumption & Model Hyperparameters
:   :   ·-- Input / Output Token Usage Meter & Pay-per-Token Cost Projection
:   :   \-- Custom SaaS Range Sliders: Temperature (0.2), Top-P (0.95), Flag Threshold (65%)
:   :
:   \-- 6.4 Gemini Chat Operational Assistant — /admin/ai/chat
:       ·-- Gemini 1.5 Pro Operational Intelligence console
:       ·-- Platform telemetry metrics (Interns 94%, Employers 88%, CAC Speed 1.8h, Precision 99%)
:       ·-- Historical query session archive & breakdown
:       \-- Multimodal document attachment drawer supporting PDF, DOCX, CSV drag-and-drop
:
+-- [7.0] E-LEARNING & COURSE MODERATION — /admin/elearning
:   :
:   +-- 7.1 Mentor Course Submission Queue (3 Pending Review)
:   :   ·-- Course Title, Submitting Mentor Name, Domain (Design, Backend, Marketing)
:   :   ·-- Curriculum Inspection: Video modules preview, syllabus breakdown, quiz review
:   :   ·-- Standardized Request Revision Modal: Clean checkbox action item cards and structured feedback notes
:   :   \-- Decision Actions: [Request Revision] | [Approve & Publish to Catalog]
:   :
:   \-- 7.2 Catalog Performance & Enrollment Analytics
:       ·-- Most Popular Courses (e.g. Modern Frontend Engineering - 1,420 Enrolled)
:       ·-- Course Completion Rates & Student Feedback Ratings
:       \-- Discipline Distribution Histogram (Design 38%, Backend 32%, Frontend 18%, Data/AI 12%)
:
+-- [8.0] SUBSCRIPTIONS & PAYSTACK FINANCIAL LEDGER — /admin/subscriptions
:   :
:   +-- 8.1 Financial KPI Summary
:   :   ·-- Monthly Recurring Revenue (MRR: ₦4,850,000 / $6,200)
:   :   ·-- Active Student Pro Subscribers (1,420 Active Plans)
:   :   \-- Employer Placement Fees Collected (₦2,400,000 from 32 Hires)
:   :
:   +-- 8.2 Paystack Transaction Audit Table
:   :   ·-- Transaction Reference with Copy action and inline "Copied!" visual feedback
:   :   ·-- Customer Name, School / Entity, Plan Category, Amount, Payment Channel, Status
:   :   ·-- Webhook Signature Verification State (HMAC-SHA512)
:   :   \-- Anti-collision horizontal scroll container with pagination controls
:   :
:   \-- 8.3 Paystack Digital POS Receipt Inspector Modal
:       ·-- Digital POS receipt layout with reference, date, customer, and settled status
:       ·-- HMAC-SHA512 cryptographic verification seal
:       \-- Collapsible raw webhook JSON payload (charge.success) viewer
:
\-- [9.0] OPERATIONS SETTINGS & WATCHING STAFF DIRECTORY — /admin/settings/team
    :
    +-- 9.1 Watching Staff & Administrative Team Directory Table
    :   ·-- Col 1: Staff Member Identity (Name, Work Email, Avatar)
    :   ·-- Col 2: Assigned Role Badge (`Platform Admin`, `Verification Officer`, `Customer Care`)
    :   ·-- Col 3: 2FA Enforcement Status (`Active TOTP`, `Pending Setup`)
    :   ·-- Col 4: Last Active Timestamp & IP Address
    :   \-- Col 5: Actions: [Edit Role] | [Reset 2FA] | [Revoke Access]
    :
    +-- 9.2 Invite Staff Member Modal Dialog
    :   ·-- Work Email Input
    :   ·-- Role Selector Dropdown (`Verification Officer`, `Customer Care`, `AI Specialist`)
    :   ·-- Permission Scopes Preview (Read/Write capabilities for selected role)
    :   \-- Action: [Send Cryptographic Invitation Email] (7-day expiry)
    :
    \-- 9.3 Immutable Platform Audit Trail Log
        ·-- Chronological Event Stream with Timestamp, Staff Name, Target Entity, Action
        ·-- Search & Filter by Staff Member, Date Range, Action Type
        \-- Event Types: CAC Approved, Candidate Suspended, Refund Issued, Role Modified
===================================================================================================
```

---

## 4. Design System Standards for Product Design Team

When translating the ASCII tree structure above into Figma/UI mockups, the design team must strictly respect the following core design guidelines:

### 4.1 Color Palette & Neutrals
- **Primary Indigo**: `#4F48E6` (Brand accents, active tabs, primary action buttons, focused inputs).
- **Deep Navy**: `#000623` (Sidebar background, dark headings, high-contrast modal backdrops).
- **Subtle Light Background**: `#F8FAFC` / `#F1F5F9` (Dashboard page canvas, table borders, neutral chips).
- **Surface White**: `#FFFFFF` (Card surfaces, tables, top navigation, modal dialogs).
- **Status Semantic Tones**:
  - Emerald (`#10B981` / bg `#ECFDF5`): Active, Placed, Verified, Positive trends.
  - Amber (`#F59E0B` / bg `#FFFBEB`): Awaiting Review, Needs Decision, Medium priority.
  - Purple (`#7E22CE` / bg `#FAF5FF`): Jenny AI insights, High match scores (90%+).
  - Rose (`#E11D48` / bg `#FFE4E6`): Suspended accounts, Rejections, Critical alerts.

### 4.2 Absolute Prohibition of Colored Accent Border Strips
- **NO COLORED ACCENT BORDERS**: Never render colored borders (e.g. `border-left: 3px solid #4F48E6` or top colored strips) on cards, sidebars, or tables.
- All panels and cards must use uniform, subtle neutral borders (`border: 1px solid #E2E8F0;`).

### 4.3 Table & Drawer Spacing Guidelines
- **Responsive Custom Scrollbars**: When viewport width is constrained or the 420px inspector drawer is open, tables feature a sleek 6px custom scrollbar (`scrollbar-width: thin; scrollbar-color: #CBD5E1 #F8FAFC`) that only appears when content actually overflows, completely avoiding ugly 17px default OS scrollbars while ensuring full access to action columns.
- **Drawer Containment**: The Intern Profile Inspector slide-over drawer has a fixed width of **420px**. When open, the table column compresses gracefully without wrapping badges or causing layout overflows.
- **Table Cell Padding**: Standard cell padding is `12px 14px;` with compact profile progress bars (`width: 105px`).
- **Row 3-Dots Action Dropdown**: Anchored right-aligned menu using a single component-level `activeMenuId` state (never duplicating across rows). Displays structured options with 15px inline SVGs: Inspect Profile, Send Message, Copy Public Link, and Suspend Account (danger red).

### 4.4 Form Controls & Modals
- **No Native Boxy Selects**: All dropdowns must use custom rounded Alpine menu components.
- **Textarea Inset & Padding Standard**: All modal textareas (e.g. Suspend Intern Notes) must strictly enforce:
  - `padding-left: 14px !important;` (generous left clearance, never touching edge)
  - `padding-right: 14px !important;`
  - `padding-top: 8px !important;` (reduced top placeholder gap so text starts crisp and naturally)
  - `min-height: 85px !important; line-height: 1.45 !important; border-radius: 12px !important;`
- **Modal Geometry**: Max width 500px, 16px-20px rounded corners, 8px blur dark backdrop (`#000623/50`).

---

## 5. Summary of Completed Admin Views

| Route | View Path | Status | Key Components |
|---|---|---|---|
| `/admin` & `/admin/overview` | `frontend/admin/overview/index.php` | Complete | Topbar Command Search, 4 KPI Count-Up Cards, Chart.js Placements Area Chart, Needs Attention Queue, Jenny AI Strategic Insights Card |
| `/admin/interns` | `frontend/admin/interns/index.php` | Complete | Category Filter Tabs, Clean Table (No Scrollbars), 420px Profile Inspector Drawer, Suspend Account Modal with Inset Textarea |
| `/admin/employers` | `frontend/admin/employers/index.php` | Complete | CAC Verification Queue Shelf (14 Pending), Verification Badges, Directory Table, Company Drawer |
| `/admin/settings/team` | `frontend/admin/settings/team.php` | Complete | Watching Staff Directory, Invite Staff Modal, Role RBAC Matrix, Platform Audit Log |
