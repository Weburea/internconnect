# Logging & Monitoring System

## Purpose

The Logging and Monitoring System provides visibility into platform activity, security events, user actions, payments, AI usage, and system health.

This system is critical for:

- Debugging
- Security Monitoring
- Compliance
- Fraud Detection
- Performance Monitoring
- Incident Investigation

---

# Logging Categories

## Authentication Logs

Track:

- Login Success
- Login Failure
- Registration
- Password Reset
- Google OAuth Login
- Session Expiration

Table:

auth_logs

Fields:

- id
- user_id
- email
- action
- ip_address
- user_agent
- created_at

---

## User Activity Logs

Track:

- Internship Application
- Internship Save
- Profile Update
- Resume Upload
- Subscription Upgrade

Table:

user_activity_logs

Fields:

- id
- user_id
- action
- description
- created_at

---

## Employer Activity Logs

Track:

- Internship Creation
- Internship Update
- Internship Closure
- Candidate Shortlist
- Candidate Rejection
- Interview Scheduling

Table:

employer_logs

Fields:

- id
- employer_id
- action
- target_id
- created_at

---

## Admin Audit Logs

Track Every Admin Action

Examples:

- User Suspension
- User Deletion
- Internship Approval
- Internship Removal
- Subscription Modification
- Settings Changes

Table:

admin_audit_logs

Fields:

- id
- admin_id
- action
- affected_record
- old_value
- new_value
- created_at

IMPORTANT:

Admin logs must never be editable.

---

## Payment Logs

Track:

- Payment Initiated
- Payment Successful
- Payment Failed
- Subscription Renewed
- Refund Processed

Table:

payment_logs

Fields:

- id
- user_id
- transaction_reference
- amount
- status
- provider
- created_at

---

## AI Usage Logs

Track:

- Resume Optimization
- Job Recommendations
- Candidate Matching
- Mock Interviews

Table:

ai_usage_logs

Fields:

- id
- user_id
- feature
- tokens_used
- request_cost
- response_time
- created_at

Purpose:

- Cost Monitoring
- Abuse Detection
- Usage Analytics

---

## API Logs

Track:

- API Request
- API Response
- Validation Failure
- Unauthorized Request

Table:

api_logs

Fields:

- id
- endpoint
- method
- status_code
- execution_time
- ip_address
- created_at

---

# Security Monitoring

Monitor:

- Failed Login Attempts
- Brute Force Activity
- Suspicious IP Addresses
- Excessive API Requests
- Payment Abuse
- AI Abuse

Automatic Alerts:

- More than 10 failed logins in 5 minutes
- More than 100 API requests per minute
- More than 20 AI requests in 1 minute
- Multiple failed payments

---

# Error Logging

Track:

- PHP Errors
- Database Errors
- API Failures
- Webhook Failures

Storage:

storage/logs/

Files:

application.log
error.log
payment.log
ai.log
security.log

---

# Performance Monitoring

Track:

- Page Load Time
- API Response Time
- Database Query Time
- AI Response Time

Targets:

Page Load:
< 2 Seconds

API Response:
< 500ms

Database Query:
< 100ms

AI Response:
< 10 Seconds

---

# Monitoring Dashboard

Admin Dashboard Widgets

- Active Users
- Active Employers
- New Registrations
- Failed Logins
- Revenue Today
- AI Requests Today
- Pending Approvals
- System Health

---

# Log Retention Policy

Authentication Logs:
12 Months

Payment Logs:
7 Years

Admin Logs:
7 Years

AI Logs:
12 Months

Error Logs:
6 Months

API Logs:
3 Months

---

# Backup Strategy

Daily Backup

- Database
- Logs
- Uploaded Files

Weekly Backup

- Full Server Snapshot

Monthly Backup

- Long-Term Archive

Retention:

Daily:
30 Days

Weekly:
12 Weeks

Monthly:
12 Months

---

# Rules

Logs Must Never Be Deleted Directly

Logs Must Be Timestamped

Logs Must Include User Reference

Logs Must Be Searchable

Logs Must Be Exportable

Sensitive Data Must Never Be Logged

Do Not Log:

- Passwords
- Card Information
- Paystack Secrets
- API Keys
- OAuth Tokens
