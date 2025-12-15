{{-- ROI Calculator --}}
<div class="card" id="roi-calculator">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">ROI Calculator</h3>
        <span class="badge badge-secondary">Finance</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Initial investment</label>
                <input id="td-roi-invest" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="1000" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Final value</label>
                <input id="td-roi-final" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="1450" />
            </div>
        </div>
        <div class="form-row" style="margin-top: 0.75rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Period (years)</label>
                <input id="td-roi-years" class="form-input" type="number" inputmode="decimal" min="0" step="0.1" value="2" />
                <div class="form-hint">Used for annualized ROI (CAGR).</div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="visibility: hidden;">Actions</label>
                <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" type="button" id="td-roi-calc">Calculate</button>
                    <button class="btn btn-ghost" type="button" id="td-roi-reset">Reset</button>
                </div>
            </div>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
            <div class="badge-list" style="margin-bottom: 0.75rem;">
                <span class="badge badge-primary" id="td-roi-roi">ROI: —</span>
                <span class="badge badge-secondary" id="td-roi-profit">Profit: —</span>
                <span class="badge badge-success" id="td-roi-cagr">CAGR: —</span>
            </div>
            <div style="font-size: 0.875rem; color: var(--muted-foreground);">Formula: ROI = (Final − Initial) / Initial</div>
        </div>
    </div>
</div>
