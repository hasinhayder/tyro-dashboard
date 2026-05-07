@php
    use HasinHayder\TyroDashboard\Support\DashboardColors;

    $dc = DashboardColors::load();
    $lightOverrides = $dc['light'] ?? [];
    $darkOverrides = $dc['dark'] ?? [];

    $cssVal = function (string $var, string $fallback, array $overrides) {
        if (isset($overrides[$var])) {
            return DashboardColors::hexAlphaToRgba($overrides[$var]['hex'], $overrides[$var]['alpha']);
        }
        return $fallback;
    };
@endphp

<style>
    /* ============================================
       SHADCN UI THEME VARIABLES
       Customize these variables to match your brand
       Compatible with shadcn/ui theming
    ============================================ */

    :root {
        /* Base radius for components */
        --radius: 0.625rem;

        /* Light mode colors */
        --background: {{ $cssVal('--background', 'oklch(1 0 0)', $lightOverrides) }};
        --foreground: {{ $cssVal('--foreground', 'oklch(0.145 0 0)', $lightOverrides) }};
        --card: {{ $cssVal('--card', 'oklch(1 0 0)', $lightOverrides) }};
        --card-foreground: {{ $cssVal('--card-foreground', 'oklch(0.145 0 0)', $lightOverrides) }};
        --popover: {{ $cssVal('--popover', 'oklch(1 0 0)', $lightOverrides) }};
        --popover-foreground: {{ $cssVal('--popover-foreground', 'oklch(0.145 0 0)', $lightOverrides) }};
        --primary: {{ $cssVal('--primary', 'oklch(0.205 0 0)', $lightOverrides) }};
        --primary-foreground: {{ $cssVal('--primary-foreground', 'oklch(0.985 0 0)', $lightOverrides) }};
        --secondary: {{ $cssVal('--secondary', 'oklch(0.97 0 0)', $lightOverrides) }};
        --secondary-foreground: {{ $cssVal('--secondary-foreground', 'oklch(0.205 0 0)', $lightOverrides) }};
        --muted: {{ $cssVal('--muted', 'oklch(0.97 0 0)', $lightOverrides) }};
        --muted-foreground: {{ $cssVal('--muted-foreground', 'oklch(0.556 0 0)', $lightOverrides) }};
        --accent: {{ $cssVal('--accent', 'oklch(0.97 0 0)', $lightOverrides) }};
        --accent-foreground: {{ $cssVal('--accent-foreground', 'oklch(0.205 0 0)', $lightOverrides) }};
        --destructive: {{ $cssVal('--destructive', 'oklch(0.577 0.245 27.325)', $lightOverrides) }};
        --destructive-foreground: {{ $cssVal('--destructive-foreground', 'oklch(1 0 0)', $lightOverrides) }};
        --border: {{ $cssVal('--border', 'oklch(0.922 0 0)', $lightOverrides) }};
        --input: {{ $cssVal('--input', 'oklch(0.922 0 0)', $lightOverrides) }};
        --ring: {{ $cssVal('--ring', 'oklch(0.708 0 0)', $lightOverrides) }};

        /* Chart colors */
        --chart-1: oklch(0.646 0.222 41.116);
        --chart-2: oklch(0.6 0.118 184.704);
        --chart-3: oklch(0.398 0.07 227.392);
        --chart-4: oklch(0.828 0.189 84.429);
        --chart-5: oklch(0.769 0.188 70.08);

        /* Sidebar colors */
        --sidebar: oklch(0.985 0 0);
        --sidebar-foreground: oklch(0.145 0 0);
        --sidebar-primary: oklch(0.205 0 0);
        --sidebar-primary-foreground: oklch(0.985 0 0);
        --sidebar-accent: oklch(0.97 0 0);
        --sidebar-accent-foreground: oklch(0.205 0 0);
        --sidebar-border: oklch(0.922 0 0);
        --sidebar-ring: oklch(0.708 0 0);

        /* Extended semantic colors */
        --success: {{ $cssVal('--success', 'oklch(0.627 0.194 149.214)', $lightOverrides) }};
        --success-foreground: {{ $cssVal('--success-foreground', 'oklch(1 0 0)', $lightOverrides) }};
        --warning: {{ $cssVal('--warning', 'oklch(0.769 0.188 70.08)', $lightOverrides) }};
        --warning-foreground: {{ $cssVal('--warning-foreground', 'oklch(0.205 0 0)', $lightOverrides) }};
        --info: {{ $cssVal('--info', 'oklch(0.623 0.214 259.815)', $lightOverrides) }};
        --info-foreground: {{ $cssVal('--info-foreground', 'oklch(1 0 0)', $lightOverrides) }};
        --danger: {{ $cssVal('--danger', '#ef4444', $lightOverrides) }};

        /* Card shadows */
        --card-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --card-shadow-hover: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }

    /* Dark mode colors - using .dark class */
    .dark {
        --background: {{ $cssVal('--background', 'oklch(0.145 0 0)', $darkOverrides) }};
        --foreground: {{ $cssVal('--foreground', 'oklch(0.985 0 0)', $darkOverrides) }};
        --card: {{ $cssVal('--card', 'oklch(0.205 0 0)', $darkOverrides) }};
        --card-foreground: {{ $cssVal('--card-foreground', 'oklch(0.985 0 0)', $darkOverrides) }};
        --popover: {{ $cssVal('--popover', 'oklch(0.205 0 0)', $darkOverrides) }};
        --popover-foreground: {{ $cssVal('--popover-foreground', 'oklch(0.985 0 0)', $darkOverrides) }};
        --primary: {{ $cssVal('--primary', 'oklch(0.922 0 0)', $darkOverrides) }};
        --primary-foreground: {{ $cssVal('--primary-foreground', 'oklch(0.205 0 0)', $darkOverrides) }};
        --secondary: {{ $cssVal('--secondary', 'oklch(0.269 0 0)', $darkOverrides) }};
        --secondary-foreground: {{ $cssVal('--secondary-foreground', 'oklch(0.985 0 0)', $darkOverrides) }};
        --muted: {{ $cssVal('--muted', 'oklch(0.269 0 0)', $darkOverrides) }};
        --muted-foreground: {{ $cssVal('--muted-foreground', 'oklch(0.708 0 0)', $darkOverrides) }};
        --accent: {{ $cssVal('--accent', 'oklch(0.269 0 0)', $darkOverrides) }};
        --accent-foreground: {{ $cssVal('--accent-foreground', 'oklch(0.985 0 0)', $darkOverrides) }};
        --destructive: {{ $cssVal('--destructive', 'oklch(0.704 0.191 22.216)', $darkOverrides) }};
        --destructive-foreground: {{ $cssVal('--destructive-foreground', 'oklch(0.9850 0 0)', $darkOverrides) }};
        --border: {{ $cssVal('--border', 'oklch(1 0 0 / 10%)', $darkOverrides) }};
        --input: {{ $cssVal('--input', 'oklch(1 0 0 / 15%)', $darkOverrides) }};
        --ring: {{ $cssVal('--ring', 'oklch(0.556 0 0)', $darkOverrides) }};

        /* Chart colors (dark mode) */
        --chart-1: oklch(0.488 0.243 264.376);
        --chart-2: oklch(0.696 0.17 162.48);
        --chart-3: oklch(0.769 0.188 70.08);
        --chart-4: oklch(0.627 0.265 303.9);
        --chart-5: oklch(0.645 0.246 16.439);

        /* Sidebar colors (dark mode) */
        --sidebar: oklch(0.205 0 0);
        --sidebar-foreground: oklch(0.985 0 0);
        --sidebar-primary: oklch(0.488 0.243 264.376);
        --sidebar-primary-foreground: oklch(0.985 0 0);
        --sidebar-accent: oklch(0.269 0 0);
        --sidebar-accent-foreground: oklch(0.985 0 0);
        --sidebar-border: oklch(1 0 0 / 10%);
        --sidebar-ring: oklch(0.556 0 0);

        --danger: {{ $cssVal('--danger', '#f87171', $darkOverrides) }};

        /* Extended semantic colors (dark mode) */
        --success: {{ $cssVal('--success', 'oklch(0.696 0.17 162.48)', $darkOverrides) }};
        --success-foreground: {{ $cssVal('--success-foreground', 'oklch(0.145 0 0)', $darkOverrides) }};
        --warning: {{ $cssVal('--warning', 'oklch(0.769 0.188 70.08)', $darkOverrides) }};
        --warning-foreground: {{ $cssVal('--warning-foreground', 'oklch(0.145 0 0)', $darkOverrides) }};
        --info: {{ $cssVal('--info', 'oklch(0.488 0.243 264.376)', $darkOverrides) }};
        --info-foreground: {{ $cssVal('--info-foreground', 'oklch(0.985 0 0)', $darkOverrides) }};

        /* Card shadows (dark mode) */
        --card-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.2);
        --card-shadow-hover: 0 4px 6px -1px rgb(0 0 0 / 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.2);
    }
</style>
