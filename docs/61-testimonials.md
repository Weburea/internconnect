# 61. Testimonials

Quotes and video stories are managed in **Admin > Testimonials** (`/admin/testimonials`) and stored in the `testimonials` table. Changes show on the site straight away.

## Where they show

| Place | What it shows |
|---|---|
| Home page section (`frontend/landing/components/testimonials.php`) | Active rows with `show_home = 1`: quotes in two floating columns around the video story player |
| `/testimonials` (`frontend/landing/testimonials.php`) | Active rows with `show_page = 1`: hero with counts, filter chips (students, employers, each platform in use), a three-column floating wall (pauses on hover, static when the user prefers reduced motion) and a video story grid |

## Fields

`kind` (quote or video), `name`, `handle`, `role`, `quote`, `platform` (linkedin, x, instagram, facebook, tiktok, youtube, google, website), `source_url`, `avatar_url`, `video_url`, `rating` (0 to 5), `audience` (student or employer), `show_home`, `show_page`, `sort` (lower shows first), `is_active`.

## Admin

- Filters: all, on home page, on testimonials page, videos, hidden.
- Switches per row for home, page and visible. Delete asks for a second click; there is no browser confirm.
- The form previews the card with its social badge. Photos (PNG, JPG, WebP or GIF up to 5 MB) and videos (MP4, MOV or WebM up to 40 MB) upload to Cloudinary `InternConnect/testimonials` through `POST /admin/testimonials/upload`. A pasted https link also works.

## Code

- `backend/Services/TestimonialService.php`: table, seed (the original home quotes and videos plus the former page stories), `list`, `find`, `save` (validation), `toggle`, `delete`, `badge()` SVGs.
- `backend/Controllers/AdminTestimonialsController.php`, routes in `backend/routes.php`.
- `backend/Services/CloudinaryService.php`: signed uploads shared with newsletter images.
- MySQL: migration `2026_11_13_000004`.
