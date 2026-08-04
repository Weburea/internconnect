# Error Handling

InternConnect uses standardized error handling to prevent sensitive data leaks and improve debugging efficiency.

---

## PHP Exception Handling

- Use generic error messages for end users in production
- Log detailed trace messages to `storage/logs/error.log`
- Use try-catch blocks around database transactions, payments, and external API calls

---

## API Error Response Format

All API errors must return a HTTP status code (400, 401, 403, 404, 422, 500) and this JSON body:

```json
{
  "success": false,
  "message": "A descriptive error message here",
  "errors": {
    "field_name": ["Specific validation error message"]
  }
}
```

---

## Sensitive Data Masking

- Never display raw database exceptions or system paths to end users
- Clean stack traces from all production logs before rendering errors
- Do not log raw credit card details, API keys, or user passwords in system logs
