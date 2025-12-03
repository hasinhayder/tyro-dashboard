<style>
    :root {
        /* Light theme */
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --bg-tertiary: #f1f5f9;
        --text-primary: #0f172a;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --border-color: #e2e8f0;
        --border-color-light: #f1f5f9;
        --input-bg: #ffffff;
        --input-border: #cbd5e1;
        --input-focus-border: #0f172a;
        --btn-primary-bg: #0f172a;
        --btn-primary-text: #ffffff;
        --btn-primary-hover: #1e293b;
        --btn-secondary-bg: #f1f5f9;
        --btn-secondary-text: #475569;
        --btn-secondary-hover: #e2e8f0;
        --btn-danger-bg: #ef4444;
        --btn-danger-text: #ffffff;
        --btn-danger-hover: #dc2626;
        --btn-success-bg: #22c55e;
        --btn-success-text: #ffffff;
        --btn-success-hover: #16a34a;
        --btn-warning-bg: #f59e0b;
        --btn-warning-text: #ffffff;
        --btn-warning-hover: #d97706;
        --link-color: #0f172a;
        --link-hover: #334155;
        --error-color: #ef4444;
        --error-bg: #fef2f2;
        --error-border: #fecaca;
        --success-color: #22c55e;
        --success-bg: #f0fdf4;
        --success-border: #bbf7d0;
        --warning-color: #f59e0b;
        --warning-bg: #fffbeb;
        --warning-border: #fde68a;
        --info-color: #3b82f6;
        --info-bg: #eff6ff;
        --info-border: #bfdbfe;
        --sidebar-bg: #0f172a;
        --sidebar-text: #94a3b8;
        --sidebar-text-active: #ffffff;
        --sidebar-hover: #1e293b;
        --sidebar-active: #334155;
        --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.05);
        --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    }

    html.dark {
        --bg-primary: #0f172a;
        --bg-secondary: #1e293b;
        --bg-tertiary: #334155;
        --text-primary: #f8fafc;
        --text-secondary: #cbd5e1;
        --text-muted: #64748b;
        --border-color: #334155;
        --border-color-light: #1e293b;
        --input-bg: #1e293b;
        --input-border: #475569;
        --input-focus-border: #f8fafc;
        --btn-primary-bg: #f8fafc;
        --btn-primary-text: #0f172a;
        --btn-primary-hover: #e2e8f0;
        --btn-secondary-bg: #334155;
        --btn-secondary-text: #cbd5e1;
        --btn-secondary-hover: #475569;
        --link-color: #f8fafc;
        --link-hover: #cbd5e1;
        --error-bg: #1f1515;
        --error-border: #7f1d1d;
        --success-bg: #052e16;
        --success-border: #14532d;
        --warning-bg: #422006;
        --warning-border: #713f12;
        --info-bg: #1e3a5f;
        --info-border: #1e40af;
        --sidebar-bg: #020617;
        --sidebar-hover: #0f172a;
        --sidebar-active: #1e293b;
        --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.2), 0 1px 2px rgba(0, 0, 0, 0.2);
        --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        background-color: var(--bg-secondary);
        min-height: 100vh;
        line-height: 1.6;
        color: var(--text-primary);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Dashboard Layout */
    .dashboard-layout {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 260px;
        background-color: var(--sidebar-bg);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 100;
        transition: transform 0.3s ease;
    }

    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
    }

    .sidebar-logo-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-logo-icon svg {
        width: 20px;
        height: 20px;
        color: white;
    }

    .sidebar-logo-text {
        font-size: 1.125rem;
        font-weight: 600;
        color: white;
    }

    .sidebar-nav {
        padding: 1rem 0;
    }

    .sidebar-section {
        padding: 0 0.75rem;
        margin-bottom: 1.5rem;
    }

    .sidebar-section-title {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--sidebar-text);
        padding: 0 0.75rem;
        margin-bottom: 0.5rem;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 0.75rem;
        border-radius: 0.5rem;
        color: var(--sidebar-text);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.15s ease;
    }

    .sidebar-link:hover {
        background-color: var(--sidebar-hover);
        color: var(--sidebar-text-active);
    }

    .sidebar-link.active {
        background-color: var(--sidebar-active);
        color: var(--sidebar-text-active);
    }

    .sidebar-link svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    /* Main Content */
    .main-content {
        flex: 1;
        margin-left: 260px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Top Bar */
    .topbar {
        background-color: var(--bg-primary);
        border-bottom: 1px solid var(--border-color);
        padding: 0.75rem 1.5rem;
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
        gap: 1rem;
    }

    .mobile-menu-btn {
        display: none;
        padding: 0.5rem;
        border: none;
        background: transparent;
        color: var(--text-primary);
        cursor: pointer;
        border-radius: 0.5rem;
    }

    .mobile-menu-btn:hover {
        background-color: var(--bg-secondary);
    }

    .mobile-menu-btn svg {
        width: 24px;
        height: 24px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .breadcrumb a {
        color: var(--text-secondary);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        color: var(--text-primary);
    }

    .breadcrumb-separator {
        color: var(--text-muted);
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .topbar-btn {
        padding: 0.5rem;
        border: none;
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        border-radius: 0.5rem;
        transition: all 0.15s ease;
    }

    .topbar-btn:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .topbar-btn svg {
        width: 20px;
        height: 20px;
    }

    .user-dropdown {
        position: relative;
    }

    .user-dropdown-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.375rem 0.75rem;
        padding-right: 0.5rem;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .user-dropdown-btn:hover {
        border-color: var(--input-border);
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .user-info {
        text-align: left;
    }

    .user-name {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .user-role {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .user-dropdown-arrow {
        width: 16px;
        height: 16px;
        color: var(--text-muted);
    }

    .user-dropdown-menu {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        box-shadow: var(--card-shadow-hover);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: all 0.15s ease;
        z-index: 100;
    }

    .user-dropdown.active .user-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 1rem;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.15s ease;
    }

    .dropdown-item:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .dropdown-item svg {
        width: 16px;
        height: 16px;
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
        padding: 1.5rem;
        flex: 1;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        letter-spacing: -0.025em;
    }

    .page-description {
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Cards */
    .card {
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        box-shadow: var(--card-shadow);
        transition: box-shadow 0.15s ease;
    }

    .card:hover {
        box-shadow: var(--card-shadow-hover);
    }

    .card-header {
        padding: 1rem 1.25rem;
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
        padding: 1.25rem;
    }

    .card-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border-color);
        background-color: var(--bg-secondary);
        border-radius: 0 0 0.75rem 0.75rem;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        padding: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: all 0.15s ease;
    }

    .stat-card:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon svg {
        width: 24px;
        height: 24px;
    }

    .stat-icon-primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
    }

    .stat-icon-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }

    .stat-icon-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .stat-icon-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .stat-icon-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .stat-change {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        font-weight: 500;
        margin-top: 0.25rem;
    }

    .stat-change-up {
        color: var(--success-color);
    }

    .stat-change-down {
        color: var(--error-color);
    }

    /* Tables */
    .table-container {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 0.875rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .table th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        background-color: var(--bg-secondary);
    }

    .table td {
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .table tbody tr:hover {
        background-color: var(--bg-secondary);
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        font-family: inherit;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .btn svg {
        width: 16px;
        height: 16px;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
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
        background-color: var(--btn-secondary-bg);
        color: var(--btn-secondary-text);
    }

    .btn-secondary:hover {
        background-color: var(--btn-secondary-hover);
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
        color: var(--text-secondary);
    }

    .btn-outline:hover {
        background-color: var(--bg-secondary);
        border-color: var(--input-border);
        color: var(--text-primary);
    }

    .btn-ghost {
        background-color: transparent;
        color: var(--text-secondary);
    }

    .btn-ghost:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .btn-icon {
        padding: 0.5rem;
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-group {
        display: flex;
        gap: 0.5rem;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
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
        font-size: 0.875rem;
        font-family: inherit;
        border: 1px solid var(--input-border);
        border-radius: 0.5rem;
        background-color: var(--input-bg);
        color: var(--text-primary);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
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
        box-shadow: 0 0 0 1px var(--input-focus-border);
    }

    .form-input.is-invalid,
    .form-select.is-invalid,
    .form-textarea.is-invalid {
        border-color: var(--error-color);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23475569'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
        padding-right: 2.5rem;
    }

    .form-hint {
        font-size: 0.8125rem;
        color: var(--text-muted);
        margin-top: 0.375rem;
    }

    .form-error {
        font-size: 0.8125rem;
        color: var(--error-color);
        margin-top: 0.375rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    /* Checkbox & Radio */
    .checkbox-group,
    .radio-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .checkbox-input,
    .radio-input {
        width: 1rem;
        height: 1rem;
        border-radius: 0.25rem;
        border: 1.5px solid var(--input-border);
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
        background-color: var(--btn-primary-bg);
        border-color: var(--btn-primary-bg);
    }

    .checkbox-input:checked::after {
        content: '';
        position: absolute;
        left: 4px;
        top: 1px;
        width: 5px;
        height: 9px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .radio-input:checked::after {
        content: '';
        position: absolute;
        left: 3px;
        top: 3px;
        width: 6px;
        height: 6px;
        background: white;
        border-radius: 50%;
    }

    .checkbox-label,
    .radio-label {
        font-size: 0.875rem;
        color: var(--text-primary);
        cursor: pointer;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--input-border);
        transition: 0.2s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.2s;
        border-radius: 50%;
    }

    .toggle-switch input:checked+.toggle-slider {
        background-color: var(--btn-primary-bg);
    }

    .toggle-switch input:checked+.toggle-slider:before {
        transform: translateX(20px);
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 9999px;
    }

    .badge-primary {
        background-color: var(--info-bg);
        color: var(--info-color);
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

    /* Alerts */
    .alert {
        padding: 1rem 1.25rem;
        border-radius: 0.5rem;
        border: 1px solid;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
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
        margin-bottom: 0.25rem;
    }

    .alert-message {
        font-size: 0.875rem;
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
        min-width: 36px;
        height: 36px;
        padding: 0 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.5rem;
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
        border-color: var(--input-border);
        color: var(--text-primary);
    }

    .pagination span.current {
        background-color: var(--btn-primary-bg);
        color: var(--btn-primary-text);
        border: 1px solid var(--btn-primary-bg);
    }

    .pagination span.disabled {
        color: var(--text-muted);
        cursor: not-allowed;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
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
        border-radius: 0.75rem;
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
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .modal-close {
        padding: 0.375rem;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 0.375rem;
        transition: all 0.15s ease;
    }

    .modal-close:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .modal-close svg {
        width: 20px;
        height: 20px;
    }

    .modal-body {
        padding: 1.25rem;
    }

    .modal-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* Search & Filters */
    .filters-bar {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 400px;
    }

    .search-box input {
        width: 100%;
        padding-left: 2.5rem;
    }

    .search-box svg {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: var(--text-muted);
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        color: var(--text-muted);
    }

    .empty-state-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .empty-state-description {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    /* User Avatar in table */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-cell-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .user-cell-info {
        min-width: 0;
    }

    .user-cell-name {
        font-weight: 500;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-cell-email {
        font-size: 0.8125rem;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

        .filter-group {
            flex-wrap: wrap;
        }

        .btn-group {
            flex-wrap: wrap;
        }

        .user-dropdown-btn .user-info {
            display: none;
        }

        .table th,
        .table td {
            padding: 0.75rem 0.5rem;
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
        padding: 0.75rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
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
        border-bottom-color: var(--btn-primary-bg);
    }

    /* Loading Spinner */
    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-color);
        border-top-color: var(--btn-primary-bg);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Skeleton loading */
    .skeleton {
        background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-secondary) 50%, var(--bg-tertiary) 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 0.25rem;
    }

    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 0.25rem;
    }

    .action-btn {
        padding: 0.375rem;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        border-radius: 0.375rem;
        transition: all 0.15s ease;
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
        width: 16px;
        height: 16px;
    }

    /* Role/Privilege badges list */
    .badge-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
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

    /* Checkbox list for privileges/roles */
    .checkbox-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .checkbox-item {
        display: flex;
        align-items: flex-start;
        gap: 0.625rem;
        padding: 0.75rem;
        background-color: var(--bg-secondary);
        border-radius: 0.5rem;
        transition: background-color 0.15s ease;
    }

    .checkbox-item:hover {
        background-color: var(--bg-tertiary);
    }

    .checkbox-item-content {
        min-width: 0;
    }

    .checkbox-item-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .checkbox-item-description {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.125rem;
    }

    /* Settings Navigation */
    .settings-nav {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        padding: 0.375rem;
        background-color: var(--bg-secondary);
        border-radius: 0.75rem;
        width: fit-content;
    }

    .settings-nav-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: 0.5rem;
        transition: all 0.15s ease;
    }

    .settings-nav-item:hover {
        color: var(--text-primary);
        background-color: var(--bg-tertiary);
    }

    .settings-nav-item.active {
        color: var(--text-primary);
        background-color: var(--bg-primary);
        box-shadow: var(--card-shadow);
    }

    .settings-nav-item svg {
        width: 18px;
        height: 18px;
    }

    /* Toggle Label */
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

    .toggle-label .toggle-slider {
        position: relative;
        width: 44px;
        height: 24px;
        background-color: var(--input-border);
        border-radius: 24px;
        transition: 0.2s;
        flex-shrink: 0;
    }

    .toggle-label .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.2s;
        border-radius: 50%;
    }

    .toggle-input:checked + .toggle-slider {
        background-color: var(--btn-primary-bg);
    }

    .toggle-input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }

    .toggle-text {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    /* Feature grid for settings */
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .feature-grid .form-group {
        margin-bottom: 0;
    }

    /* Form actions */
    .form-actions {
        display: flex;
        gap: 0.75rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    /* Profile page specific */
    .profile-avatar-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .profile-avatar-info h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .profile-avatar-info p {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Quick actions grid */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.75rem;
    }

    .quick-action-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        background-color: var(--bg-secondary);
        border-radius: 0.5rem;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .quick-action-card:hover {
        background-color: var(--bg-tertiary);
        transform: translateY(-2px);
    }

    .quick-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quick-action-icon svg {
        width: 20px;
        height: 20px;
        color: white;
    }

    .quick-action-label {
        font-size: 0.8125rem;
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
        gap: 0.75rem;
        padding: 0.875rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-icon svg {
        width: 14px;
        height: 14px;
        color: var(--text-muted);
    }

    .activity-content {
        min-width: 0;
        flex: 1;
    }

    .activity-text {
        font-size: 0.875rem;
        color: var(--text-primary);
    }

    .activity-time {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.125rem;
    }
</style>
