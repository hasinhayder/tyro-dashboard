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
    /* Preset Modal Cards */
    .emailer-preset-item-card {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        height: 100%;
        padding: 1rem 1.125rem;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--card);
        cursor: pointer;
        text-align: left;
        transition: border-color 0.15s ease, background-color 0.15s ease;
    }
    .emailer-preset-item-card:hover {
        background: color-mix(in srgb, var(--muted) 45%, var(--card));
    }
    .emailer-preset-item-card:focus-visible {
        outline: none;
        border-color: var(--ring);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--ring) 25%, transparent);
    }
    .emailer-preset-item-card.is-selected {
        border-color: var(--ring);
        background: color-mix(in srgb, var(--muted) 55%, var(--card));
    }
    .emailer-preset-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .emailer-preset-name {
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.3;
        color: var(--foreground);
    }
    .emailer-preset-check {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        color: var(--foreground);
        opacity: 0;
        transform: scale(0.8);
        transition: opacity 0.15s ease, transform 0.15s ease;
    }
    .emailer-preset-check svg {
        width: 100%;
        height: 100%;
    }
    .emailer-preset-item-card.is-selected .emailer-preset-check {
        opacity: 1;
        transform: scale(1);
    }
    .emailer-preset-desc {
        margin: 0;
        font-size: 0.8125rem;
        line-height: 1.5;
        color: var(--muted-foreground);
    }
    .emailer-preset-foot {
        margin-top: auto;
        padding-top: 0.4rem;
    }
    .emailer-preset-preview {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0;
        border: 0;
        background: none;
        color: var(--muted-foreground);
        font: inherit;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: color 0.15s ease;
    }
    .emailer-preset-preview:hover {
        color: var(--foreground);
    }
    .emailer-preset-preview svg {
        width: 13px;
        height: 13px;
    }
    /* Preset Modal Grid */
    .emailer-modal-presets-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.875rem;
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
