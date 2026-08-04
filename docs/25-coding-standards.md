# Coding Standards

These coding standards ensure codebase maintainability, readability, and consistency.

Every developer and AI agent must adhere to these rules.

---

## PHP Code Rules

### Standards

- Adhere to PSR-12 coding standard
- Enable strict types: `declare(strict_types=1);` in all PHP files
- Use standard PHP 8.3+ syntax and features

### Class Formatting

- Use PascalCase for ClassNames
- Use camelCase for methods and variables
- Declare visibility (public, protected, private) for all methods and properties

### SQL Queries

- Never use raw input in database queries
- Use PDO prepared statements with parameterized queries
- Return typed arrays or class objects from database repositories

---

## Front-End Code Rules

### HTML & Alpine.js

- Use semantic HTML5 elements
- Use unique IDs for interactive elements to support automated tests
- Keep Alpine.js expressions clean and move complex logic to controllers

### CSS & Tailwind CSS

- Use Tailwind CSS utility classes in consistent ordering
- Avoid arbitrary Tailwind values (use theme config instead)
- Use standard design system tokens for colors, typography, and spacing

### JavaScript

- Use ES6+ syntax
- Use camelCase for functions and variables
- Standardize on `const` and `let` (never use `var`)
- Use modular structure; import/export functions cleanly
