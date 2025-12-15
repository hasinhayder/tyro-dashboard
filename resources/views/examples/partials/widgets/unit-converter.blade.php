{{-- Unit Converter --}}
<div class="card" id="unit-converter">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Unit Converter</h3>
        <span class="badge badge-secondary">Utilities</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Category</label>
                <select id="td-unit-category" class="form-select">
                    <option value="length" selected>Length</option>
                    <option value="weight">Weight</option>
                    <option value="temperature">Temperature</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Value</label>
                <input id="td-unit-value" class="form-input" type="number" inputmode="decimal" step="0.01" value="1" />
            </div>
        </div>

        <div class="form-row" style="margin-top: 0.75rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">From</label>
                <select id="td-unit-from" class="form-select"></select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">To</label>
                <select id="td-unit-to" class="form-select"></select>
            </div>
        </div>

        <div style="display:flex; gap: 0.5rem; margin-top: 0.75rem; flex-wrap: wrap;">
            <button class="btn btn-primary btn-sm" type="button" id="td-unit-convert">Convert</button>
            <button class="btn btn-ghost btn-sm" type="button" id="td-unit-swap">Swap</button>
            <span class="badge badge-secondary" id="td-unit-formula">—</span>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
            <div style="font-size: 0.875rem; color: var(--muted-foreground); margin-bottom: 0.25rem;">Result</div>
            <div style="font-weight: 800; font-size: 1.25rem; letter-spacing: -0.01em;" id="td-unit-result">—</div>
            <div style="font-size: 0.8125rem; color: var(--muted-foreground); margin-top: 0.5rem;" id="td-unit-note">Conversions are local (no API calls).</div>
        </div>
    </div>
</div>
