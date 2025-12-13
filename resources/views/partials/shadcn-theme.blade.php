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
        --background: oklch(1 0 0);
        --foreground: oklch(0.145 0 0);
        --card: oklch(1 0 0);
        --card-foreground: oklch(0.145 0 0);
        --popover: oklch(1 0 0);
        --popover-foreground: oklch(0.145 0 0);
        --primary: oklch(0.205 0 0);
        --primary-foreground: oklch(0.985 0 0);
        --secondary: oklch(0.97 0 0);
        --secondary-foreground: oklch(0.205 0 0);
        --muted: oklch(0.97 0 0);
        --muted-foreground: oklch(0.556 0 0);
        --accent: oklch(0.97 0 0);
        --accent-foreground: oklch(0.205 0 0);
        --destructive: oklch(0.577 0.245 27.325);
        --destructive-foreground: oklch(1 0 0);
        --border: oklch(0.922 0 0);
        --input: oklch(0.922 0 0);
        --ring: oklch(0.708 0 0);
        
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
        --success: oklch(0.627 0.194 149.214);
        --success-foreground: oklch(1 0 0);
        --warning: oklch(0.769 0.188 70.08);
        --warning-foreground: oklch(0.205 0 0);
        --info: oklch(0.623 0.214 259.815);
        --info-foreground: oklch(1 0 0);
        --danger: #ef4444;
        
        /* Card shadows */
        --card-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --card-shadow-hover: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }

    /* Dark mode colors - using .dark class */
    .dark {
        --background: oklch(0.145 0 0);
        --foreground: oklch(0.985 0 0);
        --card: oklch(0.205 0 0);
        --card-foreground: oklch(0.985 0 0);
        --popover: oklch(0.205 0 0);
        --popover-foreground: oklch(0.985 0 0);
        --primary: oklch(0.922 0 0);
        --primary-foreground: oklch(0.205 0 0);
        --secondary: oklch(0.269 0 0);
        --secondary-foreground: oklch(0.985 0 0);
        --muted: oklch(0.269 0 0);
        --muted-foreground: oklch(0.708 0 0);
        --accent: oklch(0.269 0 0);
        --accent-foreground: oklch(0.985 0 0);
        --destructive: oklch(0.704 0.191 22.216);
        --destructive-foreground: oklch(0.9850 0 0);
        --border: oklch(1 0 0 / 10%);
        --input: oklch(1 0 0 / 15%);
        --ring: oklch(0.556 0 0);
        
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

        --danger: #f87171;
        
        /* Extended semantic colors (dark mode) */
        --success: oklch(0.696 0.17 162.48);
        --success-foreground: oklch(0.145 0 0);
        --warning: oklch(0.769 0.188 70.08);
        --warning-foreground: oklch(0.145 0 0);
        --info: oklch(0.488 0.243 264.376);
        --info-foreground: oklch(0.985 0 0);
        
        /* Card shadows (dark mode) */
        --card-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.2);
        --card-shadow-hover: 0 4px 6px -1px rgb(0 0 0 / 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.2);
    }
</style>
