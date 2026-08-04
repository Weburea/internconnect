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

