# Changelog

All notable changes to `laravel-code-standards` will be documented in this file.

## [1.0.0] - 2024-11-18

### Added
- Initial release
- PHPStan Level 8 configuration with Laravel support
- Laravel Pint configuration with strict OOP rules
- PHP_CodeSniffer configuration with PSR-12 + strict rules
- Artisan commands:
  - `standards:install` - One-time setup
  - `review` - Run full code review
  - `review:analyse` - Run PHPStan only
  - `review:lint` - Run Pint only
- Composer scripts for quick access
- GitHub Actions workflow template
- Strict OOP enforcement (SOLID, DI, type hints)
- Laravel-aware (facades explicitly allowed)
- Comprehensive documentation
- Configuration publishing
- Pre-commit hook example

### Features
- Automatic dependency installation
- Customizable rules via config file
- CI/CD ready with GitHub Actions
- IDE integration support
- Team-friendly with shared configuration
- Incremental adoption support (configurable PHPStan levels)

### Standards Enforced
- `declare(strict_types=1)` required
- Full type hints on all parameters and returns
- Visibility modifiers on all properties and methods
- Dependency injection over direct instantiation
- No static methods in business logic (facades OK)
- Form Requests for validation
- API Resources for responses
- Jobs for async operations
- Policies/Gates for authorization
- Eager loading to prevent N+1 queries
- Security checks (SQL injection, XSS, CSRF)
- Performance patterns (caching, queues)

## [Unreleased]

### Planned
- PHPUnit configuration and test standards
- Additional Laravel-specific checks
- Performance profiling integration
- Security vulnerability scanning
- Code complexity reports
- Custom rule builder
- Web dashboard for reports
- Slack/Discord notifications for CI
