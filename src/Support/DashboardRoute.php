<?php

namespace HasinHayder\TyroDashboard\Support;

use Illuminate\Support\Str;

class DashboardRoute {
    protected const LEGACY_PREFIX = 'tyro-dashboard.';

    public static function prefix(): string {
        return static::normalizePrefix(config('tyro-dashboard.routes.name_prefix', static::LEGACY_PREFIX));
    }

    public static function legacyPrefix(): string {
        return static::normalizePrefix(static::LEGACY_PREFIX);
    }

    public static function name(string $name = ''): string {
        return static::build(static::prefix(), $name);
    }

    public static function pattern(string $pattern = '*'): string {
        return static::build(static::prefix(), $pattern);
    }

    public static function translate(string $name): ?string {
        $currentPrefix = static::prefix();
        $legacyPrefix = static::legacyPrefix();

        if ($currentPrefix !== $legacyPrefix && Str::startsWith($name, $legacyPrefix)) {
            return static::build($currentPrefix, substr($name, strlen($legacyPrefix)));
        }

        if ($currentPrefix !== $legacyPrefix && $currentPrefix !== '' && Str::startsWith($name, $currentPrefix)) {
            return static::build($legacyPrefix, substr($name, strlen($currentPrefix)));
        }

        return null;
    }

    public static function normalizePrefix(?string $prefix): string {
        $prefix = trim((string) $prefix);

        if ($prefix === '') {
            return '';
        }

        return rtrim($prefix, '.').'.';
    }

    protected static function build(string $prefix, string $name): string {
        $name = ltrim($name, '.');

        if ($name === '') {
            return $prefix;
        }

        return $prefix.$name;
    }
}
