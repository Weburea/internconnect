# DevOps & Backups

This document outlines deployment pipelines, server configurations, backup strategies, and recovery verification processes.

---

## Infrastructure

- Web Server: Apache 2.4 / Nginx (XAMPP locally, Linux VPS on Production)
- Database: MySQL 8.0
- PHP Version: 8.3+
- HTTPS/TLS: Let's Encrypt SSL certificates (auto-renewed)

---

## Deployment Workflow

- Target Branch: `main`
- Method: Automated git-based pull deployments on push to `main`
- Tasks run during deployment:
  - Composer dependency updates (`composer install --no-dev`)
  - Run database migrations (`composer migration:migrate`)
  - Warm template caches and bundle web assets
  - Clean PHP opcache

---

## Backup Configurations

### Database Backups

- Method: Automated nightly mysqldump
- Target: Secure off-site cloud storage (AWS S3 or similar)
- Encryption: GPG encrypted before transfer
- Retention: Daily backups kept for 30 days, monthly backups kept for 12 months

### Uploaded File Backups

- Method: Restic or rsync daily incremental sync
- Files: Uploaded resumes, avatars, documents stored outside the public directory

---

## Restoration Verification

- Monthly Restoration Test: Run a restore script inside a clean test environment to verify database backup integrity
- Alerting: Failed backups send high-priority alerts to notifications endpoint and admin logs
