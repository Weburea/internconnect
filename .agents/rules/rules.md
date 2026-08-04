---
trigger: always_on
---

# InternConnect Global Rules

## Purpose

This document is the master governance file for the InternConnect workspace.

All AI agents, developers, designers, QA engineers, and automation systems must treat this file as the highest-level project authority.

Every task, feature, update, refactor, deployment, design decision, AI integration, database modification, and security implementation must follow the standards defined in this document and the referenced documentation files.

---

# Documentation Hierarchy

Priority Order

Level 1 - Global Rules

rules.md
agent_mistakes_preventions.md
google_signup_navigation_logic.md

follow my /docs md files a must

Level 2 - Architecture & Security

01-roadmap.md
02-system-architecture.md
03-database-architecture.md
04-authentication-system.md
07-security-standards.md
08-payment-system.md
09-ai-system.md
17-logging-monitoring.md
19-folder-structure.md

Level 3 - Business Logic

05-role-permissions.md
10-admin-system.md
11-candidate-system.md
12-employer-system.md
13-notification-system.md
14-messaging-system.md
15-interview-system.md
16-subscription-plans.md

Level 4 - Product & Design

18-design-system.md
21-future-features.md

Level 5 - Engineering Operations

20-deployment-guide.md
22-git-workflow-version-control.md
23-project-principles-rules.md
24-changelog.md

---

# Mandatory Rule

Before modifying any system:

1. Read relevant documentation.
2. Verify architecture alignment.
3. Verify security compliance.
4. Verify design compliance.
5. Verify role permissions.
6. Verify database impact.
7. Update changelog if required.

No feature may bypass this workflow.

---

# Project Mission

InternConnect exists to connect students, graduates, interns, and employers through a secure, scalable, AI-assisted internship platform.

Every feature must contribute to one of the following:

- Better candidate experience
- Better employer experience
- Better hiring outcomes
- Platform trust and security
- Business sustainability

If a feature does not support one of these goals, it should not be built.

---

# AI Agent Requirements

Every AI agent must:

- Read documentation before implementation.
- Follow architecture standards.
- Follow security standards.
- Follow design system standards.
- Follow database standards.
- Follow coding standards.

AI agents must never invent architecture that conflicts with existing documentation.

AI agents must never bypass security requirements.

AI agents must never introduce undocumented systems.

---

# Documentation Synchronization

Whenever a major feature changes:

Required Updates:

- architecture
- database
- API documentation
- changelog
- roadmap

Documentation must remain synchronized with implementation.

Code and documentation must never diverge.

---

# Security First Policy

Security takes precedence over:

- speed
- convenience
- visual design
- feature delivery

Any implementation that weakens security must be rejected.

---

# Data Protection Policy

Never expose:

- passwords
- API keys
- tokens
- payment secrets
- user private information

All sensitive information must be encrypted, protected, and audited.

---

# Version Control Policy

Every completed task must:

- be committed
- be documented
- be tracked

No major feature may exist without version history.

---

# Scalability Policy

Every implementation must consider:

- 10 users
- 100 users
- 1,000 users
- 10,000 users

Avoid architecture that requires complete rewrites later.

---

# Design Consistency Policy

All interfaces must follow:

18-design-system.md

No random colors.
No random spacing.
No random typography.
No inconsistent UI patterns.

---

# SQL Management Policy

All generated and manually created SQL files (e.g., table schemas, database dumps, seeds) must be stored in the `sql/` directory at the project root.

For example, a generated user database schema file must be stored as `sql/user.sql` (or a descriptive name in the `sql/` folder).

---

# Cloudinary Integration Policy

All UI design assets, including homepage images, logos (SVGs), and video files, must be stored and served from the Cloudinary `internConnect` folder. No static image assets or videos related to the UI design may be served locally. Dynamic loading must be routed via the `asset()` helper function in `helpers.php`.

---

# AI Governance

AI may:

- Recommend
- Analyze
- Suggest
- Score

AI may not:

- Automatically alter user data
- Make hiring decisions
- Override administrator actions

Humans retain final authority.

---

# Definition of Done

A task is complete only when:

- Functionality works
- Security review passes
- Documentation updated
- Git commit created
- Design system respected
- No critical bugs remain

Only then is the task considered finished.

24-changelog.md
25-coding-standards.md
26-testing-strategy.md
27-database-migrations.md
28-devops-backups.md
29-api-reference.md
30-environment-variables.md
31-error-handling.md
32-performance-standards.md
33-accessibility-standards.md
34-seo-standards.md
35-release-checklist.md
36-incidents-disaster-recovery.md
37-third-party-services.md
38-ai-prompt-library.md
39-workspace-global-rules.md
