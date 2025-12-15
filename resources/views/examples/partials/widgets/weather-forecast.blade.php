{{-- Weather Forecast (7 days) --}}
<div class="card" id="weather-forecast">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Weather Forecast</h3>
        <span class="badge badge-secondary">Next 7 days</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Area / Location</label>
                <input id="td-forecast-location" class="form-input" type="text" placeholder="Tokyo" value="Tokyo" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="visibility: hidden;">Actions</label>
                <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" type="button" id="td-forecast-load">Load</button>
                    <button class="btn btn-ghost" type="button" id="td-forecast-geo">Use my location</button>
                </div>
            </div>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; overflow:hidden;">
            <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); background: var(--muted); display:flex; justify-content: space-between; gap: 1rem;">
                <div style="font-size: 0.875rem; color: var(--muted-foreground);" id="td-forecast-place">—</div>
                <span class="badge badge-secondary" id="td-forecast-meta">—</span>
            </div>
            <div class="table-container" style="border-top: 0;">
                <table class="table" id="td-forecast-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Precip.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" style="color: var(--muted-foreground);">Load a forecast to see data.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
