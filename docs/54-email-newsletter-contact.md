# E-mail, newsletter, contact, legal pages, receipt

> Added 2026-11-12. Code: `MailService`, `NewsletterService`, `ContactService`, `NewsletterController`, `AdminCommunicationsController`, `frontend/emails/`. Admin: **Communications** (`/admin/communications`).

## 1. Sending e-mail

**SMTP** (testing phase): the cPanel mailbox `internconnect@weburea.com`.

```
MAIL_HOST=mail.weburea.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=internconnect@weburea.com
MAIL_PASSWORD=            <- add the mailbox password here, in .env only
MAIL_FROM_ADDRESS=internconnect@weburea.com
MAIL_FROM_NAME=InternConnect
MAIL_REPLY_TO=internconnect@weburea.com
MAIL_ADMIN_ADDRESS=internconnect@weburea.com   (receives contact-form alerts)
```

When you move to your own domain, change only these values.

**Queue first.** Every message is stored in `mail_queue`, then sent with PHPMailer:
- Transactional mail (confirmations, contact replies) is sent at once.
- Newsletter campaigns are sent in batches by the cron.
- Failures are retried with back-off, up to 5 attempts.
- Without `MAIL_PASSWORD`, nothing is sent and nothing is lost: messages wait in the queue.

**Cron** (cPanel > Cron Jobs):

```
*/5 * * * *  php /home/USER/public_html/bin/cron.php mail
15 2 * * *   php /home/USER/public_html/bin/cron.php daily
```

**Test.** Admin > Communications > Mail log > **Send test e-mail**. The log shows each message's status, attempts, last error and opens.

**Templates.**
- `frontend/emails/layout.php`: branded header and footer, table layout, inline styles.
- `message.php`: general message.
- `campaign.php`: newsletter issue.
- Images come from Cloudinary (folder "InternConnect"), delivered as PNG/JPG because Gmail blocks SVG: logo, "Celebrating success" banner, devices mockup, Jenny poses.
- Links use `APP_URL`, so cron mail never points at localhost.
- Open tracking: a 1×1 pixel at `/m/o/{token}.gif`.

## 2. Newsletter

**Subscribing** (home page "Ready to curate your future?" form):
1. The visitor enters an e-mail; the request is rate limited with a honeypot against bots.
2. A confirmation e-mail is sent (double opt-in).
3. `/newsletter/confirm/{token}` confirms the subscription and sends a welcome e-mail.

**Unsubscribing**:
- Every newsletter e-mail has an unsubscribe link: `/newsletter/unsubscribe/{token}` shows a confirm page.
- It also carries `List-Unsubscribe` one-click headers (RFC 8058, `/newsletter/one-click/{token}`), which Gmail and Yahoo require for bulk mail.
- Unsubscribing cancels any queued newsletter mail for that address.

**Admin**:
- **Compose**: plain-text body (`## ` sub-heading, `- ` bullets), header image, optional "Tip from Jenny", button. Then live preview, send yourself a test, and send to all confirmed subscribers.
- A campaign can only be sent once.
- **Campaigns**: recipients, delivered, opened, failed.
- **Subscribers**: search, filter, remove, export CSV (spreadsheet-formula safe).

## 3. Contact page

- `POST /contact` stores the message in `contact_messages` with a reference such as `IC-3F9A1C`.
- The sender gets an acknowledgement e-mail, and the team is alerted at `MAIL_ADMIN_ADDRESS`.
- It is rate limited (5 per hour per IP) and has a honeypot field.
- Admin > Communications > **Contact inbox**: read, change the status (new, open, replied, closed) and reply by e-mail. The reply is stored with the message.
- This also fixes the old toast bug: the page used `window.$dispatch`, which doesn't exist.

The addresses shown on the Contact page (`support@internconnect.ng` and others) belong to the future domain. Until then, replies come from the weburea mailbox.

## 4. Legal pages, cookies, footer

**Privacy policy** (rewritten), aligned to NDPA 2023 and GDPR principles. It covers:
- Gemini (not DeepSeek) and the full provider list: Paystack, Google Gemini, Daily, MaxMind, Cloudinary, hosting/mail.
- Training partners (only a reference code is shared).
- Newsletter and open tracking, approximate location, retention periods, and how to complain to the NDPC.
- The unprovable "NDPR & GDPR Certified" claim is removed.

**Cookie policy**: a real table of what is set:
- `PHPSESSID`, `admin_trusted_device` and `ic_consent` (all essential);
- `ic_vid` (analytics, only after consent);
- browser storage keys.

**Consent banner** (public and dashboard pages):
- Choices are "Essential only" or "Accept all"; the cookie page has the full toggles.
- The choice is stored by `public/assets/js/ic-consent.js` in the `ic_consent` cookie, which the server also reads.

**Terms and Refund**:
- Pro and Premium plans; no automatic renewal (expiry reminder instead).
- Partner grants; fair use of AI; an AI disclaimer.
- Refund cooling-off now counts AI features used.

**Footer**:
- Adds Pricing and How It Works.
- "Ask Jenny" now opens Jenny.
- Server-side year, and a cookie settings link.
- The dashboard footer's broken `/support` link now goes to `/help`.

