<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SetupAiSkillCommand extends Command {
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tyro-dashboard:setup-ai-skill';

    /**
     * The console command description.
     */
    protected $description = 'Install the Tyro Dashboard AI skill for your preferred agent (Claude, Copilot, Codex, Gemini, Kilo)';

    /**
     * Mapping of AI agents to their target skill file paths.
     */
    protected array $agentTargets = [
        'kilo' => '.kilo/skills/tyro-dashboard/SKILL.md',
        'claude' => '.claude/skills/tyro-dashboard/SKILL.md',
        'github copilot' => '.github/skills/tyro-dashboard/SKILL.md',
        'codex' => '.codex/skills/tyro-dashboard/SKILL.md',
        'gemini' => '.gemini/skills/tyro-dashboard/SKILL.md',
        'laravel boost' => '.ai/skills/tyro-dashboard/SKILL.md',
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

        $sourcePath = $this->getSourceSkillPath();

        if (! file_exists($sourcePath)) {
            $this->error('   ✗ Source skill file not found: '.$sourcePath);

            return self::FAILURE;
        }

        $agents = array_keys($this->agentTargets);
        $agents[] = 'all';

        $choice = $this->choice(
            'Which AI agent would you like to install the skill for?',
            $agents,
            0
        );

        if ($choice === 'all') {
            $installed = 0;
            foreach (array_keys($this->agentTargets) as $agent) {
                if ($this->installForAgent($agent, $sourcePath)) {
                    $installed++;
                }
            }
            $this->info('');
            $this->info("  ✓ Skill installed for {$installed} agent(s).");
        } else {
            if ($this->installForAgent($choice, $sourcePath)) {
                $this->info('');
                $this->info('  ✓ Skill installed successfully.');
            } else {
                return self::FAILURE;
            }
        }

        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Install the skill file for a specific agent.
     */
    protected function installForAgent(string $agent, string $sourcePath): bool {
        $relativePath = $this->agentTargets[$agent] ?? null;

        if (! $relativePath) {
            $this->warn("   ⚠ Unknown agent: {$agent}");

            return false;
        }

        $targetPath = base_path($relativePath);
        $targetDir = dirname($targetPath);

        // Create target directory if it doesn't exist
        if (! is_dir($targetDir)) {
            $filesystem = new Filesystem;
            $filesystem->makeDirectory($targetDir, 0755, true);
            $this->info("   ✓ Created directory: {$targetDir}");
        }

        // Copy the skill file
        $content = file_get_contents($sourcePath);
        file_put_contents($targetPath, $content);

        $this->info("   ✓ Installed for {$agent}: {$relativePath}");

        return true;
    }

    /**
     * Get the source skill file path within the package.
     */
    protected function getSourceSkillPath(): string {
        return __DIR__.'/../../../skill/tyro-dashboard.md';
    }
}
