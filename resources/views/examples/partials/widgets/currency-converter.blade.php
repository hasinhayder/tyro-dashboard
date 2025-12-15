{{-- Currency Converter --}}
<div class="card" id="currency-converter">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Currency Converter</h3>
        <span class="badge badge-secondary">FX</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Amount</label>
                <input id="td-fx-amount" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="100" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">From</label>
                <select id="td-fx-from" class="form-select"></select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">To</label>
                <select id="td-fx-to" class="form-select"></select>
            </div>
        </div>

        <div style="display:flex; gap: 0.5rem; margin-top: 0.75rem; flex-wrap: wrap;">
            <button class="btn btn-primary btn-sm" type="button" id="td-fx-convert">Convert</button>
            <button class="btn btn-ghost btn-sm" type="button" id="td-fx-swap">Swap</button>
            <button class="btn btn-secondary btn-sm" type="button" id="td-fx-refresh">Refresh rates</button>
            <span class="badge badge-secondary" id="td-fx-status">—</span>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
            <div class="badge-list" style="margin-bottom: 0.75rem;">
                <span class="badge badge-primary" id="td-fx-result">Result: —</span>
                <span class="badge badge-secondary" id="td-fx-rate">Rate: —</span>
                <span class="badge badge-success" id="td-fx-updated">Updated: —</span>
            </div>
            <div style="font-size: 0.8125rem; color: var(--muted-foreground);">Uses a same-origin proxy endpoint to avoid browser CORS issues.</div>
        </div>
    </div>
</div>
