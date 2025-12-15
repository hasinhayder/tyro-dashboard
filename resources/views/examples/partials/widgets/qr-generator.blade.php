{{-- QR Code Generator --}}
<div class="card" id="qr-generator">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">QR Code Generator</h3>
        <span class="badge badge-secondary">Utility</span>
    </div>
    <div class="card-body">
        <div class="form-group" style="margin-bottom: 1rem;">
            <label class="form-label">Content</label>
            <textarea id="td-qr-content" class="form-input" rows="3" placeholder="Enter text, URL, or any data to encode...">https://example.com</textarea>
        </div>

        <div class="form-row" style="margin-bottom: 1rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Size</label>
                <select id="td-qr-size" class="form-select">
                    <option value="128">Small (128px)</option>
                    <option value="256" selected>Medium (256px)</option>
                    <option value="512">Large (512px)</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Error Correction</label>
                <select id="td-qr-error" class="form-select">
                    <option value="L">Low (7%)</option>
                    <option value="M" selected>Medium (15%)</option>
                    <option value="Q">High (25%)</option>
                    <option value="H">Very High (30%)</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 1rem;">
            <label class="form-label">Style</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-top: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input id="td-qr-foreground" type="color" value="#000000" style="width: 40px; height: 40px; border: 1px solid var(--border); border-radius: 6px; cursor: pointer;" />
                    <label class="form-label" style="font-size: 0.875rem; margin: 0;">Foreground</label>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input id="td-qr-background" type="color" value="#ffffff" style="width: 40px; height: 40px; border: 1px solid var(--border); border-radius: 6px; cursor: pointer;" />
                    <label class="form-label" style="font-size: 0.875rem; margin: 0;">Background</label>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
            <button id="td-qr-generate" class="btn btn-primary btn-sm" type="button">Generate QR</button>
            <button id="td-qr-download" class="btn btn-secondary btn-sm" type="button" disabled>Download</button>
            <button id="td-qr-clear" class="btn btn-ghost btn-sm" type="button">Clear</button>
        </div>

        <div id="td-qr-container" style="display: flex; justify-content: center; align-items: center; min-height: 200px; border: 1px solid var(--border); border-radius: 10px; background: var(--muted); position: relative; padding:30px;">
            <div id="td-qr-placeholder" style="color: var(--muted-foreground); text-align: center;">
                <div>Enter content and click Generate</div>
            </div>
            <div id="td-qr-output" style="display: none; max-width: 100%;"></div>
        </div>

        <div style="margin-top: 1rem; font-size: 0.875rem; color: var(--muted-foreground); line-height: 1.5;">
            <div>• Generated locally in your browser</div>
            <div>• Higher error correction = more resistant to damage</div>
            <div>• Maximum content length varies with error level</div>
        </div>
    </div>
</div>