{{-- Alerts (success/warning) --}}
<div class="grid-2" style="margin-bottom: 1.5rem;">
    <div class="alert" style="border-color: color-mix(in srgb, var(--success), transparent 70%); background-color: color-mix(in srgb, var(--success), transparent 92%);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--success);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <div class="alert-content">
            <div class="alert-title">All systems operational</div>
            <div class="alert-message" style="color: var(--muted-foreground);">Queues are healthy, database latency is stable, and error rate is within target.</div>
        </div>
    </div>

    <div class="alert" style="border-color: color-mix(in srgb, var(--warning), transparent 70%); background-color: color-mix(in srgb, var(--warning), transparent 92%);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--warning);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
        </svg>
        <div class="alert-content">
            <div class="alert-title">Heads up: review pending items</div>
            <div class="alert-message" style="color: var(--muted-foreground);">A few records are waiting for approval. Use badges + tables below to display review queues.</div>
        </div>
    </div>
</div>
