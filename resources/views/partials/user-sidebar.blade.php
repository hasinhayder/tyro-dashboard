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
            <a href="{{ route('tyro-dashboard.examples.components') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.examples.components') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2v-3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2v-3z" />
                </svg>
                Example Components
            </a>
            <a href="{{ route('tyro-dashboard.examples.crm') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.examples.crm') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                </svg>
                Example CRM
            </a>
            <a href="{{ route('tyro-dashboard.examples.mail') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.examples.mail') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v16H4V4z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 6l-10 7L2 6" />
                </svg>
                Example Mail
            </a>
            <a href="{{ route('tyro-dashboard.examples.report') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.examples.report') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 19V9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V13" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 19V7" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 19V11" />
                </svg>
                Example Report
            </a>
            <a href="{{ route('tyro-dashboard.examples.media') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.examples.media') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v12H4V4z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 20h20" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 4 4 2-2 3 3" />
                </svg>
                Example Media
            </a>
            <a href="{{ route('tyro-dashboard.examples.support') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.examples.support') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 10c0 3.866-3.582 7-8 7a8.45 8.45 0 01-4-.93L2 17l1.39-3.09A6.82 6.82 0 012 10c0-3.866 3.582-7 8-7s8 3.134 8 7z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01" />
                </svg>
                Example Support
            </a>
        </div>
    </nav>
</aside>
