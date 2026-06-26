<style>
.sys-settings-intro {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.sys-settings-copy { max-width: 36rem; }
.sys-settings-kicker {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    background: color-mix(in srgb, var(--primary) 10%, var(--card));
    color: var(--primary);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 0.85rem;
}
.sys-settings-heading {
    margin: 0;
    color: var(--foreground);
    font-size: 1.25rem;
    line-height: 1.2;
}
.sys-settings-description {
    margin: 0.55rem 0 0;
    color: var(--muted-foreground);
    font-size: 0.9375rem;
    line-height: 1.7;
}
.sys-settings-surface {
    padding: 1rem 1.05rem;
    border: 1px solid var(--border);
    border-radius: 1rem;
    background: var(--muted);
}
.sys-settings-surface-title {
    margin: 0 0 0.25rem;
    color: var(--foreground);
    font-size: 0.94rem;
    font-weight: 700;
}
.sys-settings-surface-description {
    margin: 0 0 0.9rem;
    color: var(--muted-foreground);
    font-size: 0.84rem;
    line-height: 1.6;
}
.sys-settings-section-intro {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.sys-settings-section-copy { max-width: 38rem; }
.sys-settings-section-heading {
    margin: 0;
    color: var(--foreground);
    font-size: 1.05rem;
    font-weight: 700;
}
.sys-settings-section-description {
    margin: 0.45rem 0 0;
    color: var(--muted-foreground);
    font-size: 0.875rem;
    line-height: 1.65;
}
.sys-settings-section-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 3rem;
    padding: 0.45rem 0.75rem;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: var(--muted);
    color: var(--foreground);
    font-size: 0.8rem;
    font-weight: 700;
    white-space: nowrap;
}
.sys-settings-grid {
    display: grid;
    gap: 1rem;
}
.sys-settings-toggles {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}
.sys-settings-toggle {
    padding: 1rem 1.05rem;
    border: 1px solid var(--border);
    border-radius: 1rem;
    background: var(--card);
}
.sys-settings-toggle-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.sys-settings-toggle-title {
    margin: 0;
    color: var(--foreground);
    font-size: 0.95rem;
    font-weight: 700;
}
.sys-settings-toggle-description {
    margin: 0.35rem 0 0;
    color: var(--muted-foreground);
    font-size: 0.85rem;
    line-height: 1.55;
}
.sys-settings-metrics {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.9rem;
}
.sys-settings-metric .form-label { margin-bottom: 0.45rem; }
.sys-settings-metric .form-input,
.sys-settings-metric .form-select { max-width: none !important; }
.sys-settings-save-row {
    display: flex;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}
@media (max-width: 1024px) {
    .sys-settings-section-intro { flex-direction: column; }
}
@media (max-width: 640px) {
    .sys-settings-metrics { grid-template-columns: 1fr; }
    .sys-settings-toggle-top { align-items: flex-start; }
}

.branding-theme-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.85rem;
}
.branding-theme-color {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    background: var(--card);
}
.branding-theme-color-meta {
    flex: 1;
    min-width: 0;
}
.branding-theme-color-name {
    font-size: 0.84rem;
    font-weight: 600;
    color: var(--foreground);
}
.branding-theme-color-var {
    font-size: 0.72rem;
    color: var(--muted-foreground);
    font-family: monospace;
    margin-top: 0.05rem;
}
.branding-theme-color-text {
    width: 80px;
    padding: 0.4rem 0.5rem;
    border: 1px solid var(--border);
    border-radius: 0.45rem;
    background: var(--muted);
    color: var(--foreground);
    font-size: 0.8rem;
    font-family: monospace;
    text-align: center;
    transition: border-color 0.15s;
    flex-shrink: 0;
}
.branding-theme-color-text:focus {
    border-color: var(--primary);
    outline: none;
}
.branding-surface-title {
    margin: 0 0 0.25rem;
    color: var(--foreground);
    font-size: 0.94rem;
    font-weight: 700;
}
.branding-color-reset {
    padding:4px;
    border:none;
    background:none;
    cursor:pointer;
    color:var(--muted-foreground);
    flex-shrink:0;
    border-radius:4px;
    display:flex;
    align-items:center;
    justify-content:center;
    transition: color 0.15s ease, background 0.15s ease;
}
.branding-color-reset:hover {
    color: var(--destructive);
    background: color-mix(in srgb, var(--destructive), transparent 90%);
}

