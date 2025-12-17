{{-- Include shadcn theme variables --}}
@include('tyro-dashboard::partials.shadcn-theme')

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: var(--muted);
        min-height: 100vh;
        line-height: 1.6;
        color: var(--foreground);
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
        background-color: var(--sidebar);
        border-right: 1px solid var(--border);
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
        border-bottom: 1px solid var(--border);
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
        background: var(--foreground);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-logo-icon svg {
        width: 20px;
        height: 20px;
        color: var(--background);
    }

    .sidebar-logo-text {
        font-size: 1rem;
        font-weight: 600;
        color: var(--foreground);
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
        color: var(--muted-foreground);
        padding: 0.625rem 1rem;
        margin-bottom: 0.25rem;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        color: var(--sidebar-foreground);
        text-decoration: none;
        font-size: 0.9375rem;
        font-weight: 500;
        transition: all 0.15s ease;
        margin-bottom: 2px;
    }

    .sidebar-link:hover {
        background-color: var(--sidebar-accent);
        color: var(--sidebar-accent-foreground);
    }

    .sidebar-link.active {
        background-color: var(--sidebar-primary);
        color: var(--sidebar-primary-foreground);
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
        background-color: var(--background);
        border-bottom: 1px solid var(--border);
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
        color: var(--foreground);
        cursor: pointer;
        border-radius: 6px;
    }

    .mobile-menu-btn:hover {
        background-color: var(--muted);
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
        color: var(--muted-foreground);
    }

    .breadcrumb a {
        color: var(--muted-foreground);
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .breadcrumb a:hover {
        color: var(--foreground);
    }

    .breadcrumb-separator {
        color: var(--muted-foreground);
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
        color: var(--muted-foreground);
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .topbar-btn:hover {
        background-color: var(--muted);
        color: var(--foreground);
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
        border: 1px solid var(--border);
        background: var(--background);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .user-dropdown-btn:hover {
        background-color: var(--muted);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--foreground);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--background);
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .user-info {
        text-align: left;
    }

    .user-name {
        font-size: 0.9375rem;
        font-weight: 500;
        color: var(--foreground);
        line-height: 1.3;
    }

    .user-role {
        font-size: 0.8125rem;
        color: var(--muted-foreground);
        line-height: 1.3;
    }

    .user-dropdown-arrow {
        width: 14px;
        height: 14px;
        color: var(--muted-foreground);
    }

    .user-dropdown-menu {
        position: absolute;
        top: calc(100% + 4px);
        right: 0;
        background: var(--background);
        border: 1px solid var(--border);
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
        color: var(--muted-foreground);
        text-decoration: none;
        font-size: 0.8125rem;
        border-radius: 4px;
        transition: all 0.15s ease;
    }

    .dropdown-item:hover {
        background-color: var(--muted);
        color: var(--foreground);
    }

    .dropdown-item svg {
        width: 14px;
        height: 14px;
    }

    .dropdown-divider {
        height: 1px;
        background-color: var(--border);
        margin: 0.25rem 0;
    }

    .dropdown-item-danger {
        color: var(--destructive);
    }

    .dropdown-item-danger:hover {
        background-color: color-mix(in srgb, var(--destructive), transparent 90%);
        color: var(--destructive);
    }

    /* Page Content */
    .page-content {
        padding: 2rem;
        flex: 1;
        background-color: var(--background);
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
        color: var(--foreground);
        letter-spacing: -0.025em;
        line-height: 1.2;
    }

    .page-description {
        margin-top: 0.375rem;
        font-size: 0.9375rem;
        color: var(--muted-foreground);
    }

    /* Cards - shadcn style */
    .card {
        background: var(--background);
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: var(--card-shadow);
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--foreground);
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border);
        background-color: var(--muted);
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
        background: var(--background);
        border: 1px solid var(--border);
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
        background-color: var(--muted);
        color: var(--foreground);
    }

    .stat-icon-success {
        background-color: color-mix(in srgb, var(--success), transparent 90%);
        color: var(--success);
    }

    .stat-icon-warning {
        background-color: color-mix(in srgb, var(--warning), transparent 90%);
        color: var(--warning);
    }

    .stat-icon-danger {
        background-color: color-mix(in srgb, var(--destructive), transparent 90%);
        color: var(--destructive);
    }

    .stat-icon-info {
        background-color: color-mix(in srgb, var(--info), transparent 90%);
        color: var(--info);
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--muted-foreground);
        margin-bottom: 0.375rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--foreground);
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
        color: var(--success);
    }

    .stat-change-down {
        color: var(--destructive);
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
        border-bottom: 1px solid var(--border);
    }

    .table th {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--muted-foreground);
        background-color: var(--muted);
    }

    .table td {
        font-size: 0.9375rem;
        color: var(--foreground);
    }

    .table tbody tr:hover {
        background-color: var(--muted);
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
        background-color: var(--primary);
        color: var(--primary-foreground);
    }

    .btn-primary:hover {
        background-color: color-mix(in srgb, var(--primary), black 10%);
    }

    .btn-secondary {
        background-color: var(--background);
        color: var(--foreground);
        border: 1px solid var(--border);
    }

    .btn-secondary:hover {
        background-color: var(--muted);
    }

    .btn-danger {
        background-color: var(--destructive);
        color: var(--destructive-foreground);
    }

    .btn-danger:hover {
        background-color: color-mix(in srgb, var(--destructive), black 10%);
    }

    .btn-success {
        background-color: var(--success);
        color: var(--success-foreground);
    }

    .btn-success:hover {
        background-color: color-mix(in srgb, var(--success), black 10%);
    }

    .btn-warning {
        background-color: var(--warning);
        color: #fff;
    }

    .btn-warning:hover {
        background-color: color-mix(in srgb, var(--warning), black 10%);
    }

    .btn-outline {
        background-color: transparent;
        border: 1px solid var(--border);
        color: var(--foreground);
    }

    .btn-outline:hover {
        background-color: var(--muted);
    }

    .btn-ghost {
        background-color: transparent;
        color: var(--muted-foreground);
    }

    .btn-ghost:hover {
        background-color: var(--muted);
        color: var(--foreground);
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
        color: var(--foreground);
        margin-bottom: 0.5rem;
    }

    .form-label-optional {
        color: var(--muted-foreground);
        font-weight: 400;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.625rem 0.875rem;
        font-size: 0.9375rem;
        font-family: inherit;
        border: 1px solid var(--input);
        border-radius: 8px;
        background-color: var(--background);
        color: var(--foreground);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        line-height: 1.5;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: var(--muted-foreground);
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--ring);
        box-shadow: 0 0 0 2px var(--muted);
    }

    .form-input.is-invalid,
    .form-select.is-invalid,
    .form-textarea.is-invalid {
        border-color: var(--destructive);
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
        color: var(--muted-foreground);
        margin-top: 0.5rem;
    }

    .form-error {
        font-size: 0.8125rem;
        color: var(--destructive);
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
        border: 1px solid var(--input);
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
        background-color: var(--foreground);
        border-color: var(--foreground);
    }

    .checkbox-input:checked::after {
        content: '';
        position: absolute;
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid var(--background);
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
        background: var(--background);
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
        background-color: var(--input);
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
        background-color: var(--foreground);
    }

    .toggle-input:checked + .toggle-slider:before {
        transform: translateX(20px);
    }

    .toggle-text {
        font-size: 0.9375rem;
        font-weight: 500;
        color: var(--foreground);
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
        background-color: var(--muted);
        color: var(--foreground);
        border: 1px solid var(--border);
    }

    .badge-success {
        background-color: color-mix(in srgb, var(--success), transparent 90%);
        color: var(--success);
    }

    .badge-warning {
        background-color: color-mix(in srgb, var(--warning), transparent 90%);
        color: var(--warning);
    }

    .badge-danger {
        background-color: color-mix(in srgb, var(--destructive), transparent 90%);
        color: var(--destructive);
    }

    .badge-secondary {
        background-color: var(--accent);
        color: var(--muted-foreground);
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
        background-color: color-mix(in srgb, var(--success), transparent 90%);
        border-color: var(--success);
        color: var(--success);
    }

    .alert-error {
        background-color: color-mix(in srgb, var(--destructive), transparent 90%);
        border-color: var(--destructive);
        color: var(--destructive);
    }

    .alert-warning {
        background-color: color-mix(in srgb, var(--warning), transparent 90%);
        border-color: var(--warning);
        color: var(--warning);
    }

    .alert-info {
        background-color: color-mix(in srgb, var(--info), transparent 90%);
        border-color: var(--info);
        color: var(--info);
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
        color: var(--muted-foreground);
        background-color: var(--background);
        border: 1px solid var(--border);
    }

    .pagination a:hover {
        background-color: var(--muted);
        color: var(--foreground);
    }

    .pagination span.current {
        background-color: var(--foreground);
        color: var(--background);
        border: 1px solid var(--foreground);
    }

    .pagination span.disabled {
        color: var(--muted-foreground);
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
        background: var(--background);
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
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--foreground);
    }

    .modal-close {
        padding: 0.375rem;
        border: none;
        background: transparent;
        color: var(--muted-foreground);
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .modal-close:hover {
        background-color: var(--muted);
        color: var(--foreground);
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
        border-top: 1px solid var(--border);
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
        color: var(--muted-foreground);
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.75rem;
        color: var(--muted-foreground);
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
        color: var(--muted-foreground);
    }

    .empty-state-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--foreground);
        margin-bottom: 0.25rem;
    }

    .empty-state-description {
        font-size: 0.8125rem;
        color: var(--muted-foreground);
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
        background: var(--foreground);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--background);
        font-size: 0.6875rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .user-cell-name {
        font-weight: 500;
        color: var(--foreground);
        /* font-size: 0.8125rem; */
    }

    .user-cell-email {
        font-size: 0.75rem;
        color: var(--muted-foreground);
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
        color: var(--muted-foreground);
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        background-color: var(--muted);
        color: var(--foreground);
    }

    .action-btn-danger:hover {
        background-color: color-mix(in srgb, var(--destructive), transparent 90%);
        color: var(--destructive);
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
        background-color: var(--muted);
        border-radius: 6px;
        transition: background-color 0.15s ease;
        cursor: pointer;
    }

    .checkbox-item:hover {
        background-color: var(--accent);
    }

    .checkbox-item-title {
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--foreground);
    }

    .checkbox-item-description {
        font-size: 0.6875rem;
        color: var(--muted-foreground);
        margin-top: 0.125rem;
    }

    /* Settings Navigation */
    .settings-nav {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 1.5rem;
        padding: 0.25rem;
        background-color: var(--muted);
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
        color: var(--muted-foreground);
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.15s ease;
    }

    .settings-nav-item:hover {
        color: var(--foreground);
    }

    .settings-nav-item.active {
        color: var(--foreground);
        background-color: var(--background);
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
        border-top: 1px solid var(--border);
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
        background-color: var(--muted);
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.15s ease;
        border: 1px solid transparent;
    }

    .quick-action-card:hover {
        background-color: var(--accent);
        border-color: var(--border);
    }

    .quick-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--foreground);
    }

    .quick-action-icon svg {
        width: 16px;
        height: 16px;
        color: var(--background);
    }

    .quick-action-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--foreground);
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
        border-bottom: 1px solid var(--border);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: var(--muted);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-icon svg {
        width: 12px;
        height: 12px;
        color: var(--muted-foreground);
    }

    .activity-text {
        font-size: 0.8125rem;
        color: var(--foreground);
    }

    .activity-time {
        font-size: 0.6875rem;
        color: var(--muted-foreground);
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
        border-bottom: 1px solid var(--border);
        margin-bottom: 1.5rem;
    }

    .tab-link {
        padding: 0.625rem 1rem;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--muted-foreground);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: all 0.15s ease;
    }

    .tab-link:hover {
        color: var(--foreground);
    }

    .tab-link.active {
        color: var(--foreground);
        border-bottom-color: var(--foreground);
    }

    /* Spinner */
    .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid var(--border);
        border-top-color: var(--foreground);
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
        border-bottom: 1px solid var(--border);
    }

    .profile-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--foreground);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--background);
        font-size: 1.5rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .profile-avatar-info h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--foreground);
        margin-bottom: 0.125rem;
    }

    .profile-avatar-info p {
        font-size: 0.8125rem;
        color: var(--muted-foreground);
    }
</style>
