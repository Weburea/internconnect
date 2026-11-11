# Jenny AI Match Intelligence & Talent Detection Architecture (`md/jenny_ai.md`)

This specification documents the operational logic, algorithmic model, and UI integration of **Jenny AI** within the **Employer Posted Internships** and **Candidate Evaluation** workflows on InternConnect.

---

## 1. Overview & Core Purpose

Within the Employer Portal (`/employer/internships`), Jenny AI acts as an autonomous **Talent Intelligence & Candidate Detection Engine**. Rather than requiring hiring managers and recruiters to manually sift through hundreds of incoming applications, Jenny continuously analyzes, scores, and clusters applicants against the specific requirements, screening questions, and competencies configured for each posted role.

---

## 2. High-Impact AI Functionalities for Posted Internships

### A. Real-Time Applicant Quality Detection & Proactive Insights
- **Automated Ingestion**: Whenever candidates apply to a posted role, Jenny's background analysis engine parses candidate profiles, academic history, resumes, and screening question responses.
- **Dynamic Headline Generation**: Instead of generic static text, the banner dynamically reflects live candidate intake metrics:
  - *"UI Design Intern role is receiving high-quality applicants"*
  - *"5 candidates exceed 90% skill match for Senior Software Engineer Intern"*
  - *"New top applicant: Sarah Peters (95% match) just applied"*
- **Proactive Notification Pill**: Positioned cleanly with the Jenny waving mascot icon (`jenny_ai_hi.png`), communicating actionable insights with zero emojis.

### B. Multi-Dimensional Match Scoring Algorithm (0–100%)
Jenny computes a weighted composite match score across four distinct vectors:
1. **Core Skills & Tools Match (35% weight)**: Semantic similarity between required skills (e.g., Figma, UI Systems, Prototyping) and candidate verified skills and project tags.
2. **Academic & Background Alignment (25% weight)**: Relevance of university major, degree level, expected graduation year, and GPA.
3. **Screening Questions Evaluation (25% weight)**: Semantic analysis of candidate responses to custom screening questions (e.g., depth of design process explanation, motivation, portfolio quality).
4. **Verified Experience & Artifacts (15% weight)**: Completeness and quality of attached portfolio links, live URLs, GitHub repositories, and previous internships.

### C. Replacing "Apply Now" with Intelligent Recruiter Actions
- **The Problem**: The banner previously contained an "Apply Now" button, which is candidate-facing copy that makes no sense on an employer management dashboard.
- **The Rectification**: The CTA is replaced with contextual employer actions:
  - **Primary CTA**: `"View Top Matches →"` or `"Review 5 Matched Candidates →"`
  - **Action Target**: Routes directly to `/employer/candidates?internship_id={id}&sort=ai_match&min_score=90`.
  - **Behavior**: Opens the candidate roster pre-filtered to the high-scoring cohort identified by Jenny AI, allowing one-click review and bulk shortlisting.

### D. Talent Pipeline Intake Velocity & Health Metrics
Jenny monitors role health indicators displayed in the internship cards:
- **Intake Velocity**: Monitors application influx rate (e.g., *"142 applicants in 6 days — High demand"*).
- **Candidate Quality Ratio**: Computes the percentage of applicants meeting the minimum 70% threshold.
- **Saturation Alert**: Suggests closing or archiving roles once the target shortlist count (e.g., 20+ qualified candidates) has been achieved, preventing recruiter overwhelm.

---

## 3. Jenny AI Integration on the Candidate Detail Review Page (`/employer/candidates/{id}`)

### A. Intelligent Candidate Scorecard & Circular Radial Gauge
- Replaces unstyled boxy percentage borders with a precision SVG circular gauge:
  - Dynamic circumference stroke calculation (`stroke-dasharray` / `stroke-dashoffset`).
  - Tiered color coding:
    - **90% – 100%**: Emerald Green (`#059669`) — "Top Match"
    - **75% – 89%**: Indigo Blue (`#4F48E6`) — "Strong Contender"
    - **50% – 74%**: Amber (`#D97706`) — "Moderate Match"
    - **< 50%**: Slate Grey (`#64748B`) — "Low Relevance"
  - Generous internal padding ensuring the percentage value and `"MATCH SCORE"` label never collide with the radial track.

### B. Semantic Screening & Relevant Experience Parsing
- Jenny correlates candidate answers directly with the employer's configured Step 1 (Relevant Experience) and Step 3 (Screening Questions):
  - Automatically highlights key competencies identified in text answers (e.g., *"accessible enterprise software"*, *"design-to-code handoff"*).
  - Verifies presence of clickable portfolio and GitHub links, flagging live prototypes for instant recruiter access.

### C. Decision Flow Rationalization: Bottom Review Action Bar
- **UX Logic**: A recruiter must never be encouraged to reject, shortlist, or schedule a candidate before reviewing their answers, summary, experience, and education.
- **Action Placement**: The decision action group (**Reject**, **Message Recruiter**, **Shortlist**, **Schedule Interview**) is positioned at the **bottom of the candidate review page** as an explicit review completion action bar, with clear visual hierarchy and confirmation states.

---

## 4. Architectural Data Schema (Jenny AI Insights Payload)

```json
{
  "role_id": 2,
  "role_title": "UI Design Intern",
  "status": "ACTIVE",
  "total_applicants": 86,
  "ai_insights": {
    "headline": "UI Design Intern role is receiving high-quality applicants",
    "subtext": "Our algorithm suggests reviewing the latest 5 candidates who match 95% of your skill requirements.",
    "cta_label": "View Top Matches",
    "cta_url": "/employer/candidates?internship_id=2&sort=match_score",
    "top_candidates_count": 5,
    "average_match_score": 88,
    "top_match_candidate": {
      "id": 1,
      "name": "Sarah Peters",
      "score": 95,
      "key_highlight": "Extensive design system and accessibility thesis"
    }
  }
}
```

---

## 5. Summary of Permanent Guidelines
1. **Never use candidate copy ("Apply Now") on employer dashboards.** Always use recruiter-centric actions ("View Top Matches", "Review Applicants").
2. **Never render raw unicode emojis.** Use clean inline SVGs and the official Jenny AI mascot illustration (`jenny_ai_hi.png`).
3. **Always ensure responsive single-line buttons** with `white-space: nowrap !important;` and `flex-shrink: 0 !important;`.
4. **Preserve mathematical circular geometry** for all match score gauges and avatar badges.
