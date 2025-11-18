# Laravel Code Standards

[![Latest Version](https://img.shields.io/packagist/v/multicoin/laravel-code-standards.svg)](https://packagist.org/packages/multicoin/laravel-code-standards)
[![License](https://img.shields.io/packagist/l/multicoin/laravel-code-standards.svg)](https://packagist.org/packages/multicoin/laravel-code-standards)

**Strict OOP code standards for Laravel with automated linting and review tools.**

This package enforces strict object-oriented programming standards while being Laravel-aware, allowing idiomatic patterns like facades. It bundles PHPStan, Pint, and PHPCS with pre-configured rules optimized for Laravel projects.

## Features

- ✅ **Strict OOP Standards** - SOLID principles, dependency injection, type safety
- ✅ **Laravel-Aware** - Understands and allows Laravel facades and patterns
- ✅ **Automated Tools** - PHPStan (Level 8), Laravel Pint, PHP_CodeSniffer
- ✅ **Artisan Commands** - Easy-to-use CLI commands
- ✅ **CI/CD Ready** - GitHub Actions workflow included
- ✅ **Customizable** - Publish and modify all configuration files

## What Gets Checked

### 🚨 Critical Issues (Auto-Fail)
- Missing `declare(strict_types=1);`
- Static methods in business logic (facades are OK)
- Direct `new` instantiation in constructors
- Missing type hints on parameters/returns
- Missing visibility modifiers
- Potential SQL injection vulnerabilities
- Business logic in controllers

### ✅ Laravel Patterns Allowed
- **Facades explicitly allowed**: `Cache::`, `DB::`, `Log::`, etc.
- Form Request validation
- API Resources for responses
- Jobs for async operations
- Eloquent best practices

## Installation

### 1. Install via Composer

```bash
composer require multicoin/laravel-code-standards --dev
```

### 2. Run Installation Command

```bash
php artisan standards:install
```

This command will:
- Install PHPStan, Pint, PHPCS, and related packages
- Publish configuration files (`phpstan.neon`, `pint.json`, `phpcs.xml`)
- Update `composer.json` with helpful scripts
- Display available commands

### 3. (Optional) Publish Individual Components

If you want to publish specific configurations only:

```bash
# Publish config file
php artisan vendor:publish --tag=code-standards-config

# Publish PHPStan config
php artisan vendor:publish --tag=code-standards-phpstan

# Publish Pint config
php artisan vendor:publish --tag=code-standards-pint

# Publish PHPCS config
php artisan vendor:publish --tag=code-standards-phpcs

# Publish GitHub Actions workflow
php artisan vendor:publish --tag=code-standards-github

# Publish everything at once
php artisan vendor:publish --tag=code-standards
```

## Usage

### Artisan Commands

```bash
# Run full code review (Pint + PHPStan + PHPCS)
php artisan review

# Auto-fix issues where possible
php artisan review --fix

# Run only Pint (code style)
php artisan review:lint

# Auto-fix code style
php artisan review:lint --fix

# Check only uncommitted files
php artisan review:lint --dirty

# Run only PHPStan (static analysis)
php artisan review:analyse

# Analyze specific path
php artisan review:analyse app/Services

# Use different PHPStan level
php artisan review:analyse --level=6
```

### Composer Scripts

After installation, these scripts are available:

```bash
# Check code style
composer lint

# Auto-fix code style
composer lint:fix

# Run static analysis
composer analyse

# Run PHPCS
composer phpcs

# Run all checks
composer review
```

### Pre-commit Hook (Optional)

Create `.git/hooks/pre-commit`:

```bash
#!/bin/bash

echo "Running code review..."

php artisan review

if [ $? -ne 0 ]; then
    echo "❌ Code review failed. Commit aborted."
    echo "Run 'php artisan review --fix' to auto-fix issues."
    exit 1
fi

echo "✅ Code review passed!"
exit 0
```

Make it executable:

```bash
chmod +x .git/hooks/pre-commit
```

## Configuration

### Main Configuration File

After installation, edit `config/code-standards.php`:

```php
return [
    'phpstan' => [
        'enabled' => true,
        'level' => 8,
        'paths' => ['app', 'config', 'database', 'routes'],
        'memory_limit' => '2G',
    ],

    'pint' => [
        'enabled' => true,
        'preset' => 'laravel',
    ],

    'rules' => [
        'strict_types' => true,
        'type_hints' => true,
        'visibility_modifiers' => true,
        'no_static_business_logic' => true,
        'dependency_injection' => true,
        'laravel_facades_allowed' => true,
    ],

    'allowed_facades' => [
        'Cache', 'DB', 'Log', 'Auth', // ... etc
    ],

    'exclude' => [
        'vendor',
        'storage',
        'bootstrap/cache',
    ],
];
```

### Tool-Specific Configuration

Each tool has its own configuration file that you can customize:

- **phpstan.neon** - PHPStan static analysis rules
- **pint.json** - Laravel Pint code style rules
- **phpcs.xml** - PHP_CodeSniffer standards

## Standards Enforced

### OOP Standards

#### Dependency Injection
```php
// ❌ Bad
class OrderService {
    public function __construct() {
        $this->gateway = new PaymentGateway(); // Hardcoded
    }
}

// ✅ Good
class OrderService {
    public function __construct(
        private readonly PaymentGateway $gateway
    ) {}
}
```

#### Type Safety
```php
// ❌ Bad
function process($data) {
    return $data->value;
}

// ✅ Good
function process(UserData $data): int {
    return $data->value;
}
```

#### Strict Types
```php
// ✅ Required at top of every file
<?php

declare(strict_types=1);

namespace App\Services;
```

### Laravel Patterns

#### Controllers
```php
// ❌ Bad - Business logic in controller
class UserController {
    public function store(Request $request) {
        $user = new User();
        $user->name = $request->name;
        $user->save();
    }
}

// ✅ Good - Thin controller
class UserController {
    public function __construct(
        private readonly UserCreationService $service
    ) {}

    public function store(StoreUserRequest $request): UserResource {
        $user = $this->service->create($request->validated());
        return new UserResource($user);
    }
}
```

#### Facades Are OK ✅
```php
// ✅ These are all acceptable
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Cache::remember('key', 3600, fn() => User::all());
DB::table('users')->where('active', true)->get();
Log::info('Action performed');
```

## CI/CD Integration

### GitHub Actions

A GitHub Actions workflow is included. Publish it with:

```bash
php artisan vendor:publish --tag=code-standards-github
```

This creates `.github/workflows/code-review.yml` that runs on every PR and push.

### GitLab CI

Add to your `.gitlab-ci.yml`:

```yaml
code-review:
  stage: test
  image: php:8.2
  script:
    - composer install
    - composer review
  only:
    - merge_requests
```

### Bitbucket Pipelines

Add to your `bitbucket-pipelines.yml`:

```yaml
pipelines:
  pull-requests:
    '**':
      - step:
          name: Code Review
          image: php:8.2
          script:
            - composer install
            - composer review
```

## IDE Integration

### PHPStorm / IntelliJ

1. Install PHPStan plugin
2. Configure PHPStan path: `vendor/bin/phpstan`
3. Enable "Run on save"

### VS Code

1. Install "PHP Sniffer & Beautifier" extension
2. Install "PHP Intelephense" extension
3. Add to `settings.json`:

```json
{
  "php.validate.executablePath": "vendor/bin/pint",
  "phpSniffer.executablesFolder": "vendor/bin/",
  "phpSniffer.standard": "phpcs.xml"
}
```

## Examples

### Bad Code Example

```php
class UserController extends Controller {
    public function store(Request $request) {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        
        Mail::to($user)->send(new WelcomeEmail($user));
        
        return response()->json($user);
    }
}
```

**Issues:**
- ❌ Missing `declare(strict_types=1);`
- ❌ No type hints
- ❌ No validation
- ❌ Business logic in controller
- ❌ Synchronous email
- ❌ Raw model in response

### Good Code Example

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendWelcomeEmail;
use App\Services\UserCreationService;

final class UserController extends Controller
{
    public function __construct(
        private readonly UserCreationService $userService
    ) {}

    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->userService->create($request->validated());
        
        SendWelcomeEmail::dispatch($user);
        
        return new UserResource($user);
    }
}
```

**Good Practices:**
- ✅ Strict types declaration
- ✅ Full type hints
- ✅ Form Request validation
- ✅ Dependency injection
- ✅ Service layer for business logic
- ✅ Queued job for email
- ✅ API Resource for response

## Customization

### Disable Specific Rules

Edit `phpstan.neon`:

```neon
parameters:
    ignoreErrors:
        - '#Your specific pattern to ignore#'
```

### Adjust PHPStan Level

In `config/code-standards.php`:

```php
'phpstan' => [
    'level' => 6, // Lower for less strict checking
],
```

### Add Custom Pint Rules

Edit `pint.json`:

```json
{
    "preset": "laravel",
    "rules": {
        "your_custom_rule": true
    }
}
```

## Troubleshooting

### Memory Limit Issues

Increase PHPStan memory limit in `config/code-standards.php`:

```php
'phpstan' => [
    'memory_limit' => '4G',
],
```

### False Positives

Add to `phpstan.neon`:

```neon
parameters:
    ignoreErrors:
        - '#Specific error message#'
```

### Performance

Analyze specific paths instead of everything:

```bash
php artisan review:analyse app/Services
```

## Team Adoption

### For New Projects

1. Install package
2. Run `php artisan standards:install`
3. Enable pre-commit hook
4. Set up CI/CD

### For Existing Projects

1. Install package
2. Run with low PHPStan level first: `php artisan review:analyse --level=4`
3. Gradually increase level as issues are fixed
4. Use `--fix` flag liberally: `php artisan review --fix`

## Upgrading

```bash
composer update multicoin/laravel-code-standards --dev
php artisan standards:install --force
```

The `--force` flag will overwrite configuration files with updated versions.

## Contributing

Contributions are welcome! Please submit PRs for:
- Additional checks or rules
- Bug fixes
- Documentation improvements
- New features

## License

MIT

## Credits

- **PHPStan** - Static analysis
- **Laravel Pint** - Code style fixing
- **PHP_CodeSniffer** - Coding standards
- **Larastan** - Laravel-specific PHPStan rules
- **Slevomat Coding Standard** - Additional PHPCS rules

## Support

For issues and questions:
- Create an issue on GitHub
- Email: support@multicoin.com
- Documentation: https://docs.multicoin.com/code-standards

---

**Made with ❤️ for Laravel developers who care about code quality**
