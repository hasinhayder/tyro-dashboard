<style>
    .tyro-media-picker-field {
        display: grid;
        /* gap: 0.5rem; */
    }

    .tyro-media-picker-control {
        display: flex;
        align-items: stretch;
        gap: 0.5rem;
    }

    .tyro-media-picker-control.has-preview {
        align-items: center;
    }

    .tyro-media-picker-control.has-preview.preview-top,
    .tyro-media-picker-control.has-preview.preview-bottom {
        flex-direction: column;
        align-items: flex-start;
    }

    .tyro-media-picker-control.has-preview .tyro-media-picker-actions {
        order: 1;
    }

    .tyro-media-picker-control.has-preview.preview-top .tyro-media-picker-preview,
    .tyro-media-picker-control.has-preview.preview-left .tyro-media-picker-preview {
        order: 0;
    }

    .tyro-media-picker-control.has-preview.preview-bottom .tyro-media-picker-preview {
        order: 2;
    }

    .tyro-media-picker-control.has-preview.preview-left,
    .tyro-media-picker-control.has-preview.preview-right {
        flex-direction: row;
    }

    .tyro-media-picker-control.has-preview.preview-right .tyro-media-picker-preview {
        order: 2;
    }

    .tyro-media-picker-preview {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 0;
        min-height: 0;
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        background: var(--muted);
        color: var(--muted-foreground);
        cursor: pointer;
        font-size: 0.75rem;
        text-align: center;
    }

    .tyro-media-picker-preview:focus-visible {
        outline: 2px solid color-mix(in srgb, var(--primary) 70%, white);
        outline-offset: 2px;
    }

    .tyro-media-picker-preview:not([style*="width"]) {
        width: 100px;
    }

    .tyro-media-picker-preview:not(.has-fixed-height) {
        min-height: 4rem;
    }

    .tyro-media-picker-preview img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .tyro-media-picker-preview:not(.has-fixed-height) img {
        height: auto;
    }

    .tyro-media-picker-preview-placeholder {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        min-height: inherit;
        color: color-mix(in srgb, var(--muted-foreground) 70%, transparent);
        background:
            linear-gradient(135deg, color-mix(in srgb, var(--muted) 70%, white), var(--muted)),
            var(--muted);
    }

    .tyro-media-picker-preview-placeholder svg {
        width: min(42%, 2rem);
        height: min(42%, 2rem);
    }

    .tyro-media-picker-input {
        min-width: 0;
        flex: 1;
        height: 2.5rem;
    }

    .tyro-media-picker-actions .btn {
        height: 2.5rem;
    }

    .tyro-media-picker-control.size-medium .tyro-media-picker-input,
    .tyro-media-picker-control.size-medium .tyro-media-picker-actions .btn {
        height: 2.25rem;
        font-size: 0.8125rem;
    }

    .tyro-media-picker-control.size-medium .tyro-media-picker-button svg,
    .tyro-media-picker-control.size-medium .tyro-media-picker-delete svg {
        width: 0.875rem;
        height: 0.875rem;
    }

    .tyro-media-picker-control.size-small .tyro-media-picker-input,
    .tyro-media-picker-control.size-small .tyro-media-picker-actions .btn {
        height: 2rem;
        font-size: 0.75rem;
    }

    .tyro-media-picker-control.size-small .tyro-media-picker-button svg,
    .tyro-media-picker-control.size-small .tyro-media-picker-delete svg {
        width: 0.75rem;
        height: 0.75rem;
    }

    .tyro-media-picker-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .tyro-media-picker-button {
        flex-shrink: 0;
        white-space: nowrap;
    }

    .tyro-media-picker-button svg,
    .tyro-media-picker-delete svg {
        width: 1rem;
        height: 1rem;
    }

    .tyro-media-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: radial-gradient(circle at top, rgba(255, 255, 255, 0.08), transparent 32%), rgba(7, 10, 18, 0.72);
        backdrop-filter: blur(16px) saturate(130%);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: scale(1.01);
        transition: opacity 0.2s ease, visibility 0s linear 0.2s, transform 0.2s ease;
    }

    .tyro-media-modal-overlay.open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: scale(1);
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .tyro-media-modal {
        width: min(1100px, 100%);
        max-height: min(88vh, 920px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1.4rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.05), transparent 28%), var(--card);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4), 0 1px 0 rgba(255, 255, 255, 0.08) inset;
        transform: translateY(18px) scale(0.985);
        opacity: 0;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }

    .tyro-media-modal-overlay.open .tyro-media-modal {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .tyro-media-modal-header,
    .tyro-media-modal-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.35rem 1.5rem 1.1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), transparent);
    }

    .tyro-media-modal-toolbar {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.025);
    }

    .tyro-media-modal-copy {
        min-width: 0;
    }

    .tyro-media-modal-toolbar-left {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        min-width: 0;
    }

    .tyro-media-modal-toolbar-right,
    .tyro-media-modal-header-actions {
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.65rem;
        flex-shrink: 0;
        margin-left: auto;
    }

    .tyro-media-output-select-wrap {
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.65rem;
        color: var(--muted-foreground);
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .tyro-media-output-select-wrap[hidden] {
        display: none !important;
    }

    .tyro-media-output-select {
        width: 8rem;
        height: 2.5rem;
        min-height: 2.5rem;
        padding: 0 0.8rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.04);
        font-size: 0.78rem;
        letter-spacing: 0;
        text-transform: none;
    }

    .tyro-media-modal-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        /* padding: 0.3rem 0.7rem; */
        padding: 0.3rem 0rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.04);
        color: var(--muted-foreground);
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .tyro-media-modal-title {
        margin: 0.8rem 0 0;
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .tyro-media-modal-subtitle {
        margin: 0.4rem 0 0;
        max-width: 54ch;
        color: var(--muted-foreground);
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .tyro-media-modal-close {
        appearance: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.04);
        color: var(--muted-foreground);
        cursor: pointer;
        line-height: 1;
        transition: transform 0.18s ease, background-color 0.18s ease, color 0.18s ease;
    }

    .tyro-media-modal-close:hover {
        transform: rotate(90deg);
        background: rgba(255, 255, 255, 0.08);
        color: var(--foreground);
    }

    .tyro-media-modal-close svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .tyro-media-modal-search {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .tyro-media-modal-search svg {
        position: absolute;
        left: 0.95rem;
        top: 50%;
        width: 1rem;
        height: 1rem;
        color: var(--muted-foreground);
        pointer-events: none;
        transform: translateY(-50%);
    }

    .tyro-media-modal-search .form-input {
        width: 100%;
        height: 2.5rem;
        min-height: 2.5rem;
        padding-left: 2.6rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.04);
    }

    .tyro-media-modal-body {
        flex: 1;
        overflow-y: auto;
        padding: 1.35rem 1.5rem 1.5rem;
    }

    .tyro-media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .tyro-media-item {
        display: flex;
        flex-direction: column;
        min-width: 0;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
        overflow: hidden;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.045), rgba(255, 255, 255, 0.015));
        cursor: pointer;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .tyro-media-item:hover {
        transform: translateY(-4px);
        border-color: color-mix(in srgb, var(--primary) 55%, white 10%);
        box-shadow: 0 22px 40px rgba(0, 0, 0, 0.22);
    }

    .tyro-media-item.is-selected {
        border-color: color-mix(in srgb, var(--primary) 72%, white 12%);
        box-shadow: 0 0 0 2px color-mix(in srgb, var(--primary) 34%, transparent), 0 22px 40px rgba(0, 0, 0, 0.22);
    }

    .tyro-media-item-preview {
        position: relative;
        height: 148px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), transparent), var(--muted);
    }

    .tyro-media-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.35s ease;
    }

    .tyro-media-item:hover img {
        transform: scale(1.05);
    }

    .tyro-media-item-icon {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted-foreground);
        background: radial-gradient(circle at top, rgba(255, 255, 255, 0.07), transparent 55%), var(--muted);
    }

    .tyro-media-item-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 0.8rem;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.14), rgba(15, 23, 42, 0.68));
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .tyro-media-item:hover .tyro-media-item-overlay,
    .tyro-media-item.is-selected .tyro-media-item-overlay {
        opacity: 1;
    }

    .tyro-media-item-badge,
    .tyro-media-item-action {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 0.35rem 0.7rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.04em;
    }

    .tyro-media-item-badge {
        background: rgba(255, 255, 255, 0.14);
        color: #fff;
        backdrop-filter: blur(10px);
    }

    .tyro-media-item-action {
        align-self: flex-start;
        background: color-mix(in srgb, var(--primary) 84%, black 8%);
        color: var(--primary-foreground);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
    }

    .tyro-media-item-body {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        padding: 0.85rem 0.9rem 0.95rem;
    }

    .tyro-media-item-name {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        color: var(--foreground);
        font-size: 0.84rem;
        font-weight: 600;
    }

    .tyro-media-item-meta {
        color: var(--muted-foreground);
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .tyro-media-modal-state {
        grid-column: 1 / -1;
        display: grid;
        place-items: center;
        min-height: 260px;
        padding: 2rem;
        border: 1px dashed rgba(255, 255, 255, 0.08);
        border-radius: 1.2rem;
        background: rgba(255, 255, 255, 0.025);
        text-align: center;
        color: var(--muted-foreground);
    }

    .tyro-media-modal-state strong {
        display: block;
        margin-bottom: 0.45rem;
        color: var(--foreground);
        font-size: 1rem;
    }

    .tyro-media-modal-load-more {
        display: flex;
        justify-content: center;
        margin-top: 1.25rem;
    }

    .tyro-media-modal-upload {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.9rem;
        flex-wrap: wrap;
        padding: 1rem 1.5rem 1.2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.025), rgba(255, 255, 255, 0.04));
    }

    .tyro-media-upload-status {
        color: var(--muted-foreground);
        font-size: 0.78rem;
        line-height: 1.5;
    }

    .tyro-media-upload-progress {
        display: none;
        align-items: center;
        gap: 0.6rem;
    }

    .tyro-media-upload-progress-track {
        width: 160px;
        height: 6px;
        background: var(--border);
        border-radius: 999px;
        overflow: hidden;
    }

    .tyro-media-upload-progress-fill {
        width: 0%;
        height: 100%;
        background: var(--primary, #6366f1);
        border-radius: 999px;
        transition: width 0.15s ease;
    }

    .tyro-media-upload-progress-text {
        min-width: 32px;
        color: var(--muted-foreground);
        font-size: 0.72rem;
        text-align: right;
    }

    @media (max-width: 768px) {
        .tyro-media-picker-control {
            flex-direction: column;
        }

        .tyro-media-modal-overlay {
            padding: 0.75rem;
        }

        .tyro-media-modal {
            max-height: calc(100vh - 1.5rem);
            border-radius: 1.1rem;
        }

        .tyro-media-modal-header,
        .tyro-media-modal-toolbar,
        .tyro-media-modal-body,
        .tyro-media-modal-upload {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .tyro-media-modal-header {
            flex-direction: column;
        }

        .tyro-media-modal-toolbar {
            grid-template-columns: 1fr;
        }

        .tyro-media-modal-toolbar-left {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }

        .tyro-media-modal-toolbar-right,
        .tyro-media-modal-header-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .tyro-media-modal-search {
            width: 100%;
        }

        .tyro-media-grid {
            grid-template-columns: repeat(auto-fill, minmax(144px, 1fr));
            gap: 0.85rem;
        }

        .tyro-media-item-preview {
            height: 132px;
        }
    }
</style>
