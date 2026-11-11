# Phase Planning: Testing Architecture, REST API Design, Swagger & Security Audit

**InternConnect Engineering Specification**  
**Document ID:** `docs/45-testing-api-architecture-and-audit-plan.md`  
**Status:** Architecture Blueprint (Ready for Inspection & Implementation)  

---

## Executive Summary

This specification outlines the technical blueprint for foundational engineering initiatives for InternConnect:
1. **PHPUnit Testing Framework & Route/Link Audit Engine**
2. **Versioned REST API Architecture (`/api/v1/`) for Mobile App Integration**
3. **Swagger / OpenAPI Documentation (Admin-Gated)**
4. **Comprehensive Security & Vulnerability Audit Checklist**
5. **Daily.co Video Interview Integration Architecture (PHP cURL & Daily Prebuilt)**
6. **Jenny AI Architecture — Google Gemini API Integration & Full-Project Opportunity Audit**
7. **Systematic SEO Implementation & cPanel Production Hosting Audit**
8. **Hosting, Runtime & Environment Strategy (cPanel & Future Heroku)**
9. **Unified Master Implementation Roadmap & Execution Priorities**

---

## 1. PHPUnit Testing Framework & Logical Audit Engine

### 1.1 Composer Dependency Setup
PHPUnit will be installed as a development dependency using Composer:
```bash
composer require --dev phpunit/phpunit:^10.0
```

### 1.2 Configuration (`phpunit.xml`)
A standardized `phpunit.xml` placed in the project root:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10.0/phpunit.xsd"
         bootstrap="tests/bootstrap.php"
         colors="true"
         stopOnFailure="false"
         cacheDirectory=".phpunit.cache">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
    </php>
</phpunit>
```

### 1.3 Test Directory Hierarchy
```
tests/
├── bootstrap.php                # Initializes autoloader, session mock, and constants
├── TestCase.php                 # Base test case extending PHPUnit\Framework\TestCase
├── Unit/                        # Pure unit tests (no network/db dependencies)
│   ├── HelpersTest.php          # url(), asset(), sanitize() logic
│   ├── ValidationTest.php       # Form input validation rules
│   ├── CsrfTokenTest.php        # Token generation and verification
│   ├── DailyVideoServiceTest.php # Daily.co payload and token builder tests
│   ├── GeminiAiServiceTest.php  # Gemini API payload formatting and JSON response parsing
│   └── DocxRunPreservationTest.php # OpenXML run/style preservation parser tests
└── Feature/                     # HTTP and controller feature tests
    ├── AuthFlowTest.php         # Registration, login, password reset
    ├── CandidateDashboardTest.php # Application pipeline, profile gates
    ├── AiAssistantRoutesTest.php  # CV analyze, matched internships
    ├── VideoInterviewTest.php   # Room access, token issuance, owner vs attendee permissions
    ├── SeoOutputTest.php        # Canonical URLs, OpenGraph, JSON-LD Schema generation
    └── RouteCrawlerAuditTest.php  # Automated broken-link & route crawler
```

### 1.4 Automated Route & Broken Link Crawler Engine
The Route Crawler inspects `backend/routes.php` and executes an audit over all registered GET routes to detect:
- Missing controller methods (`Call to undefined method`)
- Unimplemented views (`View file not found`)
- Unhandled `404 Not Found` or `500 Server Error`
- Unintended redirect loops (`301`/`302`)

#### Logic Implementation (`tests/Feature/RouteCrawlerAuditTest.php`):
```php
<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Core\Router;

