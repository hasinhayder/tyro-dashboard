<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SetupAiSkillCommand extends Command {
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tyro-dashboard:setup-ai-skill
                            {--copy : Use physical copies instead of symlinks for vendor-specific agent directories}';

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
        'kilo'           => '.kilo/skills/tyro-dashboard',
        'claude'         => '.claude/skills/tyro-dashboard',
        'github copilot' => '.github/skills/tyro-dashboard',
        'codex'          => '.codex/skills/tyro-dashboard',
        'gemini'         => '.gemini/skills/tyro-dashboard',
        'laravel boost'  => '.ai/skills/tyro-dashboard',
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

        $useCopy  = (bool) $this->option('copy');
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

        $ok = true;

        // Phase 1: always install a PHYSICAL copy into the universal .agents directory.
        $universalPath = base_path(self::UNIVERSAL_SKILL_DIR);
        if (! $this->installPhysicalCopy($universalPath, $sourcePath, 'universal: '.self::UNIVERSAL_SKILL_DIR)) {
            $ok = false;
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
     * Removes any existing target (symlink or directory) before copying.
     */
    protected function installPhysicalCopy(string $targetPath, string $sourcePath, string $label): bool {
        $filesystem = new Filesystem;

        // Remove any existing target (symlink or directory).
        $this->removeExisting($targetPath, $filesystem);

        $parentDir = dirname($targetPath);
        if (! is_dir($parentDir)) {
            $filesystem->makeDirectory($parentDir, 0755, true);
        }

        if (! $filesystem->copyDirectory($sourcePath, $targetPath)) {
            $this->error("   ✗ Failed to copy skill files for {$label}");

            return false;
        }

        $this->info("   ✓ Installed (copy) {$label}");

        return true;
    }

    /**
     * Create a relative symlink from $targetPath pointing to $universalPath.
     *
     * Removes any existing target (symlink or directory) before linking.
     */
    protected function installSymlink(string $targetPath, string $universalPath, string $label): bool {
        $filesystem = new Filesystem;

        // Remove any existing target (symlink or directory).
        $this->removeExisting($targetPath, $filesystem);

        $parentDir = dirname($targetPath);
        if (! is_dir($parentDir)) {
            $filesystem->makeDirectory($parentDir, 0755, true);
        }

        $relativeUniversal = $this->relativePath($parentDir, $universalPath);

        if (! @symlink($relativeUniversal, $targetPath)) {
            $this->error("   ✗ Failed to create symlink for {$label}");

            return false;
        }

        $this->info("   ✓ Installed (symlink → {$relativeUniversal}) {$label}");

        return true;
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
     * Compute a relative path from $from (directory) to $to (directory).
     */
    protected function relativePath(string $from, string $to): string {
        $fromParts = explode('/', rtrim($from, '/'));
        $toParts   = explode('/', rtrim($to, '/'));

        $commonLength = 0;
        $maxCommon    = min(count($fromParts), count($toParts));

        for ($i = 0; $i < $maxCommon; $i++) {
            if ($fromParts[$i] === $toParts[$i]) {
                $commonLength++;
            } else {
                break;
            }
        }

        $upCount  = count($fromParts) - $commonLength;
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