/* ── Settings Search ─────────────────────────────────────────── */
.settings-search-bar {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.7rem 1rem;
    margin-bottom: 1.25rem;
    border: 1.5px solid var(--border);
    border-radius: 1rem;
    background: var(--card);
    box-shadow: 0 1px 2px color-mix(in srgb, var(--foreground) 4%, transparent);
    transition: border-color 0.2s ease, box-shadow 0.25s ease;
}
.settings-search-bar:focus-within {
    border-color: var(--primary);
    box-shadow:
        0 1px 2px color-mix(in srgb, var(--foreground) 4%, transparent),
        0 0 0 3.5px color-mix(in srgb, var(--primary) 14%, transparent),
        0 4px 12px color-mix(in srgb, var(--primary) 6%, transparent);
}
.settings-search-icon {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    color: var(--muted-foreground);
    transition: color 0.2s ease;
}
.settings-search-bar:focus-within .settings-search-icon {
    color: var(--primary);
}
.settings-search-input {
    flex: 1;
    min-width: 0;
    border: none;
    outline: none;
    background: transparent;
    color: var(--foreground);
    font-size: 0.92rem;
    font-weight: 450;
    letter-spacing: 0.005em;
}
.settings-search-input::placeholder {
    color: color-mix(in srgb, var(--muted-foreground) 75%, transparent);
    font-weight: 400;
    font-size: 0.9rem;
}
.settings-search-shortcut {
    flex-shrink: 0;
    padding: 0.15rem 0.5rem;
    border: 1px solid var(--border);
    border-radius: 0.35rem;
    background: var(--muted);
    color: var(--muted-foreground);
    font-size: 0.7rem;
    font-weight: 600;
    line-height: 1.4;
    letter-spacing: 0.02em;
    opacity: 0.85;
    transition: opacity 0.2s ease, transform 0.15s ease;
}
.settings-search-bar:focus-within .settings-search-shortcut {
    opacity: 0;
    transform: scale(0.92);
    pointer-events: none;
}
.settings-search-clear {
    display: none;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border: none;
    border-radius: 999px;
    background: color-mix(in srgb, var(--muted-foreground) 10%, var(--card));
    color: var(--muted-foreground);
    cursor: pointer;
    opacity: 0;
    transform: scale(0.85);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.settings-search-clear.visible {
    display: flex;
    opacity: 1;
    transform: scale(1);
}
.settings-search-clear:hover {
    background: color-mix(in srgb, var(--destructive) 14%, var(--muted));
    color: var(--destructive);
    transform: scale(1.08);
}
.settings-search-clear:active {
    transform: scale(0.95);
}
.settings-search-clear svg { width: 14px; height: 14px; }

/* Search-active: show all matching panels stacked as results */
.settings-search-active .vtabs-panel { display: block; }
.settings-search-active .vtabs-panel.search-hidden { display: none; }
.vtabs-item.search-hidden { display: none; }

/* Highlight matching fields — appear then fade over 4s */
@keyframes highlightFade {
    0%   { background: color-mix(in srgb, var(--primary) 9%, transparent); box-shadow: inset 3px 0 0 0 var(--primary); }
    65%  { background: color-mix(in srgb, var(--primary) 9%, transparent); box-shadow: inset 3px 0 0 0 var(--primary); }
    100% { background: transparent; box-shadow: inset 3px 0 0 0 transparent; }
}
.form-group.search-highlight {
    border-radius: 0.5rem;
    padding-left: 0.6rem;
    margin-left: -0.6rem;
    animation: highlightFade 4s ease-out forwards;
}
@keyframes highlightCardFade {
    0%   { border-color: color-mix(in srgb, var(--primary) 45%, var(--border)); box-shadow: inset 4px 0 0 0 var(--primary); }
    65%  { border-color: color-mix(in srgb, var(--primary) 45%, var(--border)); box-shadow: inset 4px 0 0 0 var(--primary); }
    100% { border-color: var(--border); box-shadow: inset 4px 0 0 0 transparent; }
}
.sys-settings-toggle.search-highlight,
.sys-settings-surface.search-highlight,
.branding-theme-color.search-highlight {
    animation: highlightCardFade 4s ease-out forwards;
}

/* No-results state */
.search-no-results { display: none; padding: 3rem 1rem; text-align: center; }
.search-no-results.visible { display: block; }
.search-no-results-inner { max-width: 28rem; margin: 0 auto; }
.search-no-results svg { width: 40px; height: 40px; color: var(--muted-foreground); margin-bottom: 0.75rem; }
.search-no-results-title { margin: 0 0 0.35rem; color: var(--foreground); font-size: 1rem; font-weight: 700; }
.search-no-results-text { margin: 0; color: var(--muted-foreground); font-size: 0.875rem; line-height: 1.6; }
@media (max-width: 900px) {
    .settings-search-shortcut { display: none; }
}
</style>
