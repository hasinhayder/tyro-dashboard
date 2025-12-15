{{-- Password Generator --}}
<div class="card" id="password-generator">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Password Generator</h3>
        <span class="badge badge-primary">Security</span>
    </div>
    <div class="card-body">
        <div class="form-group" style="margin-bottom: 1rem;">
            <label class="form-label">Generated Password</label>
            <div style="display: flex; gap: 0.5rem;">
                <textarea id="td-password-output" class="form-input" rows="2" readonly placeholder="Click generate to create password" style="font-family: monospace; font-size: 1rem; resize: vertical;"></textarea>
                <button id="td-password-copy" class="btn btn-secondary btn-sm" type="button">Copy</button>
            </div>
        </div>

        <div class="form-row" style="margin-bottom: 1rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Length</label>
                <input id="td-password-length" class="form-input" type="number" min="4" max="128" value="16" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Quantity</label>
                <input id="td-password-quantity" class="form-input" type="number" min="1" max="10" value="1" />
            </div>
        </div>

        <div style="margin-bottom: 1rem;">
            <label class="form-label">Character Types</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-top: 0.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input id="td-password-uppercase" type="checkbox" checked />
                    <span>Uppercase (A-Z)</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input id="td-password-lowercase" type="checkbox" checked />
                    <span>Lowercase (a-z)</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input id="td-password-numbers" type="checkbox" checked />
                    <span>Numbers (0-9)</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input id="td-password-symbols" type="checkbox" checked />
                    <span>Symbols (!@#$%)</span>
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <button id="td-password-generate" class="btn btn-primary btn-sm" type="button">Generate</button>
            <button id="td-password-clear" class="btn btn-ghost btn-sm" type="button">Clear</button>
        </div>

        <div style="margin-top: 1rem; font-size: 0.875rem; color: var(--muted-foreground); line-height: 1.5;">
            <div>• Generated locally in your browser</div>
            <div>• Minimum 4 characters, maximum 128 characters</div>
            <div>• At least one character type must be selected</div>
        </div>
    </div>
</div>