<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('tyro-dashboard.index') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="sidebar-logo-text">{{ $branding['app_name'] ?? config('app.name', 'Laravel') }}</span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <!-- Main Menu -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Menu</div>
            <a href="{{ route('tyro-dashboard.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.index') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            <a href="{{ route('tyro-dashboard.profile') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.profile*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Examples</div>
            <a href="{{ route('tyro-dashboard.components') }}" class="sidebar-link {{ (request()->routeIs('tyro-dashboard.components') || request()->routeIs('tyro-dashboard.examples.components')) ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2v-3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2v-3z" />
                </svg>
                Dashboard Components
            </a>

            <a href="{{ route('tyro-dashboard.widgets') }}" class="sidebar-link {{ (request()->routeIs('tyro-dashboard.widgets') || request()->routeIs('tyro-dashboard.examples.widgets')) ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5h6v6H5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 13h6v6h-6z" />
                </svg>
                Widgets
            </a>
        </div>
    </nav>
</aside>
