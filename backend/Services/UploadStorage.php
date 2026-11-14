<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Private upload storage (resumes, employer logos, business registration documents).
 *
 * Files live in storage/uploads/{kind}/ (outside public/, denied by storage/.htaccess)
 * under random names and are only served through controller stream actions.
 * Every upload is checked by extension, size, MIME sniffing and magic bytes.
 */
class UploadStorage
{
    private const MB = 1024 * 1024;

    private const FORMATS = [
        'pdf'  => ['mimes' => ['application/pdf'], 'magic' => ['%PDF'], 'content_type' => 'application/pdf'],
        'docx' => [
            'mimes' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/octet-stream'],
            'magic' => ["PK\x03\x04"],
            'content_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
        'png'  => ['mimes' => ['image/png'], 'magic' => ["\x89PNG"], 'content_type' => 'image/png'],
        'jpg'  => ['mimes' => ['image/jpeg'], 'magic' => ["\xFF\xD8\xFF"], 'content_type' => 'image/jpeg'],
        'jpeg' => ['mimes' => ['image/jpeg'], 'magic' => ["\xFF\xD8\xFF"], 'content_type' => 'image/jpeg'],
        'webp' => ['mimes' => ['image/webp'], 'magic' => ['RIFF'], 'content_type' => 'image/webp'],
        'doc'  => ['mimes' => ['application/msword', 'application/CDFV2', 'application/x-ole-storage', 'application/octet-stream'], 'magic' => ["\xD0\xCF\x11\xE0"], 'content_type' => 'application/msword'],
        // Plain text has no signature: checked by MIME type and for binary bytes instead.
        'txt'  => ['mimes' => ['text/plain', 'text/x-c', 'application/octet-stream'], 'magic' => [''], 'content_type' => 'text/plain; charset=utf-8'],
    ];

    /** kind => [allowed extensions, max bytes, label used in messages] */
    private const KINDS = [
        'resume'       => ['ext' => ['pdf', 'docx'], 'max' => 10 * self::MB, 'label' => 'Resume file', 'types' => 'a PDF or DOCX file'],
        'business_doc' => ['ext' => ['pdf', 'jpg', 'jpeg', 'png'], 'max' => 10 * self::MB, 'label' => 'Document', 'types' => 'a PDF, JPG or PNG file'],
        'logo'         => ['ext' => ['png', 'jpg', 'jpeg', 'webp'], 'max' => 5 * self::MB, 'label' => 'Logo image', 'types' => 'a PNG, JPG or WebP image'],
        // Shared during video interviews: documents and images only (no video).
        'interview_file' => ['ext' => ['pdf', 'docx', 'doc', 'txt', 'png', 'jpg', 'jpeg', 'webp'], 'max' => 10 * self::MB, 'label' => 'File', 'types' => 'a PDF, Word, text or image file'],
        // Attached to a Jenny AI chat message (read by Gemini; private, never public).
        'jenny'          => ['ext' => ['pdf', 'docx', 'txt', 'png', 'jpg', 'jpeg', 'webp'], 'max' => 8 * self::MB, 'label' => 'Attachment', 'types' => 'a PDF, Word, text or image file'],
    ];

    public static function directory(string $kind): string
    {
        $dir = dirname(__DIR__, 2) . '/storage/uploads/' . preg_replace('/[^a-z_]/', '', $kind);
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }
        return $dir;
    }

