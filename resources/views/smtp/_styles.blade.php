<style>
    .smtp-stats-grid .stat-card {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 1rem !important;
        padding: 1.25rem !important;
    }
    .smtp-stats-grid .stat-card-left {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 1rem !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
    }
    .smtp-stats-grid .stat-icon { width: 48px; height: 48px; flex-shrink: 0; margin-bottom: 0; }
    .smtp-stats-grid .stat-icon svg { width: 24px; height: 24px; }
    .smtp-stats-grid .stat-value { font-size: 1.1rem; word-break: break-all; }
    .smtp-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 640px) { .smtp-form-grid { grid-template-columns: 1fr; } }
    .smtp-preset-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }
    .smtp-preset-card {
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--card);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .smtp-preset-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .smtp-preset-name { font-weight: 600; font-size: 0.9375rem; color: var(--foreground); }
    .smtp-preset-meta {
        padding: 1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
    }
    .smtp-preset-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        font-size: 0.8125rem;
    }
    .smtp-preset-row span:first-child { color: var(--muted-foreground); }
    .smtp-preset-row span:last-child { color: var(--foreground); font-weight: 500; word-break: break-all; text-align: right; }
    .smtp-preset-actions {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .smtp-preset-actions .btn { flex: 1; }
    .smtp-preset-empty {
        text-align: center;
        padding: 2.5rem 1.5rem;
        border: 1px dashed var(--border);
        border-radius: 10px;
        background: var(--muted);
    }
    .smtp-preset-empty p { color: var(--muted-foreground); font-size: 0.875rem; margin-top: 0.5rem; }
    .smtp-modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 640px) { .smtp-modal-grid { grid-template-columns: 1fr; } }
    .smtp-test-row {
        display: flex;
        align-items: end;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .smtp-test-row .form-group { flex: 1; min-width: 200px; margin-bottom: 0; }
    #smtpUsePresetModal .modal { max-width: 760px; width: 96%; }
    #smtpUsePresetModal .modal-body { max-height: 60vh; overflow-y: auto; }
    .smtp-btn-save-preset {
        background-color: color-mix(in srgb, var(--info), transparent 88%);
        color: var(--info);
        border: 1px solid color-mix(in srgb, var(--info), transparent 70%);
    }
    .smtp-btn-save-preset:hover {
        background-color: color-mix(in srgb, var(--info), transparent 82%);
        border-color: color-mix(in srgb, var(--info), transparent 55%);
        color: var(--info);
    }
</style>
