{{-- Daily Comics (XKCD) --}}
<div class="card" id="daily-comics">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Daily Comics</h3>
        <span class="badge badge-secondary">XKCD</span>
    </div>
    <div class="card-body">
        <div style="display:flex; gap: 0.5rem; flex-wrap: wrap; align-items: flex-start;">
            <div class="form-group" style="margin-bottom: 0; min-width: 180px;">
                <label class="form-label">Comic # (optional)</label>
                <input id="td-xkcd-id" class="form-input" type="number" inputmode="numeric" min="1" step="1" placeholder="Latest" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="visibility: hidden;">Actions</label>
                <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" type="button" id="td-xkcd-load">Load</button>
                    <button class="btn btn-ghost" type="button" id="td-xkcd-latest">Latest</button>
                    <span class="badge badge-secondary" id="td-xkcd-meta">—</span>
                </div>
            </div>
        </div>

        <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; background: var(--background); overflow: hidden;">
            <div style="padding: 0.875rem 1rem; border-bottom: 1px solid var(--border); background: var(--muted);">
                <div style="font-weight: 700; font-size: 1rem;" id="td-xkcd-title">—</div>
                <div style="font-size: 0.875rem; color: var(--muted-foreground);" id="td-xkcd-alt">—</div>
            </div>
            <div style="padding: 1rem; display:flex; justify-content:center;">
                <img id="td-xkcd-img" alt="" style="max-width: 100%; height: auto; display:none;" />
                <div id="td-xkcd-empty" style="color: var(--muted-foreground);">Load a comic to preview it here.</div>
            </div>
        </div>
    </div>
</div>
