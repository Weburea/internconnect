<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Env;

/**
 * Signed uploads to Cloudinary (CLOUDINARY_* in .env, never sent to browsers).
 * Images: PNG, JPG, WebP, GIF up to 5 MB. Videos: MP4, MOV, WebM up to 40 MB.
 * Files are checked by content (not just name) before they leave the server.
 */
final class CloudinaryService
{
    public static function configured(): bool
    {
        return Env::get('CLOUDINARY_CLOUD_NAME') !== '' && Env::get('CLOUDINARY_API_KEY') !== '' && Env::get('CLOUDINARY_API_SECRET') !== '';
    }

    /** @return array{ok: bool, url?: string, error?: string, width?: int, height?: int, public_id?: string} */
    public static function upload(?array $file, string $folder, string $kind = 'image'): array
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_uploaded_file((string)$file['tmp_name'])) {
            $err = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
            return ['ok' => false, 'error' => in_array($err, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) ? 'That file is too large for the server.' : 'Choose a file to upload.'];
        }
        $tmp = (string)$file['tmp_name'];
        $size = (int)$file['size'];
        if ($kind === 'video') {
            $head = (string)file_get_contents($tmp, false, null, 0, 16);
            $isMp4 = substr($head, 4, 4) === 'ftyp';
            $isWebm = str_starts_with($head, "\x1A\x45\xDF\xA3");
            if (!$isMp4 && !$isWebm) {
                return ['ok' => false, 'error' => 'Upload an MP4, MOV or WebM video.'];
            }
            if ($size > 40 * 1024 * 1024) {
                return ['ok' => false, 'error' => 'Videos must be under 40 MB.'];
            }
            $mime = $isWebm ? 'video/webm' : 'video/mp4';
        } else {
            $info = @getimagesize($tmp);
            if (!$info || !in_array($info['mime'] ?? '', ['image/png', 'image/jpeg', 'image/webp', 'image/gif'], true)) {
                return ['ok' => false, 'error' => 'Upload a PNG, JPG, WebP or GIF image.'];
            }
            if ($size > 5 * 1024 * 1024) {
                return ['ok' => false, 'error' => 'Images must be under 5 MB.'];
            }
            $mime = (string)$info['mime'];
        }
        if (!self::configured()) {
            return ['ok' => false, 'error' => 'File hosting (Cloudinary) is not configured.'];
        }
        $params = ['folder' => $folder, 'timestamp' => (string)time()];
        ksort($params);
        $params['signature'] = sha1(implode('&', array_map(static fn ($k, $v) => $k . '=' . $v, array_keys($params), $params)) . Env::get('CLOUDINARY_API_SECRET'));
        $params['api_key'] = Env::get('CLOUDINARY_API_KEY');
        $params['file'] = new \CURLFile($tmp, $mime, $kind . '-upload');
        $ch = curl_init('https://api.cloudinary.com/v1_1/' . rawurlencode(Env::get('CLOUDINARY_CLOUD_NAME')) . '/' . ($kind === 'video' ? 'video' : 'image') . '/upload');
        curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $params, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => $kind === 'video' ? 180 : 40]);
        $raw = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $res = json_decode((string)$raw, true) ?: [];
        if ($status !== 200 || empty($res['secure_url'])) {
            error_log('Cloudinary ' . $kind . ' upload failed: HTTP ' . $status . ' ' . substr((string)$raw, 0, 200));
            return ['ok' => false, 'error' => 'The file could not be uploaded. Please try again.'];
        }
        $url = (string)$res['secure_url'];
        // Light delivery versions: videos muted and compressed (like the home stories), images optimised.
        $url = $kind === 'video'
            ? preg_replace('#/video/upload/#', '/video/upload/w_480,q_auto:eco,ac_none/', $url, 1)
            : preg_replace('#/image/upload/#', '/image/upload/f_auto,q_auto,w_800,c_limit/', $url, 1);
        return ['ok' => true, 'url' => (string)$url, 'width' => (int)($res['width'] ?? 0), 'height' => (int)($res['height'] ?? 0), 'public_id' => (string)($res['public_id'] ?? '')];
    }
}
