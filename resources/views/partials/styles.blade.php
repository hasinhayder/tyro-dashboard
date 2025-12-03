<style>
    /* ============================================
       SHADCN UI DESIGN SYSTEM
       Compatible with shadcn/ui theming
    ============================================ */
    
    :root {
        /* shadcn UI Variables (oklch) */
        --radius: 0.625rem;
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
        --border: oklch(0.922 0 0);
        --input: oklch(0.922 0 0);
        --ring: oklch(0.708 0 0);
        --chart-1: oklch(0.646 0.222 41.116);
        --chart-2: oklch(0.6 0.118 184.704);
        --chart-3: oklch(0.398 0.07 227.392);
        --chart-4: oklch(0.828 0.189 84.429);
        --chart-5: oklch(0.769 0.188 70.08);
        --sidebar: oklch(0.985 0 0);
        --sidebar-foreground: oklch(0.145 0 0);
        --sidebar-primary: oklch(0.205 0 0);
        --sidebar-primary-foreground: oklch(0.985 0 0);
        --sidebar-accent: oklch(0.97 0 0);
        --sidebar-accent-foreground: oklch(0.205 0 0);
        --sidebar-border: oklch(0.922 0 0);
        --sidebar-ring: oklch(0.708 0 0);
        
        /* Extended semantic colors (derived from shadcn variables) */
        --success: oklch(0.627 0.194 149.214);
        --success-foreground: oklch(1 0 0);
        --warning: oklch(0.769 0.188 70.08);
        --warning-foreground: oklch(0.205 0 0);
        --info: oklch(0.623 0.214 259.815);
        --info-foreground: oklch(1 0 0);
        
        /* Card shadows */
        --card-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --card-shadow-hover: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);

        /* Mappings to legacy variables */
        --bg-primary: var(--background);
        --bg-secondary: var(--muted);
        --bg-tertiary: var(--accent);
        --text-primary: var(--foreground);
        --text-secondary: var(--muted-foreground);
        --text-muted: var(--muted-foreground);
        --border-color: var(--border);
        --border-color-light: var(--border);
        --input-bg: var(--background);
        --input-border: var(--input);
        --input-focus-border: var(--ring);
        --btn-primary-bg: var(--primary);
        --btn-primary-text: var(--primary-foreground);
        --btn-primary-hover: var(--primary);
        --btn-secondary-bg: var(--secondary);
        --btn-secondary-text: var(--secondary-foreground);
        --btn-secondary-hover: var(--secondary);
        --btn-danger-bg: var(--destructive);
        --btn-danger-text: var(--destructive-foreground);
        --btn-danger-hover: var(--destructive);
        --btn-success-bg: var(--success);
        --btn-success-text: var(--success-foreground);
        --btn-success-hover: var(--success);
        --btn-warning-bg: var(--warning);
        --btn-warning-text: var(--warning-foreground);
        --btn-warning-hover: var(--warning);
        --link-color: var(--primary);
        --link-hover: var(--primary);
        --error-color: var(--destructive);
        --error-bg: color-mix(in srgb, var(--destructive), transparent 90%);
        --error-border: var(--destructive);
        --success-color: var(--success);
        --success-bg: color-mix(in srgb, var(--success), transparent 90%);
        --success-border: var(--success);
        --warning-color: var(--warning);
        --warning-bg: color-mix(in srgb, var(--warning), transparent 90%);
        --warning-border: var(--warning);
        --info-color: var(--info);
        --info-bg: color-mix(in srgb, var(--info), transparent 90%);
        --info-border: var(--info);
        --sidebar-bg: var(--sidebar);
        --sidebar-text: var(--sidebar-foreground);
        --sidebar-text-active: var(--sidebar-primary-foreground);
        --sidebar-hover: var(--sidebar-accent);
        --sidebar-active: var(--sidebar-primary);
    }

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
        --border: oklch(1 0 0 / 10%);
        --input: oklch(1 0 0 / 15%);
        --ring: oklch(0.556 0 0);
        --chart-1: oklch(0.488 0.243 264.376);
        --chart-2: oklch(0.696 0.17 162.48);
        --chart-3: oklch(0.769 0.188 70.08);
        --chart-4: oklch(0.627 0.265 303.9);
        --chart-5: oklch(0.645 0.246 16.439);
        --sidebar: oklch(0.205 0 0);
        --sidebar-foreground: oklch(0.985 0 0);
        --sidebar-primary: oklch(0.488 0.243 264.376);
        --sidebar-primary-foreground: oklch(0.985 0 0);
        --sidebar-accent: oklch(0.269 0 0);
        --sidebar-accent-foreground: oklch(0.985 0 0);
        --sidebar-border: oklch(1 0 0 / 10%);
        --sidebar-ring: oklch(0.556 0 0);
        
        /* Extended semantic colors (dark mode) */
        --success: oklch(0.696 0.17 162.48);
        --success-foreground: oklch(0.145 0 0);
        --warning: oklch(0.769 0.188 70.08);
        --warning-foreground: oklch(0.145 0 0);
        --info: oklch(0.488 0.243 264.376);
        --info-foreground: oklch(0.985 0 0);
        
        /* Card shadows */
        --card-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.2);
        --card-shadow-hover: 0 4px 6px -1px rgb(0 0 0 / 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.2);
    }
    
    /* Support for html.dark class (Laravel default) */
    html.dark {
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
        --border: oklch(1 0 0 / 10%);
        --input: oklch(1 0 0 / 15%);
        --ring: oklch(0.556 0 0);
        --chart-1: oklch(0.488 0.243 264.376);
        --chart-2: oklch(0.696 0.17 162.48);
        --chart-3: oklch(0.769 0.188 70.08);
        --chart-4: oklch(0.627 0.265 303.9);
        --chart-5: oklch(0.645 0.246 16.439);
        --sidebar: oklch(0.205 0 0);
        --sidebar-foreground: oklch(0.985 0 0);
        --sidebar-primary: oklch(0.488 0.243 264.376);
        --sidebar-primary-foreground: oklch(0.985 0 0);
        --sidebar-accent: oklch(0.269 0 0);
        --sidebar-accent-foreground: oklch(0.985 0 0);
        --sidebar-border: oklch(1 0 0 / 10%);
        --sidebar-ring: oklch(0.556 0 0);
        
        /* Extended semantic colors (dark mode) */
        --success: oklch(0.696 0.17 162.48);
        --success-foreground: oklch(0.145 0 0);
        --warning: oklch(0.769 0.188 70.08);
        --warning-foreground: oklch(0.145 0 0);
        --info: oklch(0.488 0.243 264.376);
        --info-foreground: oklch(0.985 0 0);
        
        /* Card shadows */
        --card-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.2);
        --card-shadow-hover: 0 4px 6px -1px rgb(0 0 0 / 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.2);
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: var(--bg-secondary);
        min-height: 100vh;
        line-height: 1.6;
        color: var(--text-primary);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-size: 16px;
    }

    /* Dashboard Layout */
    .dashboard-layout {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar - shadcn style */
    .sidebar {
        width: 280px;
        background-color: var(--sidebar-bg);
        border-right: 1px solid var(--border-color);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 100;
        transition: transform 0.2s ease;
    }

    .sidebar-header {
        padding: 1.25rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        text-decoration: none;
    }

    .sidebar-logo-icon {
        width: 36px;
        height: 36px;
        background: var(--text-primary);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-logo-icon svg {
        width: 20px;
        height: 20px;
        color: var(--bg-primary);
    }

    .sidebar-logo-text {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.01em;
    }

    .sidebar-nav {
        padding: 0.5rem 0;
    }

    .sidebar-section {
        padding: 0 0.5rem;
        margin-bottom: 1rem;
    }

    .sidebar-section-title {
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        padding: 0.625rem 1rem;
        margin-bottom: 0.25rem;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        color: var(--sidebar-text);
        text-decoration: none;
        font-size: 0.9375rem;
        font-weight: 500;
        transition: all 0.15s ease;
        margin-bottom: 2px;
    }

    .sidebar-link:hover {
        background-color: var(--sidebar-hover);
        color: var(--sidebar-text-active);
    }

    .sidebar-link.active {
        background-color: var(--sidebar-active);
        color: var(--sidebar-text-active);
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }

    .sidebar-link svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        opacity: 0.7;
    }

    .sidebar-link:hover svg,
    .sidebar-link.active svg {
        opacity: 1;
    }

    /* Main Content */
    .main-content {
        flex: 1;
        margin-left: 280px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Top Bar - shadcn style */
    .topbar {
        background-color: var(--bg-primary);
        border-bottom: 1px solid var(--border-color);
        padding: 0 1.5rem;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .mobile-menu-btn {
        display: none;
        padding: 0.5rem;
        border: none;
        background: transparent;
        color: var(--text-primary);
        cursor: pointer;
        border-radius: 6px;
    }

    .mobile-menu-btn:hover {
        background-color: var(--bg-secondary);
    }

    .mobile-menu-btn svg {
        width: 20px;
        height: 20px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
        color: var(--text-muted);
    }

    .breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .breadcrumb a:hover {
        color: var(--text-primary);
    }

    .breadcrumb-separator {
        color: var(--text-muted);
        opacity: 0.5;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .topbar-btn {
        padding: 0.5rem;
        border: none;
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .topbar-btn:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .topbar-btn svg {
        width: 18px;
        height: 18px;
    }

    /* User Dropdown */
    .user-dropdown {
        position: relative;
    }

    .user-dropdown-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem;
        padding-right: 0.5rem;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .user-dropdown-btn:hover {
        background-color: var(--bg-secondary);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bg-primary);
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .user-info {
        text-align: left;
    }

    .user-name {
        font-size: 0.9375rem;
        font-weight: 500;
        color: var(--text-primary);
        line-height: 1.3;
    }

    .user-role {
        font-size: 0.8125rem;
        color: var(--text-muted);
        line-height: 1.3;
    }

    .user-dropdown-arrow {
        width: 14px;
        height: 14px;
        color: var(--text-muted);
    }

    .user-dropdown-menu {
        position: absolute;
        top: calc(100% + 4px);
        right: 0;
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        min-width: 180px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-4px);
        transition: all 0.15s ease;
        z-index: 100;
        padding: 0.25rem;
    }

    .user-dropdown.active .user-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.625rem;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.8125rem;
        border-radius: 4px;
        transition: all 0.15s ease;
    }

    .dropdown-item:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .dropdown-item svg {
        width: 14px;
        height: 14px;
    }

    .dropdown-divider {
        height: 1px;
        background-color: var(--border-color);
        margin: 0.25rem 0;
    }

    .dropdown-item-danger {
        color: var(--error-color);
    }

    .dropdown-item-danger:hover {
        background-color: var(--error-bg);
        color: var(--error-color);
    }

    /* Page Content */
    .page-content {
        padding: 2rem;
        flex: 1;
        /* max-width: 1200px; */
    }

    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
    }

    .page-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.025em;
        line-height: 1.2;
    }

    .page-description {
        margin-top: 0.375rem;
        font-size: 0.9375rem;
        color: var(--text-muted);
    }

    /* Cards - shadcn style */
    .card {
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: var(--card-shadow);
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border-color);
        background-color: var(--bg-secondary);
        border-radius: 0 0 8px 8px;
    }

    /* Stats Cards - shadcn style */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1.5rem;
        transition: all 0.15s ease;
    }

    .stat-card:hover {
        box-shadow: var(--card-shadow-hover);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-icon svg {
        width: 22px;
        height: 22px;
    }

    .stat-icon-primary {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .stat-icon-success {
        background-color: var(--success-bg);
        color: var(--success-color);
    }

    .stat-icon-warning {
        background-color: var(--warning-bg);
        color: var(--warning-color);
    }

    .stat-icon-danger {
        background-color: var(--error-bg);
        color: var(--error-color);
    }

    .stat-icon-info {
        background-color: var(--info-bg);
        color: var(--info-color);
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-muted);
        margin-bottom: 0.375rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        letter-spacing: -0.025em;
    }

    .stat-change {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.6875rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }

    .stat-change-up {
        color: var(--success-color);
    }

    .stat-change-down {
        color: var(--error-color);
    }

    /* Tables - shadcn style */
    .table-container {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 1rem 1.25rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .table th {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-muted);
        background-color: var(--bg-secondary);
    }

    .table td {
        font-size: 0.9375rem;
        color: var(--text-primary);
    }

    .table tbody tr:hover {
        background-color: var(--bg-secondary);
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Buttons - shadcn style */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        font-size: 0.9375rem;
        font-weight: 500;
        font-family: inherit;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
        white-space: nowrap;
        line-height: 1.25;
    }

    .btn svg {
        width: 18px;
        height: 18px;
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }

    .btn-primary {
        background-color: var(--btn-primary-bg);
        color: var(--btn-primary-text);
    }

    .btn-primary:hover {
        background-color: var(--btn-primary-hover);
    }

    .btn-secondary {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .btn-secondary:hover {
        background-color: var(--bg-secondary);
    }

    .btn-danger {
        background-color: var(--btn-danger-bg);
        color: var(--btn-danger-text);
    }

    .btn-danger:hover {
        background-color: var(--btn-danger-hover);
    }

    .btn-success {
        background-color: var(--btn-success-bg);
        color: var(--btn-success-text);
    }

    .btn-success:hover {
        background-color: var(--btn-success-hover);
    }

    .btn-warning {
        background-color: var(--btn-warning-bg);
        color: var(--btn-warning-text);
    }

    .btn-warning:hover {
        background-color: var(--btn-warning-hover);
    }

    .btn-outline {
        background-color: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    .btn-outline:hover {
        background-color: var(--bg-secondary);
    }

    .btn-ghost {
        background-color: transparent;
        color: var(--text-secondary);
    }

    .btn-ghost:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Form Elements - shadcn style */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.9375rem;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-label-optional {
        color: var(--text-muted);
        font-weight: 400;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.625rem 0.875rem;
        font-size: 0.9375rem;
        font-family: inherit;
        border: 1px solid var(--input-border);
        border-radius: 8px;
        background-color: var(--input-bg);
        color: var(--text-primary);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        line-height: 1.5;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: var(--text-muted);
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--input-focus-border);
        box-shadow: 0 0 0 2px var(--bg-secondary);
    }

    .form-input.is-invalid,
    .form-select.is-invalid,
    .form-textarea.is-invalid {
        border-color: var(--error-color);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2371717a'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 1rem;
        padding-right: 2rem;
    }

    .form-hint {
        font-size: 0.8125rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
    }

    .form-error {
        font-size: 0.8125rem;
        color: var(--error-color);
        margin-top: 0.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }

    /* Checkbox & Radio - shadcn style */
    .checkbox-input,
    .radio-input {
        width: 20px;
        height: 20px;
        border-radius: 5px;
        border: 1px solid var(--input-border);
        background-color: transparent;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        transition: all 0.15s ease;
        position: relative;
        flex-shrink: 0;
    }

    .radio-input {
        border-radius: 50%;
    }

    .checkbox-input:checked,
    .radio-input:checked {
        background-color: var(--text-primary);
        border-color: var(--text-primary);
    }

    .checkbox-input:checked::after {
        content: '';
        position: absolute;
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid var(--bg-primary);
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .radio-input:checked::after {
        content: '';
        position: absolute;
        left: 5px;
        top: 5px;
        width: 8px;
        height: 8px;
        background: var(--bg-primary);
        border-radius: 50%;
    }

    /* Toggle Switch - shadcn style */
    .toggle-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
    }

    .toggle-input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }

    .toggle-slider {
        position: relative;
        width: 44px;
        height: 24px;
        background-color: var(--input-border);
        border-radius: 24px;
        transition: 0.2s;
        flex-shrink: 0;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: 0.2s;
        border-radius: 50%;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.1);
    }

    .toggle-input:checked + .toggle-slider {
        background-color: var(--text-primary);
    }

    .toggle-input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }

    .toggle-text {
        font-size: 0.9375rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    /* Badges - shadcn style */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.625rem;
        font-size: 0.8125rem;
        font-weight: 500;
        border-radius: 9999px;
        line-height: 1.5;
    }

    .badge-primary {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .badge-success {
        background-color: var(--success-bg);
        color: var(--success-color);
    }

    .badge-warning {
        background-color: var(--warning-bg);
        color: var(--warning-color);
    }

    .badge-danger {
        background-color: var(--error-bg);
        color: var(--error-color);
    }

    .badge-secondary {
        background-color: var(--bg-tertiary);
        color: var(--text-secondary);
    }

    /* Alerts - shadcn style */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: 10px;
        border: 1px solid;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
    }

    .alert svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-weight: 600;
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    .alert-message {
        font-size: 0.9375rem;
    }

    .alert-success {
        background-color: var(--success-bg);
        border-color: var(--success-border);
        color: var(--success-color);
    }

    .alert-error {
        background-color: var(--error-bg);
        border-color: var(--error-border);
        color: var(--error-color);
    }

    .alert-warning {
        background-color: var(--warning-bg);
        border-color: var(--warning-border);
        color: var(--warning-color);
    }

    .alert-info {
        background-color: var(--info-bg);
        border-color: var(--info-border);
        color: var(--info-color);
    }

    /* Pagination */
    .pagination {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        justify-content: center;
        padding: 1rem;
    }

    .pagination a,
    .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 0.5rem;
        font-size: 0.8125rem;
        font-weight: 500;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .pagination a {
        color: var(--text-secondary);
        background-color: var(--bg-primary);
        border: 1px solid var(--border-color);
    }

    .pagination a:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .pagination span.current {
        background-color: var(--text-primary);
        color: var(--bg-primary);
        border: 1px solid var(--text-primary);
    }

    .pagination span.disabled {
        color: var(--text-muted);
        cursor: not-allowed;
    }

    /* Modal - shadcn style */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        z-index: 200;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal {
        background: var(--bg-primary);
        border-radius: 12px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        transform: scale(0.95);
        transition: transform 0.2s ease;
    }

    .modal-overlay.active .modal {
        transform: scale(1);
    }

    .modal-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .modal-close {
        padding: 0.375rem;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .modal-close:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .modal-close svg {
        width: 18px;
        height: 18px;
    }

    .modal-body {
        padding: 1.25rem;
    }

    .modal-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    /* Search & Filters */
    .filters-bar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 320px;
    }

    .search-box input {
        width: 100%;
        padding-left: 2.25rem;
    }

    .search-box svg {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        width: 14px;
        height: 14px;
        color: var(--text-muted);
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        white-space: nowrap;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .empty-state-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 1rem;
        color: var(--text-muted);
    }

    .empty-state-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .empty-state-description {
        font-size: 0.8125rem;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }

    /* User cell in tables */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .user-cell-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bg-primary);
        font-size: 0.6875rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .user-cell-name {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.8125rem;
    }

    .user-cell-email {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .action-buttons form {
        display: flex;
        align-items: center;
    }

    .action-btn {
        padding: 0.5rem;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .action-btn-danger:hover {
        background-color: var(--error-bg);
        color: var(--error-color);
    }

    .action-btn svg {
        width: 18px;
        height: 18px;
        display: block;
    }

    /* Badge list */
    .badge-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    /* Grid layouts */
    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    /* Checkbox list */
    .checkbox-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.5rem;
    }

    .checkbox-item {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.625rem;
        background-color: var(--bg-secondary);
        border-radius: 6px;
        transition: background-color 0.15s ease;
        cursor: pointer;
    }

    .checkbox-item:hover {
        background-color: var(--bg-tertiary);
    }

    .checkbox-item-title {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .checkbox-item-description {
        font-size: 0.6875rem;
        color: var(--text-muted);
        margin-top: 0.125rem;
    }

    /* Settings Navigation */
    .settings-nav {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 1.5rem;
        padding: 0.25rem;
        background-color: var(--bg-secondary);
        border-radius: 8px;
        width: fit-content;
    }

    .settings-nav-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .settings-nav-item:hover {
        color: var(--text-primary);
    }

    .settings-nav-item.active {
        color: var(--text-primary);
        background-color: var(--bg-primary);
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }

    .settings-nav-item svg {
        width: 14px;
        height: 14px;
    }

    /* Feature grid */
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.75rem;
    }

    .feature-grid .form-group {
        margin-bottom: 0;
    }

    /* Form actions */
    .form-actions {
        display: flex;
        gap: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    /* Quick actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.5rem;
    }

    .quick-action-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.375rem;
        padding: 0.875rem;
        background-color: var(--bg-secondary);
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.15s ease;
        border: 1px solid transparent;
    }

    .quick-action-card:hover {
        background-color: var(--bg-tertiary);
        border-color: var(--border-color);
    }

    .quick-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--text-primary);
    }

    .quick-action-icon svg {
        width: 16px;
        height: 16px;
        color: var(--bg-primary);
    }

    .quick-action-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-primary);
        text-align: center;
    }

    /* Activity list */
    .activity-list {
        display: flex;
        flex-direction: column;
    }

    .activity-item {
        display: flex;
        gap: 0.625rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: var(--bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-icon svg {
        width: 12px;
        height: 12px;
        color: var(--text-muted);
    }

    .activity-text {
        font-size: 0.8125rem;
        color: var(--text-primary);
    }

    .activity-time {
        font-size: 0.6875rem;
        color: var(--text-muted);
        margin-top: 0.125rem;
    }

    /* Responsive */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 99;
    }

    @media (max-width: 1024px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-overlay.active {
            display: block;
        }

        .main-content {
            margin-left: 0;
        }

        .mobile-menu-btn {
            display: flex;
        }
    }

    @media (max-width: 768px) {
        .page-header-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            max-width: none;
        }

        .user-dropdown-btn .user-info {
            display: none;
        }

        .table th,
        .table td {
            padding: 0.625rem 0.5rem;
        }

        .page-content {
            padding: 1rem;
        }
    }

    /* Tabs */
    .tabs {
        display: flex;
        gap: 0;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .tab-link {
        padding: 0.625rem 1rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-muted);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: all 0.15s ease;
    }

    .tab-link:hover {
        color: var(--text-primary);
    }

    .tab-link.active {
        color: var(--text-primary);
        border-bottom-color: var(--text-primary);
    }

    /* Spinner */
    .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid var(--border-color);
        border-top-color: var(--text-primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Profile avatar */
    .profile-avatar-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .profile-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--bg-primary);
        font-size: 1.5rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .profile-avatar-info h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.125rem;
    }

    .profile-avatar-info p {
        font-size: 0.8125rem;
        color: var(--text-muted);
    }
</style>
