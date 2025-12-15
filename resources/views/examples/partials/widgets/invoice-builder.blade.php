{{-- Quick Invoice Builder --}}
<div class="card" id="invoice-builder">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Quick Invoice Builder</h3>
        <span class="badge badge-secondary">Interactive</span>
    </div>
    <div class="card-body">
        <div class="grid-2" style="margin-bottom: 1rem;">
            <div>
                <div class="form-row">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Invoice #</label>
                        <input id="td-inv-number" class="form-input" type="text" value="INV-1001" />
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Date</label>
                        <input id="td-inv-date" class="form-input" type="date" />
                    </div>
                </div>

                <div class="form-row" style="margin-top: 0.75rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Bill to</label>
                        <input id="td-inv-billto" class="form-input" type="text" placeholder="Client name" value="Acme Ltd." />
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Currency</label>
                        <select id="td-inv-currency" class="form-select">
                            <option value="$" selected>$ (USD)</option>
                            <option value="৳">৳ (BDT)</option>
                            <option value="€">€ (EUR)</option>
                            <option value="£">£ (GBP)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div style="display:flex; justify-content: space-between; gap: 1rem; margin-bottom: 0.5rem;">
                    <div style="color: var(--muted-foreground);">Subtotal</div>
                    <strong id="td-inv-subtotal">—</strong>
                </div>
                <div style="display:flex; justify-content: space-between; gap: 1rem; margin-bottom: 0.5rem;">
                    <div style="color: var(--muted-foreground);">Tax</div>
                    <strong id="td-inv-tax">—</strong>
                </div>
                <div style="display:flex; justify-content: space-between; gap: 1rem; margin-bottom: 0.75rem;">
                    <div style="color: var(--muted-foreground);">Discount</div>
                    <strong id="td-inv-discount">—</strong>
                </div>
                <div style="padding-top: 0.75rem; border-top: 1px solid var(--border); display:flex; justify-content: space-between; gap: 1rem;">
                    <div style="font-weight: 700;">Total</div>
                    <div style="font-weight: 800; font-size: 1.125rem;" id="td-inv-total">—</div>
                </div>
            </div>
        </div>

        <div style="display:flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Tax (%)</label>
                <input id="td-inv-tax-rate" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="7.5" style="width: 140px;" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Discount</label>
                <input id="td-inv-discount-amt" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="0" style="width: 140px;" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="visibility: hidden;">Actions</label>
                <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" type="button" id="td-inv-add">Add line</button>
                    <button class="btn btn-secondary" type="button" id="td-inv-print">Print</button>
                    <button class="btn btn-ghost" type="button" id="td-inv-export">Export JSON</button>
                </div>
            </div>
        </div>

        <div class="table-container" style="border-radius: 10px;">
            <table class="table" id="td-inv-table">
                <thead>
                    <tr>
                        <th style="width: 44%;">Item</th>
                        <th style="width: 14%;">Qty</th>
                        <th style="width: 18%;">Unit</th>
                        <th style="width: 18%; text-align:right;">Line total</th>
                        <th style="width: 6%;"></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--background);">
            <div style="font-size: 0.8125rem; color: var(--muted-foreground); margin-bottom: 0.5rem;">Export</div>
            <textarea id="td-inv-json" class="form-textarea" readonly style="min-height: 120px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;"></textarea>
        </div>
    </div>
</div>
