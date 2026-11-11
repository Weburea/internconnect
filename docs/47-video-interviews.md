# In-Platform Video Interviews (Daily.co)

> Updated 2026-11-11. Source of truth for interview sessions, video rooms, timing, scorecards, files, AI support, history and admin controls.
> AI agents and contributors: extend the shared components and services listed below. Do not rebuild per-role video pages, never show stock photos as participant video, never link to Google Meet or Zoom, and never send the Daily, Gemini or MaxMind keys to the browser.

## 1. Model: one interview = one session = one room

- Every interview is a row in `scheduled_interviews`. Its `id` is the video room id for both people:
  - employer: `/employer/interviews/room/{id}`
  - candidate: `/candidate/interview/room/{id}`
- Rows created by the scheduling flow have `created_by` = `employer` or `admin`. Older demo/test rows (`created_by` NULL, Google Meet era) are kept but hidden from all lists.
- Each session stores its schedule (`scheduled_at`, `duration_min`) and the employer's **setup** (`setup_json`: criteria, questions, decision options).

## 2. Who can do what

| Who | Where | What |
|---|---|---|
| Employer | `/employer/interviews/schedule/{candidateId}` | Pick date/time (real pickers), length (default 30 min), meeting type, and the **interview setup**: rating criteria (up to 8), decision preset (Hiring decision / Next stage / Fit level), questions (own + "Suggest with Jenny"). |
| Employer | `/employer/interviews` | Shortlisted (Join room / Reschedule), **Upcoming** (Join room, Copy link), **History** (result + View report), Cancelled. `?tab=upcoming|history|cancelled` deep links. |
| Employer | room | Lobby, call, scorecard from their setup (auto-saves), Questions checklist + **Jenny AI follow-ups**, candidate profile, chat + files, "End interview for everyone". |
| Employer | `/employer/interviews/{id}/report` | Full report: Jenny AI summary + suggested rating/decision, scorecard and private notes, questions covered, chat, files, activity. |
| Candidate | `/candidate/interview/invite` | Latest real session: date/time, **Join interview** (platform room), countdown, interview history with "View summary". |
| Candidate | room / `/candidate/interview/{id}/report` | Lobby, call, agenda, Jenny tips, chat + files. Report shows timeline, files and chat only (no scorecard, no AI assessment). |
| Admin | `/admin/interviews` | KPIs, **live interview map**, sessions table (filters, View, Mark ready, End room, Reopen, Cancel), **Schedule interview**, video settings, recent activity (last 60 events, filter chips All / Joins / Screens & files / Room & admin, 6 per page) with badges + location + device. |
| Admin | `/admin/interviews/{id}` | Brief: timeline with approximate locations, scores, decision, AI summary, files. No chat content and no private notes. |
| Admin | `/admin` (overview) | Second map card "Live interviews" next to "Live visitors". |

## 3. Timing rules (admin settings, `platform_settings` keys `video.*`)

| Setting | Default | Effect |
|---|---|---|
| `default_duration_min` | 30 | Interview length when the employer does not choose one. |
| `grace_min` | 1 | Extra wrap-up time after the length, announced to both people. |
| `join_early_min` | 10 | The room opens this many minutes before `scheduled_at`. Earlier visitors see "Room opens in ..." and Join is disabled until then. |
| `video_enabled`, `allow_screenshare`, `allow_chat`, `allow_files` | on | Feature switches. |

In the call (both people): timer counts down; toast at **5 minutes** left; banner + toast at **1 minute** left; at 0 the timer turns red and shows the **extra minute** ("Time is up, the room closes in 00:52"); at the end of the grace minute the call closes for both. The server also sets the Daily room expiry to the same moment, so nobody can stay in. The timer starts at the first join (`interview_rooms.started_at`); a reopened room starts a fresh timer.

## 4. Inside the room

- **Presenter view**: whoever shares their screen sees a "You are presenting" card instead of their own screen (no mirror effect), with "Preview my screen" to check what the other person sees; the other person sees the screen large with the people in a filmstrip.
- **Shared screen toolbar** (top-right of the shared screen, both people): **Hide people** (drops the filmstrip so the screen uses the full height), **Minimize** (the screen becomes a floating window above the controls and the cameras take the stage again; the presenter gets a small "You are presenting · Stop" pill), **Expand**, and **Full screen**. Double-click the shared screen to toggle full screen (or expand it when minimized).
- **Full screen** (toolbar or the control bar button): the whole room goes full screen (native Fullscreen API, or a fixed overlay on browsers without it, e.g. iPhone). Entering full screen hides the side panel and leaving restores it; Esc works too. The view resets when sharing stops or the call ends.
- **Lobby**: camera and microphone selects use the shared `.vr-select-wrap` (device icon, inset chevron, hover/focus states).
- **Chat** is sent over Daily app-messages and saved (`interview_chat`) for the history page.
- **Files**: paperclip or drag-and-drop in the chat. PDF, DOC/DOCX, TXT, PNG, JPG, WEBP up to 10MB, no video. Checked by extension, size, MIME type and file signature (`UploadStorage` kind `interview_file`), stored privately in `storage/uploads/interview_file/` and served only through `/{role}/.../room/{id}/files/{fileId}` or `/admin/interviews/{id}/files/{fileId}`.
- **Activity** (`interview_room_events`): scheduled, joined (with masked IP, city/country via MaxMind, device), left, screen share start/stop, file shared, room ended/reopened, status changes, settings changes.

