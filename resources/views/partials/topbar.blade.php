<header class="topbar">
    <div class="topbar-left">
        <button type="button" class="mobile-menu-btn" onclick="toggleSidebar()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <nav class="breadcrumb">
            @yield('breadcrumb')
        </nav>
    </div>

    <div class="topbar-right">
        <!-- Theme Toggle -->
        <button type="button" class="topbar-btn" onclick="toggleTheme()" aria-label="Toggle theme">
            <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>

        <!-- User Dropdown -->
        <div class="user-dropdown" id="userDropdown">
            <button type="button" class="user-dropdown-btn" onclick="toggleUserDropdown()">
                <div class="user-avatar">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ $user->name ?? 'User' }}</div>
                    <div class="user-role">
                        @if(method_exists($user, 'roles') && $user->roles->count())
                            {{ $user->roles->first()->name }}
                        @else
                            User
                        @endif
                    </div>
                </div>
                <svg class="user-dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div class="user-dropdown-menu">
                <a href="{{ route('tyro-dashboard.profile') }}" class="dropdown-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    My Profile
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('tyro-login.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item dropdown-item-danger" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
