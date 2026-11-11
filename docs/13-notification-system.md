# Notification System

## Purpose

Keep users informed of important platform activities.

---

## Notification Channels

- In-App
- Email

Future

- SMS
- Push Notifications

---

## Candidate Notifications

- Application submitted
- Application reviewed
- Interview invitation
- Interview reminder
- Application accepted
- Application rejected

---

## Employer Notifications

- New application received
- Candidate accepted interview
- Payment successful
- Subscription expiring

---

## Admin Notifications

- New employer registration
- Payment issue
- Security alert
- System error

---

## Database

notifications

Fields

- id
- user_id
- title
- message
- type
- is_read
- created_at


---

## Notification Center UI (Candidate + Employer)

> Updated 2026-11-11. This section is the source of truth for the notifications page layout.
> AI agents and contributors: do **not** rebuild per-portal notification pages. Extend the shared component instead.

### Files

| Role | File |
|---|---|
| Shared inbox component (markup, styles, Alpine logic) | `frontend/shared/components/notification-center.php` |
| Candidate page (thin wrapper: sidebar + top bar + component) | `frontend/candidate/notifications/index.php` |
| Employer page (thin wrapper) | `frontend/employer/notifications/index.php` |
| Data shaping + categories | `CommunicationService::presentNotifications()` and `CommunicationService::notificationCategories()` in `backend/Services/CommunicationService.php` |
| Controllers | `CandidateController::notifications()`, `EmployerController::notifications()` (+ `markNotificationRead`, `markAllNotificationsRead`, `deleteNotification`) |

### Layout

1. **Header**: page title "Notifications", one-line subtitle, `Preferences` (links to `/{role}/profile#notification-preferences`) and primary `Mark all as read` (disabled when nothing is unread).
2. **Category rail** (left, 240px, sticky on desktop):
   - Inbox: `All`, `Unread`.
   - Categories (per role, from `notificationCategories()`):
     - Candidate: Interviews, Offers, Applications, Messages, AI & Career, Account & System.
     - Employer: Applications, Interviews, Messages, AI Matches, Billing & System.
   - Count badge shows unread count (indigo) when a category has unread items, otherwise the total (grey).
3. **Inbox panel** (right):
   - Toolbar: search (title + body, debounced) and an "Active filter · N items" summary.
   - Rows grouped by date: **Today, Yesterday, Earlier this week, Older** (`group` field from the server).
   - Compact row: tinted category icon, title (bold when unread), relative time (`time_label`, full date in `aria-label`), one-line body (two lines on phones), category tag, inline action link (`action_label` -> `action_url`).
   - Unread rows: soft indigo background + small dot. No coloured side borders (project rule).
   - Row actions: `Mark as read` and `Delete` (visible on hover/focus; always visible on touch devices).
   - **Pagination, not infinite scroll**: 15 per page (`perPage`), "Showing 1-15 of N", prev/next + numbered pages with ellipsis. 1,000 notifications = 67 short pages, never one endless list.
4. **Delete** uses a 5-second **Undo** bar (no native `confirm()`); the server delete is sent when the window closes or another delete happens.
5. Deep link to a filter: `?filter=unread` or `?filter={categoryKey}` (URL updates as the user switches filters).

### Responsive behaviour

| Width | Behaviour |
|---|---|
| > 1024px | Rail + panel side by side; rail sticky. |
| <= 1024px (iPad) | Rail turns into a horizontal, scrollable chip bar (`.no-scrollbar`) above the panel. |
| <= 640px (phone) | Header buttons stretch full width, summary hidden, titles and bodies wrap to two lines, row actions stack vertically, pager centred. Zero horizontal overflow at 360/390/440px. |

### Item shape (output of `presentNotifications()`)

```php
[
  'id'           => 123,
  'type'         => 'interview',          // raw type stored in in_app_notifications.type
  'category'     => 'interviews',         // rail category key
  'title'        => 'Interview Invitation from Nexura Tech Labs',
  'body'         => 'You have been invited ...',
  'timestamp'    => 1793000000,           // unix seconds, app timezone
  'time_label'   => '15m ago',            // Just now / Xm ago / Xh ago / Yesterday / Mon / Oct 3
  'full_time'    => 'Tue, Nov 11, 2026 at 3:00 PM',
  'group'        => 'today',              // today | yesterday | week | earlier
  'is_read'      => false,
  'action_url'   => '/candidate/interview/invite',
  'action_label' => 'View interview',
]
```

Items are sorted newest first. `created_at` is written explicitly in the app timezone by `createNotification()` (SQLite `CURRENT_TIMESTAMP` is UTC and made fresh items read as "1h ago").

### Adding a new notification type

1. Call `CommunicationService::createNotification($role, $userId, $title, $message, $type, $actionUrl)`.
2. Map the `$type` to a category by adding it to that category's `types` list in `notificationCategories()` (unknown types fall into `system`).
3. Optionally add a default action label in `presentNotifications()`.
4. No view changes are needed.

### Endpoints

| Method | Route | Body |
|---|---|---|
| POST | `/candidate/notifications/mark-read`, `/employer/notifications/mark-read` | `{ "id": 123 }` |
| POST | `/candidate/notifications/mark-all-read`, `/employer/notifications/mark-all-read` | none |
| POST | `/candidate/notifications/delete`, `/employer/notifications/delete` | `{ "id": 123 }` (scoped to role + user) |

### Backend TODO (when real accounts land)

- Paginate on the server (cursor on `created_at, id`) and load pages via JSON once a user can exceed ~2,000 notifications; the component already pages client-side, so only the data source changes.
- Scope `markNotificationRead` by `user_role` and `user_id` (delete already is).
- Add CSRF tokens to the three POST endpoints.
