# CV Autofill, Applications and the Employer Candidate View

> Updated 2026-11-11. Source of truth for how a candidate's CV is read, how it fills forms, how applications are stored, and what employers see on `/employer/candidates/{id}`.

## 1. Reading a CV (`backend/Services/CvService.php`)

- `CvService::parse($uploadMeta)`: Gemini reads the stored file itself (PDF sent inline; DOCX as extracted text) and returns name, email, phone, location, headline, latest job title and company, a short summary, work experience (title, company, location, start, end, highlights), education, skills and links.
- Without Gemini, or if it fails, the rule reader still finds email, phone (10-15 digits, so date ranges are ignored), name (skipping "Curriculum Vitae" and headline words) and LinkedIn/GitHub links. `source` is `gemini`, `rules` or `none`.
- The AI never invents details, and summaries refer to the person by name or "they" (no assumed gender).

The parsed CV is kept per candidate in `candidate_cv_profiles` (created on first use) and mirrored in `$_SESSION['profile_cv']`.

## 2. When a CV is read

| Where | Endpoint | Then |
|---|---|---|
| Signup step 5 | `POST /register/student/parse-resume` | Badge shows "Reading with AI", then "Parsed by AI" + what was found. Completing signup saves the CV and reuses this parse. |
| Profile resume upload | `POST /candidate/profile/resume` | Parsed and saved for the candidate. |
| Application | `POST /apply/{slug}/parse-resume` | Fills the Personal Details step live (see 3). Submitting reuses this parse. |
| CV Analyzer | `POST /candidate/ai-assistant/cv-analyze` | Gemini reads the uploaded PDF (or the saved resume) for the ATS review. |

Live parses store a temporary copy that is deleted when the form is submitted (`CvService::parseUpload()` / `takeSessionParse()`).

## 3. Application autofill (`/apply/{slug}`)

- On load, Personal Details are prefilled by `CvService::prefill()`: account name/email/phone first, then the saved CV (name, email, phone, latest job title and company).
- One status line under the step subtitle (styled inline, same design as before) shows: "Have a CV? We can fill these in for you" with Upload CV, "Reading your CV...", "Filled in from your CV (file)" with Upload a different CV, or "We could not read details from this CV".
- Uploading a CV (from the banner or the Resume step, same file input) fills **only fields the candidate has not changed**. Relevant experience answers and employer questions stay manual.
- Name and email are editable and required. Resumes are PDF or DOCX (RTF removed: it cannot be verified safely).

## 4. Submitting (`CandidateController::submitApplication`)

The resume is validated and stored privately (`UploadStorage`, kind `resume`). It becomes the candidate's current CV, and the parsed CV is saved. The cover-letter file and portfolio attachment are also stored privately.

`ApplicationService::submit()` saves a row in `application_submissions` with:

- contact details
- shared job and company
- salary expectation
- cover letter text and file
- employer question answers
- links
- the three file references
- `ai_summary` / `ai_source`: a 3-4 sentence profile summary for the hiring team, written by Gemini from the CV and answers. A rule-based summary is used if Gemini is unavailable.

## 5. Employer candidate page (`/employer/candidates/{id}`)

`EmployerController::withSubmittedApplication()` overlays the latest submission and parsed CV on the profile. Sample data is used only when the candidate has neither.

| Card | Source |
|---|---|
| Application Answers | Submission answers |
| Relevant Experience Details | Job title, company, salary from the application |
| Candidate Statement / Cover Letter | Typed letter, or the attached file, with a Submitted / Not submitted badge |
| Professional Summary | Jenny AI summary ("Written by Jenny AI" pill) or the CV summary ("From CV") |
| Work Experience | Every role from the CV with dates and highlights ("From CV") |
| Application Details | Applied for, submitted date, application number and status |
| Documents & Links | Resume / cover letter / attachment downloads via `GET /employer/candidates/{id}/files/{resume|cover_letter|portfolio}` (private stream), portfolio, Figma, LinkedIn, GitHub |
| Contact, Education, Skills | Application contact details; CV education and skills |

## 6. Gaps (MySQL phase)

- Candidate identity is the demo candidate #1 (`CvService::currentCandidateId()`); `application_submissions` merges into `applications` when accounts move to MySQL.
- Employer ownership checks on the candidate page and file downloads (any employer session can open them today).
- Employers see the latest submission per candidate; a per-internship applications list comes with the MySQL `applications` table.

Tests: `tests/Unit/BillingAndApplicationsTest.php`.

## 7. Saved resume, Word previews, Jenny AI letter, pipeline stage (2026-11-11)

- **Saved resume**: the Resume step shows the resume already on the profile (signup or profile page) in the same "Uploaded File" + Document Visualizer layout, labelled "Your saved resume". Submitting sends `use_saved_resume=1`; the server attaches its own copy (`UploadStorage::duplicate`) so a later profile change never alters what the employer received. Uploading a new file replaces it for this application and becomes the profile resume.
- **Word (DOCX) preview**: same method as the admin document viewer (docx-preview + JSZip), shared in `public/assets/js/ic-docx-preview.js` (`window.icRenderDocx(fileOrUrl, host, name)`). Used in the application's visualizer and preview modal, and on the employer page.
- **Statement of Intent**: "Write it with Jenny AI" bar above the textarea. Tone + Generate/Rewrite with AI (`POST /candidate/ai-assistant/cover-letter/generate` with company, role, `max_chars: 1900`), "Use my saved letter" (latest letter from the dashboard AI Cover Letter tool, `$_SESSION['ai_last_cover_letter']`), and Undo. The letter stays editable.
- **Employer documents**: resume, cover-letter file and attachment names open a viewer (PDF, Word, images; Download for anything else).
- **Cover letter sent as a file only**: the card previews the file in place (no empty typed-letter body), with Open full view and Download.
- **Pipeline stage**: Under Review / Shortlisted / Interviewing / Offer Extended, plus Shortlist and Reject buttons, are saved via `POST /employer/candidates/{id}/stage` (`ApplicationService::setStage`). The candidate gets a notification. Sample profiles without a submission change only on screen.
