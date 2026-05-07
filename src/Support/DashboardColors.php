<?php

namespace HasinHayder\TyroDashboard\Support;

class DashboardColors {
    public static function load(): array {
        $path = static::storagePath();
        if (! file_exists($path)) {
            return ['light' => [], 'dark' => []];
        }
        return json_decode(file_get_contents($path), true) ?? ['light' => [], 'dark' => []];
    }

    public static function save(array $colors): void {
        $path = static::storagePath();
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($path, json_encode($colors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public static function defaults(): array {
        return static::buildDefaults();
    }

    public static function form(): array {
        $overrides = static::load();
        $defaults = static::buildDefaults();

        $merged = [];
        foreach (['light', 'dark'] as $mode) {
            foreach ($defaults[$mode] as $var => $config) {
                $saved = $overrides[$mode][$var] ?? null;
                $merged[$mode][$var] = [
                    'hex' => $saved['hex'] ?? $config['hex'],
                    'alpha' => $saved['alpha'] ?? $config['alpha'],
                    'label' => $config['label'],
                ];
            }
        }
        return $merged;
    }

    public static function hexAlphaToRgba(string $hex, int $alpha): string {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $a = max(0, min(100, $alpha)) / 100;

        return "rgba({$r}, {$g}, {$b}, {$a})";
    }

    protected static function storagePath(): string {
        return storage_path('app/dashboard-colors.json');
    }

    protected static function buildDefaults(): array {
        return [
            'light' => [
                '--background' => ['hex' => '#ffffff', 'alpha' => 100, 'label' => 'Background'],
                '--foreground' => ['hex' => '#09090b', 'alpha' => 100, 'label' => 'Text (Foreground)'],
                '--card' => ['hex' => '#ffffff', 'alpha' => 100, 'label' => 'Card Background'],
                '--card-foreground' => ['hex' => '#09090b', 'alpha' => 100, 'label' => 'Card Text'],
                '--popover' => ['hex' => '#ffffff', 'alpha' => 100, 'label' => 'Popover Background'],
                '--popover-foreground' => ['hex' => '#09090b', 'alpha' => 100, 'label' => 'Popover Text'],
                '--primary' => ['hex' => '#18181b', 'alpha' => 100, 'label' => 'Primary'],
                '--primary-foreground' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Primary Text'],
                '--secondary' => ['hex' => '#f4f4f5', 'alpha' => 100, 'label' => 'Secondary'],
                '--secondary-foreground' => ['hex' => '#18181b', 'alpha' => 100, 'label' => 'Secondary Text'],
                '--muted' => ['hex' => '#f4f4f5', 'alpha' => 100, 'label' => 'Muted'],
                '--muted-foreground' => ['hex' => '#71717a', 'alpha' => 100, 'label' => 'Muted Text'],
                '--accent' => ['hex' => '#f4f4f5', 'alpha' => 100, 'label' => 'Accent'],
                '--accent-foreground' => ['hex' => '#18181b', 'alpha' => 100, 'label' => 'Accent Text'],
                '--destructive' => ['hex' => '#ef4444', 'alpha' => 100, 'label' => 'Destructive'],
                '--destructive-foreground' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Destructive Text'],
                '--border' => ['hex' => '#e4e4e7', 'alpha' => 100, 'label' => 'Border'],
                '--input' => ['hex' => '#e4e4e7', 'alpha' => 100, 'label' => 'Input'],
                '--ring' => ['hex' => '#18181b', 'alpha' => 100, 'label' => 'Focus Ring'],
                '--success' => ['hex' => '#22c55e', 'alpha' => 100, 'label' => 'Success'],
                '--success-foreground' => ['hex' => '#ffffff', 'alpha' => 100, 'label' => 'Success Text'],
                '--warning' => ['hex' => '#f59e0b', 'alpha' => 100, 'label' => 'Warning'],
                '--warning-foreground' => ['hex' => '#ffffff', 'alpha' => 100, 'label' => 'Warning Text'],
                '--info' => ['hex' => '#3b82f6', 'alpha' => 100, 'label' => 'Info'],
                '--info-foreground' => ['hex' => '#ffffff', 'alpha' => 100, 'label' => 'Info Text'],
                '--danger' => ['hex' => '#ef4444', 'alpha' => 100, 'label' => 'Danger'],
            ],
            'dark' => [
                '--background' => ['hex' => '#09090b', 'alpha' => 100, 'label' => 'Background'],
                '--foreground' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Text (Foreground)'],
                '--card' => ['hex' => '#18181b', 'alpha' => 100, 'label' => 'Card Background'],
                '--card-foreground' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Card Text'],
                '--popover' => ['hex' => '#18181b', 'alpha' => 100, 'label' => 'Popover Background'],
                '--popover-foreground' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Popover Text'],
                '--primary' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Primary'],
                '--primary-foreground' => ['hex' => '#18181b', 'alpha' => 100, 'label' => 'Primary Text'],
                '--secondary' => ['hex' => '#27272a', 'alpha' => 100, 'label' => 'Secondary'],
                '--secondary-foreground' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Secondary Text'],
                '--muted' => ['hex' => '#27272a', 'alpha' => 100, 'label' => 'Muted'],
                '--muted-foreground' => ['hex' => '#a1a1aa', 'alpha' => 100, 'label' => 'Muted Text'],
                '--accent' => ['hex' => '#27272a', 'alpha' => 100, 'label' => 'Accent'],
                '--accent-foreground' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Accent Text'],
                '--destructive' => ['hex' => '#7f1d1d', 'alpha' => 100, 'label' => 'Destructive'],
                '--destructive-foreground' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Destructive Text'],
                '--border' => ['hex' => '#ffffff', 'alpha' => 10, 'label' => 'Border'],
                '--input' => ['hex' => '#ffffff', 'alpha' => 15, 'label' => 'Input'],
                '--ring' => ['hex' => '#d4d4d8', 'alpha' => 100, 'label' => 'Focus Ring'],
                '--success' => ['hex' => '#22c55e', 'alpha' => 100, 'label' => 'Success'],
                '--success-foreground' => ['hex' => '#052e16', 'alpha' => 100, 'label' => 'Success Text'],
                '--warning' => ['hex' => '#f59e0b', 'alpha' => 100, 'label' => 'Warning'],
                '--warning-foreground' => ['hex' => '#000000', 'alpha' => 100, 'label' => 'Warning Text'],
                '--info' => ['hex' => '#3b82f6', 'alpha' => 100, 'label' => 'Info'],
                '--info-foreground' => ['hex' => '#fafafa', 'alpha' => 100, 'label' => 'Info Text'],
                '--danger' => ['hex' => '#f87171', 'alpha' => 100, 'label' => 'Danger'],
            ],
        ];
    }
}
