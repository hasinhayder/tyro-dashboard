{{-- Unsplash / Pixabay image finder --}}
<div class="card" id="image-finder">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Image Finder</h3>
        <span class="badge badge-secondary">Unsplash / Pixabay</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Search</label>
                <input id="td-img-query" class="form-input" type="text" placeholder="mountains, coffee, workspace" value="workspace" />
                <div class="form-hint">Unsplash uses a no-key “source” endpoint (random images by keyword). Pixabay API typically needs an API key.</div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Provider</label>
                <select id="td-img-provider" class="form-select">
                    <option value="unsplash" selected>Unsplash (no key)</option>
                    <option value="pixabay">Pixabay (opens search)</option>
                </select>
            </div>
        </div>
        <div style="display:flex; gap: 0.5rem; margin-top: 0.75rem; flex-wrap: wrap;">
            <button class="btn btn-primary btn-sm" type="button" id="td-img-load">Search</button>
            <a href="#" class="btn btn-ghost btn-sm" id="td-img-open" onclick="return false;" style="display:none;">Open results</a>
        </div>

        <div id="td-img-grid" style="margin-top: 1rem; display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem;"></div>
    </div>
</div>
