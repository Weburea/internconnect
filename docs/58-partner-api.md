# InternConnect Partner API: developer guide

For developers at training partners (Jigide and others) who report student results to InternConnect.

## What you build

InternConnect sends students to your application page with a reference. When a student finishes your course, you send us their score. We do the rest: the student's plan, their certificate badge, and what employers see.

```
Student on InternConnect --(redirect with ic_ref)--> your application page
Your system --(signed HTTPS POST)--> InternConnect: status updates and the final score
```

You never receive personal data from us, and you do not need to send us any. The reference is all that links the two sides.

## 1. The hand-off link

We redirect the student to your application link with three query parameters:

| Parameter | Example | Meaning |
|---|---|---|
| `ic_ref` | `ICT3F9A1C2B7D4E` | The student's reference. Store it with the student's enrolment. |
| `ic_ts` | `1763000000` | Unix time of the redirect |
| `ic_sig` | `9f2c...` (64 hex) | `HMAC-SHA256(signing_secret, ic_ref + "." + ic_ts)`, hex |

Checking `ic_sig` is optional but recommended. It proves the link came from InternConnect. You can also call `GET /students/{reference}` (below).

## 2. Credentials

InternConnect gives you two values, once, over a secure channel:

- **API key** (`icp_xxxxxxxx_...`): sent in every request as `X-Partner-Key`.
- **Signing secret** (`whsec_...`): never sent; used to sign each request.

Keep them on your server only. If either leaks, ask us to rotate them. Rotation stops the old key immediately.

- Base URL (production): `https://YOUR-INTERNCONNECT-DOMAIN/api/v1/partners`
- Testing: we will share a staging URL.

## 3. Signing every request

Headers:

```
X-Partner-Key:   icp_xxxxxxxx_...
X-Timestamp:     1763000000                  (current Unix time, seconds, UTC)
X-Signature:     hex(HMAC-SHA256(signing_secret, X-Timestamp + "." + raw_body))
Idempotency-Key: 7d1c2f6e-...                (POST only: unique per logical request)
Content-Type:    application/json            (POST)
```

Rules:
- `raw_body` is the **exact bytes** you send (for GET, an empty string).
- `X-Timestamp` must be within **5 minutes** of our clock.
- **Idempotency-Key**: generate one per result or status change. Reuse the **same key when you retry** after a timeout. We return the original answer with the header `Idempotent-Replayed: true` and do nothing twice. The same key with a different body is refused (409).
- Limit: **60 requests per minute** per partner (429 with `Retry-After` when exceeded).

### PHP

```php
$body = json_encode(['reference' => $ref, 'score' => 87.5, 'course' => 'Data Analytics', 'certificate_id' => 'JIG-2026-00123']);
$ts = (string) time();
$sig = hash_hmac('sha256', $ts . '.' . $body, $signingSecret);
$ch = curl_init($baseUrl . '/results');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'X-Partner-Key: ' . $apiKey,
        'X-Timestamp: ' . $ts,
        'X-Signature: ' . $sig,
        'Idempotency-Key: result-' . $ref . '-v1',
    ],
]);
$response = json_decode(curl_exec($ch), true);
```

### Node.js

```js
const crypto = require('crypto');
const body = JSON.stringify({ reference, score: 87.5, course: 'Data Analytics' });
const ts = Math.floor(Date.now() / 1000).toString();
const sig = crypto.createHmac('sha256', signingSecret).update(ts + '.' + body).digest('hex');
const res = await fetch(baseUrl + '/results', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json', 'X-Partner-Key': apiKey, 'X-Timestamp': ts, 'X-Signature': sig, 'Idempotency-Key': `result-${reference}-v1` },
  body,
});
```

### Python

```python
import hmac, hashlib, json, time, requests
body = json.dumps({"reference": ref, "score": 87.5}, separators=(",", ":"))
ts = str(int(time.time()))
sig = hmac.new(signing_secret.encode(), (ts + "." + body).encode(), hashlib.sha256).hexdigest()
r = requests.post(base_url + "/results", data=body, headers={
    "Content-Type": "application/json", "X-Partner-Key": api_key,
    "X-Timestamp": ts, "X-Signature": sig, "Idempotency-Key": f"result-{ref}-v1"})
```

## 4. Endpoints

### POST /results: final score

Send it once the student finishes, whether they passed or not.

```json
{
  "reference": "ICT3F9A1C2B7D4E",
  "score": 87.5,
  "course": "Data Analytics Bootcamp",
  "certificate_id": "JIG-2026-00123",
  "certificate_url": "https://jigide.com/cert/JIG-2026-00123",
  "completed_at": "2026-11-12T14:30:00+01:00"
}
```

**Fields**:
- `reference` and `score` (0–100, decimals allowed) are required.
- `certificate_url` must be `https://`.
- `completed_at` is ISO 8601 and optional (defaults to now).

