# Quick Installation Guide

## For Your Own Use (Private Package)

### 1. Add to Your Project

Copy the entire `laravel-code-standards` folder to a location, then:

**Option A: Local Path (Development)**

Add to your project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../laravel-code-standards"
        }
    ],
    "require-dev": {
        "multicoin/laravel-code-standards": "@dev"
    }
}
```

Then run:
```bash
composer update multicoin/laravel-code-standards
php artisan standards:install
```

**Option B: Private Git Repository**

1. Create a private Git repo (GitHub, GitLab, Bitbucket)
2. Push the package to that repo
3. Add to your project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/multicoin/laravel-code-standards.git"
        }
    ],
    "require-dev": {
        "multicoin/laravel-code-standards": "^1.0"
    }
}
```

Then run:
```bash
composer install
php artisan standards:install
```

### 2. Customize Package Name

Before publishing, update these files:

**composer.json:**
```json
{
    "name": "multicoin/laravel-code-standards",
    "authors": [
        {
            "name": "Your Name",
            "email": "your.email@example.com"
        }
    ]
}
```

**Namespace in all PHP files:**
Replace `Multicoin\LaravelCodeStandards` with your actual namespace.

Files to update:
- `src/CodeStandardsServiceProvider.php`
- `src/Commands/*.php`
- `composer.json` (autoload section)

## For Team Distribution

### Option 1: Private Packagist (Recommended for Teams)

1. Create account at https://packagist.com or use private packagist
2. Add your private repository
3. Team members install with:

```bash
composer config repositories.multicoin composer https://repo.packagist.com/multicoin/
composer require multicoin/laravel-code-standards --dev
```

### Option 2: Satis (Self-Hosted)

1. Set up Satis server: https://github.com/composer/satis
2. Add your package to Satis configuration
3. Team members add your Satis URL to composer.json:

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://satis.multicoin.com"
        }
    ]
}
```

### Option 3: Git Submodule

```bash
# In each project
git submodule add https://github.com/multicoin/laravel-code-standards.git packages/code-standards
```

Then add to composer.json:
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/code-standards"
        }
    ]
}
```

## Publishing to Public Packagist (Open Source)

If you want to share publicly:

1. Create public GitHub repository
2. Push your package
3. Register at https://packagist.org
4. Link your GitHub repo
5. Add webhook for auto-updates

Then anyone can install with:
```bash
composer require multicoin/laravel-code-standards --dev
```

## Verification

After installation, verify it works:

```bash
# Should show all available commands
php artisan list | grep review

# Should output:
#   review
#   review:analyse
#   review:lint
#   standards:install
```

## Next Steps

1. Run `php artisan standards:install` in each project
2. Customize `config/code-standards.php` if needed
3. Set up pre-commit hook (optional)
4. Configure CI/CD (optional)
5. Share with team!

## Updating the Package

When you make changes to the package:

```bash
# For path repositories
composer update multicoin/laravel-code-standards

# For VCS repositories
git push
composer update multicoin/laravel-code-standards

# Re-publish configs if needed
php artisan standards:install --force
```

## Troubleshooting

### "Class not found" errors

Run:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Package not found

Check:
1. Repository URL in composer.json is correct
2. Package name matches exactly
3. You've run `composer update` or `composer install`

### Commands not appearing

1. Clear config cache: `php artisan config:clear`
2. Check service provider is auto-discovered
3. Manually add to `config/app.php` if needed:

```php
'providers' => [
    // ...
    Multicoin\LaravelCodeStandards\CodeStandardsServiceProvider::class,
],
```
