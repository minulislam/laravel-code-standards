<?php

declare(strict_types=1);

namespace Multicoin\LaravelCodeStandards\Commands;

use Illuminate\Console\Command;

final class RunAnalysisCommand extends Command
{
    protected $signature = 'review:analyse 
                            {path? : Specific path to analyze}
                            {--level=8 : PHPStan level (0-9)}';

    protected $description = 'Run PHPStan static analysis';

    public function handle(): int
    {
        $this->info('🔍 Running PHPStan static analysis...');
        $this->newLine();

        $level = $this->option('level');
        $path = $this->argument('path') ?? 'app';
        $memoryLimit = config('code-standards.phpstan.memory_limit', '2G');

        $command = sprintf(
            'vendor/bin/phpstan analyse %s --level=%s --memory-limit=%s',
            $path,
            $level,
            $memoryLimit
        );

        passthru($command, $exitCode);

        $this->newLine();

        if ($exitCode === 0) {
            $this->info('✅ Static analysis completed successfully!');
            return self::SUCCESS;
        } else {
            $this->error('❌ Static analysis found issues.');
            return self::FAILURE;
        }
    }
}
