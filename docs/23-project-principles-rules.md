# InternConnect Project Principles

## Purpose

These rules override personal preferences.

All contributors must follow them.

---

# Rule 1

Build Simple First

Never build an advanced solution when a simple solution works.

---

# Rule 2

Security Before Features

Every feature must pass security review before release.

---

# Rule 3

Mobile First Thinking

Every screen must work on mobile.

Nigeria is a mobile-first market.

---

# Rule 4

AI Assists

AI never makes final decisions.

AI recommends.

Humans decide.

---

# Rule 5

User Data Protection

User information must never be exposed.

Never sell user data.

Never expose private information.

---

# Rule 6

Performance Matters

Page Load Target

Less than 2 seconds.

API Response Target

Less than 500ms.

---

# Rule 7

Accessibility

Interfaces must be usable by everyone.

Maintain proper contrast.

Use semantic HTML.

---

# Rule 8

No Hardcoded Secrets

Never commit:

- API Keys
- Passwords
- Tokens
- Secrets

Use .env files.

---

# Rule 9

Consistency Over Creativity

Follow existing patterns.

Do not create new patterns unless necessary.

---

# Rule 10

Documentation Required

Every major feature must include:

- Documentation
- Database updates
- API updates

before deployment.

---

# Rule 11

Design System First

Every UI element must use:

- Typography Tokens
- Color Tokens
- Component Library

No random styling.

---

# Rule 12

Scalability

Build for:

10 users

100 users

1,000 users

10,000 users

without major rewrites.

---

# Rule 13

Data Integrity

Never delete important records permanently.

Use:

soft deletes

when possible.

---

# Rule 14

InternConnect Mission

Help students and graduates discover opportunities faster.

Help employers find talent efficiently.

Every feature must support this mission.

# Rule 15

internconnect/

├── docs/
│
├── design/
│
├── public/
│ ├── assets/
│ │ ├── css/
│ │ ├── js/
│ │ ├── images/
│ │ ├── icons/
│ │ └── fonts/
│
├── frontend/
│ ├── landing/
│ ├── auth/
│ ├── candidate/
│ ├── employer/
│ ├── admin/
│ └── shared/
│
├── backend/
│
├── database/
│
├── sql/
│
├── api/
│
└── docs/

# Rule 16

Frontend should be SPA(Single Page Application)
Backend should be RESTful API

1. Homepage
2. Internship Listing
3. Internship Details
4. Login
5. Registration
6. Candidate Dashboard
7. Employer Dashboard
8. Admin Dashboard

---

# Rule 17

SQL File Storage

All generated and manually created SQL files (e.g., table schemas, database dumps, seeds) must be stored in the `sql/` directory at the project root.
For example, if you generate a schema for a user database, it must be stored as `sql/user.sql`.

---

# Rule 18

Exact Visual Fidelity and Layout Compliance

When a visual design image, mockup, or screenshot is provided for editing or implementation, the layout must match all details of the mockup precisely on the first attempt. Developers and AI agents must rigorously verify:
- Background colors, layout contrast, and panel styling.
- Exact icon representation (avoiding placeholder icons and matching specific layout details).
- Button default styles (reusing standard global classes and preventing purge/reset issues).
- Screen dimensions and card heights (avoiding vertical stretching that creates excessive top spacing).
This strict alignment prevents design mismatches, unnecessary iterations, and token consumption waste.


