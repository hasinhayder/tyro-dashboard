{{-- Stock Viewer --}}
<div class="card" id="stock-viewer">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Stock Viewer</h3>
        <span class="badge badge-secondary">Quote</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Symbol (Stooq)</label>
                <input id="td-stock-symbol" class="form-input" type="text" placeholder="aapl.us" value="aapl.us" />
                <div class="form-hint">Examples: <strong>aapl.us</strong>, <strong>tsla.us</strong>, <strong>googl.us</strong></div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="visibility: hidden;">Actions</label>
                <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" type="button" id="td-stock-load">Load</button>
                </div>
            </div>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
            <div class="badge-list" style="margin-bottom: 0.75rem;">
                <span class="badge badge-primary" id="td-stock-last">Last: —</span>
                <span class="badge badge-secondary" id="td-stock-range">Range: —</span>
                <span class="badge badge-success" id="td-stock-volume">Vol: —</span>
            </div>
            <div style="font-size: 0.875rem; color: var(--muted-foreground);" id="td-stock-meta">—</div>
        </div>
    </div>
</div>
