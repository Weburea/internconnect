# Workspace Global Rules

This file documents the core governance policies for developers and AI agents working on the InternConnect repository.

---

## Documentation Hierarchy

All modifications must align with the documentation hierarchy in the priority order below:

1. Global Rules (`rules.md`, `39-workspace-global-rules.md`)
2. Architecture & Security (`01-roadmap.md` to `09-ai-system.md`, `17-logging-monitoring.md`, `19-folder-structure.md`)
3. Business Logic (`05-role-permissions.md`, `10-admin-system.md` to `16-subscription-plans.md`)
4. Product & Design (`18-design-system.md`, `21-future-features.md`)
5. Engineering Operations (`20-deployment-guide.md` to `23-project-principles-rules.md`, `24-changelog.md`+)

---

## Core Policies

### Security First

- Security and data protection take precedence over features, convenience, and deployment speed.
- Never commit credentials, keys, or passwords.
- Use parameterized prepared statements for all database interactions.

### AI Agent Governance

- AI tools must analyze existing docs before changing any files.
- AI must recommend and help code but never automatically override manual administrator decisions or manipulate user data without human confirmation.
- AI tools must perform a strict visual analysis of any dropped design mockups or screenshots, implementing all layout properties (icons, backgrounds, spacing, and buttons) precisely on the first attempt to avoid layout iterations and token waste.

### Data Protection

- Strictly protect user private information, transactions, and passwords.
- Encrypt sensitive columns and secure uploads outside the web public root directory.

### SQL Management

- All generated and manually created SQL files (e.g., table schemas, database dumps, seeds) must be stored in the `sql/` directory at the project root.
- For example, if you generate a schema for a user database, it must be stored as `sql/user.sql`.

---

## Definition of Done

A task is complete only when:

1. Functionality runs without errors
2. Security review passes
3. Documentation files are updated
4. Design system guidelines are respected
5. No critical console errors or debug codes remain
6. Version control commit is logged in the Git repository

---

## Cloudinary Quick-Image Workflow

### Purpose
To speed up UI content creation, developers and AI agents should upload assets directly to
Cloudinary and reference them immediately - no local copy required. This eliminates the need
to generate placeholder or synthetic images.

### Upload and Reference Rules

1. Upload to the correct folder: all design assets must go inside internConnect/ on Cloudinary.
   Example path: internConnect/images/homepage_hero_illustration_bnhu4c.png
2. Register in helpers.php: every new Cloudinary asset must be added as an explicit if mapping
   inside ackend/Core/helpers.php under the sset() function.
3. Always reference via sset() helper in PHP templates:
   <?= asset( assets/images/<filename>) ?>
   Never hardcode full Cloudinary URLs in templates.
4. Exception: root-level logo SVGs (outside the internConnect/ prefix) may use the full URL
   directly but must include a version-ID comment.

### Asset Naming Convention

- Lowercase with underscores: homepage_hero_illustration_bnhu4c.png
- Prefix with page/section: homepage_, dashboard_, employer_
- Suffix icons with _icon, showcases with _showcase, illustrations with _illustration

### AI Agent Image Rules

- AI agents must NEVER generate or synthesize images unless the user explicitly requests it.
- When the user provides a Cloudinary URL, use it directly — no alternatives.
- If an image is missing and no URL is provided, ask the user to upload to Cloudinary first.
