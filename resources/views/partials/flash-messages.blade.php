@php
    $notificationStyle = config('tyro-dashboard.notifications.notification_style', 'legacy');
    $toastPosition = config('tyro-dashboard.notifications.toast_position', 'bottom-right');
    $autoDismissSeconds = config('tyro-dashboard.notifications.auto_dismiss_seconds', 5);
@endphp

@if ($notificationStyle === 'toast')
    <div id="toast-container" class="toast-container" data-position="{{ $toastPosition }}" data-auto-dismiss="{{ $autoDismissSeconds * 1000 }}">
        
        @if (session('success'))
        <div class="toast toast-success">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="toast-content">
                <p class="toast-message">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="toast toast-error">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="toast-content">
                <p class="toast-message">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if (session('warning'))
        <div class="toast toast-warning">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="toast-content">
                <p class="toast-message">{{ session('warning') }}</p>
            </div>
        </div>
        @endif

        @if (session('info'))
        <div class="toast toast-info">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="toast-content">
                <p class="toast-message">{{ session('info') }}</p>
            </div>
        </div>
        @endif

        @if ($errors->any() && config('tyro-dashboard.resource_ui.show_global_errors', true))
        <div class="toast toast-error">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="toast-content">
                <p class="toast-title">Please correct the following errors:</p>
                <ul class="toast-error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
@else
    @if (session('success'))
    <div class="alert alert-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="alert-content">
            <p class="alert-message">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="alert-content">
            <p class="alert-message">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if (session('warning'))
    <div class="alert alert-warning">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div class="alert-content">
            <p class="alert-message">{{ session('warning') }}</p>
        </div>
    </div>
    @endif

    @if (session('info'))
    <div class="alert alert-info">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="alert-content">
            <p class="alert-message">{{ session('info') }}</p>
        </div>
    </div>
    @endif

    @if ($errors->any() && config('tyro-dashboard.resource_ui.show_global_errors', true))
    <div class="alert alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="alert-content">
            <p class="alert-title">Please correct the following errors:</p>
            <ul style="margin-top: 0.5rem; margin-left: 1rem; list-style: disc;">
                @foreach ($errors->all() as $error)
                    <li style="font-size: 0.875rem;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
@endif
