<?php

declare(strict_types=1);

namespace App\Api;

/**
 * OpenAPI 3.0 description of /api/v1, served to admins at /admin/api-docs (Swagger UI)
 * and /admin/api-docs/openapi.json. tests/PHPUnit/Unit/OpenApiSpecTest.php checks that every
 * /api/v1 route in backend/routes.php is documented here and vice versa.
 */
final class OpenApiSpec
{
    public static function build(string $serverUrl): array
    {
        $paths = [];
        $add = static function (string $method, string $path, array $op) use (&$paths) {
            $paths[$path][$method] = $op;
        };
        $ok = static fn (string $description, array $dataSchema, bool $paginated = false) => [
            '200' => [
                'description' => $description,
                'content' => ['application/json' => ['schema' => [
                    'allOf' => [
                        ['$ref' => '#/components/schemas/Envelope'],
                        ['type' => 'object', 'properties' => array_filter([
                            'data' => $dataSchema,
                            'meta' => $paginated ? ['$ref' => '#/components/schemas/Pagination'] : null,
                        ])],
                    ],
                ]]],
            ],
        ];
        $errors = static function (int ...$codes): array {
            $out = [];
            foreach ($codes as $c) {
                $out[(string)$c] = ['$ref' => '#/components/responses/E' . $c];
            }
            return $out;
        };
        $auth = [['bearerAuth' => []]];
        $jsonBody = static fn (array $props, array $required = []) => [
            'required' => true,
            'content' => ['application/json' => ['schema' => array_filter(['type' => 'object', 'properties' => $props, 'required' => $required ?: null])]],
        ];
        $idParam = static fn (string $name, string $type = 'integer', string $desc = '') => ['name' => $name, 'in' => 'path', 'required' => true, 'schema' => ['type' => $type], 'description' => $desc];
        $pageParams = [
            ['name' => 'page', 'in' => 'query', 'schema' => ['type' => 'integer', 'minimum' => 1, 'default' => 1]],
            ['name' => 'per_page', 'in' => 'query', 'schema' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 50, 'default' => 10]],
        ];
        $ref = static fn (string $name) => ['$ref' => '#/components/schemas/' . $name];
        $list = static fn (string $name) => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/' . $name]];

        // ── System
        $add('get', '/health', ['tags' => ['System'], 'summary' => 'Health check', 'security' => [],
            'responses' => $ok('API is running', ['type' => 'object', 'properties' => ['status' => ['type' => 'string', 'example' => 'ok'], 'version' => ['type' => 'string', 'example' => 'v1'], 'time' => ['type' => 'string', 'format' => 'date-time']]]) + $errors(429)]);

        // ── Auth
        $add('post', '/auth/login', ['tags' => ['Auth'], 'summary' => 'Sign in and get a token pair', 'security' => [],
            'description' => 'Limited to 5 attempts per minute per e-mail and IP, 20 per 15 minutes per IP.',
            'requestBody' => $jsonBody(['email' => ['type' => 'string', 'format' => 'email', 'example' => 'candidate@internconnect.ng'], 'password' => ['type' => 'string', 'format' => 'password']], ['email', 'password']),
            'responses' => $ok('Signed in', ['allOf' => [$ref('TokenPair'), ['type' => 'object', 'properties' => ['user' => $ref('Account')]]]]) + $errors(401, 422, 429, 503)]);
        $add('post', '/auth/refresh-token', ['tags' => ['Auth'], 'summary' => 'Rotate the refresh token', 'security' => [],
            'description' => 'Each refresh token works once. Re-using a spent token revokes every token of that sign-in (stolen token detection).',
            'requestBody' => $jsonBody(['refresh_token' => ['type' => 'string']], ['refresh_token']),
            'responses' => $ok('New token pair', $ref('TokenPair')) + $errors(401, 422)]);
        $add('post', '/auth/logout', ['tags' => ['Auth'], 'summary' => 'Sign out', 'security' => $auth,
            'description' => 'Revokes the current access token and, when given, the refresh token family.',
            'requestBody' => ['required' => false, 'content' => ['application/json' => ['schema' => ['type' => 'object', 'properties' => ['refresh_token' => ['type' => 'string']]]]]],
            'responses' => $ok('Signed out', ['nullable' => true]) + $errors(401)]);
        $add('get', '/auth/me', ['tags' => ['Auth'], 'summary' => 'Current account', 'security' => $auth,
            'responses' => $ok('Account', $ref('Account')) + $errors(401)]);

