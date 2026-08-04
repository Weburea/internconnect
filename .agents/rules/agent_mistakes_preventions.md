# Agent Mistake Prevention & UI/UX Styling Rules

To prevent repeating design discrepancies, layout bugs, and stylesheet loading issues, all future agent tasks must strictly adhere to the guidelines and rules outlined in this document.

---

## 1. Documented Mistakes & Rectifications

### A. Missing or Cached Stylesheet Updates
* **Mistake**: Placing new layout classes in external files (e.g. `welcome.css`) that are cached by the browser, resulting in elements (like benefits cards) rendering unstyled and stacked vertically.
* **Rule**: For critical custom layouts and newly added UI grids, embed the CSS directly in a local `<style>` block of the PHP view, or append versioning query parameters to the style links (e.g., `?v=<?= time() ?>`) to force cache busting.

### B. Uncompiled Tailwind CSS Utilities
* **Mistake**: Using Tailwind classes (like `right-4`, `top-4`, `animate-spin`) that are not pre-compiled in `style.css`, causing elements (like close buttons) to align incorrectly in the center, or loaders to remain static.
* **Rule**: Always verify if a Tailwind utility is compiled. If it is not present in the stylesheet, use explicit inline CSS styles (e.g. `style="position: absolute; right: 16px; top: 16px;"`) or define custom CSS animations inside the local `<style>` block.

### C. Improper Layout Element Truncation
* **Mistake**: Leaving orphaned HTML tags, closing divs, or redundant CTA buttons (like the leftover Step 8 code in `register.php`) during step-by-step layout edits.
* **Rule**: Conduct a full code block comparison and syntax check (`php -l`) after each replacement. Ensure all parent and child wrappers close cleanly without duplicating actions or rendering raw elements.

### D. Modal Flow & Transition Actions
* **Mistake**: Dismissing confirmation modals on backdrop or close button clicks without triggering the state machine to advance to the next step, leaving the user stuck on the current form step.
* **Rule**: Always link all cancellation/close pathways (backdrop clicks, escape keys, and close buttons) to the proper state transitions (e.g. `@click="show_success_modal = false; step = 4;"`) so the user flow continues smoothly.

### E. Header and Footer Structural Alignment
* **Mistake**: Deviating from the alignment grid of the main content column (e.g., centering the header logo or letting footers stretch to screen boundaries without symmetric margins).
* **Rule**: Maintain consistent alignment containers (e.g., wrapping headers and footers in matching max-width flex groups like `max-width: 1140px; margin: 0 auto; padding: 0 32px;`) to match margins across pages.

### F. PHP Helpers Inside External `.js` Files ⚠️ CRITICAL
* **Mistake**: Writing PHP expressions like `<?= url('/welcome') ?>` or `<?= asset('...') ?>` directly inside a `.js` file (e.g. `onboarding-student.js`). PHP **never processes `.js` files** — the raw tag is sent to the browser as a literal string, causing broken redirects and 404 errors.
* **Rule**: **Never place PHP expressions inside any `.js` file.** When an external JS file needs a server-generated URL or path:
  1. Inject a `window.VAR_NAME` variable in a small inline `<script>` block inside the **PHP template** (where PHP is parsed), placed **before** the `<script src="...">` tag that loads the external file.
  2. Reference `window.VAR_NAME` inside the `.js` file with a fallback: `window.VAR_NAME || '/fallback-path'`.
  * **Example** (in the `.php` file):
    ```html
    <script>window.INTERN_WELCOME_URL = '<?= url('/welcome') ?>';</script>
    <script src="<?= asset('assets/js/onboarding-student.js') ?>"></script>
    ```
  * **Example** (in the `.js` file):
    ```js
    window.location.href = (window.INTERN_WELCOME_URL || '/welcome') + '?name=' + encodeURIComponent(name);
    ```
  * **Apply this same pattern to ALL new onboarding JS files** — including `onboarding-employer.js` and any future wizard JS file.

### G. Missing Route Registration for New Views ⚠️ CRITICAL
* **Mistake**: Creating a new PHP view file (e.g. `register-student.php`) and redirecting to its URL (e.g. `/register/student`) without first registering the corresponding GET route in `backend/routes.php` and adding a controller method in `AuthController.php`. The result is a 404 page on navigation.
* **Rule**: **Every new view page must have three matching pieces before it is considered complete:**
  1. The view file under `frontend/auth/`.
  2. A GET route entry in `backend/routes.php`: `$router->get('/path', [Controller::class, 'method']);`
  3. A controller method in the matching controller that calls `$this->renderView(...)`.
  * **Checklist for any new auth page**:
    - [ ] View file created: `frontend/auth/<name>.php`
    - [ ] Route registered: `backend/routes.php`
    - [ ] Controller method added: `backend/Controllers/AuthController.php`

### H. Wrong Alpine Step Initialization When Splitting Wizard Across Multiple Pages
* **Mistake**: When a multi-step wizard is split across pages (e.g. `register.php` handles Step 1, `register-student.php` handles Steps 2–7), the Alpine component in the second page still initializes `step: 1` and falls back to `|| 1` when reading localStorage. This makes the second page render a blank/invisible form because no `x-show="step === 1"` block exists on it.
* **Rule**: When a wizard is split across multiple pages, **each page's Alpine component must initialize `step` to the first step it owns**, and the localStorage fallback must match:
  * The **first page** (Step 1) sets `step: 1` and falls back to `|| 1`.
  * The **second page** (Steps 2–7) sets `step: 2` and falls back to `|| 2`, plus clamps: `loadedStep < 2 ? 2 : loadedStep`.
  * **Apply this same pattern to `onboarding-employer.js`** — it must initialize and clamp to the first step it owns.

---

## 2. Mandatory Rules for Design Implementations

* **No Placeholders**: Never render blank or unstyled containers. Always style grids, cards, icons, and text nodes to feel premium.
* **Icon Shaded Wrappers**: Wrap all list icons in custom shaded circular badges (using `bg-primary-light` and `text-primary`) rather than displaying raw icons next to text.
* **Loading State Indicators**: All processing buttons must use animated rotating spinner components. Use reliable looping loader icons (like `line-md:loading-twotone-loop`) to render smooth rotation on any browser.
* **Sufficient Cooldown Indicators**: Auto-redirection modal prompts must provide a minimum of 5 seconds delay so that the user has comfortable reading time.
* **External JS URL Safety**: Before shipping any external `.js` file that handles redirects or asset URLs, verify zero PHP expressions exist inside it. All dynamic URLs must go through `window.*` variables set in the PHP template.
* **Route + Controller + View Triple Check**: Before marking any new page as done, confirm all three exist: route in `routes.php`, method in the controller, and view file in `frontend/`.
