# Git Workflow & Version Control

## Purpose

Prevent code loss, bad deployments, and irreversible mistakes.

Provide complete project history and rollback capability.

https://github.com/Weburea/InterConnect.git

---

# Repository

Main Repository

InternConnect

Branch Strategy

main
develop
feature/\*

Example

feature/auth-system
feature/paystack
feature/ai-matching

---

# Development Workflow

Step 1

Create feature branch

Step 2

Develop feature

Step 3

Test feature locally

Step 4

Commit changes

Step 5

Push to GitHub

Step 6

Merge into develop

Step 7

Merge develop into main

---

# Commit Rules

Bad Example

fix bug

update

change code

Good Example

feat(auth): implement Google OAuth login

fix(payment): resolve Paystack webhook verification

feat(ai): add candidate matching service

refactor(profile): optimize candidate profile queries

---

# Commit Frequency

Minimum

1 commit every completed task

Recommended

3–10 commits daily

Never work more than 24 hours without pushing code.

---

# Push Schedule

End Of Day Push

Required

Every Day

Weekly Stable Release

Friday

Tag Example

v0.1.0
v0.2.0
v1.0.0

---

# Release Strategy

Development

develop branch

Production

main branch

Only tested code enters main.

---

# Rollback Strategy

If deployment fails:

Identify release tag

Example

v0.5.2

Rollback

git checkout v0.5.2

Restore stable version

---

# Backup Rules

GitHub Repository

Primary Backup

Server Backup

Secondary Backup

Database Backup

Daily

Uploads Backup

Daily

---

# Protected Branches

Protect:

main

Require:

- Pull Request
- Successful Tests
- Review Before Merge

---

# Documentation Rule

Every major feature must update:

- roadmap.md
- architecture.md
- changelog.md

before merging.
