<!-- Global Modal Component -->
<div id="globalModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-content-wrapper">
            <div class="modal-body">
                <div class="modal-body-inner">
                    <div id="globalModalIcon" class="modal-icon"></div>
                    <div class="modal-text-content">
                        <h2 id="globalModalTitle" class="modal-title"></h2>
                        <p id="globalModalMessage" class="modal-message"></p>
                        <div id="globalModalPromptInput" class="modal-prompt-input" style="display: none;">
                            <input type="text" id="promptInput" class="form-input" placeholder="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="globalModalCancel" class="btn btn-modal-cancel" onclick="closeGlobalModal()">Cancel</button>
                <button type="button" id="globalModalConfirm" class="btn btn-modal-confirm"></button>
            </div>
        </div>
        <button type="button" class="modal-close" onclick="closeGlobalModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
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
        backdrop-filter: blur(2px);
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
        border-radius: 0.5rem;
        border: 1px solid var(--border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        max-width: 420px;
        width: 90%;
        transform: scale(0.95) translateY(10px);
        transition: transform 0.2s ease;
        overflow: hidden;
        position: relative;
    }

    .modal-overlay.active .modal-container {
        transform: scale(1) translateY(0);
    }

    .modal-content-wrapper {
        display: flex;
        flex-direction: column;
    }

    /* Modal Body */
    .modal-body {
        padding: 1.5rem;
    }

    .modal-body-inner {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .modal-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--muted);
        color: var(--foreground);
    }

    .modal-icon svg {
        width: 20px;
        height: 20px;
        stroke-width: 2;
    }

    .modal-text-content {
        flex: 1;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--foreground);
        margin: 0 0 0.25rem 0;
        line-height: 1.4;
    }

    .modal-message {
        font-size: 0.875rem;
        color: var(--muted-foreground);
        line-height: 1.5;
        margin: 0;
    }

    .modal-prompt-input {
        margin-top: 1rem;
    }

    .modal-prompt-input .form-input {
        width: 100%;
        font-size: 0.875rem;
    }

    /* Modal Footer */
    .modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        padding: 1rem 1.5rem 1rem;
        background: transparent;
    }

    .btn-modal-cancel {
        background-color: transparent;
        color: var(--foreground);
        border: 1px solid var(--border);
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-modal-cancel:hover {
        background-color: var(--muted);
        border-color: var(--border);
    }

    .btn-modal-confirm {
        background-color: var(--foreground);
        color: var(--background);
        border: 1px solid var(--foreground);
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-modal-confirm:hover {
        background-color: var(--foreground);
        opacity: 0.9;
        border-color: var(--foreground);
    }

    .btn-modal-confirm:active {
        opacity: 0.95;
    }

    .btn-modal-danger {
        background-color: #ef4444;
        color: white;
        border: 1px solid #ef4444;
    }

    .btn-modal-danger:hover {
        background-color: #dc2626;
        border-color: #dc2626;
        opacity: 1;
    }

    /* Modal Close Button */
    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 0.375rem;
        border: none;
        background: transparent;
        color: var(--muted-foreground);
        cursor: pointer;
        transition: all 0.15s ease;
        padding: 0;
    }

    .modal-close:hover {
        background-color: var(--muted);
        color: var(--foreground);
    }

    .modal-close svg {
        width: 18px;
        height: 18px;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .modal-container {
            width: 95%;
            margin: 0.5rem;
        }

        .modal-body {
            padding: 1.25rem;
        }

        .modal-body-inner {
            gap: 0.75rem;
        }

        .modal-footer {
            padding: 1rem 1.25rem 1rem;
            flex-direction: column-reverse;
        }

        .btn-modal-cancel,
        .btn-modal-confirm {
            width: 100%;
        }
    }
</style>
