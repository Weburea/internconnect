# Jenny AI chat on Google Gemini

> Added 2026-11-12. Code: `backend/Services/JennyService.php`, `backend/Controllers/JennyController.php`, `frontend/shared/components/chatbot/jenny-ai.php`, `frontend/candidate/ai-assistant/chat.php`, `frontend/admin/ai/chat.php`.

## 1. Where Jenny runs

| Channel | Where | Who | Limits |
|---|---|---|---|
| `widget` | Floating chat on public and dashboard pages | Visitors, candidates, employers | Jenny Coins (section 9). Visitors use a Free wallet per IP; admins are not charged |
| `assistant` | `/candidate/ai-assistant/chat` (the AI Assistant prompt box opens it) | Candidates | New chats: `assistant_chats` (Free 0, Pro 5, Premium 15 a day, editable), max 40 messages per chat, plus Jenny Coins |
| `admin` | Admin > Jenny AI > Gemini Chat | Staff | Unlimited |

Every channel also has a burst limit of 12 messages per minute.

## 2. How an answer is made

The Gemini prompt is grounded in real data:
- the internship listings, with Premium pay hidden from viewers who can't see it;
- the plans and current limits (`PlanService`);
- the training partners (`ElearningService`);
- the site pages Jenny may link to;
- the last 12 messages of the conversation;
- any attached file;
- for staff only, **live platform numbers**: applications, payments, subscriptions, newsletter, inbox, mail queue, grants and Jenny stats.

**Reply format.** Gemini returns JSON `{reply, links[], listing_ids[]}`:
- Links are limited to site paths.
- Listing IDs become internship cards; locked Premium cards say "Pay shown on Premium".
- Rules in the prompt: no emojis, no guessing gender, no invented listings or prices.

**Speed and fallback.**
- Chat uses one model only: `GEMINI_MODEL` (`gemini-flash-lite-latest`, currently `gemini-3.5-flash-lite`), with a 22-second limit and one retry on overload.
- Typical reply time is 7–10 seconds.
- `gemini-flash-latest` was timing out at over 40 seconds during testing, so it is not used for chat.
- If Gemini fails, a rule-based answer is used and the chat never breaks. Admin sees "answered without Gemini".

**Attachments** (signed-in users; visitors are asked to sign in):
- PDF, Word, text and images up to 8 MB, stored privately (`storage/uploads/jenny`).
- PDF and images go to Gemini directly; Word and text are converted to text first.
- Files open only for their owner or staff (`/jenny/files/{conversation}/{message}`).

**Voice** (Web Speech API, where the browser supports it):
- Microphone button for speech-to-text (en-NG).
- Speaker toggle in the widget header reads replies aloud.
- `Permissions-Policy` allows the microphone and geolocation for this site only.

## 3. Widget changes (same design)

- Scripted demo replies removed; real chat with formatted replies (bold, bullets), link chips and internship cards.
- Persona switch redesigned as a segmented control (**Job seeker | Employer**) plus **New chat**.
- One set of events opens Jenny everywhere: `open-jenny-ai`, `open-jenny-chat` (with `detail.prompt`, used by the company page and Find Jobs) and `open-support-chat`.
- The conversation survives page changes in the same tab (sessionStorage).
- No native title tooltips.

## 4. Search assist (Find Jobs)

"Search with Jenny" bar above the results (`POST /jenny/search`):
- A sentence such as "remote design internship in Lagos paying over 250k" becomes filters: keywords, city, category, work mode, minimum stipend.
- Pro: 10 a day; Premium: unlimited.
- Free users and visitors get an upgrade or sign-in message.

## 5. Admin console

- The Gemini Chat page keeps its design, now with real data:
  - **My chats** (the admin's own conversations) and **User chats** (every widget and assistant conversation, with city, read-only).
  - The telemetry rail shows chats, messages, visitors and the Gemini share for the last 24 hours.
  - The model badge shows the real model.
- Conversation locations also feed the visitor map (docs/56).

## 6. Data

- `ai_conversations`: public ID, channel, persona, owner hash, account, title, page, city, country, latitude/longitude, message count.
- `ai_messages`: role, content, attachment metadata, links, source (`gemini` / `rules`).

## 9. Coins, global framing, voice (2026-11-13)

- Usage is billed in Jenny Coins (docs/53, "Jenny Coins"). Each answer returns `coins` (balance, allowance, refill time), and the widget chip and the AI Assistant rail update live.
- Jenny's prompt describes InternConnect as a **global** platform. The company was founded in Lagos, but Jenny never assumes the user's country or currency.
- The read-aloud (speaker) button was removed because browser speech output was unreliable. Voice **input** (microphone) is kept.
- AI Assistant chats can be **pinned** (`ai_conversations.pinned`, additive; pinned first) and **deleted** with a second-click check: `POST /candidate/ai-assistant/chats/{id}/pin` and `/delete`, owner only.
- The coin balance shows top right on the AI Assistant pages (candidate and employer, `frontend/shared/components/jenny-coin-pill.php`), in the chat header and in the sidebar plan card.
