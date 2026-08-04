# API Reference

This document lists the core endpoints, parameters, and payloads of the InternConnect backend API.

---

## Authentication Endpoints

### POST `/api/auth/register`

Register a new user.

Payload:

```json
{
  "email": "user@example.com",
  "password": "Password123!",
  "role": "candidate"
}
```

### POST `/api/auth/login`

Login and start session.

Payload:

```json
{
  "email": "user@example.com",
  "password": "Password123!"
}
```

---

## Candidates API

### GET `/api/internships`

List internships.

Query parameters:

- `search`: Keyword string
- `location`: Location string
- `category`: Category string

### POST `/api/applications`

Submit internship application.

Payload:

```json
{
  "internship_id": 42,
  "resume_id": 7
}
```

---

## Employers API

### POST `/api/internships/create`

Create a new internship listing.

Payload:

```json
{
  "title": "Backend Intern",
  "description": "PHP role...",
  "requirements": "Basic PHP knowledge...",
  "salary": 150000
}
```

---

## AI API

### POST `/api/ai/optimize-resume`

Optimize a resume with DeepSeek.

Payload:

```json
{
  "resume_id": 7,
  "focus_skills": ["PHP", "SQL"]
}
```
