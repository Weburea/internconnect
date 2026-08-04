# SEO Standards

To maximize organic search traffic, InternConnect implements search engine optimization best practices.

---

## Metadata Guidelines

### Title Tags

- Length: 50-60 characters
- Pattern: `Page Name | InternConnect`
- Rule: Every public route must render a unique title tag

### Meta Descriptions

- Length: 150-160 characters
- Pattern: Summarize page content; include a clear call-to-action
- Rule: Avoid generic template descriptions; customize descriptions for active listings

---

## Structured Data

- Job Listings: Render Schema.org `JobPosting` JSON-LD structured data on all internship detail pages
- Fields Required:
  - `title`
  - `description`
  - `datePosted`
  - `validThrough`
  - `hiringOrganization`
  - `jobLocation`

---

## Technical SEO

- URL Structure: Use descriptive, lowercase slugs (e.g., `/internships/laravel-backend-intern-lagos`)
- Robots.txt: Allow search crawlers to scan internship listings, but block access to candidate profiles, settings, and checkout pages
- Sitemap: Automatically update `sitemap.xml` with active internship listings weekly
