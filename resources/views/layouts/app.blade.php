<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">

    <title>@yield('title', 'Dashboard') - {{ $branding['app_name'] ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @include('tyro-dashboard::partials.styles')
    @stack('styles')
</head>

<body>
    <div class="dashboard-layout">
        <!-- Sidebar - Conditional based on role -->
        @hasanyrole('admin', 'superadmin')
            @include('tyro-dashboard::partials.admin-sidebar')
        @else
            @include('tyro-dashboard::partials.user-sidebar')
        @endhasanyrole

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            @include('tyro-dashboard::partials.topbar')

            <!-- Page Content -->
            <main class="page-content">
                <!-- Flash Messages -->
                @include('tyro-dashboard::partials.flash-messages')

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    @include('tyro-dashboard::partials.scripts')
    @stack('scripts')
</body>

</html>