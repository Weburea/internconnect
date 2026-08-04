# Database Migrations

All schema changes must be managed through database migrations.

Direct manual changes in MySQL are forbidden on production environments.

---

## Migration Tools

- Framework: Phinx (or custom PHP database migration runner)
- Storage: `database/migrations/`
- Naming format: `YYYYMMDDHHMMSS_description.php`

---

## Migration Workflow

### Step 1: Create a Migration

- Use migration runner script: `composer migration:create add_status_to_applications`
- Avoid database-specific dialects if possible

### Step 2: Implement `up` and `down`

- `up()`: Code to apply structural/schema changes
- `down()`: Code to safely reverse/rollback the schema changes
- Ensure rollback logic preserves existing data when possible

### Step 3: Run Migrations

- Local execution: `composer migration:migrate`
- Test rollbacks: `composer migration:rollback`

---

## Schema Guidelines

- Every table must have an auto-incrementing primary key named `id` (or UUID where required)
- Every table must include `created_at` and `updated_at` datetime fields
- Foreign key constraints must define appropriate deletion cascades (e.g., `ON DELETE RESTRICT` or `ON DELETE CASCADE`)
- Add indexes to frequently filtered/searched columns (e.g., `email`, `status`, `user_id`)