## 5. Jenny AI (Gemini)

- `GeminiClient::json()` tries `GEMINI_MODEL`, then `GEMINI_FALLBACK_MODEL`, then `gemini-flash-lite-latest` (always available), with short timeouts for interactive calls (8s per model) and 15s for summaries.
- Uses: question suggestions while preparing (`POST /employer/interviews/suggest-questions`), follow-ups during the call (`POST .../room/{id}/suggest`), and the post-interview summary (`POST /employer/interviews/{id}/report/ai`, `POST /admin/interviews/{id}/report/ai`).
- Summary = overview, highlights, concerns, suggested rating (1-5), suggested decision (one of the employer's options), confidence. If AI is unavailable, a rule-based summary is built from attendance, questions asked and ratings, and labelled as such. The employer always makes the decision.
- Not implemented: transcription of the spoken conversation (needs Daily's paid transcription add-on).

## 6. Location (MaxMind GeoLite2 web service)

- `GeoService::lookup()` uses `MAXMIND_ACCOUNT_ID` / `MAXMIND_LICENSE_KEY`; results cached 7 days in `geo_cache`. Local/private IPs use the "me" lookup (the server's public IP), so local testing shows your real city.
- Only masked IPs (`102.89.41.xxx`) are stored or shown. Locations are city-level and approximate.
- Live interview map feed: `GET /admin/api/interviews/live-map` (same shape as `/admin/api/live-sessions`, rendered by `frontend/shared/components/admin-live-map.php` with `$liveMap` options).

## 7. Files

| Part | File |
|---|---|
| Sessions, rooms, timing, setup, scorecards, files, chat, reports, map feed | `backend/Services/VideoInterviewService.php` |
| Gemini client | `backend/Services/GeminiClient.php` |
| IP location, masking, device labels | `backend/Services/GeoService.php` |
| Private uploads | `backend/Services/UploadStorage.php` |
| In-call actions + history pages (role from URL) | `backend/Controllers/InterviewController.php` |
| Join/leave/end, schedule | `EmployerController`, `CandidateController` |
| Admin console, detail, schedule, status, map | `AdminController` |
| Room UI (lobby, call, timing, presenter view, chat, files) | `frontend/shared/components/video-room.php` |
| Employer drawer (scorecard, questions + Jenny, profile) | `frontend/employer/interviews/partials/room-drawer.php` |
| Candidate drawer (agenda, tips) | `frontend/candidate/interview/partials/room-drawer.php` |
| Report body (all audiences) | `frontend/shared/components/interview-report.php` |
| Admin console / detail | `frontend/admin/interviews/index.php`, `show.php` |
| Live map component | `frontend/shared/components/admin-live-map.php` |
| MySQL tables | `database/schema_mysql.sql` sections 6, 7, 7b, 7c |
| Tests | `tests/Unit/VideoInterviewTest.php`, `tests/Unit/CommunicationFeaturesTest.php` |

## 8. Setup

1. `.env`: `DAILY_API_KEY` (Daily dashboard > Developers). The Daily account needs a payment method on file (free tier still applies), otherwise joins fail with `account-missing-payment-method`.
2. `.env`: `MAXMIND_ACCOUNT_ID`, `MAXMIND_LICENSE_KEY` for locations; `GEMINI_API_KEY` for Jenny. Recommended: `GEMINI_FALLBACK_MODEL="gemini-flash-lite-latest"` (`gemini-1.5-flash` is retired).
3. Production needs HTTPS for camera/microphone and outbound HTTPS from PHP (cURL) to `api.daily.co`, `generativelanguage.googleapis.com` and `geolite.info`.
4. MySQL: run `database/schema_mysql.sql` (new installs) or the `ALTER TABLE` lines at the end of section 7c (existing databases).

## 9. Known gaps (backend phase)

- Ownership checks: interview pages and endpoints do not yet verify that the signed-in employer/candidate belongs to interview `{id}`; add this in `InterviewController`, `joinInterviewRoom()` and the report actions before launch.
- CSRF tokens on the POST endpoints.
- Admin scheduling uses a demo directory of candidates/employers (`AdminController::schedulablePeople()`).
- Speech transcription and call recording are not implemented.