    /**
     * Validate and store an entry from $_FILES.
     *
     * @return array{ok: bool, error?: string, meta?: array}
     */
    public static function store(?array $upload, string $kind): array
    {
        $rules = self::KINDS[$kind] ?? null;
        if ($rules === null) {
            return ['ok' => false, 'error' => 'Unsupported upload.'];
        }
        if (!$upload || ($upload['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ['ok' => false, 'error' => 'Please choose a file to upload.'];
        }
        if (($upload['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'The upload failed. Please try again.'];
        }
        if (!is_uploaded_file((string)$upload['tmp_name'])) {
            return ['ok' => false, 'error' => 'Invalid upload.'];
        }

        $size = (int)($upload['size'] ?? 0);
        if ($size <= 0 || $size > $rules['max']) {
            return ['ok' => false, 'error' => $rules['label'] . ' must be under ' . ($rules['max'] / self::MB) . 'MB.'];
        }

        $originalName = basename(str_replace('\\', '/', (string)($upload['name'] ?? 'file')));
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, $rules['ext'], true)) {
            return ['ok' => false, 'error' => 'Unsupported file type. Please upload ' . $rules['types'] . '.'];
        }

        $tmp = (string)$upload['tmp_name'];
        $head = (string)file_get_contents($tmp, false, null, 0, 12);
        $mime = function_exists('finfo_open') ? (string)(new \finfo(FILEINFO_MIME_TYPE))->file($tmp) : '';
        $format = self::FORMATS[$ext];
        $magicOk = false;
        foreach ($format['magic'] as $magic) {
            if (str_starts_with($head, $magic)) {
                $magicOk = true;
            }
        }
        if ($ext === 'webp' && substr($head, 8, 4) !== 'WEBP') {
            $magicOk = false;
        }
        if ($ext === 'txt' && str_contains((string)file_get_contents($tmp, false, null, 0, 4096), "\0")) {
            $magicOk = false; // binary content renamed to .txt
        }
        if (!$magicOk || ($mime !== '' && !in_array($mime, $format['mimes'], true))) {
            return ['ok' => false, 'error' => 'That file does not look like a valid ' . strtoupper($ext) . ' file.'];
        }

        $stored = bin2hex(random_bytes(16)) . '.' . $ext;
        if (!move_uploaded_file($tmp, self::directory($kind) . '/' . $stored)) {
            return ['ok' => false, 'error' => 'Could not save the file. Please try again.'];
        }

        $cleanName = preg_replace('/[^\w\s.\-()]+/u', '', $originalName) ?: ($kind . '.' . $ext);

        return ['ok' => true, 'meta' => [
            'kind'        => $kind,
            'file'        => $stored,
            'name'        => mb_substr($cleanName, 0, 120),
            'size'        => $size,
            'ext'         => $ext,
            'uploaded_at' => time(),
        ]];
    }

    /**
     * Copy a stored file under a new random name (e.g. a profile resume attached to an application,
     * so replacing the profile resume later never changes what the employer received).
     */
    public static function duplicate(?array $meta): ?array
    {
        $path = self::path($meta);
        if ($path === null) {
            return null;
        }
        $kind = (string)$meta['kind'];
        $ext = strtolower((string)pathinfo($path, PATHINFO_EXTENSION));
        $stored = bin2hex(random_bytes(16)) . '.' . $ext;
        if (!@copy($path, self::directory($kind) . '/' . $stored)) {
            return null;
        }
        return ['file' => $stored, 'uploaded_at' => time()] + $meta;
    }

    /** Absolute path for stored metadata, or null if missing/tampered. */
    public static function path(?array $meta): ?string
    {
        $kind = (string)($meta['kind'] ?? 'resume');
        $file = (string)($meta['file'] ?? '');
        if (!isset(self::KINDS[$kind]) || !preg_match('/^[a-f0-9]{32}\.(pdf|docx|doc|txt|png|jpe?g|webp)$/', $file)) {
            return null;
        }
        $path = self::directory($kind) . '/' . $file;
        return is_file($path) ? $path : null;
    }

    public static function contentType(array $meta): string
    {
        return self::FORMATS[$meta['ext'] ?? '']['content_type'] ?? 'application/octet-stream';
    }

    public static function delete(?array $meta): void
    {
        $path = self::path($meta);
        if ($path !== null) {
            @unlink($path);
        }
    }

    /** Send the stored file to the browser and stop. 404 when missing. */
    public static function stream(?array $meta, bool $download = false): void
    {
        $path = self::path($meta);
        if ($path === null) {
            http_response_code(404);
            echo 'File not found.';
            exit;
        }

        header('Content-Type: ' . self::contentType($meta));
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: ' . ($download ? 'attachment' : 'inline') . '; filename="' . addcslashes((string)$meta['name'], '"\\') . '"');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store');
        readfile($path);
        exit;
    }

    /** Shape used by profile pages. */
    public static function describe(?array $meta, string $viewUrl): ?array
    {
        if (self::path($meta) === null) {
            return null;
        }
        return [
            'name'        => $meta['name'],
            'ext'         => $meta['ext'],
            'size_label'  => (int)$meta['size'] < self::MB ? max(1, (int)round(((int)$meta['size']) / 1024)) . ' KB' : number_format(((int)$meta['size']) / self::MB, 1) . ' MB',
            'uploaded_on' => date('M j, Y', (int)$meta['uploaded_at']),
            'url'         => $viewUrl . (str_contains($viewUrl, '?') ? '&' : '?') . 'v=' . (int)$meta['uploaded_at'],
        ];
    }
}
