{{-- Weather Check (Current) --}}
<div class="card" id="weather-check">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Weather Check</h3>
        <span class="badge badge-secondary">Open-Meteo</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Area / Location</label>
                <input id="td-weather-location" class="form-input" type="text" placeholder="Dhaka, Bangladesh" value="Dhaka" />
                <div class="form-hint">No API key required.</div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="visibility: hidden;">Actions</label>
                <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" type="button" id="td-weather-now">Check</button>
                    <button class="btn btn-ghost" type="button" id="td-weather-geo">Use my location</button>
                </div>
            </div>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
            <div class="badge-list" style="margin-bottom: 0.75rem;">
                <span class="badge badge-primary" id="td-weather-temp">Temp: —</span>
                <span class="badge badge-secondary" id="td-weather-humidity">Humidity: —</span>
                <span class="badge badge-success" id="td-weather-wind">Wind: —</span>
            </div>
            <div style="font-size: 0.875rem; color: var(--muted-foreground);" id="td-weather-place">—</div>
        </div>
    </div>
</div>
