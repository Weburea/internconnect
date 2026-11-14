# MySQL: internconnect_db alignment and migrations

> Added 2026-11-12. Runner: `bin/migrate.php`. Migrations: `database/migrations/`.

## 1. State of `internconnect_db` (local XAMPP)

**Before**: the six Laravel-style tables from 1 Aug 2026:
- `users`, `candidate_profiles`, `employer_profiles`, `internships`, `applications`, `saved_internships`;
- plus `sessions`, `password_reset_tokens` and `migrations` (6 rows).

**After `php bin/migrate.php`** (2026-11-12):
- 3 new migrations recorded as batch 2.
- 43 new tables (52 in total) for every feature the app runs today (list in `database/migrations/mysql_schema_snapshot.sql`).
- Existing tables and their rows were not touched.

| Migration | What |
|---|---|
| `2026_11_12_000001_feature_tables.sql` | Video interviews (rooms, events, scorecards, files, chat, reports), scheduled interviews, notifications, messages, AI usage log, audit log, geo cache, platform settings. Taken from `schema_mysql.sql` **without foreign keys** to the account tables |
| `2026_11_12_000002_app_tables.php` | Runs each service's own MySQL DDL: rate limits, API tokens, billing, applications, CV profiles, e-learning, plans/grants/usage, mail queue, newsletter, contact, Jenny, visits, training partners. Seeds defaults without overwriting edits |
| `2026_11_12_000003_plan_reminders.php` | Table for one expiry reminder per paid period |

## 2. Commands

```
php bin/migrate.php --status              # applied / pending
php bin/migrate.php                       # apply pending (default database: internconnect_db, or DB_DATABASE when DB_CONNECTION=mysql)
php bin/migrate.php --database=other_db   # another database
```

**Rules for new migrations**:
- Add a new file with the next number.
- Never edit an applied one.
- Only additive statements: `CREATE TABLE IF NOT EXISTS`, `ADD COLUMN`.
- After each run, `mysql_schema_snapshot.sql` is refreshed for phpMyAdmin imports on cPanel.

## 3. Verified on MySQL

A scratch database was created, migrated, exercised and dropped. 24 checks passed:
- plan grant, quotas (`ON DUPLICATE KEY`), admin limit edits, Premium listings;
- receipt columns;
- mail queue; newsletter subscribe, confirm and campaign; contact;
- Jenny storage; visit tracking;
- training partner keys, signature, result, correction (transactions), audit and idempotency;
- rate limiter, e-learning catalogue, cron maintenance.

## 4. Switching the app to MySQL

The site still runs on SQLite locally (`DB_CONNECTION=sqlite`), which keeps your current demo data. To switch:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=internconnect_db
DB_USERNAME=root
DB_PASSWORD=
```

Data already in SQLite (demo payments, grants, chats) is not copied automatically.

## 5. Still for the accounts phase

The full design in `database/schema_mysql.sql` (`users` with UUIDs, `candidate_profiles`, and so on) differs from the existing Laravel `users` table. Moving accounts to MySQL needs a planned data migration for the 2 existing users, done with `ALTER TABLE ... ADD COLUMN` and copies, never drops. The foreign keys to the account tables are added in that phase.
