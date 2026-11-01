{{-- Deprecated: split into topic partials. Kept so views rendering this partial directly keep working. system.blade.php no longer includes it. --}}
@include('tyro-dashboard::settings.partials._tab-auth-signin')
@include('tyro-dashboard::settings.partials._tab-auth-verification')
@include('tyro-dashboard::settings.partials._tab-auth-social')
@include('tyro-dashboard::settings.partials._tab-auth-lockout')
@include('tyro-dashboard::settings.partials._tab-auth-pages')
@include('tyro-dashboard::settings.partials._tab-auth-emails')
