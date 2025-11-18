<?php

declare(strict_types=1);

namespace Multicoin\LaravelCodeStandards\Commands;

use Illuminate\Console\Command;

final class CodeReviewCommand extends Command
{
    protected $signature = 'review 
                            {--fix : Automatically fix issues where possible}';

    protected $description = 'Run comprehensive code review (Pint + PHPStan + PHPCS)';

    public function handle(): int
    {
        $this->info('🔍 Running comprehensive code review...');
        $this->newLine();

        $allPassed = true;

        // Run Pint
        $this->info('1️⃣  Running Pint (Code Style)...');
        $pintExitCode = $this->runPint();
        
        if ($pintExitCode !== 0) {
            $allPassed = false;
            $this->error('   ❌ Pint found style issues');
            
            if ($this->option('fix')) {
                $this->info('   🔧 Auto-fixing with Pint...');
                $this->call('review:lint', ['--fix' => true]);
            } else {
                $this->line('   💡 Run with --fix to auto-fix issues');
            }
        } else {
            $this->info('   ✅ Code style looks good');
        }
        $this->newLine();

        // Run PHPStan
        $this->info('2️⃣  Running PHPStan (Static Analysis)...');
        $phpstanExitCode = $this->runPHPStan();
        
        if ($phpstanExitCode !== 0) {
            $allPassed = false;
            $this->error('   ❌ PHPStan found issues');
        } else {
            $this->info('   ✅ Static analysis passed');
        }
        $this->newLine();

        // Run PHPCS
        $this->info('3️⃣  Running PHP_CodeSniffer (Coding Standards)...');
        $phpcsExitCode = $this->runPHPCS();
        
        if ($phpcsExitCode !== 0) {
            $allPassed = false;
            $this->error('   ❌ PHPCS found violations');
        } else {
            $this->info('   ✅ Coding standards met');
        }
        $this->newLine();

        // Summary
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        if ($allPassed) {
            $this->info('🎉 All checks passed! Code review complete.');
            return self::SUCCESS;
        } else {
            $this->error('⚠️  Some checks failed. Please review and fix the issues above.');
            return self::FAILURE;
        }
    }

    private function runPint(): int
    {
        $command = 'vendor/bin/pint --test';
        
        exec($command . ' 2>&1', $output, $exitCode);
        
        foreach ($output as $line) {
            $this->line('   ' . $line);
        }
        
        return $exitCode;
    }

    private function runPHPStan(): int
    {
        $memoryLimit = config('code-standards.phpstan.memory_limit', '2G');
        $command = "vendor/bin/phpstan analyse --memory-limit={$memoryLimit}";
        
        exec($command . ' 2>&1', $output, $exitCode);
        
        foreach ($output as $line) {
            $this->line('   ' . $line);
        }
        
        return $exitCode;
    }

    private function runPHPCS(): int
    {
        $command = 'vendor/bin/phpcs';
        
        exec($command . ' 2>&1', $output, $exitCode);
        
        foreach ($output as $line) {
            $this->line('   ' . $line);
        }
        
        return $exitCode;
    }
}