final class RouteCrawlerAuditTest extends TestCase
{
    /**
     * Crawls all registered GET routes and outputs a status audit report.
     * Does NOT modify or fix links — records and classifies them.
     */
    public function testAuditAllRegisteredRoutes(): void
    {
        $router = new Router();
        require dirname(__DIR__, 2) . '/backend/routes.php';

        $routes = $router->getRegisteredRoutes()['GET'] ?? [];
        $auditReport = [
            '200_OK'        => [],
            '302_REDIRECT'  => [],
            '404_NOT_FOUND' => [],
            '500_ERROR'     => [],
        ];

        foreach ($routes as $routePattern => $handler) {
            $response = $this->simulateGetRequest($routePattern);
            $statusCode = $response['status_code'];

            if ($statusCode === 200) {
                $auditReport['200_OK'][] = $routePattern;
            } elseif ($statusCode >= 300 && $statusCode < 400) {
                $auditReport['302_REDIRECT'][] = [
                    'route' => $routePattern,
                    'target' => $response['headers']['Location'] ?? 'unknown'
                ];
            } elseif ($statusCode === 404) {
                $auditReport['404_NOT_FOUND'][] = $routePattern;
            } else {
                $auditReport['500_ERROR'][] = [
                    'route' => $routePattern,
                    'error' => $response['error'] ?? 'Internal Error'
                ];
            }
        }

        // Writes audit summary to docs/broken-links-audit.md
        $this->generateAuditMarkdownReport($auditReport);
        $this->assertNotEmpty($routes);
    }
}
```

---

## 2. Versioned REST API Architecture (`/api/v1/`)

To support the future cross-platform mobile application (Flutter / React Native) and decouple client-side interactions, the backend provides a dedicated `/api/v1/` endpoint namespace isolated from HTML views.

### 2.1 Standard JSON Response Envelope
Every API response follows a strict, predictable JSON envelope:

#### Success Response (`200 OK`, `201 Created`):
```json
{
  "success": true,
  "code": 200,
  "message": "Internships retrieved successfully",
  "data": [
    {
      "id": 1,
      "role": "Product Design Intern",
      "company": "Flutterwave",
      "stipend": "₦150,000/month",
      "location": "Lagos",
      "duration": "3 months",
      "match_score": 96,
      "match_tier": "Excellent Match",
      "skills": ["Figma", "UX Research", "Prototyping"]
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total_records": 12,
    "total_pages": 2
  }
}
```

#### Error Response (`400`, `401`, `403`, `422`, `500`):
```json
{
  "success": false,
  "code": 422,
  "message": "Validation failed",
  "errors": {
    "email": ["The email format is invalid."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### 2.2 Endpoint Directory Matrix (`/api/v1/`)

| Namespace | Method | Endpoint | Description | Auth Required |
|:---|:---|:---|:---|:---|
| **Auth** | `POST` | `/api/v1/auth/register` | Account registration | No |
| | `POST` | `/api/v1/auth/login` | Email/password login | No |
| | `POST` | `/api/v1/auth/verify-code` | OTP verification | No |
| | `POST` | `/api/v1/auth/refresh-token`| Refresh expired JWT | Yes (Refresh token) |
| | `POST` | `/api/v1/auth/logout` | Invalidate active token | Yes |
| **Internships** | `GET` | `/api/v1/internships` | Browse internships (with filters) | Optional |
| | `GET` | `/api/v1/internships/{id}` | Internship details | Optional |
| | `POST` | `/api/v1/internships/{id}/apply` | Submit application | Yes (Candidate) |
| | `POST` | `/api/v1/internships/{id}/save` | Toggle bookmark | Yes (Candidate) |
| **Candidate** | `GET` | `/api/v1/candidate/profile` | Candidate profile data | Yes (Candidate) |
| | `PUT` | `/api/v1/candidate/profile` | Update profile data | Yes (Candidate) |
| | `GET` | `/api/v1/candidate/applications` | Applications pipeline | Yes (Candidate) |
| | `GET` | `/api/v1/candidate/matched` | AI-matched internships list | Yes (Candidate, Pro) |
| | `POST` | `/api/v1/candidate/cv-analyze`| Upload CV for AI review | Yes (Candidate, Pro) |
| **Employer** | `GET` | `/api/v1/employer/dashboard` | Metric counters & applicants | Yes (Employer) |
| | `GET` | `/api/v1/employer/internships`| Employer's posted roles | Yes (Employer) |
| | `POST` | `/api/v1/employer/internships`| Create new internship | Yes (Employer) |
| | `GET` | `/api/v1/employer/candidates` | Candidate pool & match scores | Yes (Employer) |
| | `POST` | `/api/v1/employer/interviews` | Schedule interview invite | Yes (Employer) |
| **Interviews (Daily.co)** | `POST` | `/api/v1/interviews/{id}/room` | Provision private Daily meeting room | Yes (Employer) |
| | `POST` | `/api/v1/interviews/{id}/token` | Issue signed meeting token (candidate/employer) | Yes (Interviewee/Interviewer) |
| | `GET` | `/api/v1/interviews/{id}/status` | Check if interview is active or in lobby | Yes (Authenticated) |
| | `POST` | `/api/v1/webhooks/daily` | Ingest Daily.co meeting status webhooks | Webhook Signature |
| **Jenny AI (Gemini)** | `POST` | `/api/v1/ai/cv/analyze` | Parse and audit CV content & structure | Yes (Candidate, Pro) |
| | `POST` | `/api/v1/ai/cv/apply-edits` | Apply approved text edits preserving DOCX runs | Yes (Candidate, Pro) |
| | `POST` | `/api/v1/ai/job-description/generate`| Generate complete role requirements from title | Yes (Employer) |
| | `POST` | `/api/v1/ai/interview-prep/questions`| Generate tailored interview practice questions | Yes (Candidate/Employer) |
| | `POST` | `/api/v1/ai/match-explanation` | Get deep relational match breakdown for role | Yes (Candidate) |

### 2.3 Mobile Authentication Standard
1. Requests supply `Authorization: Bearer <access_token>` in HTTP headers.
2. An `ApiAuthMiddleware` intercepts all requests prefixed with `/api/v1/`, verifies token signature and expiration, and binds the authenticated `$user` to the request context.
3. Tokens are signed using `HS256` with rotating server secrets (`JWT_SECRET`).

---

## 3. Swagger / OpenAPI 3.0 Documentation Architecture

### 3.1 Specification Standard
* **OpenAPI 3.0.3** standard specification compiled into `public/docs/swagger.json`.
* Includes schemas for all models (`Candidate`, `Employer`, `Internship`, `Application`, `AiMatchResult`, `DailyRoom`, `DailyMeetingToken`, `CvAnalysisResult`).

### 3.2 Role-Gated Admin Access Only
To protect internal business schemas and API structures from public scraping, Swagger UI is gated:
* Route: `GET /admin/api-docs` (and `/api/docs`)
* Gate Rule:
  ```php
  if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
      http_response_code(403);
      require_once dirname(__DIR__, 2) . '/frontend/errors/403.php';
      exit;
  }
  ```
* Production Environment Guard: If `APP_ENV === 'production'`, access additionally requires an Admin Two-Factor Auth token.

---

## 4. Comprehensive Security Audit Checklist

| Domain | Security Rule & Verification Standard | Target Status |
|:---|:---|:---:|
| **CSRF Protection** | Every state-mutating POST/PUT/DELETE form must include a cryptographically random `csrf_token` validated in controller actions. | Pending Audit |
| **SQL Injection** | 100% of SQL queries must utilize `PDO::prepare()` with bound parameters. Zero string interpolation (`"SELECT * FROM users WHERE id = $id"`) is permitted anywhere. | Pending Audit |
| **XSS Prevention** | All dynamic output in PHP templates must be escaped via `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')`. | Active Standard |
| **Session Hardening** | `session_regenerate_id(true)` upon authentication; cookies enforce `HttpOnly=true`, `SameSite=Lax`, and `Secure=true` on HTTPS. | Pending Audit |
| **Rate Limiting** | Auth endpoints (`/login`, `/register`, `/verify-code`, `/api/v1/auth/*`) capped at 5 attempts per IP per minute. | Pending Audit |
| **File Upload Bounds** | CV and CAC uploads strictly validated for MIME type (`application/pdf`, `application/vnd.openxmlformats-officedocument.wordprocessingml.document`), size limits (max 10MB), and sanitized filenames. | Active Standard |
| **Daily.co Secret Isolation**| `DAILY_API_KEY` stored exclusively in server `.env`; client only ever receives short-lived, role-gated meeting tokens. | Architectural Standard |
| **Video Access Control** | Meeting rooms strictly set to `privacy: 'private'`; users without signed cryptographic tokens cannot join. | Architectural Standard |
| **Gemini Secret Isolation** | `GEMINI_API_KEY` never sent to browser; AI calls originate strictly from PHP cURL. | Architectural Standard |
| **AI Prompt Injection Guard**| Candidate CV text and user inputs escaped and wrapped in strict markdown delimiters before prompt assembly. | Architectural Standard |
| **NDPA Data Separation** | Candidate documents and AI evaluation logs isolated by employer/candidate tenant IDs; cross-tenant data access blocked. | Architectural Standard |
| **Human-in-the-Loop Mandate**| AI cannot reject candidates or mutate database records automatically without employer or candidate manual approval. | Architectural Standard |

---

## 5. Daily.co Video Interview Architecture & Integration Plan

### 5.1 Provider Architecture & Why Daily.co
Per `docs/15-interview-system.md`, InternConnect explicitly delegates video transport infrastructure to **Daily.co** to avoid maintaining complex WebRTC media servers. Daily provides:
- High-quality WebRTC video/audio with automatic bandwidth adaptation for Nigerian network conditions.
- **Daily Prebuilt**: A battle-tested, localized, ready-to-embed video room UI that works across Chrome, Firefox, Safari, Edge, and mobile browsers without requiring custom WebRTC canvas development.
- Secure, token-gated rooms with granular permissions (owner moderation, screen sharing, lobby/waiting room).

### 5.2 API Key Management & Environment Configuration
Daily.co credentials must remain strictly server-side. Add the following to `.env` and `.env.example`:
```ini
# ==============================================================================
# DAILY.CO VIDEO INTERVIEW CONFIGURATION
# ==============================================================================
DAILY_API_KEY="your_daily_api_key_here"
DAILY_DOMAIN_NAME="internconnect"
DAILY_REST_URL="https://api.daily.co/v1"
DAILY_WEBHOOK_SECRET="your_daily_webhook_hmac_secret"
DAILY_MAX_PARTICIPANTS_DEFAULT=2
DAILY_ROOM_BUFFER_MINUTES=30
```

### 5.3 PHP cURL Integration Layer (`DailyVideoService.php`)
All Daily.co API calls originate from a dedicated backend service: `backend/Services/DailyVideoService.php`.
```php
<?php
declare(strict_types=1);

namespace App\Services;

class DailyVideoService
{
    private string $apiKey;
    private string $restUrl;

    public function __construct()
    {
        $this->apiKey = $_ENV['DAILY_API_KEY'] ?? '';
        $this->restUrl = rtrim($_ENV['DAILY_REST_URL'] ?? 'https://api.daily.co/v1', '/');
    }

    private function request(string $endpoint, string $method = 'GET', array $payload = []): array
    {
        $ch = curl_init();
        $url = $this->restUrl . '/' . ltrim($endpoint, '/');

        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \RuntimeException('Daily.co cURL request failed: ' . $curlError);
        }

        $decoded = json_decode($response, true);
        if ($httpCode >= 400) {
            $msg = $decoded['error'] ?? $decoded['info'] ?? 'Daily API Error';
            throw new \RuntimeException("Daily.co API error ({$httpCode}): {$msg}");
        }

        return $decoded ?? [];
    }

    /**
     * Create private interview room with start and expiration windows
     */
    public function createInterviewRoom(string $roomName, int $startTimeUnix, int $durationMinutes = 45, int $maxParticipants = 2): array
    {
        $buffer = (int)($_ENV['DAILY_ROOM_BUFFER_MINUTES'] ?? 30) * 60;
        $nbf = $startTimeUnix - (10 * 60); // 10 minutes early window
        $exp = $startTimeUnix + ($durationMinutes * 60) + $buffer; // Expiration + buffer

        $payload = [
            'name'       => $roomName,
            'privacy'    => 'private',
            'properties' => [
                'nbf'                  => $nbf,
                'exp'                  => $exp,
                'max_participants'     => $maxParticipants,
                'enable_screenshare'   => true,
                'enable_chat'          => true,
                'start_video_off'      => false,
                'start_audio_off'      => false,
                'enable_knocking'      => true, // Lobby waiting state
                'lang'                 => 'en',
            ],
        ];

        return $this->request('rooms', 'POST', $payload);
    }

    /**
     * Generate signed cryptographic meeting token for room access
     */
    public function createMeetingToken(string $roomName, string $userName, int $userId, bool $isOwner, int $expUnix): string
    {
        $payload = [
            'properties' => [
                'room_name'          => $roomName,
                'user_name'          => $userName,
                'user_id'            => (string)$userId,
                'is_owner'           => $isOwner,
                'enable_screenshare' => true,
                'exp'                => $expUnix,
            ],
        ];

        $res = $this->request('meeting-tokens', 'POST', $payload);
        return $res['token'] ?? '';
    }
}
```

### 5.4 Room Creation & Lifecycle Management
1. **Trigger**: When an employer schedules an interview in [`EmployerController::scheduleInterview`](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/EmployerController.php) or when the candidate accepts an interview invitation in [`CandidateController::viewInterviewInvite`](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/CandidateController.php).
2. **Room Naming Convention**: `ic-int-{interview_id}-{hash}` (e.g. `ic-int-104-a9f2e3`) ensuring unpredictability and zero room-name collisions.
3. **Private Meeting Rooms**: Rooms are strictly set to `privacy: 'private'`. Direct unauthenticated URL access is refused by Daily.
4. **Meeting Expiration**: Rooms automatically expire (`exp`) 30 minutes after scheduled interview duration ends, releasing resources.
5. **Participant Limits**: Default capped at `2` for 1-on-1 interviews; expandable up to `6` for panel interview evaluations.

### 5.5 Cryptographic Meeting Token Generation
Meeting tokens grant secure, temporary admission:
- **Employer / Hiring Lead Token**: Issued with `is_owner: true`. Grants moderation rights: admitting candidates from the lobby (`knocking`), muting participants, screen sharing, and ending the call.
- **Candidate Token**: Issued with `is_owner: false`. Permits camera/mic transmission, screen sharing, and in-call text chat, but denies moderation controls.
- **Time Bounds**: Tokens carry `nbf` and `exp` matching the interview time window.

### 5.6 Embedded Daily Prebuilt Frontend Integration
Daily Prebuilt is embedded directly within the InternConnect Candidate and Employer dashboards (`frontend/employer/interviews/room.php` and `frontend/candidate/interview/room.php`):
```html
<!-- Container for Daily Prebuilt -->
<div id="daily-interview-container" class="interview-video-frame"></div>

<!-- Daily JS SDK (CDN) -->
<script crossorigin src="https://unpkg.com/@daily-co/daily-js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const roomUrl = "<?= htmlspecialchars($roomUrl, ENT_QUOTES, 'UTF-8') ?>";
    const token = "<?= htmlspecialchars($meetingToken, ENT_QUOTES, 'UTF-8') ?>";
    const container = document.getElementById('daily-interview-container');

    const callFrame = window.DailyIframe.createFrame(container, {
        iframeStyle: {
            width: '100%',
            height: '100%',
            minHeight: '620px',
            border: 'none',
            borderRadius: '16px'
        },
        showLeaveButton: true,
        showFullscreenButton: true
    });

    callFrame.on('left-meeting', () => {
        window.location.href = "<?= url('/interviews/feedback/' . $interviewId) ?>";
    });

    callFrame.on('camera-error', (err) => {
        window.dispatchEvent(new CustomEvent('show-toast', {
            detail: { text: 'Camera access denied. Please allow permissions in browser.', type: 'error' }
        }));
    });

    // Join room with secure token
    callFrame.join({ url: roomUrl, token: token });
});
</script>
```

### 5.7 Database Requirements & Relationships
The following tables support the video interview system:

```sql
-- Enhanced interviews record
CREATE TABLE IF NOT EXISTS `interviews` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT UNSIGNED NOT NULL,
    `employer_id` INT UNSIGNED NOT NULL,
    `candidate_id` INT UNSIGNED NOT NULL,
    `scheduled_at` DATETIME NOT NULL,
    `duration_minutes` SMALLINT UNSIGNED NOT NULL DEFAULT 45,
    `type` ENUM('virtual', 'physical') NOT NULL DEFAULT 'virtual',
    `status` ENUM('scheduled', 'confirmed', 'completed', 'cancelled', 'rescheduled') NOT NULL DEFAULT 'scheduled',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_employer_status` (`employer_id`, `status`),
    INDEX `idx_candidate_status` (`candidate_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dedicated Daily.co room tracking
CREATE TABLE IF NOT EXISTS `interview_rooms` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `interview_id` INT UNSIGNED NOT NULL,
    `daily_room_name` VARCHAR(128) NOT NULL UNIQUE,
    `daily_room_url` VARCHAR(255) NOT NULL,
    `privacy` VARCHAR(32) NOT NULL DEFAULT 'private',
    `nbf_timestamp` INT UNSIGNED NOT NULL,
    `exp_timestamp` INT UNSIGNED NOT NULL,
    `max_participants` TINYINT UNSIGNED NOT NULL DEFAULT 2,
    `status` ENUM('provisioned', 'active', 'concluded', 'expired') NOT NULL DEFAULT 'provisioned',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`interview_id`) REFERENCES `interviews`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Participant connection audit
CREATE TABLE IF NOT EXISTS `interview_participants` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `interview_room_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `role` ENUM('interviewer', 'candidate', 'observer') NOT NULL,
    `joined_at` DATETIME NULL,
    `left_at` DATETIME NULL,
    `duration_seconds` INT UNSIGNED DEFAULT 0,
    INDEX `idx_room_user` (`interview_room_id`, `user_id`),
    FOREIGN KEY (`interview_room_id`) REFERENCES `interview_rooms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 5.8 Error Handling & Webhook Ingestion
- Daily.co sends webhooks for `meeting.started`, `meeting.ended`, and `participant.left`.
- Ingested at `POST /api/v1/webhooks/daily`.
- Validates the `X-Daily-Signature` header using HMAC-SHA256 and `DAILY_WEBHOOK_SECRET` before processing.

---

## 6. Jenny AI Architecture — Google Gemini API Integration & Opportunity Audit

### 6.1 Provider-Independent Architecture
InternConnect utilizes an **Adapter / Provider Interface** pattern. This isolates the platform logic from any single LLM vendor:
```
[Application Controllers & Services]
               |
      [AiProviderInterface]
         /              \
[GeminiAiService]   [AnthropicAiService]
```
If a future shift back to Claude or an alternate LLM is needed, only a new implementation of `AiProviderInterface` is registered in `backend/Services/`, without altering any controller, view, or database structure.

### 6.2 Google Gemini Model Verification & Configuration
- **Verified Primary Model Identifier**: `gemini-3.5-flash`
- **Fallback Models**: `gemini-2.0-flash` / `gemini-1.5-flash`
- **Official API Endpoint**:
  `https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent?key={API_KEY}`
- **Environment Configuration (`.env`)**:
```ini
# ==============================================================================
# JENNY AI / GOOGLE GEMINI CONFIGURATION
# ==============================================================================
GEMINI_API_KEY="your_google_gemini_api_key"
GEMINI_MODEL="gemini-3.5-flash"
GEMINI_FALLBACK_MODEL="gemini-1.5-flash"
AI_PROVIDER="gemini"
AI_REQUEST_TIMEOUT_SECONDS=25
AI_MAX_OUTPUT_TOKENS=2048
AI_TEMPERATURE=0.2
```

### 6.3 PHP cURL Request & Structured JSON Response Validation
To guarantee reliable frontend processing, Gemini requests must specify `response_mime_type: "application/json"` with an explicit JSON schema.
```php
<?php
declare(strict_types=1);

namespace App\Services;

class GeminiAiService implements AiProviderInterface
{
    private string $apiKey;
    private string $model;
    private int $timeout;

    public function __construct()
    {
        $this->apiKey = $_ENV['GEMINI_API_KEY'] ?? '';
        $this->model = $_ENV['GEMINI_MODEL'] ?? 'gemini-3.5-flash';
        $this->timeout = (int)($_ENV['AI_REQUEST_TIMEOUT_SECONDS'] ?? 25);
    }

    public function generateStructuredJson(string $systemPrompt, string $userPrompt, array $responseSchema): array
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $payload = [
            'contents' => [
                [
                    'role'  => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\nUser Input:\n" . $userPrompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature'       => (float)($_ENV['AI_TEMPERATURE'] ?? 0.2),
                'maxOutputTokens'   => (int)($_ENV['AI_MAX_OUTPUT_TOKENS'] ?? 2048),
                'responseMimeType'  => 'application/json',
                'responseSchema'    => $responseSchema
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $raw = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 429) {
            // Exponential backoff or fallback model
            return $this->executeFallback($systemPrompt, $userPrompt, $responseSchema);
        }

        if ($httpCode >= 400 || !$raw) {
            throw new \RuntimeException("Gemini API call failed with code {$httpCode}: {$raw}");
        }

        $decoded = json_decode($raw, true);
        $contentString = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
        $data = json_decode($contentString, true);

        if (!is_array($data)) {
            throw new \RuntimeException('Gemini failed to return valid JSON conforming to schema.');
        }

        return $data;
    }
}
```

### 6.4 Token Usage, Audit Logging & Subscription Plan Enforcement
Every interaction is logged in `ai_usage_logs` to audit token consumption and cost:
```sql
CREATE TABLE IF NOT EXISTS `ai_usage_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `user_role` ENUM('candidate', 'employer') NOT NULL,
    `feature` VARCHAR(64) NOT NULL,
    `model` VARCHAR(64) NOT NULL,
    `prompt_tokens` INT UNSIGNED DEFAULT 0,
    `completion_tokens` INT UNSIGNED DEFAULT 0,
    `total_tokens` INT UNSIGNED DEFAULT 0,
    `latency_ms` INT UNSIGNED DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user_feature` (`user_id`, `feature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
- **Plan Limits**:
  - **Free Plan**: AI features disabled or gated to 1 teaser CV score.
  - **Pro Plan**: 5 full CV analyses and in-place optimizations per month.
  - **Premium Plan**: Unlimited CV optimizations and AI mock interview simulations.

### 6.5 Jenny AI — CV Analysis & In-Place Editing

#### A. Analysis Capabilities
- **Structural Audit**: Detects missing critical sections (Professional Summary, Education, SIWES Experience, Skills, Certifications).
- **Impact Scoring**: Evaluates action verbs, quantifiable achievements, and Nigerian industry keywords.
- **Grammar & Tone**: Identifies passive voice, spelling slips, and inconsistent tense usage.
- **Anti-Hallucination Guard**: Jenny suggests rewrites strictly derived from the candidate's existing text. Prompts explicitly prohibit inventing GPA, past companies, unearned credentials, or fabricated metrics.

#### B. Preserving Candidate's Original Formatting (DOCX vs PDF)

##### 1. DOCX In-Place Run Preservation Architecture (Zero-Flattening Policy)
Word DOCX files are compressed XML archives (`word/document.xml`). Within Word OpenXML:
- A paragraph is `<w:p>`.
- Within a paragraph, text is split across multiple `<w:r>` (runs).
- Each run has `<w:rPr>` properties defining its exact formatting (font name, font size, text color, bold, italic, highlight, underline).
- Naive text replacement on paragraphs destroys all inline bolding, links, and text formatting.

**Implementation Strategy (`DocxRunPreservationEditor.php`)**:
```
[Uploaded .docx] 
       │
   (Unzip via ZipArchive)
       │
[word/document.xml DOM]
       │
 (Extract Paragraphs & Run Maps) ──> [Send Text to Jenny AI for Suggestions]
       │                                            │
       ▼                                            ▼
[Target Run Search] <─────────────────── [User Approves Changes]
       │
 (Replace text strictly inside target <w:t> tags while preserving all <w:rPr>, <w:pPr>, tables, borders)
       │
   (Re-zip via ZipArchive)
       │
[Updated .docx with Exact Formatting Intact]
```
- **Preserved Elements**:
  - Paragraph fonts, headings, and alignments.
  - Text colors, highlights, and font sizes.
  - Horizontal rules and borders (`<w:pBdr>`).
  - Embedded logos and header images (`word/media/`).
  - Multi-column tables (`<w:tbl>`, `<w:tc>`) and text boxes (`<w:txbxContent>`).

##### 2. PDF Handling Strategy
- PDF documents use absolute vector coordinate placement (`BT ... Tm ... Tj ET`) rather than reflowable semantic text trees.
- In-place text replacement in arbitrary PDFs inevitably clips text, breaks bounding boxes, and corrupts layouts.
- **Architectural Flow for PDF**:
  1. Extract raw text for Jenny AI audit using PHP PDF parser (`smalot/pdfparser`).
  2. Deliver granular scores, missing sections, and actionable rewrites.
  3. Offer to export the candidate's approved data into a clean, ATS-compliant **InternConnect Professional Resume Template** (rendered via HTML-to-PDF / standard template).
  4. Display transparent user notice: *"PDF layouts cannot be safely edited in place without breaking layout alignment. We have generated an ATS-optimized version in our standard format."*

#### C. User Approval & Diff Review Flow
- Suggestions are returned as a granular diff array: `[{ id, section, original_text, suggested_text, reason }]`.
- The frontend renders an interactive side-by-side diff viewer.
- The candidate clicks **Accept** or **Reject** on each specific suggestion.
- Only accepted suggestions are processed by `DocxRunPreservationEditor`.

### 6.6 Full-Project AI Opportunity Audit (12 High-Value Areas)

Beyond CV optimization, a comprehensive scan of the InternConnect repository identifies 12 strategic areas where Jenny AI delivers immediate value:

#### 1. Candidate Profile Bio & Headline Optimizer
* **Existing Feature**: [`frontend/candidate/profile.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/profile.php) (Bio and headline fields).
* **Proposed AI Functionality**: Suggests concise, high-impact headlines (e.g. *"UI/UX Designer | Specializing in Design Systems & Fintech UX"*) based on candidate's course of study and completed projects.
* **Why Useful**: Most students write generic bios ("Hardworking student looking for opportunities").
* **Required Data**: University, department, skills array, target industry.
* **Implementation**: Lightweight JSON endpoint returning 3 headline variants.
* **Dependencies**: `GeminiAiService`.
* **Security**: Sanitize inputs to prevent prompt injection.
* **Testing**: Assert 3 distinct, non-empty headlines conforming to character limits.
* **Priority**: High.

#### 2. Employer Job Description Generator
* **Existing Feature**: [`frontend/employer/internships/create.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/internships/create.php) (Internship creation form).
* **Proposed AI Functionality**: Automatically drafts responsibilities, qualifications, and learning outcomes when employer types the job title and selects department tags.
* **Why Useful**: Reduces role creation time from 15 minutes to 30 seconds for busy employers.
* **Required Data**: Role title, department, work mode (remote/hybrid/onsite), stipend tier.
* **Implementation**: Button: *"Generate with Jenny AI"* populating form textareas.
* **Dependencies**: `GeminiAiService`.
* **Security**: Filter offensive or non-inclusive keywords.
* **Testing**: Validate schema with responsibilities, requirements, and benefits arrays.
* **Priority**: High.

#### 3. Employer Job Description Inclusivity & Clarity Audit
* **Existing Feature**: [`frontend/employer/internships/review.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/internships/review.php) (Review & publish screen).
* **Proposed AI Functionality**: Flags unrealistic requirements for internship levels (e.g. demanding 5 years experience for an internship) and suggests student-friendly phrasing.
* **Why Useful**: Increases applicant pool quality and prevents student intimidation.
* **Required Data**: Job description text, required experience.
* **Implementation**: Review page sidebar banner with actionable suggestions.
* **Dependencies**: `GeminiAiService`.
* **Security**: Non-blocking recommendation only.
* **Testing**: Verify flagging of "5+ years experience" on internship postings.
* **Priority**: Medium.

#### 4. Candidate-to-Job Relational Match Explanation
* **Existing Feature**: [`frontend/candidate/dashboard/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/dashboard/index.php) and [`frontend/landing/internships.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/internships.php).
* **Proposed AI Functionality**: Generates dynamic match reason: *"Matches 96% because your React.js and REST API projects directly align with Paystack's requirements."*
* **Why Useful**: Replaces black-box match scores with transparent, empowering explanations.
* **Required Data**: Candidate skills + experience summary, internship required skills.
* **Implementation**: Micro-summary stored in cache/session.
* **Dependencies**: `GeminiAiService`.
* **Security**: Strip personal contact info from prompt.
* **Testing**: Confirm 1-2 sentence concise explanation output.
* **Priority**: High.

#### 5. Employer AI Applicant Ranking & Shortlist Prioritization
* **Existing Feature**: [`frontend/employer/candidates/index.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/candidates/index.php) (Applicant pool).
* **Proposed AI Functionality**: Analyzes applicants against role criteria and groups them into *"Top Recommended"*, *"Strong Fit"*, and *"Needs Development"*.
* **Why Useful**: Helps hiring leads process 200+ applications efficiently.
* **Required Data**: Candidate profile, CV text, role description.
* **Implementation**: Asynchronous background evaluation populating match tags.
* **Dependencies**: `GeminiAiService`, database queue.
* **Security**: Mandate human review; AI can never automatically reject candidates.
* **Testing**: Assert zero state mutations occur without employer action.
* **Priority**: High.

#### 6. Role-Specific Interview Question Generator for Employers
* **Existing Feature**: [`frontend/employer/interviews/schedule.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/interviews/schedule.php) (Interview scheduler).
* **Proposed AI Functionality**: Generates 5 tailored technical and behavioral questions based on the candidate's submitted project portfolio.
* **Why Useful**: Equips recruiters to run structured, objective interviews.
* **Required Data**: Role title, candidate portfolio/answers.
* **Implementation**: Accordion card inside interview detail view.
* **Dependencies**: `GeminiAiService`.
* **Security**: Confidential to the employer only.
* **Testing**: Verify questions map to stated candidate skills.
* **Priority**: Medium.

#### 7. Candidate AI Mock Interview Simulator & Answer Coaching
* **Existing Feature**: [`frontend/candidate/interview/guide.php`](file:///c:/xampp/htdocs/InternConnect/frontend/candidate/interview/guide.php) (Interview guide).
* **Proposed AI Functionality**: Jenny AI acts as a mock interviewer, asking 3 questions, listening to candidate responses, and giving constructive feedback on structure and confidence.
* **Why Useful**: Significantly reduces interview anxiety for first-time student applicants.
* **Required Data**: Target role, candidate experience.
* **Implementation**: Step-by-step interactive chat dialog.
* **Dependencies**: `GeminiAiService`.
* **Security**: Candidate responses not shared with real employers.
* **Testing**: Validate turn-by-turn state progression.
* **Priority**: High.

#### 8. Candidate Career Path & Skill Gap Recommendations
* **Existing Feature**: Candidate Dashboard Pro Career Insights widget.
* **Proposed AI Functionality**: Identifies the 2 highest-demand skills missing from a candidate's profile to reach a 95%+ match in their discipline.
* **Why Useful**: Guides students on which tools/libraries to learn next to get hired.
* **Required Data**: Candidate skills, market demand keywords from active postings.
* **Implementation**: Visual recommendation cards linking to E-Learning courses.
* **Dependencies**: `GeminiAiService`.
* **Security**: Read-only profile data.
* **Testing**: Verify skill recommendations exist in InternConnect E-Learning taxonomy.
* **Priority**: Medium.

#### 9. Application Cover Letter Personalization Assistant
* **Existing Feature**: Application submission flow (`/apply/{slug}`).
* **Proposed AI Functionality**: Helps candidates draft a structured 3-paragraph cover letter highlighting why they admire the company and how their coursework connects.
* **Why Useful**: Overcomes writer's block while teaching students proper professional correspondence.
* **Required Data**: Role title, company name, candidate background bullet points.
* **Implementation**: Modal assistant on application page with editable textarea.
* **Dependencies**: `GeminiAiService`.
* **Security**: Explicit candidate edit required before submission.
* **Testing**: Ensure output contains zero fabricated credentials.
* **Priority**: High.

#### 10. Employer Candidate Comparison Matrix
* **Existing Feature**: [`frontend/employer/candidates/show.php`](file:///c:/xampp/htdocs/InternConnect/frontend/employer/candidates/show.php).
* **Proposed AI Functionality**: Side-by-side comparison summary between 2 final shortlisted candidates (strengths, skill tradeoffs, availability).
* **Why Useful**: Facilitates quick hiring decisions between hiring committee members.
* **Required Data**: Profiles of two selected candidates, job description.
* **Implementation**: Modal comparison table.
* **Dependencies**: `GeminiAiService`.
* **Security**: Restrict strictly to authenticated employer organization.
* **Testing**: Anonymize personal identifiers during comparison.
* **Priority**: Low.

#### 11. Smart Search & Natural Language Query Expansion
* **Existing Feature**: [`frontend/landing/search.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/search.php) and [`frontend/landing/internships.php`](file:///c:/xampp/htdocs/InternConnect/frontend/landing/internships.php).
* **Proposed AI Functionality**: Expands natural queries (e.g. *"finance remote roles with stipend for UNILAG student"*) into structured database filter tags.
* **Why Useful**: Improves search relevance beyond rigid keyword matching.
* **Required Data**: Search query string.
* **Implementation**: Pre-filter query interpreter returning taxonomy tags.
* **Dependencies**: `GeminiAiService`.
* **Security**: Strict query sanitization against injection.
* **Testing**: Assert expanded terms map to active database category columns.
* **Priority**: Medium.

#### 12. Automated Application Review Summary for Hiring Leads
* **Existing Feature**: Candidate Application Review modal in Employer Dashboard.
* **Proposed AI Functionality**: 3-bullet executive brief of the candidate's qualifications at the top of their application review card.
* **Why Useful**: Saves recruiters from reading entire resumes for immediate preliminary fit check.
* **Required Data**: Candidate CV text, screening question answers.
* **Implementation**: Generated on-demand upon first viewing and cached.
* **Dependencies**: `GeminiAiService`.
* **Security**: Employer-gated.
* **Testing**: Verify caching prevents repeated API hits on page refresh.
* **Priority**: Medium.

### 6.7 AI Security, Privacy & NDPA Compliance
1. **Server-Side Key Isolation**: `GEMINI_API_KEY` is never exposed in HTML, JS, or client-side bundles.
2. **Strict NDPA Data Isolation**: All prompts strip personal identifiers (phone numbers, full street addresses). Data processing agreements comply with the Nigeria Data Protection Act (NDPA).
3. **No Autonomous Database Mutation**: AI suggestions are presented as drafts. No database record is modified until the human user clicks **Confirm / Accept**.
4. **Prohibition of Automated Rejections**: AI may score or sort candidates for employer review, but can never trigger an automatic rejection letter without employer confirmation.

### 6.8 AI Testing Scenarios
- **Connectivity & Key Validation**: Verify graceful fallback on invalid or expired Gemini API key.
- **Timeout Resilience**: Test behavior when Gemini exceeds 25-second timeout (fallback message without crashing UI).
- **Malformed Response Handling**: Test handling when API returns non-JSON or partial response.
- **Run Preservation Tests**: Unit test `DocxRunPreservationEditor` with complex real-world DOCX files containing multi-font runs, tables, and headers to verify zero formatting degradation.
- **Anti-Hallucination Assertion**: Automated checks confirming rewrites contain zero unearned degrees or false metrics.

---

## 7. SEO Implementation & cPanel Production Hosting Audit

### 7.1 Exhaustive Audit of Existing SEO Architecture

| SEO Parameter / Mechanism | Implementation Location | Verified Status in Codebase |
|:---|:---|:---:|
| **Environment Configuration** | [`.env`](file:///c:/xampp/htdocs/InternConnect/.env) and [`.env.example`](file:///c:/xampp/htdocs/InternConnect/.env.example) | **Verified Active**: All 14 SEO keys present (`SEO_SITE_NAME`, `SEO_CANONICAL_BASE_URL`, `SEO_DEFAULT_OG_IMAGE`, `SEO_GEO_*`, etc.) |
| **PHP Environment Loading** | [`public/index.php`](file:///c:/xampp/htdocs/InternConnect/public/index.php#L18-L46) | **Verified Active**: Dual-path `.env` parser loads into both `$_ENV` and `$_SERVER` |
| **Centralized SEO Service** | [`backend/Services/SeoService.php`](file:///c:/xampp/htdocs/InternConnect/backend/Services/SeoService.php) | **Verified Active**: Manages Title, Meta, OpenGraph, Twitter, Geo, and JSON-LD |
| **Global Layout Integration**| [`frontend/shared/layouts/app.php`](file:///c:/xampp/htdocs/InternConnect/frontend/shared/layouts/app.php#L16) | **Verified Active**: `seo_render_head()` outputs meta tags dynamically in `<head>` |
| **Canonical URL Resolution** | [`backend/Services/SeoService.php`](file:///c:/xampp/htdocs/InternConnect/backend/Services/SeoService.php#L73-L95) | **Verified Active**: Auto-resolves live domain from `$_SERVER['HTTP_HOST']` or `.env` |
| **Nigerian Local SEO Tags** | [`backend/Services/SeoService.php`](file:///c:/xampp/htdocs/InternConnect/backend/Services/SeoService.php#L295-L300) | **Verified Active**: Renders `geo.region: NG-LA`, `geo.placename: Lagos`, and `ICBM` |
| **Schema.org Structured Data**| `spatie/schema-org` in `composer.json` | **Verified Active**: Outputs `WebSite` (with `SearchAction`) and `Organization` JSON-LD |
| **Dynamic XML Sitemap** | [`backend/Controllers/HomeController.php`](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/HomeController.php#L125-L165) | **Verified Active**: Route `GET /sitemap.xml` generates clean XML |
| **Dynamic robots.txt** | [`backend/Controllers/HomeController.php`](file:///c:/xampp/htdocs/InternConnect/backend/Controllers/HomeController.php#L168-L185) | **Verified Active**: Route `GET /robots.txt` disallows private dashboard routes |
| **Apache URL Rewriting** | [`.htaccess`](file:///c:/xampp/htdocs/InternConnect/.htaccess) | **Verified Active**: Relative asset mapping, front controller rewrite, and `.env` 403 block |

### 7.2 What Currently Works vs What Requires Configuration vs What Needs Development

#### A. What Already Works (Foundation Only, Not Yet Production Ready)
> The site must not be submitted to Google Search Console until sections B and C below are complete. Standards are defined in `34-seo-standards.md`.

1. **Dynamic Meta Generation**: Every public marketing page (`/about`, `/contact`, `/pricing`, `/how-it-works`, `/elearning`, `/terms`, etc.) renders semantic `<title>`, meta description, and canonical link. `/internships`, `/search`, and internship detail pages do not yet set their own descriptions and fall back to the site default.
2. **Social Media Sharing Previews**: OpenGraph and Twitter card meta tags are rendered for title, description, and image. The default image is the square logo, so a dedicated 1200x630 share image is still needed.
3. **Structured Search Engines (JSON-LD)**: `WebSite` and `Organization` schema are output directly from the HTML head.
4. **Sitemap & Crawler Directives**: `/sitemap.xml` and `/robots.txt` are served, but the sitemap only lists 13 static pages and the crawl rules are incomplete (see C).
5. **Security Isolation on Apache**: Direct access to `.env` returns `403 Forbidden` on Apache servers.

#### B. What Requires Configuration upon cPanel Deployment
1. **Production Domain Setup**: Change `SEO_CANONICAL_BASE_URL` in cPanel `.env` from `http://localhost/InternConnect` to your live domain (e.g. `https://internconnect.ng`).
2. **SSL Activation**: Ensure AutoSSL / Let's Encrypt is enabled in cPanel so `$_SERVER['HTTPS']` reports `on`.
3. **Google & Bing Verification Tokens**: Paste your search console tokens into `SEO_GOOGLE_SITE_VERIFICATION` and `SEO_BING_SITE_VERIFICATION` inside `.env`.
4. **HTTPS & Single-Host Redirects**: `.htaccess` has no redirect to HTTPS or to one preferred www/non-www host. Both must be added before launch.

#### C. What Needs Development in Upcoming Phases
1. **Real Internship and Company Data**: `InternshipController::show()` reads a hardcoded list of two sample internships, and `EmployerController::publicCompanyProfile()` contains placeholder profiles, including real brand names. These must be replaced before any markup or sitemap submission.
2. **Dynamic JobPosting Schema for Internships**: Hook `SeoService::setJobPosting()` into `InternshipController::show()` so every individual internship listing generates Google Jobs Schema.org markup. `setJobPosting()` is currently never called. Before connecting it, correct `buildStructuredData()`: salaries containing currency symbols convert to `0`, the currency is fixed to NGN, missing dates are generated, the region is fixed to Lagos, and remote fields are missing.
3. **Automated XML Sitemap Expansion**: Query active database internships in `HomeController::sitemap()` to automatically append live internship URLs into the sitemap, with each page's real `lastmod` date in place of the current date.
4. **Real 404 Responses**: Unknown internship slugs currently show the first internship with a `200` status, and `/companies` shows the TechFlow profile. Both must return correct responses.
5. **Crawlable Internship Links**: Listing links on `/internships` are generated by Alpine.js and must be rendered as plain HTML links.
6. **Crawl Rule Corrections**: Add checkout to `robots.txt`, correct `/admin/` so that it also covers `/admin`, and render `noindex` on private pages outside the `/candidate/` and `/employer/` paths.
7. **Performance**: Replace the `time()` CSS version in `app.php`, reduce the page loader's impact on public pages, compress the hero image, and add compression and caching rules to `.htaccess`.
8. **Location, Category, and Programme Landing Pages**: Build the keyword pages defined in `34-seo-standards.md`.

### 7.3 cPanel Hosting Compatibility Assessment
- **Compatible Out-of-the-Box**: Pure PHP 8.2/8.3, Apache `mod_rewrite`, PDO MySQL, and Composer autoloading work seamlessly on standard cPanel installations.
- **Zero Framework Bloat**: Operates cleanly without requiring long-running Node.js processes, Supervisor daemons, or custom server ports.
- **Required cPanel PHP Extensions**: `curl`, `openssl`, `mbstring`, `pdo_mysql`, `zip` (for DOCX run preservation), `simplexml` (for XML/DOCX parsing), and `fileinfo` (for MIME validation). All are standard modules enabled by default in cPanel MultiPHP Manager.

---

## 8. Hosting, Runtime & Environment Strategy (cPanel & Future Heroku)

### 8.1 Production Environment Architecture (cPanel)
```
[Client Web Browser / Mobile App]
                │
         HTTPS (Port 443)
                │
   [cPanel Apache Web Server]
          │             │
   (Static Assets)   (.htaccess Rewrites)
          │             │
   [public/assets/]  [public/index.php (Front Controller)]
                        │
                  [App Router]
                 /            \
        [HTML Views]        [/api/v1/ REST Controllers]
               \                /
             [Backend Services Layer]
             ├── DailyVideoService ──> Outbound HTTPS ──> Daily.co API
             ├── GeminiAiService   ──> Outbound HTTPS ──> Google Gemini API
             ├── PaystackService   ──> Outbound HTTPS ──> Paystack API
             └── SeoService        ──> Spatie Schema-Org
                        │
                [MySQL Database]
```

### 8.2 Outbound HTTPS Connectivity
- Daily.co, Google Gemini, and Paystack all require outbound HTTPS connections on port 443.
- Standard cPanel shared hosting accounts permit outbound cURL requests to public HTTPS endpoints.
- In rare cPanel hosting accounts with strict external firewall rules, contact hosting support to ensure outbound port 443 to `*.daily.co` and `*.googleapis.com` is unrestricted.

### 8.3 Timeouts & Background Processing on cPanel
- **cPanel PHP Execution Limits**: Shared hosting typically caps script execution at 30 to 60 seconds (`max_execution_time`).
- **Synchronous AI Calls**: Direct interactive AI calls (e.g. CV analysis) must be configured with a 20–25 second timeout to prevent hitting server script termination limits.
- **Long-Running Batch Tasks**: For bulk candidate matching across hundreds of applicants, execute via cPanel Scheduled Cron Jobs (`php -f /home/user/public_html/cron/process_ai_queue.php`) rather than blocking web requests.

### 8.4 Future Heroku Deployment Notes
The current architecture remains 100% compatible with future Heroku container deployment:
- Uses standard environment variables via `.env` (maps directly to Heroku Config Vars).
- Stateless application design (CV uploads can route to Cloudinary or S3 storage).
- Standard `composer.json` with PSR-4 autoloading.
- No local hardcoded absolute paths; all asset and route resolution uses dynamic helpers (`url()`, `asset()`).

---

## 9. Unified Master Implementation Roadmap & Execution Priorities

```
+-------------------------------------------------------------------------------+
|                        MASTER IMPLEMENTATION ROADMAP                          |
+-------------------------------------------------------------------------------+
                                        │
    +-----------------------------------v-----------------------------------+
    | PHASE 1: CORE AUDIT & TESTING FOUNDATION                              |
    | - Run automated Route Crawler audit on backend/routes.php             |
    | - Setup phpunit.xml and initial test suites in tests/                 |
    | - Build unit tests for helpers, validation, and CSRF                  |
    +-----------------------------------┬-----------------------------------+
                                        │
    +-----------------------------------v-----------------------------------+
    | PHASE 2: SEO PRODUCTION HARDENING & CPANEL READINESS                  |
    | - Connect dynamic JobPosting schema on /internship/{slug}             |
    | - Expand /sitemap.xml to dynamically ingest database internships      |
    | - Verify cPanel PHP extensions (zip, curl, pdo_mysql) and permissions |
    +-----------------------------------┬-----------------------------------+
                                        │
    +-----------------------------------v-----------------------------------+
    | PHASE 3: DAILY.CO VIDEO INTERVIEW INTEGRATION                         |
    | - Implement backend/Services/DailyVideoService.php                    |
    | - Create database tables: interview_rooms, interview_participants    |
    | - Wire room creation into EmployerController::scheduleInterview      |
    | - Embed Daily Prebuilt iframe in candidate & employer room views      |
    | - Build meeting token issuance endpoint with owner/attendee gates     |
    +-----------------------------------┬-----------------------------------+
                                        │
    +-----------------------------------v-----------------------------------+
    | PHASE 4: JENNY AI (GEMINI) INTEGRATION & CV IN-PLACE EDITOR           |
    | - Implement backend/Services/GeminiAiService.php (AiProviderInterface)|
    | - Setup ai_usage_logs database table and subscription quota guards    |
    | - Implement DocxRunPreservationEditor.php for in-place DOCX XML edits |
    | - Build interactive diff viewer UI for user Accept/Reject approvals   |
    | - Implement High-Priority AI features (JD generator, match reasons)   |
    +-----------------------------------┬-----------------------------------+
                                        │
    +-----------------------------------v-----------------------------------+
    | PHASE 5: REST API (/api/v1/) & SWAGGER OPENAPI DOCUMENTATION          |
    | - Scaffold /api/v1/ router with JSON envelope handler                 |
    | - Implement ApiAuthMiddleware for mobile JWT tokens                   |
    | - Document all endpoints in public/docs/swagger.json                  |
    | - Gate /admin/api-docs behind role-based admin authentication         |
    +-----------------------------------------------------------------------+
```

### Detailed Dependency Sequence

| Sequence | Task / Milestone | Prerequisites | Key Deliverables | Risk / Mitigation |
|:---:|:---|:---|:---|:---|
| **1.1** | PHPUnit Setup & Route Crawler | None | `phpunit.xml`, `tests/bootstrap.php`, `RouteCrawlerAuditTest.php` | Low risk; runs in dev environment |
| **1.2** | Broken Links & Route Catalog | Task 1.1 | `docs/broken-links-audit.md` | Non-destructive; identifies missing views |
| **2.1** | JobPosting Schema & Dynamic Sitemap | `spatie/schema-org` | Updated `HomeController::sitemap()` and `InternshipController::show()` | Low risk; boosts Google indexing |
| **3.1** | Daily.co Backend Service | Daily.co API key | `DailyVideoService.php` with cURL requests | Verify outbound port 443 permissions |
| **3.2** | Video Room Database Schema | Database access | `interview_rooms`, `interview_participants` tables | Use standard MySQL migration |
| **3.3** | Embedded Daily Prebuilt UI | Task 3.1, 3.2 | Candidate and Employer meeting room templates | Mobile camera/mic permissions check |
| **4.1** | Gemini Provider Service | Gemini API key | `AiProviderInterface.php`, `GeminiAiService.php` | Set 25s timeout and backoff retries |
| **4.2** | DOCX Run Preservation Editor | PHP `zip`, `simplexml` | `DocxRunPreservationEditor.php` | Comprehensive unit tests on mixed-run DOCX |
| **4.3** | CV Analysis & Diff Approval UI | Task 4.1, 4.2 | Interactive diff viewer with Accept/Reject triggers | Prevent automatic file mutation |
| **4.4** | Job Description & Match Explanations | Task 4.1 | Employer JD builder, candidate match reason chips | Cache output to preserve API quota |
| **5.1** | `/api/v1/` Router & Middleware | None | `ApiRouter.php`, `ApiAuthMiddleware.php` | Isolated from web session middleware |
| **5.2** | Swagger UI Specification | Tasks 3, 4, 5.1 | `public/docs/swagger.json`, `/admin/api-docs` | Gate behind admin role and 2FA |
