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
        @if(config('tyro-dashboard.collapsible_sidebar', false))
        <button class="sidebar-collapse-btn" onclick="toggleSidebarCollapse()" aria-label="Collapse sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        @endif
    </div>
    @if(config('tyro-dashboard.collapsible_sidebar', false))
    <button class="sidebar-expand-btn" onclick="toggleSidebarCollapse()" aria-label="Expand sidebar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </button>
    @endif

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
            @if(config('tyro-dashboard.features.invitation_system', true))
            <a href="{{ route('tyro-dashboard.invitations.index') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.invitations.index') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                My Invitation Link
            </a>
            @endif

            @if(!empty($commonMenuItems))
                @foreach($commonMenuItems as $item)
                    <a href="{{ route($item['route'] ?? '#') }}" class="sidebar-link {{ request()->routeIs($item['route'] ?? '') ? 'active' : '' }}">
                        @if(isset($item['icon']))
                            {!! $item['icon'] !!}
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        @endif
                        {{ $item['title'] ?? 'Menu Item' }}
                    </a>
                @endforeach
            @endif

            @if(!empty($userMenuItems))
                @foreach($userMenuItems as $item)
                    <a href="{{ route($item['route'] ?? '#') }}" class="sidebar-link {{ request()->routeIs($item['route'] ?? '') ? 'active' : '' }}">
                        @if(isset($item['icon']))
                            {!! $item['icon'] !!}
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        @endif
                        {{ $item['title'] ?? 'Menu Item' }}
                    </a>
                @endforeach
            @endif
        </div>

        @php
            // Filter resources to only those user can access
            $accessibleResources = [];
            foreach ($allResources ?? config('tyro-dashboard.resources', []) as $key => $resource) {
                $canAccess = true;
                if (isset($resource['roles']) && !empty($resource['roles'])) {
                    $canAccess = false;
                    $user = auth()->user();
                    if ($user && method_exists($user, 'tyroRoleSlugs')) {
                        $userRoles = $user->tyroRoleSlugs();
                        // Check allowed roles
                        foreach ($resource['roles'] as $role) {
                            if (in_array($role, $userRoles)) {
                                $canAccess = true;
                                break;
                            }
                        }
                        // Check readonly roles (if not already allowed)
                        if (!$canAccess && isset($resource['readonly']) && !empty($resource['readonly'])) {
                            foreach ($resource['readonly'] as $role) {
                                if (in_array($role, $userRoles)) {
                                    $canAccess = true;
                                    break;
                                }
                            }
                        }
                    }
                }
                if ($canAccess) {
                    $accessibleResources[$key] = $resource;
                }
            }
        @endphp

        @if(!empty($accessibleResources))
        <div class="sidebar-section">
            <div class="sidebar-section-title">Resources</div>
            @foreach($accessibleResources as $key => $resource)
                <a href="{{ route('tyro-dashboard.resources.index', $key) }}" class="sidebar-link {{ request()->is('*resources/'.$key.'*') ? 'active' : '' }}">
                    @if(isset($resource['icon']))
                        {!! $resource['icon'] !!}
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    @endif
                    {{ $resource['title'] }}
                </a>
            @endforeach
        </div>
        @endif

        @if(!config('tyro-dashboard.disable_examples', false) && !app()->environment('production'))
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

            @if(class_exists('HasinHayder\\TyroDashboardComponents\\TyroDashboardComponentsServiceProvider'))
            <a href="{{ route('tyro-dashboard.x-components') }}" class="sidebar-link {{ request()->routeIs('tyro-dashboard.x-components') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Form Components
            </a>
            @endif
        </div>
        @endif
    </nav>
</aside>