Have a Nigerian data-protection lawyer review the legal pages before launch.

## 5. Payment receipt

`/candidate/billing/receipt/{reference}` (and the employer equivalent) is a ticket-style receipt:
- InternConnect logo with a status badge.
- "Thank you!" for paid; "Payment declined", "cancelled", "pending" or "refunded" otherwise.
- Perforated notches and a dashed separator.
- Ticket ID, amount, date & time, plan, billed to.
- A card box (brand mark, "Mastercard ending in 4567", expiry, bank).
- A Code128 barcode of the reference, scannable (`App\Services\Barcode`).
- A torn bottom edge. Responsive down to 360px.

**Downloads**:
- **PDF**: a vector PDF drawn with jsPDF (real text, crisp barcode).
- **Image**: a PNG via html2canvas, served locally from `public/assets/js/`.

**Data and links**:
- New nullable columns `card_exp` and `card_bank` are filled from Paystack's authorization.
- Billing history links a receipt for declined payments too, next to "Try again".

## 6. Offer letter PDF

On the candidate offer page, **Download PDF** builds a real A4 letter with jsPDF:
- letterhead, the letter text exactly as shown on the page;
- an offer summary (position, location, type, duration, start date, respond-by date), responsibilities;
- a footer with page numbers.

The demo offer data now matches the Applications list (Stellar Systems, Data Analyst Intern, future response date).

## Campaign types, images and Jenny drafts (2026-11-13)

Admin > Communications > Compose builds a campaign with a live preview (iframe, `POST /admin/communications/preview-draft`).

| Type | Layout |
|---|---|
| General | Header image, text, optional body image, button |
| Marketing | Feature tiles and an indigo call-to-action band |
| Event | "Event invitation" badge and a navy date box (date, time, place or link) |
| For interns | "Picked for you" internship cards chosen from live listings |
| For employers | Navy ticks and a navy button |

- **Audience:** everyone, students or employers. Subscribers have an `audience` column (`employer` when they signed up from an employer source, otherwise `student`). Admins can change a subscriber's group on the Subscribers tab. Sending queues only the chosen group.
- **Images:** header and body images upload to Cloudinary `InternConnect/newsletter` (`POST /admin/communications/images`; PNG, JPG, WebP or GIF up to 5 MB), delivered as `f_jpg/f_png,q_auto,w_1200`. Pasted links must be https.
- **Write with Jenny:** `POST /admin/communications/jenny-draft` takes a brief, type, audience and tone, and returns subject, preheader, title, body, button, a Jenny tip and suggestions. Without Gemini, a template draft is used.
- New columns (additive, migration `2026_11_13_000004`): `newsletter_campaigns.type, audience, hero_url, body_image_url, extra`; `newsletter_subscribers.audience`.

**Cloudinary folder rule (2026-11-13):** every uploaded image goes to Cloudinary under `InternConnect/images/<section>` (for example `InternConnect/images/newsletter`, `InternConnect/images/testimonials`). Videos go to `InternConnect/videos/<section>`. Private documents (CVs, business registration) stay in `storage/uploads`.

## E-mail design and account e-mails (2026-11-13)

- **Layout** (`frontend/emails/layout.php`): one rounded card with a banner (the banner carries the logo), an optional badge pill (`badge`), the body, then a navy footer with the brand band, social links (`SOCIAL_LINKEDIN`, `SOCIAL_X`, `SOCIAL_INSTAGRAM`, `SOCIAL_FACEBOOK` in `.env`; generic links until set), Find internships, Support, Privacy and the unsubscribe line.
- **Banners** in Cloudinary `InternConnect/images/emails`: `email_welcome`, `email_verify`, `email_reset`, `email_updates` (1200x400) and `email_footer` (1200x96). `MailService::image('email_reset')` etc.
- **Code e-mail** (`frontend/emails/code.php`): heading, greeting, code box, expiry, ignore-if-not-you note.
- **AuthCodeService**: 6-digit codes, hashed (HMAC with APP_KEY), 10 minutes, 5 tries, one per e-mail and purpose (`auth_codes` table). `resetEmail()`, `verifyEmail()`, `welcomeEmail()`.
- **Forgot password** now e-mails a real reset code (5 requests per 15 minutes per address; the same answer whether or not the address exists). The verify-code page checks the code on the server and "Resend" sends a new one. Setting the new password still needs real accounts (MySQL users), as before.
- **Welcome e-mail** is queued at the end of student sign-up.

**Campaign design options (2026-11-13):** the header picker now offers only designed banners (Updates, Welcome, Event invitation, New internships, For employers, Training and certificates, Pro and Premium offer) in Cloudinary `InternConnect/images/emails`; the older photo headers were removed from the picker but stay in Cloudinary and still render for old campaigns. Each campaign also picks a **footer design** (Circles, Logo pattern, Waves, Dots and logo) and a **button colour** (Layout default, Indigo, Navy, Violet, Ocean, Sunset), saved in `newsletter_campaigns.extra`. Button text is always white, with dark-mode rules (`prefers-color-scheme`, Outlook `data-ogsc`) that keep it white.
