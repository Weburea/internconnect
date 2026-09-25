# Jenny AI Assistant - Robotics Architecture & Walkthrough (`md/robotics.md`)

This document provides a comprehensive operational breakdown and technical walkthrough of the **Jenny AI Assistant** robot implemented across the **InternConnect** platform.

---

## 1. Overview & Core Mission

**Jenny AI** is the unified conversational AI assistant and intelligent guide powering InternConnect. Jenny operates across all candidate portals, employer workspaces, public discovery pages, and the student Help Center, providing contextual guidance, automated resume optimization, candidate matching, and interview scheduling.

### Modes of Operation
1. **Jenny Support Mode** (`mode: 'support'`):
   - Active on `/help`, `/help/search`, and `/help/article/{slug}`.
   - Initialized with support greeting: *"Hi [FirstName]! I'm Jenny from Student Support. How can I help with your account or applications today?"*
   - Focuses on FAQ assistance, ticket dispatch, and candidate issue resolution.
2. **Jenny AI Assistant Mode** (`mode: 'ai'`):
   - Active platform-wide across candidate dashboards, employer workspaces, landing page, internships catalog, and pricing.
   - Features the full dual-persona intelligence with career and talent tools.

---

## 2. Visual Identity & Brand Design System

Jenny AI adheres strictly to the official InternConnect brand tokens:

| Token Name | Hex Code | Usage in Jenny AI Robot |
| :--- | :--- | :--- |
| **Navy** | `#000623` | Chat window header background, high-contrast typography, dark button hover states. |
| **Indigo** | `#4F48E6` | Primary accents, interactive buttons, user chat bubbles, active slot pills, launcher glow. |
| **Light Grey** | `#F2F2F2` | Bot chat bubbles, input form background, disabled states, subtle container rows. |
| **White** | `#FFFFFF` | Main chat window surface, card backgrounds, high-contrast text on navy/indigo. |

*Zero placeholder colors (`#1E1B4B`, `#0F172A`, `#6366F1`, `#4F46E5`) are permitted.*

---

## 3. Launcher Button Geometry & Asset Calibration

