{{-- EMI Calculator --}}
<div class="card" id="emi-calculator">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">EMI Calculator</h3>
        <span class="badge badge-secondary">Loans</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Principal</label>
                <input id="td-emi-principal" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="500000" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Annual interest (%)</label>
                <input id="td-emi-rate" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="10.5" />
            </div>
        </div>
        <div class="form-row" style="margin-top: 0.75rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Tenure (months)</label>
                <input id="td-emi-months" class="form-input" type="number" inputmode="numeric" min="1" step="1" value="60" />
                <div class="form-hint">E.g. 60 months = 5 years.</div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="visibility: hidden;">Actions</label>
                <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" type="button" id="td-emi-calc">Calculate</button>
                    <button class="btn btn-ghost" type="button" id="td-emi-reset">Reset</button>
                </div>
            </div>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
            <div class="badge-list" style="margin-bottom: 0.75rem;">
                <span class="badge badge-primary" id="td-emi-monthly">Monthly EMI: —</span>
                <span class="badge badge-secondary" id="td-emi-total">Total pay: —</span>
                <span class="badge badge-success" id="td-emi-interest">Total interest: —</span>
            </div>
            <div style="font-size: 0.875rem; color: var(--muted-foreground);">Formula: EMI = P·r·(1+r)^n / ((1+r)^n − 1)</div>
        </div>
    </div>
</div>