        // ── Catalog
        $add('get', '/internships', ['tags' => ['Internships'], 'summary' => 'Browse internships', 'security' => [],
            'parameters' => array_merge([
                ['name' => 'q', 'in' => 'query', 'schema' => ['type' => 'string'], 'description' => 'Search title, company, description and tags'],
                ['name' => 'category', 'in' => 'query', 'schema' => ['type' => 'string'], 'description' => 'Category or tag, e.g. ENGINEERING, Figma'],
                ['name' => 'location', 'in' => 'query', 'schema' => ['type' => 'string'], 'example' => 'Lagos'],
                ['name' => 'remote', 'in' => 'query', 'schema' => ['type' => 'integer', 'enum' => [0, 1]]],
            ], $pageParams),
            'responses' => $ok('Internships', $list('Internship'), true) + $errors(429)]);
        $add('get', '/internships/{id}', ['tags' => ['Internships'], 'summary' => 'Internship details', 'security' => [],
            'parameters' => [$idParam('id', 'string', 'Internship slug, e.g. software-engineer-intern-nexura')],
            'responses' => $ok('Internship', $ref('Internship')) + $errors(404)]);
        $add('get', '/elearning/programs', ['tags' => ['E-Learning'], 'summary' => 'Partner programs', 'security' => [],
            'description' => '`enroll_url` records a referral and redirects to the partner.',
            'parameters' => array_merge([['name' => 'category', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['tech', 'design', 'data', 'business', 'free']]]], $pageParams),
            'responses' => $ok('Programs', $list('ElearningProgram'), true)]);

        // ── Candidate
        $add('get', '/candidate/profile', ['tags' => ['Candidate'], 'summary' => 'Profile built from the parsed CV', 'security' => $auth,
            'responses' => $ok('Profile', $ref('CandidateProfile')) + $errors(401, 403)]);
        $add('get', '/candidate/applications', ['tags' => ['Candidate'], 'summary' => 'My applications', 'security' => $auth, 'parameters' => $pageParams,
            'responses' => $ok('Applications', $list('ApplicationSummary'), true) + $errors(401, 403)]);
        $add('get', '/candidate/applications/{id}', ['tags' => ['Candidate'], 'summary' => 'One of my applications', 'security' => $auth,
            'parameters' => [$idParam('id')], 'responses' => $ok('Application', $ref('Application')) + $errors(401, 403, 404)]);

        // ── Account (candidate + employer)
        $add('get', '/billing', ['tags' => ['Account'], 'summary' => 'Plan and payment history', 'security' => $auth,
            'parameters' => [['name' => 'page', 'in' => 'query', 'schema' => ['type' => 'integer', 'default' => 1]]],
            'responses' => $ok('Billing', $ref('Billing'), true) + $errors(401, 403)]);
        $add('get', '/notifications', ['tags' => ['Account'], 'summary' => 'Notifications', 'security' => $auth,
            'parameters' => array_merge([['name' => 'unread', 'in' => 'query', 'schema' => ['type' => 'integer', 'enum' => [0, 1]]]], $pageParams),
            'responses' => $ok('Notifications', $list('Notification'), true) + $errors(401, 403)]);

        // ── Employer
        $add('get', '/employer/candidates/{id}/application', ['tags' => ['Employer'], 'summary' => "Candidate's latest application with CV data", 'security' => $auth,
            'parameters' => [$idParam('id', 'integer', 'Candidate id')], 'responses' => $ok('Application', $ref('EmployerApplication')) + $errors(401, 403, 404)]);
        $add('post', '/employer/candidates/{id}/stage', ['tags' => ['Employer'], 'summary' => 'Move the application to a pipeline stage', 'security' => $auth,
            'parameters' => [$idParam('id', 'integer', 'Candidate id')],
            'requestBody' => $jsonBody(['stage' => ['type' => 'string', 'enum' => ['Under Review', 'Shortlisted', 'Interviewing', 'Offer Extended', 'Rejected']]], ['stage']),
            'responses' => $ok('Stage updated', ['type' => 'object', 'properties' => ['application_id' => ['type' => 'integer'], 'stage' => ['type' => 'string']]]) + $errors(401, 403, 404, 422)]);

        // ── Interviews
        $add('get', '/interviews', ['tags' => ['Interviews'], 'summary' => 'My interview sessions (admin: all)', 'security' => $auth, 'parameters' => $pageParams,
            'responses' => $ok('Interviews', $list('Interview'), true) + $errors(401, 403)]);
        $add('get', '/interviews/{id}/status', ['tags' => ['Interviews'], 'summary' => 'Schedule, join window and room state', 'security' => $auth,
            'parameters' => [$idParam('id')], 'responses' => $ok('Status', $ref('InterviewStatus')) + $errors(401, 404)]);
        $add('post', '/interviews/{id}/token', ['tags' => ['Interviews'], 'summary' => 'Daily.co room URL and meeting token', 'security' => $auth,
            'description' => 'Candidates can join from 5 minutes before the start (409 `too_early` before that). Employers and admins join as hosts any time. 10 requests per minute.',
            'parameters' => [$idParam('id')],
            'responses' => $ok('Room access', $ref('RoomAccess')) + $errors(401, 404, 409, 429, 503)]);

        // ── Jenny AI
        $add('post', '/ai/cv/parse', ['tags' => ['Jenny AI'], 'summary' => 'Read a CV and save it to the profile', 'security' => $auth,
            'description' => 'Gemini reads the file; without Gemini a rule-based reader extracts contact details. `meta.source` says which. 10 AI requests per minute.',
            'requestBody' => ['required' => true, 'content' => ['multipart/form-data' => ['schema' => ['type' => 'object', 'required' => ['resume'], 'properties' => ['resume' => ['type' => 'string', 'format' => 'binary', 'description' => 'PDF or DOCX, max 10MB']]]]]],
            'responses' => $ok('Parsed CV', $ref('ParsedCv')) + $errors(401, 403, 422, 429)]);
        $add('post', '/ai/cover-letter', ['tags' => ['Jenny AI'], 'summary' => 'Write a cover letter', 'security' => $auth,
            'requestBody' => $jsonBody([
                'company' => ['type' => 'string', 'example' => 'Paystack'], 'role' => ['type' => 'string', 'example' => 'Product Design Intern'],
                'tone' => ['type' => 'string', 'enum' => ['professional', 'confident', 'technical', 'enthusiastic']], 'description' => ['type' => 'string', 'maxLength' => 2000],
            ], ['company', 'role']),
            'responses' => $ok('Cover letter', ['type' => 'object', 'properties' => [
                'letter' => ['type' => 'string'], 'paragraphs' => ['type' => 'array', 'items' => ['type' => 'string']], 'tone' => ['type' => 'string'],
                'matched_skills' => ['type' => 'array', 'items' => ['type' => 'string']], 'word_count' => ['type' => 'integer'],
            ]]) + $errors(401, 403, 422, 429)]);
        $add('post', '/ai/interview-prep/questions', ['tags' => ['Jenny AI'], 'summary' => 'Practice interview questions for a role', 'security' => $auth,
            'requestBody' => $jsonBody(['role' => ['type' => 'string', 'example' => 'Product Designer']], ['role']),
            'responses' => $ok('Questions', ['type' => 'array', 'items' => ['type' => 'object', 'properties' => ['id' => ['type' => 'integer'], 'question' => ['type' => 'string'], 'category' => ['type' => 'string']]]]) + $errors(401, 403, 422, 429)]);

        // ── Training partners (docs/58-partner-api.md): API key + HMAC signature, not Bearer tokens
        $partnerAuth = [['partnerKey' => [], 'partnerSignature' => [], 'partnerTimestamp' => []]];
        $idemHeader = ['name' => 'Idempotency-Key', 'in' => 'header', 'required' => true, 'schema' => ['type' => 'string', 'minLength' => 8, 'maxLength' => 80], 'description' => 'Unique per logical request. Retries reuse it and get the first answer back (header Idempotent-Replayed: true).'];
        $partnerResult = ['type' => 'object', 'properties' => ['id' => ['type' => 'integer'], 'reference' => ['type' => 'string', 'example' => 'ICT3F9A1C2B7D4E'], 'score' => ['type' => 'number'], 'passed' => ['type' => 'boolean'], 'status' => ['type' => 'string', 'enum' => ['active', 'superseded', 'revoked']], 'course' => ['type' => 'string', 'nullable' => true], 'certificate_id' => ['type' => 'string', 'nullable' => true], 'plan_unlocked' => ['type' => 'string', 'nullable' => true, 'enum' => ['pro', 'premium', null]], 'plan_days' => ['type' => 'integer', 'nullable' => true], 'supersedes' => ['type' => 'integer', 'nullable' => true], 'recorded_at' => ['type' => 'string', 'format' => 'date-time'], 'outcome' => ['type' => 'string', 'enum' => ['created', 'corrected', 'unchanged']]]];
        $add('post', '/partners/results', ['tags' => ['Training partners'], 'summary' => 'Report a final score for a student', 'security' => $partnerAuth,
            'description' => 'Send once the student finishes. A different score for the same reference corrects the earlier one (history is kept). Scores are 0-100; the pass mark and the plan a score unlocks are set by InternConnect.',
            'parameters' => [$idemHeader],
            'requestBody' => $jsonBody(['reference' => ['type' => 'string', 'example' => 'ICT3F9A1C2B7D4E'], 'score' => ['type' => 'number', 'minimum' => 0, 'maximum' => 100, 'example' => 87.5], 'course' => ['type' => 'string', 'example' => 'Data Analytics Bootcamp'], 'certificate_id' => ['type' => 'string', 'example' => 'JIG-2026-00123'], 'certificate_url' => ['type' => 'string', 'format' => 'uri', 'example' => 'https://jigide.com/cert/JIG-2026-00123'], 'completed_at' => ['type' => 'string', 'format' => 'date-time']], ['reference', 'score']),
            'responses' => $ok('Result recorded (201), corrected or already recorded (200)', $partnerResult) + $errors(400, 401, 403, 404, 409, 422, 429)]);
        $add('post', '/partners/applications/status', ['tags' => ['Training partners'], 'summary' => 'Update a student application status', 'security' => $partnerAuth,
            'parameters' => [$idemHeader],
            'requestBody' => $jsonBody(['reference' => ['type' => 'string'], 'status' => ['type' => 'string', 'enum' => ['enrolled', 'in_progress', 'withdrawn']]], ['reference', 'status']),
            'responses' => $ok('Status updated', ['type' => 'object', 'properties' => ['reference' => ['type' => 'string'], 'status' => ['type' => 'string'], 'updated_at' => ['type' => 'string', 'format' => 'date-time']]]) + $errors(400, 401, 403, 404, 409, 422, 429)]);
        $add('get', '/partners/students/{reference}', ['tags' => ['Training partners'], 'summary' => 'Check a student reference', 'security' => $partnerAuth,
            'description' => 'Confirms a reference that InternConnect sent you. No personal data is returned. Sign an empty body.',
            'parameters' => [$idParam('reference', 'string', 'The ic_ref value from the hand-off link')],
            'responses' => $ok('Reference is valid', ['type' => 'object', 'properties' => ['reference' => ['type' => 'string'], 'status' => ['type' => 'string'], 'program' => ['type' => 'string', 'nullable' => true], 'started_at' => ['type' => 'string', 'format' => 'date-time']]]) + $errors(401, 403, 404, 429)]);

        $partnerFilters = [
            ['name' => 'from', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date'], 'example' => '2026-11-01', 'description' => 'Students who started on or after this date'],
            ['name' => 'to', 'in' => 'query', 'schema' => ['type' => 'string', 'format' => 'date'], 'example' => '2026-11-30', 'description' => 'Students who started on or before this date'],
        ];
        $add('get', '/partners/students', ['tags' => ['Training partners'], 'summary' => 'List your students and their scores', 'security' => $partnerAuth,
            'description' => 'Every student InternConnect sent you, newest first, with the latest score. No names or e-mails: match students by reference. Sign an empty body.',
            'parameters' => array_merge($partnerFilters, [
                ['name' => 'status', 'in' => 'query', 'schema' => ['type' => 'string', 'enum' => ['started', 'enrolled', 'in_progress', 'completed', 'failed', 'withdrawn', 'expired']]],
                ['name' => 'passed', 'in' => 'query', 'schema' => ['type' => 'boolean'], 'description' => 'true: passed only, false: failed only'],
            ], $pageParams),
            'responses' => $ok('Students', ['type' => 'array', 'items' => ['type' => 'object', 'properties' => [
                'reference' => ['type' => 'string', 'example' => 'ICT3F9A1C2B7D4E'], 'program' => ['type' => 'string', 'nullable' => true], 'status' => ['type' => 'string'],
                'started_at' => ['type' => 'string', 'format' => 'date-time'], 'status_at' => ['type' => 'string', 'format' => 'date-time', 'nullable' => true],
                'score' => ['type' => 'number', 'nullable' => true, 'example' => 87.5], 'passed' => ['type' => 'boolean', 'nullable' => true],
                'course' => ['type' => 'string', 'nullable' => true], 'certificate_id' => ['type' => 'string', 'nullable' => true],
                'plan_unlocked' => ['type' => 'string', 'nullable' => true, 'enum' => ['pro', 'premium', null]], 'completed_at' => ['type' => 'string', 'format' => 'date-time', 'nullable' => true]]]], true) + $errors(401, 403, 422, 429)]);
        $add('get', '/partners/stats', ['tags' => ['Training partners'], 'summary' => 'Totals, pass rate and average score', 'security' => $partnerAuth,
            'description' => 'Counts for your students only: how many started, by status, how many results, passed and failed, pass rate, average, highest and lowest score, and plans unlocked. Optional date range. Sign an empty body.',
            'parameters' => $partnerFilters,
            'responses' => $ok('Statistics', ['type' => 'object', 'properties' => [
                'students' => ['type' => 'integer', 'example' => 120],
                'by_status' => ['type' => 'object', 'additionalProperties' => ['type' => 'integer'], 'example' => ['started' => 20, 'enrolled' => 30, 'in_progress' => 25, 'completed' => 35, 'failed' => 8, 'withdrawn' => 2, 'expired' => 0]],
                'results' => ['type' => 'integer', 'example' => 43], 'passed' => ['type' => 'integer', 'example' => 35], 'failed' => ['type' => 'integer', 'example' => 8],
                'pass_rate' => ['type' => 'number', 'nullable' => true, 'example' => 81.4], 'average_score' => ['type' => 'number', 'nullable' => true, 'example' => 76.2],
                'highest_score' => ['type' => 'number', 'nullable' => true], 'lowest_score' => ['type' => 'number', 'nullable' => true],
                'plans_unlocked' => ['type' => 'object', 'properties' => ['pro' => ['type' => 'integer'], 'premium' => ['type' => 'integer']]],
                'pass_mark' => ['type' => 'number', 'nullable' => true, 'example' => 70],
                'period' => ['type' => 'object', 'properties' => ['from' => ['type' => 'string', 'nullable' => true], 'to' => ['type' => 'string', 'nullable' => true]]]]]) + $errors(401, 403, 422, 429)]);

        return [
            'openapi' => '3.0.3',
            'info' => [
                'title' => 'InternConnect API',
                'version' => '1.0.0',
                'description' => "REST API for the InternConnect mobile and partner clients.\n\n"
                    . "**Auth**: `POST /auth/login` returns a 15-minute access token (JWT, HS256) and a 30-day single-use refresh token. Send `Authorization: Bearer <access_token>`.\n\n"
                    . "**Envelope**: every response is `{success, code, message, data, meta?}`; errors add `error` (machine code) and `errors` (field messages).\n\n"
                    . "**Limits**: 120 requests per minute per account (or IP), 10 per minute for AI and room tokens. Over the limit: `429` with `Retry-After`.\n\n"
                    . "Planned (backend phase, needs MySQL accounts): register, OTP verification, applying and saving internships, employer job posting.",
            ],
            'servers' => [['url' => rtrim($serverUrl, '/') . '/api/v1']],
            'security' => $auth,
            'tags' => array_map(static fn ($t) => ['name' => $t], ['System', 'Auth', 'Internships', 'E-Learning', 'Candidate', 'Account', 'Employer', 'Interviews', 'Jenny AI', 'Training partners']),
            'paths' => $paths,
            'components' => self::components(),
        ];
    }

    private static function components(): array
    {
        $s = static fn (array $props) => ['type' => 'object', 'properties' => $props];
        $str = ['type' => 'string'];
        $int = ['type' => 'integer'];
        $bool = ['type' => 'boolean'];
        $dt = ['type' => 'string', 'format' => 'date-time', 'nullable' => true];
        $money = $s(['kobo' => $int, 'label' => ['type' => 'string', 'example' => '₦2,499']]);
        $strs = ['type' => 'array', 'items' => ['type' => 'string']];
        $error = static fn (string $desc, string $code) => ['description' => $desc, 'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Error'], 'example' => ['success' => false, 'code' => (int)substr($desc, 0, 3), 'message' => substr($desc, 4), 'error' => $code]]]];
        return [
            'securitySchemes' => [
                'bearerAuth' => ['type' => 'http', 'scheme' => 'bearer', 'bearerFormat' => 'JWT'],
                'partnerKey' => ['type' => 'apiKey', 'in' => 'header', 'name' => 'X-Partner-Key', 'description' => 'Training partner API key (icp_...).'],
                'partnerSignature' => ['type' => 'apiKey', 'in' => 'header', 'name' => 'X-Signature', 'description' => 'hex(HMAC-SHA256(signing secret, X-Timestamp + "." + raw body)).'],
                'partnerTimestamp' => ['type' => 'apiKey', 'in' => 'header', 'name' => 'X-Timestamp', 'description' => 'Unix seconds, within 5 minutes of InternConnect time.'],
            ],
            'responses' => [
                'E401' => $error('401 Sign in to use this endpoint.', 'unauthenticated'),
                'E403' => $error('403 Your account type cannot use this endpoint.', 'forbidden'),
                'E404' => $error('404 Not found.', 'not_found'),
                'E400' => $error('400 Bad request (for example a missing Idempotency-Key).', 'idempotency_key_required'),
                'E409' => $error('409 Conflict: the room is not open yet, the application is closed, or an Idempotency-Key was reused with another body.', 'too_early'),
                'E422' => ['description' => 'Validation failed', 'content' => ['application/json' => ['schema' => ['$ref' => '#/components/schemas/Error'], 'example' => ['success' => false, 'code' => 422, 'message' => 'Some fields need attention.', 'error' => 'validation_failed', 'errors' => ['email' => ['The email must be a valid e-mail address.']]]]]],
                'E429' => $error('429 Too many requests. Try again in 30 seconds.', 'too_many_requests'),
                'E503' => $error('503 Service unavailable or not configured.', 'service_unavailable'),
            ],
            'schemas' => [
                'Envelope' => $s(['success' => $bool, 'code' => $int, 'message' => $str]),
                'Error' => $s(['success' => ['type' => 'boolean', 'example' => false], 'code' => $int, 'message' => $str, 'error' => $str, 'errors' => ['type' => 'object', 'additionalProperties' => $strs]]),
                'Pagination' => $s(['current_page' => $int, 'per_page' => $int, 'total_records' => $int, 'total_pages' => $int]),
                'TokenPair' => $s(['token_type' => ['type' => 'string', 'example' => 'Bearer'], 'access_token' => $str, 'expires_in' => ['type' => 'integer', 'example' => 900], 'refresh_token' => $str, 'refresh_expires_in' => ['type' => 'integer', 'example' => 2592000]]),
                'Account' => $s(['id' => $int, 'role' => ['type' => 'string', 'enum' => ['candidate', 'employer', 'admin']], 'name' => $str, 'email' => $str, 'company' => $str]),
                'Internship' => $s(['id' => $str, 'title' => $str, 'company' => $str, 'company_slug' => $str, 'category' => $str, 'location' => $str, 'country' => $str, 'commitment' => $str, 'work_mode' => $str, 'posted' => $str, 'stipend' => array_merge($s(['amount' => $int, 'currency' => $str, 'label' => $str]), ['nullable' => true, 'description' => 'Null on Premium listings the plan of the caller does not include.']), 'premium' => $bool, 'locked' => $bool, 'tags' => $strs, 'description' => $str, 'url' => $str]),
                'ElearningProgram' => $s(['id' => $int, 'platform' => $str, 'tier' => $str, 'logo' => $str, 'website' => $str, 'about_platform' => $str, 'services' => ['type' => 'array', 'items' => $s(['name' => $str, 'desc' => $str])], 'title' => $str, 'category' => $str, 'category_label' => $str, 'duration' => $str, 'delivery' => $str, 'level' => $str, 'desc' => $str, 'highlights' => $strs, 'outcomes' => $str, 'perk' => $str, 'perk_code' => $str, 'enroll_url' => $str]),
                'Experience' => $s(['title' => $str, 'company' => $str, 'location' => $str, 'start' => $str, 'end' => $str, 'current' => $bool, 'highlights' => $strs]),
                'Education' => $s(['school' => $str, 'degree' => $str, 'field' => $str, 'start' => $str, 'end' => $str]),
                'ParsedCv' => $s(['name' => $str, 'email' => $str, 'phone' => $str, 'location' => $str, 'headline' => $str, 'job_title' => $str, 'company' => $str, 'summary' => $str, 'experience' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/Experience']], 'education' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/Education']], 'skills' => $strs, 'source' => ['type' => 'string', 'enum' => ['gemini', 'rules', 'none']]]),
                'CandidateProfile' => $s(['id' => $int, 'name' => $str, 'email' => $str, 'headline' => $str, 'location' => $str, 'summary' => $str, 'skills' => $strs, 'experience' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/Experience']], 'education' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/Education']], 'cv' => array_merge($s(['name' => $str, 'updated_at' => $str, 'parsed_by' => $str]), ['nullable' => true])]),
                'ApplicationSummary' => $s(['id' => $int, 'internship' => $s(['slug' => $str, 'title' => $str, 'company' => $str]), 'stage' => ['type' => 'string', 'enum' => ['Under Review', 'Shortlisted', 'Interviewing', 'Offer Extended', 'Rejected']], 'submitted_at' => $str]),
                'Application' => ['allOf' => [['$ref' => '#/components/schemas/ApplicationSummary'], $s(['job_title' => $str, 'job_company' => $str, 'salary_expectation' => $str, 'cover_letter' => $str, 'has_cover_letter_file' => $bool, 'answers' => ['type' => 'array', 'items' => $s(['question' => $str, 'answer' => $str])], 'links' => ['type' => 'object', 'additionalProperties' => $str], 'ai_summary' => $str, 'resume' => $s(['name' => $str, 'ext' => $str])])]],
                'EmployerApplication' => ['allOf' => [['$ref' => '#/components/schemas/Application'], $s(['candidate' => $s(['id' => $int, 'name' => $str, 'email' => $str, 'phone' => $str, 'experience' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/Experience']], 'education' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/Education']], 'skills' => $strs])])]],
                'Billing' => $s(['plan' => $s(['key' => $str, 'name' => $str, 'cycle' => $str, 'status' => ['type' => 'string', 'enum' => ['active', 'expired', 'free']], 'amount' => $money, 'started_at' => $str, 'renews_at' => $str]), 'total_paid' => $money, 'payments' => ['type' => 'array', 'items' => $s(['reference' => $str, 'plan' => $str, 'cycle' => $str, 'amount' => $money, 'status' => ['type' => 'string', 'enum' => ['pending', 'success', 'failed', 'abandoned', 'reversed']], 'method' => $str, 'paid_at' => $dt, 'created_at' => $str])]]),
                'Notification' => $s(['id' => $int, 'title' => $str, 'body' => $str, 'category' => $str, 'is_read' => $bool, 'time_label' => $str, 'timestamp' => $int, 'action_url' => $str]),
                'Interview' => $s(['id' => $int, 'candidate' => $s(['id' => $int, 'name' => $str, 'role' => $str]), 'company' => $str, 'interviewer' => $str, 'status' => $str, 'scheduled_at' => $dt, 'duration_min' => $int, 'room_status' => $str]),
                'InterviewStatus' => $s(['id' => $int, 'status' => $str, 'scheduled_at' => $dt, 'duration_min' => $int, 'grace_min' => $int, 'opens_at' => $dt, 'can_join_now' => $bool, 'room_status' => $str, 'ends_at' => $dt]),
                'RoomAccess' => $s(['room_url' => $str, 'token' => $str, 'owner' => $bool, 'ends_at' => $str, 'hard_end' => $str]),
            ],
        ];
    }
}