**Responses**:
- **201** `outcome: created`: the result is recorded.
- **200** `outcome: corrected`: you sent a different score for the same reference. The earlier one is kept in history and the new score applies.
- **200** `outcome: unchanged`: we already had this result.

```json
{ "success": true, "code": 201, "message": "Result recorded.",
  "data": { "id": 42, "reference": "ICT3F9A1C2B7D4E", "score": 87.5, "passed": true, "status": "active",
            "course": "Data Analytics Bootcamp", "certificate_id": "JIG-2026-00123",
            "plan_unlocked": "premium", "plan_days": 180, "supersedes": null,
            "recorded_at": "2026-11-12T14:30:05+01:00", "outcome": "created" } }
```

InternConnect sets the pass mark and which plan a score unlocks. These can change over time and never need a change on your side.

### POST /applications/status: progress (optional)

```json
{ "reference": "ICT3F9A1C2B7D4E", "status": "enrolled" }
```

- `status` is one of `enrolled`, `in_progress` or `withdrawn`.
- Final outcomes go to `/results`.
- Updates after a result has been recorded return **409** `application_closed`.

### GET /students/{reference}: check a reference

Returns `{reference, status, program, started_at}` if we sent you this student, otherwise **404**. Sign an empty body.

### GET /students: list your students and their scores

Every student InternConnect sent you, newest first, each with their latest score. You only ever see your own students. No names or e-mails are returned: match students by `reference`. Sign an empty body.

Query parameters (all optional):
- `from`, `to`: `YYYY-MM-DD`, filter by the date the student started.
- `status`: one of `started`, `enrolled`, `in_progress`, `completed`, `failed`, `withdrawn`, `expired`.
- `passed`: `true` (passed only) or `false` (failed only).
- `page` and `per_page` (up to 50, default 20).

```json
{ "success": true, "code": 200, "message": "2 student(s).",
  "data": [
    { "reference": "ICT3F9A1C2B7D4E", "program": "Data Analytics", "status": "completed",
      "started_at": "2026-11-02T10:15:00+01:00", "status_at": "2026-11-12T14:30:05+01:00",
      "score": 87.5, "passed": true, "course": "Data Analytics Bootcamp",
      "certificate_id": "JIG-2026-00123", "plan_unlocked": "premium", "completed_at": "2026-11-12T14:30:00+01:00" }
  ],
  "meta": { "current_page": 1, "per_page": 20, "total_records": 2, "total_pages": 1 } }
```

Students without a result yet have `score`, `passed`, `course` and `certificate_id` set to `null`.

### GET /stats: totals, pass rate and average score

Counts for your students, with the same optional `from` and `to`:

```json
{ "success": true, "code": 200, "message": "Statistics for your students.",
  "data": { "students": 120,
            "by_status": { "started": 20, "enrolled": 30, "in_progress": 25, "completed": 35, "failed": 8, "withdrawn": 2, "expired": 0 },
            "results": 43, "passed": 35, "failed": 8, "pass_rate": 81.4,
            "average_score": 76.2, "highest_score": 98, "lowest_score": 31,
            "plans_unlocked": { "pro": 20, "premium": 15 },
            "pass_mark": 70, "period": { "from": null, "to": null } } }
```

## 5. Errors

Every error uses the same envelope:

```json
{ "success": false, "code": 422, "message": "The result has errors.", "error": "validation_failed",
  "errors": { "score": ["score must be between 0 and 100."] } }
```

| HTTP | `error` | What to do |
|---|---|---|
| 400 | `idempotency_key_required`, `invalid_json` | Fix the request |
| 401 | `partner_key_missing`, `invalid_partner_key`, `invalid_signature`, `stale_timestamp` | Check the key, the signing (exact raw body) and your server clock |
| 403 | `partner_paused` | Your access is paused; contact InternConnect |
| 404 | `unknown_reference` | The reference is not one we sent you |
| 409 | `idempotency_conflict`, `application_closed` | Use a new Idempotency-Key for a different request |
| 422 | `validation_failed` | See `errors` per field |
| 429 | `too_many_requests` | Wait `Retry-After` seconds |
| 5xx | `server_error` | Retry with the **same** Idempotency-Key, with back-off |

Every call is logged on our side (time, endpoint, result, reference, Idempotency-Key, masked IP), which helps both teams when debugging.

## 6. Go-live checklist

1. You receive the API key and signing secret (staging first).
2. The hand-off link is set on our side to your application page.
3. You store `ic_ref` with each enrolment.
4. You send a test result for a test reference, see `201`, resend it with the same Idempotency-Key and see the replay.
5. We activate your partner account in production and share production credentials.

Questions: the InternConnect team will give you a technical contact during onboarding. The full machine-readable spec is in the InternConnect OpenAPI document, under the "Training partners" tag.
