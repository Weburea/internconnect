# Cloudinary UI Asset Migration Plan

This document details the migration of InternConnect's local image assets to Cloudinary, ensuring all user interface image assets are served dynamically from the cloud rather than local storage.

---

## 1. Migration Strategy Overview

The primary objective is to stop serving UI design images locally and start serving them from Cloudinary using the existing global helper function structure.

### Asset Paths & Mapping Rules

All assets will be mapped under the Cloudinary root folder `internConnect/images/`:

| Local Source Path | Cloudinary Destination Folder | Target Public ID Format |
| :--- | :--- | :--- |
| `public/assets/images/logos/<file>` | `internConnect/images/logo/` | `internConnect/images/logo/<filename_without_extension>` |
| `public/assets/images/<file>` | `internConnect/images/` | `internConnect/images/<filename_without_extension>` |

*Note: The local folder `logos` (plural) is mapped to the singular folder `logo` on Cloudinary per user requirements.*

---

## 2. Configuration & environment setup

A new environment variable `CLOUDINARY_CLOUD_NAME` will be added to configure the active Cloudinary account.

### `.env` File Updates

```ini
# Cloudinary Configuration
CLOUDINARY_CLOUD_NAME=weburea
```

---

## 3. Dynamic serving via helper function

To avoid modifying dozens of views and templates directly, the `asset()` helper function in `backend/Core/helpers.php` will be updated to intercept requests for local images and dynamically route them to Cloudinary:

```php
if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $cloudinaryCloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? $_SERVER['CLOUDINARY_CLOUD_NAME'] ?? null;
        
        if ($cloudinaryCloudName) {
            $cleanPath = ltrim($path, '/');
            
            // Check if this is a local image asset under assets/images
            if (str_starts_with($cleanPath, 'assets/images/')) {
                $subPath = substr($cleanPath, strlen('assets/images/'));
                
                // Map logos/ to logo/ (singular)
                if (str_starts_with($subPath, 'logos/')) {
                    $cloudinaryPath = 'internConnect/images/logo/' . substr($subPath, strlen('logos/'));
                } else {
                    $cloudinaryPath = 'internConnect/images/' . $subPath;
                }
                
                return "https://res.cloudinary.com/{$cloudinaryCloudName}/image/upload/{$cloudinaryPath}";
            }
        }
        
        return url($path);
    }
}
```

---

## 4. Execution steps [COMPLETED]

1. **Upload Assets**: Uploaded all SVG logos (as image type) and PNG/JPG homepage images to matching folders in `internConnect/images`.
2. **Configure Environment**: Set the `CLOUDINARY_CLOUD_NAME` variable in local `.env` and `.env.example`.
3. **Deploy Code**: Replaced the helper logic in `backend/Core/helpers.php`.
4. **Verify**: Mapped assets deliver successfully over CDN with correct content types (`image/svg+xml` for SVGs).