### Circular Perfection (Fixing the Oval Distortion)
- **Problem Diagnosed**: The raw badge image (`442px` width $\times$ `395px` height) contained asymmetrical transparent margins and an angled bottom-right speech pointer, causing standard `width: 66px; height: 66px;` CSS to stretch the icon vertically by ~12%, appearing oval.
- **Solution Implemented**:
  1. Cropped the asset into a true **1:1 square canvas** (`320px` $\times$ `320px`) centered on the circular badge body: [`jenny_icon.png`](file:///c:/xampp/htdocs/InternConnect/public/assets/images/jenny_ai/jenny_icon.png).
  2. Uploaded the squared asset to Cloudinary under public ID `internConnect/images/jenny_ai/jenny_icon` with version **`v1790304925`**.
  3. Calibrated `.jenny-launcher-btn` with `width: 62px; height: 62px; border-radius: 50%;` and `.jenny-launcher-img` with `width: 60px; height: 60px; object-fit: contain;`.
  4. The launcher renders as a true circle with zero visual distortion across all viewports and DPI scales.

### Mascot Pose Mapping
All mascot graphics are rendered purely as pose illustrations (no baked text layers). All brand names and copy are rendered via semantic HTML/CSS:

| Pose Asset | Cloudinary Version URL | Role & Trigger State |
| :--- | :--- | :--- |
| `jenny_icon` | `.../v1790304925/.../jenny_icon.png` | Floating launcher button badge & small header avatar |
| `jenny_ai_hi` (waving) | `.../v1790296466/.../jenny_ai_hi.png` | Welcome hero illustration & initial greeting bubbles |
| `Thinking_jenny_ai` (thinking) | `.../v1790296471/.../Thinking_jenny_ai.png` | Interactive typing & processing indicator |
| `Pointing_jenny_ai` (pointing) | `.../v1790296510/.../Pointing_jenny_ai.png` | AI recommendations, CV suggestions & tips |
| `Full-body_standing_pose_jenny_ai` | `.../v1790296516/.../Full-body_standing_pose_jenny_ai.png` | Full-body empty state & overview screens |

---

## 4. Strict Zero-Emoji & SVG Token Governance

In accordance with workspace design standards:
- **Zero Emojis**: Unicode glyphs (e.g. 📅, 💼, 👤, ✓, 💡, 🚀) are prohibited from chatbot copy, bubbles, and buttons.
- **Inline SVGs**:
  - **Job Seeker Role Icon**: Clean student/user silhouette vector.
  - **Recruiter Role Icon**: Executive briefcase vector.
  - **Interview Calendar Marker**: Styled calendar vector (`width: 18px; height: 18px; stroke: #4F48E6`).
  - **Status Checkmarks**: Clean vector checkmark (`<svg width="12" height="12"><path d="M20 6L9 17l-5-5"/></svg>`).
  - **Footer Brand Mark**: Inline InternConnect graduate/book SVG mark (`width: 13px; height: 15px; fill: #4F48E6`), guaranteeing zero broken image frames across all browsers.

### Single-Line Button Integrity
Interactive buttons that transition state (e.g., `.jenny-apply-btn` switching from "Apply" to "Applied"):
```css
.jenny-apply-btn {
    white-space: nowrap !important;
    flex-shrink: 0 !important;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    gap: 5px;
    min-width: 74px;
    height: 28px;
}
```
This guarantees the checkmark and text never wrap onto a second line.

---

## 5. Dual Persona Intelligence System

The chatbot provides dynamic switching between **Job Seeker** and **Recruiter** personas:

### A. Role Bar & Dynamic Personalization
- **Job Seeker**: Displays `Job Seeker ([FirstName])` (defaults to `Adaeze` or the logged-in candidate's first name).
- **Recruiter**: Displays `Recruiter ([FirstName])` (defaults to `Tunde` or the logged-in employer's first name).
- **Pill Toggle**: A rounded pill button (`Switch to Recruiter` / `Switch to Job Seeker`) with a bidirectional swap SVG icon and smooth hover animations.

---

## 6. Interactive Robot Workflows

### Flow 1: CV Optimization & Review (Job Seeker)
1. **Trigger**: Click `Update my CV` or type *"Update CV"*.
2. **Upload Simulation**: Renders `Adaeze_CV.pdf` (2.4 MB) with animated progress bar (`15%` $\rightarrow$ `45%` $\rightarrow$ `75%` $\rightarrow$ `100%`) and transitions to `Upload complete` with an SVG checkmark.
3. **Radial Score Card (78%)**: Displays radial progress circle with score, competitive assessment, and 3 actionable suggestions:
   - *Add a professional summary to showcase your career goals.*
   - *Quantify achievements with clear numbers and percentage results.*
   - *Include relevant industry tools and frameworks.*
4. **Interactive Apply Action**: Clicking "Apply" transforms the button to a green `#10B981` pill displaying `Applied` + inline SVG checkmark on a single line.
5. **Before & After Preview**: When 2 or more suggestions are applied, Jenny automatically generates a side-by-side experience section preview (Before vs After).
6. **Accept Changes CTA**: Clicking "Accept Changes" finalizes the CV and displays a direct link to `Show matches →` (`/internships`).

### Flow 2: Candidate Shortlisting (Recruiter)
1. **Trigger**: Click `Shortlist candidates`.
2. **Candidate Dossier**: Displays matched profiles:
   - **Chinonso Eze**: `96% match`, Lagos, Product Design Intern.
   - **Amara Okafor**: `88% match`, Abuja, UX Research Intern.
3. **Quick Action**: Direct `Invite to interview` button opens the scheduling flow.

### Flow 3: Premium Online Interview Scheduling (Recruiter)
1. **Trigger**: Click `Schedule interviews` or `Invite to interview`.
2. **Interview Card Components**:
   - Header badge: `Online Video Interview` (video camera SVG) + `30 mins` duration pill.
   - Candidate details: `Chinonso Eze • Product Design Intern`.
   - Date banner: Styled box with SVG calendar icon, `Tuesday, 18 Nov 2026`, and `West Africa Time (WAT)`.
   - Time slots grid: 5 interactive slot pills (`9:00 AM`, `10:30 AM`, `2:00 PM`, `4:00 PM`, `5:30 PM`) with active indigo state.
   - Single-line CTA: `Confirm Slot: 10:30 AM →`.
3. **Confirmed State**: Displays green confirmation card with SVG check circle, session details, and confirmation that calendar invites have been dispatched.

---

## 7. Global Layout Integration

Jenny AI is included globally in both shared layout wrappers:
- [`frontend/shared/layouts/dashboard.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/layouts/dashboard.php): Covers Candidate Dashboard, Saved Roles, Applications, Profile Settings, Company Overview, and the Help Center.
- [`frontend/shared/layouts/app.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/layouts/app.php): Covers the Public Landing Page, Browse Internships, and Pricing.

Component File: [`frontend/shared/components/chatbot/jenny-ai.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/components/chatbot/jenny-ai.php)

---

## 8. Verification & Test Checklist

- [x] Header Avatar loads crisp 3D badge from Cloudinary (`v1790304925`).
- [x] Launcher button is a true 1:1 circle (`62px × 62px`, `object-fit: contain`).
- [x] Zero emojis present in entire chatbot component.
- [x] Applied button remains on a single line with inline SVG checkmark.
- [x] Online interview card uses SVG calendar icon and responsive slot grid.
- [x] Footer "Powered by InternConnect AI" renders inline SVG mark.
- [x] Role switcher dynamically personalizes first names (`Adaeze` / `Tunde`).
