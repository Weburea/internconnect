# Testing Strategy

We maintain high reliability through comprehensive testing.

All major features require tests before release.

---

## Testing Levels

### Unit Testing

- Focus: Isolated methods, utility functions, and model methods
- Framework: PHPUnit
- Directory: `tests/Unit/`
- Rule: No external API requests or database writes in Unit Tests

### Integration Testing

- Focus: API endpoints, repository interactions, controllers, and authentication
- Framework: PHPUnit (with mock database connections)
- Directory: `tests/Integration/`
- Rule: Use transaction rollbacks to keep the database state clean

### End-to-End (E2E) Testing

- Focus: Critical user journeys (e.g., candidate registration, application, payment)
- Framework: Cypress or Playwright
- Directory: `tests/e2e/`
- Rule: Run against a local test database instance

---

## Testing Targets

### Coverage

- Code Coverage Target: 80%+ for core business logic
- Coverage Target: 100% for authentication, role permissions, and payment verification

### Automated Workflows

- Run tests locally before pushing branches
- Automated test runners block merging pull requests with failing tests
