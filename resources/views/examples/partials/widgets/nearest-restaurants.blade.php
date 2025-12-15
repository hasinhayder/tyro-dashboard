{{-- Restaurants + Map --}}
<div class="card" id="nearest-restaurants">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Find Nearest Restaurants</h3>
        <span class="badge badge-secondary">Maps</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Current location or area</label>
                <input id="td-rest-location" class="form-input" type="text" placeholder="Gulshan 2, Dhaka" value="" />
                <div class="form-hint">Tip: click “Use my location” for coordinates.</div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Restaurant type</label>
                <input id="td-rest-type" class="form-input" type="text" placeholder="pizza, sushi, biryani" value="pizza" />
            </div>
        </div>
        <div style="display:flex; gap: 0.5rem; margin-top: 0.75rem; flex-wrap: wrap;">
            <button class="btn btn-primary btn-sm" type="button" id="td-rest-search">Search</button>
            <button class="btn btn-ghost btn-sm" type="button" id="td-rest-geo">Use my location</button>
            <span class="badge badge-secondary" id="td-rest-meta">—</span>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; overflow:hidden; background: var(--background);">
            <iframe
                id="td-rest-map"
                title="Restaurants map"
                src="about:blank"
                style="width: 100%; height: 320px; border: 0; display:none;"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
            <div id="td-rest-empty" style="padding: 1rem; color: var(--muted-foreground);">Search to render a map here (Google Maps iframe).</div>
        </div>
    </div>
</div>
