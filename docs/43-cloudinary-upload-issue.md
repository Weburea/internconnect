# Cloudinary Dynamic Folder Mode — Root Cause & Rule

## 1. What Happened

During the initial asset migration, all files were uploaded to Cloudinary successfully and their CDN URLs worked correctly. However, the files were **not visible in the Media Library folder tree**.

**Root cause:** The Cloudinary `weburea` product environment has **Dynamic Folder Mode enabled**. In this mode, Cloudinary does **not** use the path inside `public_id` to determine where an asset belongs in the Media Library. It uses a completely separate field called `asset_folder`.

The initial uploads did not include the `asset_folder` parameter, so every asset was stored with `asset_folder: ""` (empty). The CDN URLs worked, but the Media Library had no folder association to display them under.

---

## 2. The Fix Applied

All assets were re-uploaded with the explicit `asset_folder` parameter set:

- Logo SVGs → `asset_folder: "internConnect/images/logo"`
- All other UI images → `asset_folder: "internConnect/images"`

After this, the folders and files immediately appeared correctly in the Cloudinary Media Library.

---

## 3. Permanent Rule — MUST FOLLOW

> [!IMPORTANT]
> Every future Cloudinary asset upload via the MCP tool **MUST** include the `asset_folder` parameter explicitly set to the correct destination folder path.

### Correct Upload Pattern

```php
// Logo assets
asset_folder: "internConnect/images/logo"
public_id:    "internConnect/images/logo/<filename>"

// All other UI images
asset_folder: "internConnect/images"
public_id:    "internConnect/images/<filename>"
```

### MCP Upload Example (always include asset_folder)

```json
{
  "resource_type": "image",
  "upload_request": {
    "file": "file://C:/path/to/file.png",
    "public_id": "internConnect/images/filename",
    "asset_folder": "internConnect/images",
    "overwrite": true,
    "invalidate": true
  }
}
```

> [!CAUTION]
> Never upload to Cloudinary without `asset_folder` when Dynamic Folder Mode is ON. The file will exist on the CDN but will be invisible in the Media Library.


---

## 2. Technical Details

## 2. Technical Details

### Browser Console Mismatch
- In your **API Keys** screenshot, the active product environment is **`weburea`** (badge at the top says `Cloud name: weburea`).
- In your **Media Library** screenshot, the active product environment selected in the top-left dropdown is **`webureacloude`**.

Because of this, your Media Library is showing the `webureacloude` environment, while all the API keys and uploaded assets belong to the `weburea` environment.

---

## 3. How to View the Images in Your Browser

1. Open your **Cloudinary Media Library** page in your browser.
2. Click the dropdown in the **top-left corner** (which currently shows `webureacloude`).
3. Select **`weburea`** from the list.
4. The page will reload and show all the uploaded files and the `logo` folder under `internConnect > images`!


