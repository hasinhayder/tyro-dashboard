<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SetupAiSkillCommand extends Command {
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tyro-dashboard:setup-ai-skill
                            {--copy : Use physical copies instead of symlinks for vendor-specific agent directories}
                            {--force : Replace existing skill directories without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Install the Tyro Dashboard AI skill for your preferred agent (Claude, Copilot, Codex, Gemini, Kilo)';

    /**
     * Universal agents.md skill discovery location.
     *
     * Always receives a physical copy of the skill files. Vendor-specific
     * directories (e.g. .claude/skills/tyro-dashboard) symlink here by
     * default, or receive a physical copy when --copy is passed.
     */
    public const UNIVERSAL_SKILL_DIR = '.agents/skills/tyro-dashboard';

    /**
     * Mapping of AI agents to their vendor-specific target skill directory.
     */
    protected array $agentTargets = [
        'kilo' => '.kilo/skills/tyro-dashboard',
        'claude' => '.claude/skills/tyro-dashboard',
        'github copilot' => '.github/skills/tyro-dashboard',
        'codex' => '.codex/skills/tyro-dashboard',
        'gemini' => '.gemini/skills/tyro-dashboard',
        'laravel boost' => '.ai/skills/tyro-dashboard',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int {
        $this->info('');
        $this->info('  ╔════════════════════════════════════════╗');
        $this->info('  ║      Tyro Dashboard AI Skill Setup     ║');
        $this->info('  ╚════════════════════════════════════════╝');
        $this->info('');

        $useCopy = (bool) $this->option('copy');
        $sourcePath = $this->getSourceSkillPath();

        if (! is_dir($sourcePath)) {
            $this->error('   ✗ Source skill directory not found: '.$sourcePath);

            return self::FAILURE;
        }

        $agents = array_keys($this->agentTargets);
        $agents[] = 'all';

        $choice = $this->choice(
            'Which AI agent would you like to install the skill for?',
            $agents,
            0
        );

        $selectedAgents = $choice === 'all'
            ? array_keys($this->agentTargets)
            : [$choice];

        $selectedPaths = array_map(
            fn (string $agent): string => base_path($this->agentTargets[$agent]),
            $selectedAgents
        );
        $replaceTargets = $this->existingTargets([
            base_path(self::UNIVERSAL_SKILL_DIR),
            ...$selectedPaths,
        ]);

        if ($replaceTargets !== [] && ! $this->option('force')) {
            $this->warn('   Existing AI skill install targets will be replaced:');
            foreach ($replaceTargets as $path) {
                $this->line('   - '.$path);
            }

            if (! $this->confirm('Continue replacing these targets?', false)) {
                $this->warn('   Setup cancelled.');

                return self::FAILURE;
            }
        }

        $ok = true;

        // Phase 1: always install a PHYSICAL copy into the universal .agents directory.
        $universalPath = base_path(self::UNIVERSAL_SKILL_DIR);
        if (! $this->installPhysicalCopy($universalPath, $sourcePath, 'universal: '.self::UNIVERSAL_SKILL_DIR)) {
            return self::FAILURE;
        }

        // Phase 2: create symlinks (or physical copies with --copy) in each vendor-specific directory.
        foreach ($selectedAgents as $agent) {
            $relativePath = $this->agentTargets[$agent] ?? null;

            if (! $relativePath) {
                $this->warn("   ⚠ Unknown agent: {$agent}");
                $ok = false;

                continue;
            }

            $targetPath = base_path($relativePath);

            if ($useCopy) {
                if (! $this->installPhysicalCopy($targetPath, $sourcePath, "{$agent}: {$relativePath}")) {
                    $ok = false;
                }
            } else {
                if (! $this->installSymlink($targetPath, $universalPath, "{$agent}: {$relativePath}")) {
                    $ok = false;
                }
            }
        }

        $this->info('');
        if (! $useCopy) {
            $this->info('   💡 Tip: Use --copy to install physical copies instead of symlinks.');
        }

        return $ok ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Install a physical copy of the skill directory at the target path.
     *
     * Stages the copy first, then swaps it into place.
     */
    protected function installPhysicalCopy(string $targetPath, string $sourcePath, string $label): bool {
        $filesystem = new Filesystem;
        $parentDir = dirname($targetPath);
        $stagingPath = $this->temporaryPath($targetPath, 'installing');

        $this->removeExisting($stagingPath, $filesystem);
        $this->ensureDirectory($parentDir, $filesystem);

        if (! $filesystem->copyDirectory($sourcePath, $stagingPath)) {
            $this->removeExisting($stagingPath, $filesystem);
            $this->error("   ✗ Failed to copy skill files for {$label}");

            return false;
        }

        if (! $this->swapIntoPlace($stagingPath, $targetPath, $filesystem)) {
            $this->error("   ✗ Failed to replace skill files for {$label}");

            return false;
        }

        $this->info("   ✓ Installed (copy) {$label}");

        return true;
    }

    /**
     * Create a relative symlink from $targetPath pointing to $universalPath.
     *
     * Creates the link at a staging path first, then swaps it into place.
     */
    protected function installSymlink(string $targetPath, string $universalPath, string $label): bool {
        $filesystem = new Filesystem;
        $parentDir = dirname($targetPath);
        $stagingPath = $this->temporaryPath($targetPath, 'installing');

        $this->removeExisting($stagingPath, $filesystem);
        $this->ensureDirectory($parentDir, $filesystem);
        $relativeUniversal = $this->relativePath($parentDir, $universalPath);

        if (! @symlink($relativeUniversal, $stagingPath)) {
            $this->removeExisting($stagingPath, $filesystem);
            $this->error("   ✗ Failed to create symlink for {$label}");

            return false;
        }

        if (! $this->swapIntoPlace($stagingPath, $targetPath, $filesystem)) {
            $this->error("   ✗ Failed to replace symlink for {$label}");

            return false;
        }

        $this->info("   ✓ Installed (symlink → {$relativeUniversal}) {$label}");

        return true;
    }

    /**
     * Swap a staged path into place while preserving the previous target on failure.
     */
    protected function swapIntoPlace(string $stagingPath, string $targetPath, Filesystem $filesystem): bool {
        $backupPath = $this->temporaryPath($targetPath, 'backup');

        $this->removeExisting($backupPath, $filesystem);

        if ($this->targetExists($targetPath) && ! @rename($targetPath, $backupPath)) {
            $this->removeExisting($stagingPath, $filesystem);

            return false;
        }

        if (@rename($stagingPath, $targetPath)) {
            $this->removeExisting($backupPath, $filesystem);

            return true;
        }

        if ($this->targetExists($backupPath)) {
            @rename($backupPath, $targetPath);
        }

        $this->removeExisting($stagingPath, $filesystem);

        return false;
    }

    /**
     * Build a sibling temporary path for staged installs and backups.
     */
    protected function temporaryPath(string $targetPath, string $suffix): string {
        return dirname($targetPath).'/.'.basename($targetPath).'.__'.$suffix.'__';
    }

    /**
     * Ensure a directory exists.
     */
    protected function ensureDirectory(string $path, Filesystem $filesystem): void {
        if (! is_dir($path)) {
            $filesystem->makeDirectory($path, 0755, true);
        }
    }

    /**
     * Remove an existing target, whether it is a symlink or a directory.
     */
    protected function removeExisting(string $path, Filesystem $filesystem): void {
        if (is_link($path) || is_file($path)) {
            @unlink($path);
        } elseif (is_dir($path)) {
            $filesystem->deleteDirectory($path);
        }
    }

    /**
     * Determine whether a target exists, including symlinks.
     */
    protected function targetExists(string $path): bool {
        return is_link($path) || file_exists($path);
    }

    /**
     * Return install targets that already exist.
     *
     * @param  array<int, string>  $paths
     * @return array<int, string>
     */
    protected function existingTargets(array $paths): array {
        return array_values(array_filter(
            array_unique($paths),
            fn (string $path): bool => $this->targetExists($path)
        ));
    }

    /**
     * Compute a relative path from $from (directory) to $to (directory).
     */
    protected function relativePath(string $from, string $to): string {
        $fromParts = explode('/', rtrim($from, '/'));
        $toParts = explode('/', rtrim($to, '/'));

        $commonLength = 0;
        $maxCommon = min(count($fromParts), count($toParts));

        for ($i = 0; $i < $maxCommon; $i++) {
            if ($fromParts[$i] === $toParts[$i]) {
                $commonLength++;
            } else {
                break;
            }
        }

        $upCount = count($fromParts) - $commonLength;
        $relative = str_repeat('../', $upCount).implode('/', array_slice($toParts, $commonLength));

        return $relative ?: '.';
    }

    /**
     * Get the source skill directory within the package.
     *
     * The source lives at `skills/tyro-dashboard/` (capital SKILL.md)
     * as of the 1.31.0 layout. The old `skill/tyro-dashboard.md` file
     * is no longer authoritative.
     */
    protected function getSourceSkillPath(): string {
        return __DIR__.'/../../../skills/tyro-dashboard';
    }
}
