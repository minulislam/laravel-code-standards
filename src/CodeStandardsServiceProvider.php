<?php

declare(strict_types=1);

namespace Multicoin\LaravelCodeStandards;

use Illuminate\Support\ServiceProvider;
use Multicoin\LaravelCodeStandards\Commands\CodeReviewCommand;
use Multicoin\LaravelCodeStandards\Commands\InstallStandardsCommand;
use Multicoin\LaravelCodeStandards\Commands\RunAnalysisCommand;
use Multicoin\LaravelCodeStandards\Commands\RunLintCommand;

final class CodeStandardsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/code-standards.php',
            'code-standards'
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish configuration
            $this->publishes([
                __DIR__ . '/../config/code-standards.php' => config_path('code-standards.php'),
            ], 'code-standards-config');

            // Publish PHPStan configuration
            $this->publishes([
                __DIR__ . '/../stubs/phpstan.neon' => base_path('phpstan.neon'),
            ], 'code-standards-phpstan');

            // Publish Pint configuration
            $this->publishes([
                __DIR__ . '/../stubs/pint.json' => base_path('pint.json'),
            ], 'code-standards-pint');

            // Publish PHPCS configuration
            $this->publishes([
                __DIR__ . '/../stubs/phpcs.xml' => base_path('phpcs.xml'),
            ], 'code-standards-phpcs');

            // Publish GitHub Actions workflow
            $this->publishes([
                __DIR__ . '/../stubs/code-review.yml' => base_path('.github/workflows/code-review.yml'),
            ], 'code-standards-github');

            // Publish all at once
            $this->publishes([
                __DIR__ . '/../stubs/phpstan.neon' => base_path('phpstan.neon'),
                __DIR__ . '/../stubs/pint.json' => base_path('pint.json'),
                __DIR__ . '/../stubs/phpcs.xml' => base_path('phpcs.xml'),
                __DIR__ . '/../config/code-standards.php' => config_path('code-standards.php'),
            ], 'code-standards');

            // Register commands
            $this->commands([
                InstallStandardsCommand::class,
                CodeReviewCommand::class,
                RunAnalysisCommand::class,
                RunLintCommand::class,
            ]);
        }
    }
}
