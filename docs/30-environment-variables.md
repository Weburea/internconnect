# Environment Variables

All settings and credentials must be configured via environment variables.

Never commit `.env` files to git. Use `.env.example` as a template.

---

## App Configuration

- `APP_ENV`: Environment state (`local`, `staging`, `production`)
- `APP_DEBUG`: Toggle detailed errors (`true` or `false`)
- `APP_URL`: Base application URL (e.g. `http://localhost/InterConnect`)

---

## Database Configuration

- `DB_HOST`: Database server IP or hostname (e.g., `127.0.0.1`)
- `DB_PORT`: Database port (default `3306`)
- `DB_DATABASE`: MySQL database name (`interconnect`)
- `DB_USERNAME`: Database connection user
- `DB_PASSWORD`: Database connection password

---

## DeepSeek AI Configuration

- `DEEPSEEK_API_KEY`: API Key for AI resume parsing and matching
- `DEEPSEEK_API_BASE`: API base URL for DeepSeek requests
- `DEEPSEEK_MODEL`: Model name (default `deepseek-chat`)

---

## Paystack Configuration

- `PAYSTACK_PUBLIC_KEY`: public key for JS-side integration
- `PAYSTACK_SECRET_KEY`: server-side verification key
- `PAYSTACK_WEBHOOK_SECRET`: payload signature verification token

---

## Google OAuth Configuration

- `GOOGLE_CLIENT_ID`: OAuth client id
- `GOOGLE_CLIENT_SECRET`: OAuth secret token
- `GOOGLE_REDIRECT_URL`: Redirect callback URL for OAuth flow

---

## Mail Configuration

- `MAIL_HOST`: SMTP server hostname
- `MAIL_PORT`: SMTP port (default `587`)
- `MAIL_USERNAME`: SMTP login user
- `MAIL_PASSWORD`: SMTP login password
- `MAIL_ENCRYPTION`: Encryption wrapper (`tls` or `ssl`)
