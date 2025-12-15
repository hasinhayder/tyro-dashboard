{{-- Flight Tracker --}}
<div class="card" id="flight-tracker">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Flight Tracker</h3>
        <span class="badge badge-secondary">Aviation</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">ICAO24 (hex)</label>
                <input id="td-flight-icao24" class="form-input" type="text" placeholder="e.g. 3c6444" value="" />
                <div class="form-hint">Tip: if you don’t know ICAO24, use “Nearby”.</div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="visibility: hidden;">Actions</label>
                <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" type="button" id="td-flight-track">Track</button>
                    <button class="btn btn-ghost" type="button" id="td-flight-reset">Reset</button>
                </div>
            </div>
        </div>

        <div class="form-row" style="margin-top: 0.75rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Latitude</label>
                <input id="td-flight-lat" class="form-input" type="number" inputmode="decimal" step="0.0001" placeholder="23.8103" value="" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Longitude</label>
                <input id="td-flight-lon" class="form-input" type="number" inputmode="decimal" step="0.0001" placeholder="90.4125" value="" />
            </div>
        </div>

        <div style="display:flex; gap: 0.5rem; margin-top: 0.75rem; flex-wrap: wrap; align-items: flex-start;">
            <div class="form-group" style="margin-bottom: 0; min-width: 170px;">
                <label class="form-label">Radius (km)</label>
                <input id="td-flight-radius" class="form-input" type="number" inputmode="decimal" min="1" step="1" value="150" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="visibility: hidden;">Actions</label>
                <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-secondary" type="button" id="td-flight-near">Nearby</button>
                    <button class="btn btn-ghost" type="button" id="td-flight-geo">Use my location</button>
                    <span class="badge badge-secondary" id="td-flight-status">—</span>
                </div>
            </div>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; background: var(--background); overflow:hidden;">
            <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); background: var(--muted); display:flex; justify-content: space-between; gap: 1rem; align-items:center; flex-wrap: wrap;">
                <div style="font-weight: 700; font-size: 0.9375rem;">Results</div>
                <div class="badge-list">
                    <span class="badge badge-primary" id="td-flight-count">Flights: —</span>
                    <span class="badge badge-secondary" id="td-flight-updated">Updated: —</span>
                </div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Callsign</th>
                            <th>Country</th>
                            <th>Lat</th>
                            <th>Lon</th>
                            <th style="text-align:right;">Alt (m)</th>
                            <th style="text-align:right;">Speed (km/h)</th>
                        </tr>
                    </thead>
                    <tbody id="td-flight-tbody">
                        <tr><td colspan="6" style="color: var(--muted-foreground);">Search by ICAO24 or find nearby flights.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
