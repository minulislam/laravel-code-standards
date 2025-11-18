<?php

declare(strict_types=1);

namespace Multicoin\LaravelCodeStandards\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class InstallStandardsCommand extends Command
{
    protected $signature = 'standards:install 
                            {--force : Overwrite existing configuration files}';

    protected $description = 'Install code standards configuration files';

    public function handle(): int
    {
        $this->info('🚀 Installing Laravel Code Standards...');
        $this->newLine();

        // Install dependencies
        $this->installDependencies();

        // Publish configuration files
        $this->publishConfigurations();

        // Update composer.json scripts
        $this->updateComposerScripts();

        $this->newLine();
        $this->info('✅ Code standards installed successfully!');
        $this->newLine();
        
        $this->line('📋 Available commands:');
        $this->line('  php artisan review         - Run full code review');
        $this->line('  php artisan review:analyse - Run PHPStan analysis');
        $this->line('  php artisan review:lint    - Run Pint linting');
        $this->newLine();
        
        $this->line('🎯 Or use composer scripts:');
        $this->line('  composer review            - Run all checks');
        $this->line('  composer lint              - Check code style');
        $this->line('  composer lint:fix          - Auto-fix code style');
        $this->line('  composer analyse           - Run static analysis');

        return self::SUCCESS;
    }

    private function installDependencies(): void
    {
        $this->info('📦 Installing dependencies...');

        $dependencies = [
            'laravel/pint' => '--dev',
            'phpstan/phpstan' => '--dev',
            'larastan/larastan' => '--dev',
            'squizlabs/php_codesniffer' => '--dev',
            'slevomat/coding-standard' => '--dev',
        ];

        foreach ($dependencies as $package => $flag) {
            $this->line("  Installing {$package}...");
            exec("composer require {$package} {$flag} --no-interaction 2>&1", $output, $exitCode);
            
            if ($exitCode !== 0) {
                $this->warn("  ⚠️  Failed to install {$package}");
            }
        }

        $this->info('  ✓ Dependencies installed');
    }

    private function publishConfigurations(): void
    {
        $this->info('📝 Publishing configuration files...');

        $force = $this->option('force');

        // Publish all configurations
        $tags = [
            'code-standards-config' => 'config/code-standards.php',
            'code-standards-phpstan' => 'phpstan.neon',
            'code-standards-pint' => 'pint.json',
            'code-standards-phpcs' => 'phpcs.xml',
        ];

        foreach ($tags as $tag => $file) {
            if ($force || !File::exists(base_path($file))) {
                $this->call('vendor:publish', [
                    '--tag' => $tag,
                    '--force' => $force,
                ]);
                $this->line("  ✓ Published {$file}");
            } else {
                $this->line("  ⊗ {$file} already exists (use --force to overwrite)");
            }
        }

        $this->info('  ✓ Configuration files published');
    }

    private function updateComposerScripts(): void
    {
        $this->info('📜 Updating composer.json scripts...');

        $composerPath = base_path('composer.json');
        
        if (!File::exists($composerPath)) {
            $this->warn('  ⚠️  composer.json not found');
            return;
        }

        $composer = json_decode(File::get($composerPath), true);

        $scripts = [
            'lint' => 'pint --test',
            'lint:fix' => 'pint',
            'analyse' => 'phpstan analyse --memory-limit=2G',
            'phpcs' => 'phpcs',
            'review' => [
                '@lint',
                '@analyse',
                '@phpcs',
            ],
        ];

        $composer['scripts'] = array_merge($composer['scripts'] ?? [], $scripts);

        File::put(
            $composerPath,
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        );

        $this->info('  ✓ Composer scripts updated');
    }
}
