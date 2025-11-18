<?php

declare(strict_types=1);

namespace Multicoin\LaravelCodeStandards\Commands;

use Illuminate\Console\Command;

final class RunLintCommand extends Command
{
    protected $signature = 'review:lint 
                            {path? : Specific path to lint}
                            {--fix : Automatically fix issues}
                            {--dirty : Only check uncommitted files}';

    protected $description = 'Run Pint code style checker';

    public function handle(): int
    {
        $fix = $this->option('fix');
        $dirty = $this->option('dirty');
        $path = $this->argument('path') ?? '';

        if ($fix) {
            $this->info('🔧 Running Pint (auto-fixing)...');
        } else {
            $this->info('🔍 Running Pint (checking only)...');
        }
        
        $this->newLine();

        $command = 'vendor/bin/pint';

        if (!$fix) {
            $command .= ' --test';
        }

        if ($dirty) {
            $command .= ' --dirty';
        }

        if ($path) {
            $command .= ' ' . $path;
        }

        passthru($command, $exitCode);

        $this->newLine();

        if ($exitCode === 0) {
            if ($fix) {
                $this->info('✅ Code style fixed!');
            } else {
                $this->info('✅ Code style is clean!');
            }
            return self::SUCCESS;
        } else {
            if ($fix) {
                $this->error('❌ Some issues could not be auto-fixed.');
            } else {
                $this->error('❌ Code style issues found.');
                $this->line('💡 Run with --fix to automatically fix issues:');
                $this->line('   php artisan review:lint --fix');
            }
            return self::FAILURE;
        }
    }
}
