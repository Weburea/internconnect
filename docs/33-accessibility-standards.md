# Accessibility Standards

InternConnect must be usable by everyone.

We aim for compliance with Web Content Accessibility Guidelines (WCAG) 2.1 AA standards.

---

## Semantic HTML

- Use appropriate HTML5 tags (`<header>`, `<nav>`, `<main>`, `<footer>`, `<section>`, `<article>`)
- Maintain a logical heading hierarchy (one `<h1>` per page, followed by `<h2>`, `<h3>` sequentially)
- Ensure all form fields have explicit, clickable `<label>` elements linked via `for` attributes

---

## Navigation & Controls

- Ensure full keyboard accessibility; all links, buttons, and form elements must receive focus outline styles
- Do not bypass default outline behavior unless replaced by custom high-contrast focus states
- Use descriptive anchor text; avoid generic "Click here" or "Read more" links

---

## Visuals & Color

- Maintain a minimum contrast ratio of 4.5:1 for normal text and 3:1 for large text
- Never convey information using color alone (e.g. use icons or text labels alongside red/green highlights)
- Provide descriptive `alt` tags for all user avatars, logos, and illustrative images
