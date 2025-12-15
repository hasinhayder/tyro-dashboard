{{-- BMI Calculator --}}
<div class="card" id="bmi-calculator">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">BMI Calculator</h3>
        <span class="badge badge-secondary">Health</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Height (cm)</label>
                <input id="td-bmi-height" class="form-input" type="number" inputmode="decimal" min="0" step="0.1" value="175" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Weight (kg)</label>
                <input id="td-bmi-weight" class="form-input" type="number" inputmode="decimal" min="0" step="0.1" value="70" />
            </div>
        </div>
        <div style="display:flex; gap: 0.5rem; margin-top: 0.75rem; flex-wrap: wrap;">
            <button class="btn btn-primary btn-sm" type="button" id="td-bmi-calc">Calculate</button>
            <button class="btn btn-ghost btn-sm" type="button" id="td-bmi-reset">Reset</button>
            <span class="badge badge-secondary" id="td-bmi-formula">BMI = kg / m²</span>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
            <div class="badge-list" style="margin-bottom: 0.75rem;">
                <span class="badge badge-primary" id="td-bmi-value">BMI: —</span>
                <span class="badge badge-secondary" id="td-bmi-category">Category: —</span>
            </div>
            <div style="font-size: 0.875rem; color: var(--muted-foreground);">Categories (WHO): Underweight &lt; 18.5, Normal 18.5–24.9, Overweight 25–29.9, Obese ≥ 30.</div>
        </div>
    </div>
</div>
