# Third-Party Services

InternConnect integrates with several external service providers to handle payments, authentication, messaging, and AI logic.

---

## Services Overview

### DeepSeek API

- Purpose: Resume parsing, recommendation calculations, and mock interviews
- Access: REST API over HTTPS
- Credentials required: `DEEPSEEK_API_KEY`
- Fallback: Fall back to local rule-based database queries if DeepSeek experiences outages

### Paystack

- Purpose: Credit card payments, bank transfers, and subscription upgrades
- Access: Secure inline payment popup and server-side verification webhooks
- Credentials required: `PAYSTACK_PUBLIC_KEY`, `PAYSTACK_SECRET_KEY`
- Fallback: Temporary banner indicating payment service degradation; queue pending activations

### Google OAuth

- Purpose: One-click registration and login for candidate accounts
- Access: Google Identity Services API
- Credentials required: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`
- Fallback: Fall back to standard email/password registration with validation checks

### SMTP/Mail Service

- Purpose: System transactional emails (e.g. registration, application status changes)
- Access: Standard SMTP connection
- Credentials required: `MAIL_USERNAME`, `MAIL_PASSWORD`
- Fallback: Queue outbound emails in local storage database tables for retries
