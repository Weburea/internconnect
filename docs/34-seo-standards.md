# SEO Standards

To maximize organic search traffic, InternConnect implements search engine optimization best practices.

These are the standards every public page must meet. For what is currently implemented, see section 7 of `45-testing-api-architecture-and-audit-plan.md`.

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
- Rule: Every internship detail page must generate its own description from the listing

### Tags Not To Invest In

Google does not use these for ranking or display. Do not spend time tuning them:

- `meta keywords`
- Sitemap `priority` and `changefreq`
- `FAQPage` markup for rich results (Google stopped showing FAQ rich results in May 2026)
- `SearchAction` markup for the sitelinks search box (removed by Google in November 2024). `WebSite` markup is still used for site names

---

## Structured Data

- Job Listings: Render Schema.org `JobPosting` JSON-LD structured data on all internship detail pages
- Fields Required:
  - `title`
  - `description` (the complete listing: responsibilities, qualifications, and working arrangement)
  - `datePosted`
  - `validThrough`
  - `hiringOrganization`
  - `jobLocation`
- Remote Listings:
  - `jobLocationType` must be `TELECOMMUTE` on 100% remote roles
  - `applicantLocationRequirements` must name at least one eligible country
  - The description must state that the role is 100% remote and where applicants may be located
- Salary: Render `baseSalary` only when the listing has a real numeric amount, using the listing's actual currency and pay period
- Accuracy Rules:
  - Only real, open listings may carry `JobPosting` markup. Never mark up sample or placeholder listings
  - Never invent values. If `datePosted` or the deadline is missing, fix the listing instead of generating a date
  - Location values must come from the listing, not a hardcoded city or region

---

## Technical SEO

- URL Structure: Use descriptive, lowercase slugs (e.g., `/internship/laravel-backend-intern-lagos`)
- HTTPS: All HTTP requests must 301 redirect to HTTPS
- Single Host: Choose either the www or non-www address and 301 redirect the other to it
- Canonical: Every indexable page must output one absolute canonical URL on the production domain. Sitemap URLs must match the canonical URLs exactly
- Status Codes: Unknown internships, companies, and pages must return a real `404`. Never show fallback content with a `200` status
- Robots.txt: Allow search crawlers to scan internship listings, but block access to candidate profiles, settings, and checkout pages
- Noindex: Private pages (dashboards, saved items, messages, notifications, checkout, application forms, and on-site search results) must render `noindex`. A page blocked in `robots.txt` cannot show its `noindex` to crawlers, so do not rely on both for the same URL
- Sitemap: Automatically update `sitemap.xml` whenever an internship is published, edited, or closed. Use each page's real `lastmod` date, never the current date
- Expired Listings: When an internship closes, remove it from the sitemap and either return `404`/`410` or remove its `JobPosting` markup
- Crawlable Links: Links to internship and company pages must exist as plain `<a href>` tags in the server-rendered HTML, not only after JavaScript runs

---

## Performance

Measured on mobile, against the Core Web Vitals "good" thresholds:

- Largest Contentful Paint: 2.5 seconds or less
- Interaction to Next Paint: 200 milliseconds or less
- Cumulative Layout Shift: 0.1 or less

Implementation rules:

- Enable Gzip or Brotli compression and long-lived browser caching for static assets
- Version CSS and JS by file modification time, never by the current time
- Serve hero and content images as compressed WebP or AVIF at the size they display
- The page loader must not hold back the main content of public pages

---

## Content & Landing Pages

Keyword targets need dedicated, indexable pages with their own URL, title, description, and useful content. Query-string filters do not rank separately because they share the canonical of `/internships`.

- Location pages: `/internships/lagos`, `/internships/abuja`
- Category pages: `/internships/software-engineering`, `/internships/product-design`
- Programme pages: `/internships/siwes`, `/internships/nysc`
- Guides: SIWES placement, NYSC preparation, CV writing, and interview preparation

A landing page may only be indexed when it has real listings or substantial original content.

---

## Search Console Launch Checklist

Complete in order before and after going live:

1. Replace all sample internships, companies, and testimonials with real content
2. Set `SEO_CANONICAL_BASE_URL` to the live HTTPS domain
3. Confirm the HTTPS and single-host redirects work
4. Add `SEO_GOOGLE_SITE_VERIFICATION` and `SEO_BING_SITE_VERIFICATION`, then verify ownership
5. Submit `/sitemap.xml` in Google Search Console and Bing Webmaster Tools
6. Test internship pages with Google's Rich Results Test
7. Confirm job results are available in the target country in Google's current job posting documentation
8. Review the eligibility of Google's Indexing API for faster job updates
9. Monitor the Page Indexing, Job Posting, and Core Web Vitals reports weekly for the first three months
