<!-- Global Modal Component -->
<div id="globalModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 id="globalModalTitle" class="modal-title"></h3>
            <button type="button" class="modal-close" onclick="closeGlobalModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-body-inner">
                <div id="globalModalIcon" class="modal-icon"></div>
                <div class="modal-content">
                    <p id="globalModalMessage" class="modal-message"></p>
                    <div id="globalModalPromptInput" class="modal-prompt-input" style="display: none;">
                        <input type="text" id="promptInput" class="form-input" placeholder="">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="globalModalCancel" class="btn btn-secondary" onclick="closeGlobalModal()">Cancel</button>
            <button type="button" id="globalModalConfirm" class="btn btn-primary"></button>
        </div>
    </div>
</div>

<style>
    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Modal Container */
    .modal-container {
        background: var(--card);
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        max-width: 480px;
        width: 90%;
        margin: 1rem;
        transform: scale(0.95) translateY(10px);
        transition: transform 0.2s ease;
        overflow: hidden;
    }

    .modal-overlay.active .modal-container {
        transform: scale(1) translateY(0);
    }

    /* Modal Header */
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid var(--border);
    }

    .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--foreground);
        margin: 0;
    }

    .modal-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 0.5rem;
        border: none;
        background: transparent;
        color: var(--muted-foreground);
        cursor: pointer;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .modal-close:hover {
        background-color: var(--muted);
        color: var(--foreground);
    }

    .modal-close svg {
        width: 20px;
        height: 20px;
    }

    /* Modal Body */
    .modal-body {
        padding: 2rem 1.5rem;
    }

    .modal-body-inner {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .modal-icon {
        flex-shrink: 0;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .modal-icon svg {
        width: 28px;
        height: 28px;
        stroke-width: 2.5;
    }

    .modal-content {
        flex: 1;
        text-align: left;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Icon Variants */
    .modal-icon.confirm {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
    }

    .modal-icon.success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }

    .modal-icon.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .modal-icon.info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .modal-message {
        color: var(--foreground);
        font-size: 0.9375rem;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    .modal-prompt-input {
        margin-top: 1rem;
    }

    .modal-prompt-input .form-input {
        width: 100%;
    }

    /* Modal Footer */
    .modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border);
        background: color-mix(in srgb, var(--muted) 50%, transparent);
    }

    .modal-footer .btn {
        min-width: 100px;
        font-weight: 500;
        transition: all 0.15s ease;
        border: 1px solid transparent;
    }

    .modal-footer .btn-secondary {
        background-color: transparent;
        color: var(--muted-foreground);
        border-color: var(--border);
    }

    .modal-footer .btn-secondary:hover {
        background-color: var(--muted);
        color: var(--foreground);
        border-color: var(--border);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .modal-footer .btn-secondary:active {
        transform: translateY(0);
    }

    .modal-footer .btn-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border-color: transparent;
    }

    .modal-footer .btn-primary:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3), 0 2px 4px -1px rgba(99, 102, 241, 0.2);
    }

    .modal-footer .btn-primary:active {
        transform: translateY(0);
    }

    .modal-footer .btn-success {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: white;
        border-color: transparent;
    }

    .modal-footer .btn-success:hover {
        background: linear-gradient(135deg, #111827 0%, #000000 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
    }

    .modal-footer .btn-success:active {
        transform: translateY(0);
    }

    .modal-footer .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-color: transparent;
    }

    .modal-footer .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3), 0 2px 4px -1px rgba(239, 68, 68, 0.2);
    }

    .modal-footer .btn-danger:active {
        transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 640px) {
        .modal-container {
            width: 95%;
            margin: 0.5rem;
        }

        .modal-header {
            padding: 1rem;
        }

        .modal-body {
            padding: 1.5rem 1rem;
        }

        .modal-body-inner {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .modal-content {
            text-align: center;
        }

        .modal-message {
            margin: 0;
        }

        .modal-footer {
            padding: 1rem;
            flex-direction: column-reverse;
        }

        .modal-footer .btn {
            width: 100%;
        }
    }
</style>
