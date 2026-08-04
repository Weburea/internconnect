# Performance Standards

Performance directly impacts user retention and conversion.

We design all modules to meet strict performance metrics.

---

## Performance Targets

- Initial Page Load Time: Under 2 seconds
- API Response Time: Under 500ms
- Database Query Time: Under 100ms per query
- DeepSeek AI Response Time: Under 10 seconds

---

## Database Optimization

- Always define indexes on fields in WHERE, JOIN, and ORDER BY clauses
- Avoid `SELECT *` queries; fetch only columns needed by the view or service
- Use pagination for lists containing more than 20 items

---

## Caching Strategy

- Cache static pages and translation dictionaries locally
- Use OPcache to precompile PHP scripts in production
- Cache database results for heavy queries that change infrequently (e.g., dashboard statistics)

---

## Assets Optimization

- Minimize and compress JavaScript and CSS files
- Lazy-load images and avatars below the fold
- Serve optimized WEBP formats for avatars and logos
