@php
    $toastPosition = config('tyro-dashboard.notifications.toast_position', 'bottom-right');
    $autoDismissSeconds = config('tyro-dashboard.notifications.auto_dismiss_seconds', 5);
@endphp

<div id="toast-container" 
     class="toast-container" 
     data-position="{{ $toastPosition }}"
     data-auto-dismiss="{{ $autoDismissSeconds * 1000 }}">
    
    @if (session('success'))
        <div class="toast toast-success" data-toast-type="success">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="toast-content">
                <p class="toast-message">{{ session('success') }}</p>
            </div>
            <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="toast toast-error" data-toast-type="error">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="toast-content">
                <p class="toast-message">{{ session('error') }}</p>
            </div>
            <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div class="toast toast-warning" data-toast-type="warning">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="toast-content">
                <p class="toast-message">{{ session('warning') }}</p>
            </div>
            <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('info'))
        <div class="toast toast-info" data-toast-type="info">
            <div class="toast-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="toast-content">
                <p class="toast-message">{{ session('info') }}</p>
            </div>
            <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if ($errors->any() && config('tyro-dashboard.resource_ui.show_global_errors', true))
        <div class="toast toast-error" data-toast-type="error">
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
            <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const autoDismiss = parseInt(container.dataset.autoDismiss || 5000);
        const toasts = container.querySelectorAll('.toast');

        toasts.forEach((toast, index) => {
            // Stagger the appearance
            setTimeout(() => {
                toast.style.animation = 'toast-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards';
            }, index * 100);

            // Auto dismiss
            setTimeout(() => {
                dismissToast(toast);
            }, autoDismiss + (index * 100));
        });
    });

    function dismissToast(toast) {
        if (!toast || toast.classList.contains('toast-dismissing')) return;
        
        const position = document.getElementById('toast-container')?.dataset.position || 'bottom-right';
        
        toast.classList.add('toast-dismissing');
        
        if (position === 'top-right') {
            toast.style.animation = 'toast-out-top 0.3s ease forwards';
        } else {
            toast.style.animation = 'toast-out 0.3s ease forwards';
        }
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
</script>