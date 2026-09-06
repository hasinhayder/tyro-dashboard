<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<style>
    .emailer-container {
        width: 100%;
    }
    .emailer-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .emailer-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .emailer-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--foreground);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .emailer-card-body {
        padding: 1.5rem;
    }
    .emailer-field-group {
        margin-bottom: 1.25rem;
    }
    .emailer-field-group:last-child {
        margin-bottom: 0;
    }
    .emailer-field-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--foreground);
        margin-bottom: 0.375rem;
    }
    .emailer-field-label span.req {
        color: #ef4444;
    }
    .emailer-field-input {
        width: 100%;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--background);
        color: var(--foreground);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        outline: none;
    }
    .emailer-field-input:focus {
        border-color: var(--foreground);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
    }
    .emailer-recipient-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    .emailer-carbon-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8125rem;
        color: var(--muted-foreground);
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        font-weight: 500;
    }
    .emailer-carbon-toggle:hover {
        color: var(--foreground);
    }
    .emailer-carbon-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }
    @media (max-width: 640px) {
        .emailer-carbon-fields {
            grid-template-columns: 1fr;
        }
    }
    /* Quill Container Styling */
    .emailer-quill-wrapper {
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        background: var(--background);
    }
    .emailer-quill-wrapper .ql-toolbar.ql-snow {
        border: none;
        border-bottom: 1px solid var(--border);
        background: var(--muted);
        padding: 0.5rem 0.75rem;
    }
    .emailer-quill-wrapper .ql-container.ql-snow {
        border: none;
        min-height: 240px;
        font-size: 0.9375rem;
        font-family: inherit;
        color: var(--foreground);
    }
    .emailer-quill-wrapper .ql-editor {
        min-height: 240px;
        padding: 1rem;
        line-height: 1.65;
    }
    .emailer-quill-wrapper .ql-editor.ql-blank::before {
        color: var(--muted-foreground);
        font-style: normal;
    }
    /* Preset Sidebar Card */
    .emailer-preset-active-card {
        padding: 1.25rem;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--card);
        margin-bottom: 1.25rem;
    }
    .emailer-preset-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--muted);
        color: var(--foreground);
        border: 1px solid var(--border);
    }
    .emailer-preset-item-card {
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.1rem;
        background: var(--card);
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .emailer-preset-item-card:hover {
        border-color: color-mix(in srgb, var(--foreground) 35%, var(--border));
        transform: translateY(-1px);
    }
    .emailer-preset-item-card.is-selected {
        border-color: color-mix(in srgb, var(--foreground) 40%, var(--border));
        background: color-mix(in srgb, var(--muted) 40%, var(--card));
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
    }
    .emailer-preset-badge-icon {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--muted);
        color: var(--muted-foreground);
        border: 1px solid var(--border);
    }
    .emailer-preset-item-card.is-selected .emailer-preset-badge-icon {
        background: color-mix(in srgb, var(--foreground) 65%, transparent);
        color: #ffffff;
        border-color: transparent;
    }
    /* Preset Modal Grid */
    .emailer-modal-presets-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    @media (max-width: 640px) {
        .emailer-modal-presets-grid {
            grid-template-columns: 1fr;
        }
    }
    #emailerPresetModal .modal, #emailerPreviewModal .modal {
        max-width: 680px;
        width: 95%;
    }
    #emailerPreviewModal .modal {
        max-width: 760px;
    }
    #emailerPreviewIframe {
        width: 100%;
        height: 480px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #ffffff;
    }
</style>
