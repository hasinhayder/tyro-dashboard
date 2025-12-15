@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@section('title', 'Widgets')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Examples</span>
<span class="breadcrumb-separator">/</span>
<span>Widgets</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Widgets</h1>
            <p class="page-description" style="font-size: 1rem;">Copy-ready interactive dashboard widgets where the UI and business logic live mostly in JavaScript.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-secondary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Back to Dashboard
            </a>
            <a href="{{ route('tyro-dashboard.components') }}" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2v-3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2v-3z" />
                </svg>
                Components
            </a>
        </div>
    </div>
</div>

<div class="alert" style="margin-bottom: 1.5rem; border-color: color-mix(in srgb, var(--warning), transparent 70%); background-color: color-mix(in srgb, var(--warning), transparent 92%);">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--warning);">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
    </svg>
    <div class="alert-content">
        <div class="alert-title">Note</div>
        <div class="alert-message" style="color: var(--muted-foreground);">Some widgets call third-party APIs. Where browsers block CORS, this page uses same-origin proxy endpoints under <strong>/examples/widgets/*</strong>.</div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- ROI Calculator --}}
    <div class="card" id="roi-calculator">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">ROI Calculator</h3>
            <span class="badge badge-secondary">Finance</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Initial investment</label>
                    <input id="td-roi-invest" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="1000" />
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Final value</label>
                    <input id="td-roi-final" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="1450" />
                </div>
            </div>
            <div class="form-row" style="margin-top: 0.75rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Period (years)</label>
                    <input id="td-roi-years" class="form-input" type="number" inputmode="decimal" min="0" step="0.1" value="2" />
                    <div class="form-hint">Used for annualized ROI (CAGR).</div>
                </div>
                <div style="display:flex; align-items:flex-end; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary btn-sm" type="button" id="td-roi-calc">Calculate</button>
                    <button class="btn btn-ghost btn-sm" type="button" id="td-roi-reset">Reset</button>
                </div>
            </div>

            <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div class="badge-list" style="margin-bottom: 0.75rem;">
                    <span class="badge badge-primary" id="td-roi-roi">ROI: —</span>
                    <span class="badge badge-secondary" id="td-roi-profit">Profit: —</span>
                    <span class="badge badge-success" id="td-roi-cagr">CAGR: —</span>
                </div>
                <div style="font-size: 0.875rem; color: var(--muted-foreground);">Formula: ROI = (Final − Initial) / Initial</div>
            </div>
        </div>
    </div>

    {{-- EMI Calculator --}}
    <div class="card" id="emi-calculator">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">EMI Calculator</h3>
            <span class="badge badge-secondary">Loans</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Principal</label>
                    <input id="td-emi-principal" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="500000" />
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Annual interest (%)</label>
                    <input id="td-emi-rate" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="10.5" />
                </div>
            </div>
            <div class="form-row" style="margin-top: 0.75rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tenure (months)</label>
                    <input id="td-emi-months" class="form-input" type="number" inputmode="numeric" min="1" step="1" value="60" />
                    <div class="form-hint">E.g. 60 months = 5 years.</div>
                </div>
                <div style="display:flex; align-items:flex-end; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary btn-sm" type="button" id="td-emi-calc">Calculate</button>
                    <button class="btn btn-ghost btn-sm" type="button" id="td-emi-reset">Reset</button>
                </div>
            </div>

            <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div class="badge-list" style="margin-bottom: 0.75rem;">
                    <span class="badge badge-primary" id="td-emi-monthly">Monthly EMI: —</span>
                    <span class="badge badge-secondary" id="td-emi-total">Total pay: —</span>
                    <span class="badge badge-success" id="td-emi-interest">Total interest: —</span>
                </div>
                <div style="font-size: 0.875rem; color: var(--muted-foreground);">Formula: EMI = P·r·(1+r)^n / ((1+r)^n − 1)</div>
            </div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
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

</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Daily Comics (XKCD) --}}
    <div class="card" id="daily-comics">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Daily Comics</h3>
            <span class="badge badge-secondary">XKCD</span>
        </div>
        <div class="card-body">
            <div style="display:flex; gap: 0.5rem; flex-wrap: wrap; align-items: end;">
                <div class="form-group" style="margin-bottom: 0; min-width: 180px;">
                    <label class="form-label">Comic # (optional)</label>
                    <input id="td-xkcd-id" class="form-input" type="number" inputmode="numeric" min="1" step="1" placeholder="Latest" />
                </div>
                <button class="btn btn-primary btn-sm" type="button" id="td-xkcd-load">Load</button>
                <button class="btn btn-ghost btn-sm" type="button" id="td-xkcd-latest">Latest</button>
                <span class="badge badge-secondary" id="td-xkcd-meta">—</span>
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
                <div style="display:flex; align-items:flex-end; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary btn-sm" type="button" id="td-weather-now">Check</button>
                    <button class="btn btn-ghost btn-sm" type="button" id="td-weather-geo">Use my location</button>
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
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
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
                <div style="display:flex; align-items:flex-end; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary btn-sm" type="button" id="td-forecast-load">Load</button>
                    <button class="btn btn-ghost btn-sm" type="button" id="td-forecast-geo">Use my location</button>
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
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Stock Viewer --}}
    <div class="card" id="stock-viewer">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Stock Viewer</h3>
            <span class="badge badge-secondary">Quote</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Symbol (Stooq)</label>
                    <input id="td-stock-symbol" class="form-input" type="text" placeholder="aapl.us" value="aapl.us" />
                    <div class="form-hint">Examples: <strong>aapl.us</strong>, <strong>tsla.us</strong>, <strong>googl.us</strong></div>
                </div>
                <div style="display:flex; align-items:flex-end; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary btn-sm" type="button" id="td-stock-load">Load</button>
                </div>
            </div>

            <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div class="badge-list" style="margin-bottom: 0.75rem;">
                    <span class="badge badge-primary" id="td-stock-last">Last: —</span>
                    <span class="badge badge-secondary" id="td-stock-range">Range: —</span>
                    <span class="badge badge-success" id="td-stock-volume">Vol: —</span>
                </div>
                <div style="font-size: 0.875rem; color: var(--muted-foreground);" id="td-stock-meta">—</div>
            </div>
        </div>
    </div>

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
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Unit Converter --}}
    <div class="card" id="unit-converter">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Unit Converter</h3>
            <span class="badge badge-secondary">Utilities</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Category</label>
                    <select id="td-unit-category" class="form-select">
                        <option value="length" selected>Length</option>
                        <option value="weight">Weight</option>
                        <option value="temperature">Temperature</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Value</label>
                    <input id="td-unit-value" class="form-input" type="number" inputmode="decimal" step="0.01" value="1" />
                </div>
            </div>

            <div class="form-row" style="margin-top: 0.75rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">From</label>
                    <select id="td-unit-from" class="form-select"></select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">To</label>
                    <select id="td-unit-to" class="form-select"></select>
                </div>
            </div>

            <div style="display:flex; gap: 0.5rem; margin-top: 0.75rem; flex-wrap: wrap;">
                <button class="btn btn-primary btn-sm" type="button" id="td-unit-convert">Convert</button>
                <button class="btn btn-ghost btn-sm" type="button" id="td-unit-swap">Swap</button>
                <span class="badge badge-secondary" id="td-unit-formula">—</span>
            </div>

            <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div style="font-size: 0.875rem; color: var(--muted-foreground); margin-bottom: 0.25rem;">Result</div>
                <div style="font-weight: 800; font-size: 1.25rem; letter-spacing: -0.01em;" id="td-unit-result">—</div>
                <div style="font-size: 0.8125rem; color: var(--muted-foreground); margin-top: 0.5rem;" id="td-unit-note">Conversions are local (no API calls).</div>
            </div>
        </div>
    </div>

    {{-- Currency Converter --}}
    <div class="card" id="currency-converter">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Currency Converter</h3>
            <span class="badge badge-secondary">FX</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Amount</label>
                    <input id="td-fx-amount" class="form-input" type="number" inputmode="decimal" min="0" step="0.01" value="100" />
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">From</label>
                    <select id="td-fx-from" class="form-select"></select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">To</label>
                    <select id="td-fx-to" class="form-select"></select>
                </div>
            </div>

            <div style="display:flex; gap: 0.5rem; margin-top: 0.75rem; flex-wrap: wrap;">
                <button class="btn btn-primary btn-sm" type="button" id="td-fx-convert">Convert</button>
                <button class="btn btn-ghost btn-sm" type="button" id="td-fx-swap">Swap</button>
                <button class="btn btn-secondary btn-sm" type="button" id="td-fx-refresh">Refresh rates</button>
                <span class="badge badge-secondary" id="td-fx-status">—</span>
            </div>

            <div style="margin-top: 1rem; border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div class="badge-list" style="margin-bottom: 0.75rem;">
                    <span class="badge badge-primary" id="td-fx-result">Result: —</span>
                    <span class="badge badge-secondary" id="td-fx-rate">Rate: —</span>
                    <span class="badge badge-success" id="td-fx-updated">Updated: —</span>
                </div>
                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">Uses a same-origin proxy endpoint to avoid browser CORS issues.</div>
            </div>
        </div>
    </div>
</div>

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
            <div style="display:flex; align-items:flex-end; gap: 0.5rem; flex-wrap: wrap;">
                <button class="btn btn-primary btn-sm" type="button" id="td-inv-add">Add line</button>
                <button class="btn btn-secondary btn-sm" type="button" id="td-inv-print">Print</button>
                <button class="btn btn-ghost btn-sm" type="button" id="td-inv-export">Export JSON</button>
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

@push('scripts')
<script>
    (function () {
        function $(id) { return document.getElementById(id); }
        function clamp(n, min, max) { return Math.min(max, Math.max(min, n)); }
        function toNum(v) {
            var n = Number(v);
            return Number.isFinite(n) ? n : 0;
        }
        function money(n, currency) {
            var v = toNum(n);
            var sign = v < 0 ? '-' : '';
            v = Math.abs(v);
            return sign + currency + v.toLocaleString(undefined, { maximumFractionDigits: 2, minimumFractionDigits: 2 });
        }

        // ROI
        function calcRoi() {
            var initial = toNum($('td-roi-invest').value);
            var finalValue = toNum($('td-roi-final').value);
            var years = toNum($('td-roi-years').value);

            if (initial <= 0) {
                $('td-roi-roi').textContent = 'ROI: —';
                $('td-roi-profit').textContent = 'Profit: —';
                $('td-roi-cagr').textContent = 'CAGR: —';
                return;
            }

            var profit = finalValue - initial;
            var roi = (profit / initial) * 100;

            var cagr = null;
            if (years > 0 && finalValue > 0) {
                cagr = (Math.pow(finalValue / initial, 1 / years) - 1) * 100;
            }

            $('td-roi-roi').textContent = 'ROI: ' + roi.toFixed(2) + '%';
            $('td-roi-profit').textContent = 'Profit: ' + money(profit, '');
            $('td-roi-cagr').textContent = 'CAGR: ' + (cagr === null ? '—' : cagr.toFixed(2) + '%');
        }

        // EMI
        function calcEmi() {
            var principal = toNum($('td-emi-principal').value);
            var annualRate = toNum($('td-emi-rate').value);
            var months = Math.max(1, Math.floor(toNum($('td-emi-months').value)));

            if (principal <= 0) {
                $('td-emi-monthly').textContent = 'Monthly EMI: —';
                $('td-emi-total').textContent = 'Total pay: —';
                $('td-emi-interest').textContent = 'Total interest: —';
                return;
            }

            var r = (annualRate / 100) / 12;
            var emi = 0;
            if (r === 0) {
                emi = principal / months;
            } else {
                var pow = Math.pow(1 + r, months);
                emi = (principal * r * pow) / (pow - 1);
            }

            var totalPay = emi * months;
            var totalInterest = totalPay - principal;

            $('td-emi-monthly').textContent = 'Monthly EMI: ' + money(emi, '');
            $('td-emi-total').textContent = 'Total pay: ' + money(totalPay, '');
            $('td-emi-interest').textContent = 'Total interest: ' + money(totalInterest, '');
        }

        // XKCD (via same-origin proxy)
        async function loadXkcd(id) {
            $('td-xkcd-meta').textContent = 'Loading…';
            $('td-xkcd-title').textContent = '—';
            $('td-xkcd-alt').textContent = '—';
            $('td-xkcd-img').style.display = 'none';
            $('td-xkcd-empty').style.display = '';

            var url = id ? ('{{ route('tyro-dashboard.examples.widgets.xkcd', ['id' => 1]) }}'.replace('/1', '/' + encodeURIComponent(String(id))))
                         : '{{ route('tyro-dashboard.examples.widgets.xkcd') }}';

            try {
                var res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                var data = await res.json();
                if (!res.ok) throw new Error(data && data.error ? data.error : 'Failed');

                $('td-xkcd-meta').textContent = '#' + data.num + ' • ' + [data.year, data.month, data.day].join('-');
                $('td-xkcd-title').textContent = data.safe_title || data.title || 'XKCD';
                $('td-xkcd-alt').textContent = data.alt || '';

                if (data.img) {
                    $('td-xkcd-img').src = data.img;
                    $('td-xkcd-img').alt = data.alt || $('td-xkcd-title').textContent;
                    $('td-xkcd-img').style.display = '';
                    $('td-xkcd-empty').style.display = 'none';
                }
            } catch (e) {
                $('td-xkcd-meta').textContent = 'Failed to load';
                $('td-xkcd-title').textContent = 'XKCD';
                $('td-xkcd-alt').textContent = String(e && e.message ? e.message : e);
            }
        }

        // Open-Meteo helpers
        async function geocodeByName(name) {
            var url = 'https://geocoding-api.open-meteo.com/v1/search?name=' + encodeURIComponent(name) + '&count=1&language=en&format=json';
            var res = await fetch(url);
            var data = await res.json();
            if (!data || !data.results || !data.results[0]) throw new Error('Location not found');
            return data.results[0];
        }

        async function reverseGeocode(lat, lon) {
            var url = 'https://geocoding-api.open-meteo.com/v1/reverse?latitude=' + encodeURIComponent(lat) + '&longitude=' + encodeURIComponent(lon) + '&count=1&language=en&format=json';
            var res = await fetch(url);
            var data = await res.json();
            if (data && data.results && data.results[0]) return data.results[0];
            return { name: lat.toFixed(4) + ',' + lon.toFixed(4), latitude: lat, longitude: lon };
        }

        async function fetchForecast(lat, lon) {
            var url = 'https://api.open-meteo.com/v1/forecast?latitude=' + encodeURIComponent(lat) + '&longitude=' + encodeURIComponent(lon)
                + '&current=temperature_2m,relative_humidity_2m,wind_speed_10m'
                + '&daily=temperature_2m_max,temperature_2m_min,precipitation_sum'
                + '&forecast_days=7&timezone=auto';
            var res = await fetch(url);
            if (!res.ok) throw new Error('Weather API error');
            return await res.json();
        }

        async function checkWeatherNow(place) {
            $('td-weather-temp').textContent = 'Temp: Loading…';
            $('td-weather-humidity').textContent = 'Humidity: —';
            $('td-weather-wind').textContent = 'Wind: —';
            $('td-weather-place').textContent = '—';

            try {
                var forecast = await fetchForecast(place.latitude, place.longitude);
                var c = forecast.current || {};
                $('td-weather-temp').textContent = 'Temp: ' + (c.temperature_2m != null ? (c.temperature_2m + '°C') : '—');
                $('td-weather-humidity').textContent = 'Humidity: ' + (c.relative_humidity_2m != null ? (c.relative_humidity_2m + '%') : '—');
                $('td-weather-wind').textContent = 'Wind: ' + (c.wind_speed_10m != null ? (c.wind_speed_10m + ' km/h') : '—');
                $('td-weather-place').textContent = (place.name || '—') + (place.country ? (', ' + place.country) : '');
            } catch (e) {
                $('td-weather-temp').textContent = 'Temp: —';
                $('td-weather-humidity').textContent = 'Humidity: —';
                $('td-weather-wind').textContent = 'Wind: —';
                $('td-weather-place').textContent = String(e && e.message ? e.message : e);
            }
        }

        async function loadForecastTable(place) {
            $('td-forecast-place').textContent = 'Loading…';
            $('td-forecast-meta').textContent = '—';
            var tbody = $('td-forecast-table').querySelector('tbody');
            tbody.innerHTML = '<tr><td colspan="4" style="color: var(--muted-foreground);">Loading…</td></tr>';

            try {
                var forecast = await fetchForecast(place.latitude, place.longitude);
                var daily = forecast.daily || {};
                var time = daily.time || [];
                var tmin = daily.temperature_2m_min || [];
                var tmax = daily.temperature_2m_max || [];
                var precip = daily.precipitation_sum || [];

                $('td-forecast-place').textContent = (place.name || '—') + (place.country ? (', ' + place.country) : '');
                $('td-forecast-meta').textContent = (forecast.timezone || '—');

                tbody.innerHTML = '';
                for (var i = 0; i < time.length; i++) {
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td>' + String(time[i]) + '</td>' +
                        '<td>' + (tmin[i] != null ? (Number(tmin[i]).toFixed(1) + '°C') : '—') + '</td>' +
                        '<td>' + (tmax[i] != null ? (Number(tmax[i]).toFixed(1) + '°C') : '—') + '</td>' +
                        '<td>' + (precip[i] != null ? (Number(precip[i]).toFixed(1) + ' mm') : '—') + '</td>';
                    tbody.appendChild(tr);
                }
                if (!time.length) {
                    tbody.innerHTML = '<tr><td colspan="4" style="color: var(--muted-foreground);">No data returned.</td></tr>';
                }
            } catch (e) {
                $('td-forecast-place').textContent = '—';
                $('td-forecast-meta').textContent = 'Failed';
                tbody.innerHTML = '<tr><td colspan="4" style="color: var(--muted-foreground);">' + String(e && e.message ? e.message : e) + '</td></tr>';
            }
        }

        function getBrowserLocation() {
            return new Promise(function (resolve, reject) {
                if (!navigator.geolocation) return reject(new Error('Geolocation is not supported'));
                navigator.geolocation.getCurrentPosition(function (pos) {
                    resolve({ lat: pos.coords.latitude, lon: pos.coords.longitude });
                }, function () {
                    reject(new Error('Location permission denied'));
                }, { enableHighAccuracy: true, timeout: 8000, maximumAge: 300000 });
            });
        }

        // Restaurants map
        function renderRestaurantMap(query) {
            var iframe = $('td-rest-map');
            var empty = $('td-rest-empty');
            var src = 'https://www.google.com/maps?q=' + encodeURIComponent(query) + '&output=embed';
            iframe.src = src;
            iframe.style.display = '';
            empty.style.display = 'none';
        }

        // Stocks
        async function loadStock(symbol) {
            $('td-stock-last').textContent = 'Last: Loading…';
            $('td-stock-range').textContent = 'Range: —';
            $('td-stock-volume').textContent = 'Vol: —';
            $('td-stock-meta').textContent = '—';

            var url = '{{ route('tyro-dashboard.examples.widgets.stocks', ['symbol' => 'aapl.us']) }}'.replace('aapl.us', encodeURIComponent(symbol));
            try {
                var res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                var data = await res.json();
                if (!res.ok) throw new Error(data && data.error ? data.error : 'Failed');

                $('td-stock-last').textContent = 'Last: ' + (data.close != null ? data.close : '—');
                $('td-stock-range').textContent = 'Range: ' + (data.low != null && data.high != null ? (data.low + ' – ' + data.high) : '—');
                $('td-stock-volume').textContent = 'Vol: ' + (data.volume != null ? String(data.volume) : '—');
                $('td-stock-meta').textContent = (data.symbol || symbol) + (data.date ? (' • ' + data.date) : '') + (data.time ? (' ' + data.time) : '');
            } catch (e) {
                $('td-stock-last').textContent = 'Last: —';
                $('td-stock-range').textContent = 'Range: —';
                $('td-stock-volume').textContent = 'Vol: —';
                $('td-stock-meta').textContent = String(e && e.message ? e.message : e);
            }
        }

        // Images
        function clearImages() {
            $('td-img-grid').innerHTML = '';
        }
        function addImage(url) {
            var wrap = document.createElement('div');
            wrap.style.border = '1px solid var(--border)';
            wrap.style.borderRadius = '10px';
            wrap.style.overflow = 'hidden';
            wrap.style.background = 'var(--muted)';

            var img = document.createElement('img');
            img.src = url;
            img.alt = 'Result';
            img.loading = 'lazy';
            img.style.display = 'block';
            img.style.width = '100%';
            img.style.height = '160px';
            img.style.objectFit = 'cover';

            wrap.appendChild(img);
            $('td-img-grid').appendChild(wrap);
        }
        function loadImages(query, provider) {
            clearImages();
            var openBtn = $('td-img-open');
            openBtn.style.display = 'none';

            query = (query || '').trim();
            if (!query) return;

            if (provider === 'pixabay') {
                openBtn.style.display = '';
                openBtn.href = 'https://pixabay.com/images/search/' + encodeURIComponent(query) + '/';
                openBtn.onclick = function () { window.open(openBtn.href, '_blank', 'noopener'); return false; };
                var note = document.createElement('div');
                note.style.gridColumn = '1 / -1';
                note.style.border = '1px solid var(--border)';
                note.style.borderRadius = '10px';
                note.style.padding = '0.875rem 1rem';
                note.style.background = 'var(--muted)';
                note.style.color = 'var(--muted-foreground)';
                note.textContent = 'Pixabay API typically needs an API key; this demo opens the Pixabay search page.';
                $('td-img-grid').appendChild(note);
                return;
            }

            for (var i = 0; i < 8; i++) {
                var url = 'https://source.unsplash.com/600x400/?' + encodeURIComponent(query) + '&sig=' + i;
                addImage(url);
            }
        }

        // Invoice
        function invoiceRowTemplate(row) {
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td><input class="form-input" type="text" value="' + (row.item || '') + '" placeholder="Item description" data-inv="item" /></td>' +
                '<td><input class="form-input" type="number" min="0" step="1" value="' + (row.qty != null ? row.qty : 1) + '" data-inv="qty" /></td>' +
                '<td><input class="form-input" type="number" min="0" step="0.01" value="' + (row.unit != null ? row.unit : 0) + '" data-inv="unit" /></td>' +
                '<td style="text-align:right; font-weight: 700;" data-inv="total">—</td>' +
                '<td style="text-align:right;"><button type="button" class="btn btn-ghost btn-sm" data-inv="remove">×</button></td>';
            return tr;
        }

        function getInvoiceState() {
            var currency = $('td-inv-currency').value || '$';
            var taxRate = toNum($('td-inv-tax-rate').value);
            var discount = toNum($('td-inv-discount-amt').value);
            var rows = [];
            $('td-inv-table').querySelectorAll('tbody tr').forEach(function (tr) {
                rows.push({
                    item: (tr.querySelector('[data-inv="item"]').value || '').trim(),
                    qty: toNum(tr.querySelector('[data-inv="qty"]').value),
                    unit: toNum(tr.querySelector('[data-inv="unit"]').value)
                });
            });
            return {
                number: $('td-inv-number').value,
                date: $('td-inv-date').value,
                billTo: $('td-inv-billto').value,
                currency: currency,
                taxRate: taxRate,
                discount: discount,
                lines: rows
            };
        }

        function renderInvoice() {
            var state = getInvoiceState();
            var currency = state.currency;
            var subtotal = 0;

            $('td-inv-table').querySelectorAll('tbody tr').forEach(function (tr) {
                var qty = toNum(tr.querySelector('[data-inv="qty"]').value);
                var unit = toNum(tr.querySelector('[data-inv="unit"]').value);
                qty = clamp(qty, 0, 1e9);
                unit = clamp(unit, 0, 1e12);
                var lineTotal = qty * unit;
                subtotal += lineTotal;
                tr.querySelector('[data-inv="total"]').textContent = money(lineTotal, currency);
            });

            var tax = subtotal * (state.taxRate / 100);
            var discount = clamp(state.discount, 0, 1e12);
            var total = Math.max(0, subtotal + tax - discount);

            $('td-inv-subtotal').textContent = money(subtotal, currency);
            $('td-inv-tax').textContent = money(tax, currency);
            $('td-inv-discount').textContent = money(discount, currency);
            $('td-inv-total').textContent = money(total, currency);

            var exportData = {
                invoice: {
                    number: state.number,
                    date: state.date,
                    billTo: state.billTo,
                    currency: currency,
                    taxRate: state.taxRate,
                    discount: discount,
                    subtotal: subtotal,
                    tax: tax,
                    total: total,
                    lines: state.lines
                }
            };
            $('td-inv-json').value = JSON.stringify(exportData, null, 2);
        }

        function addInvoiceLine(row) {
            var tr = invoiceRowTemplate(row || { item: '', qty: 1, unit: 0 });
            $('td-inv-table').querySelector('tbody').appendChild(tr);
            renderInvoice();
        }

        function initInvoice() {
            var dateInput = $('td-inv-date');
            if (dateInput && !dateInput.value) {
                var d = new Date();
                var mm = String(d.getMonth() + 1).padStart(2, '0');
                var dd = String(d.getDate()).padStart(2, '0');
                dateInput.value = d.getFullYear() + '-' + mm + '-' + dd;
            }

            addInvoiceLine({ item: 'Dashboard setup', qty: 1, unit: 250 });
            addInvoiceLine({ item: 'Monthly maintenance', qty: 2, unit: 80 });

            $('td-inv-table').addEventListener('input', function (e) {
                if (!e.target || !e.target.matches('[data-inv]')) return;
                renderInvoice();
            });

            $('td-inv-table').addEventListener('click', function (e) {
                var btn = e.target && e.target.closest('[data-inv="remove"]');
                if (!btn) return;
                var tr = btn.closest('tr');
                if (tr) tr.remove();
                renderInvoice();
            });

            ['td-inv-tax-rate', 'td-inv-discount-amt', 'td-inv-currency', 'td-inv-number', 'td-inv-date', 'td-inv-billto'].forEach(function (id) {
                var el = $(id);
                if (el) el.addEventListener('input', renderInvoice);
                if (el) el.addEventListener('change', renderInvoice);
            });

            $('td-inv-add').addEventListener('click', function () { addInvoiceLine({ item: '', qty: 1, unit: 0 }); });
            $('td-inv-print').addEventListener('click', function () { window.print(); });
            $('td-inv-export').addEventListener('click', async function () {
                renderInvoice();
                try {
                    await navigator.clipboard.writeText($('td-inv-json').value);
                } catch (e) {
                    // ignore if clipboard isn't available
                }
            });

            renderInvoice();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // ROI
            $('td-roi-calc').addEventListener('click', calcRoi);
            $('td-roi-reset').addEventListener('click', function () {
                $('td-roi-invest').value = 1000;
                $('td-roi-final').value = 1450;
                $('td-roi-years').value = 2;
                calcRoi();
            });
            ['td-roi-invest', 'td-roi-final', 'td-roi-years'].forEach(function (id) {
                $(id).addEventListener('input', calcRoi);
            });
            calcRoi();

            // EMI
            $('td-emi-calc').addEventListener('click', calcEmi);
            $('td-emi-reset').addEventListener('click', function () {
                $('td-emi-principal').value = 500000;
                $('td-emi-rate').value = 10.5;
                $('td-emi-months').value = 60;
                calcEmi();
            });
            ['td-emi-principal', 'td-emi-rate', 'td-emi-months'].forEach(function (id) {
                $(id).addEventListener('input', calcEmi);
            });
            calcEmi();

            // BMI
            (function initBmi() {
                function bmiCategory(bmi) {
                    if (bmi < 18.5) return 'Underweight';
                    if (bmi < 25) return 'Normal';
                    if (bmi < 30) return 'Overweight';
                    return 'Obese';
                }

                function calcBmi() {
                    var hCm = toNum($('td-bmi-height').value);
                    var wKg = toNum($('td-bmi-weight').value);
                    if (hCm <= 0 || wKg <= 0) {
                        $('td-bmi-value').textContent = 'BMI: —';
                        $('td-bmi-category').textContent = 'Category: —';
                        return;
                    }

                    var hM = hCm / 100;
                    var bmi = wKg / (hM * hM);
                    if (!Number.isFinite(bmi)) {
                        $('td-bmi-value').textContent = 'BMI: —';
                        $('td-bmi-category').textContent = 'Category: —';
                        return;
                    }

                    $('td-bmi-value').textContent = 'BMI: ' + bmi.toFixed(1);
                    $('td-bmi-category').textContent = 'Category: ' + bmiCategory(bmi);
                }

                $('td-bmi-calc').addEventListener('click', calcBmi);
                $('td-bmi-reset').addEventListener('click', function () {
                    $('td-bmi-height').value = 175;
                    $('td-bmi-weight').value = 70;
                    calcBmi();
                });
                ['td-bmi-height', 'td-bmi-weight'].forEach(function (id) {
                    $(id).addEventListener('input', calcBmi);
                });
                calcBmi();
            })();

            // XKCD
            $('td-xkcd-load').addEventListener('click', function () {
                var id = $('td-xkcd-id').value ? Number($('td-xkcd-id').value) : null;
                loadXkcd(id && Number.isFinite(id) ? id : null);
            });
            $('td-xkcd-latest').addEventListener('click', function () {
                $('td-xkcd-id').value = '';
                loadXkcd(null);
            });
            loadXkcd(null);

            // Weather now
            $('td-weather-now').addEventListener('click', async function () {
                try {
                    var place = await geocodeByName($('td-weather-location').value);
                    await checkWeatherNow(place);
                } catch (e) {
                    $('td-weather-place').textContent = String(e && e.message ? e.message : e);
                }
            });
            $('td-weather-geo').addEventListener('click', async function () {
                try {
                    var loc = await getBrowserLocation();
                    var place = await reverseGeocode(loc.lat, loc.lon);
                    $('td-weather-location').value = place.name || '';
                    await checkWeatherNow(place);
                } catch (e) {
                    $('td-weather-place').textContent = String(e && e.message ? e.message : e);
                }
            });
            $('td-weather-now').click();

            // Restaurants
            function searchRestaurantsWith(query) {
                $('td-rest-meta').textContent = query;
                renderRestaurantMap(query);
            }
            $('td-rest-search').addEventListener('click', function () {
                var loc = ($('td-rest-location').value || '').trim();
                var type = ($('td-rest-type').value || '').trim();
                var q = (type ? type : 'restaurants') + (loc ? (' near ' + loc) : '');
                searchRestaurantsWith(q);
            });
            $('td-rest-geo').addEventListener('click', async function () {
                try {
                    var loc = await getBrowserLocation();
                    var type = ($('td-rest-type').value || '').trim();
                    var q = (type ? type : 'restaurants') + ' near ' + loc.lat.toFixed(5) + ',' + loc.lon.toFixed(5);
                    $('td-rest-location').value = loc.lat.toFixed(5) + ',' + loc.lon.toFixed(5);
                    searchRestaurantsWith(q);
                } catch (e) {
                    $('td-rest-meta').textContent = String(e && e.message ? e.message : e);
                }
            });

            // Forecast
            $('td-forecast-load').addEventListener('click', async function () {
                try {
                    var place = await geocodeByName($('td-forecast-location').value);
                    await loadForecastTable(place);
                } catch (e) {
                    $('td-forecast-place').textContent = String(e && e.message ? e.message : e);
                }
            });
            $('td-forecast-geo').addEventListener('click', async function () {
                try {
                    var loc = await getBrowserLocation();
                    var place = await reverseGeocode(loc.lat, loc.lon);
                    $('td-forecast-location').value = place.name || '';
                    await loadForecastTable(place);
                } catch (e) {
                    $('td-forecast-place').textContent = String(e && e.message ? e.message : e);
                }
            });
            $('td-forecast-load').click();

            // Stocks
            $('td-stock-load').addEventListener('click', function () {
                var symbol = ($('td-stock-symbol').value || '').trim();
                if (!symbol) return;
                loadStock(symbol);
            });
            $('td-stock-load').click();

            // Images
            $('td-img-load').addEventListener('click', function () {
                var q = $('td-img-query').value;
                var p = $('td-img-provider').value;
                loadImages(q, p);
            });
            $('td-img-load').click();

            // Unit converter
            (function initUnitConverter() {
                var unitCatalog = {
                    length: {
                        label: 'Length',
                        base: 'm',
                        units: {
                            m: { label: 'Meters (m)', factor: 1 },
                            km: { label: 'Kilometers (km)', factor: 1000 },
                            cm: { label: 'Centimeters (cm)', factor: 0.01 },
                            mm: { label: 'Millimeters (mm)', factor: 0.001 },
                            in: { label: 'Inches (in)', factor: 0.0254 },
                            ft: { label: 'Feet (ft)', factor: 0.3048 },
                            yd: { label: 'Yards (yd)', factor: 0.9144 },
                            mi: { label: 'Miles (mi)', factor: 1609.344 }
                        }
                    },
                    weight: {
                        label: 'Weight',
                        base: 'kg',
                        units: {
                            kg: { label: 'Kilograms (kg)', factor: 1 },
                            g: { label: 'Grams (g)', factor: 0.001 },
                            mg: { label: 'Milligrams (mg)', factor: 0.000001 },
                            lb: { label: 'Pounds (lb)', factor: 0.45359237 },
                            oz: { label: 'Ounces (oz)', factor: 0.028349523125 }
                        }
                    },
                    temperature: {
                        label: 'Temperature',
                        base: 'c',
                        units: {
                            c: { label: 'Celsius (°C)' },
                            f: { label: 'Fahrenheit (°F)' },
                            k: { label: 'Kelvin (K)' }
                        }
                    }
                };

                function setOptions(selectEl, units, selected) {
                    selectEl.innerHTML = '';
                    Object.keys(units).forEach(function (key) {
                        var opt = document.createElement('option');
                        opt.value = key;
                        opt.textContent = units[key].label;
                        if (key === selected) opt.selected = true;
                        selectEl.appendChild(opt);
                    });
                }

                function convertTemperature(value, from, to) {
                    var c;
                    if (from === 'c') c = value;
                    else if (from === 'f') c = (value - 32) * (5 / 9);
                    else if (from === 'k') c = value - 273.15;
                    else c = value;

                    if (to === 'c') return c;
                    if (to === 'f') return (c * (9 / 5)) + 32;
                    if (to === 'k') return c + 273.15;
                    return c;
                }

                function calcUnit() {
                    var cat = $('td-unit-category').value;
                    var amount = toNum($('td-unit-value').value);
                    var from = $('td-unit-from').value;
                    var to = $('td-unit-to').value;

                    if (!unitCatalog[cat]) return;
                    if (!from || !to) return;

                    var result;
                    if (cat === 'temperature') {
                        result = convertTemperature(amount, from, to);
                        $('td-unit-formula').textContent = 'Temp conversion';
                    } else {
                        var units = unitCatalog[cat].units;
                        var fromFactor = units[from].factor;
                        var toFactor = units[to].factor;
                        var base = amount * fromFactor;
                        result = base / toFactor;
                        $('td-unit-formula').textContent = 'Base: ' + unitCatalog[cat].base;
                    }

                    var rounded = Number.isFinite(result) ? result.toLocaleString(undefined, { maximumFractionDigits: 6 }) : '—';
                    $('td-unit-result').textContent = rounded;
                }

                function refreshUnitSelects() {
                    var cat = $('td-unit-category').value;
                    var def;
                    if (cat === 'length') def = { from: 'm', to: 'ft' };
                    else if (cat === 'weight') def = { from: 'kg', to: 'lb' };
                    else def = { from: 'c', to: 'f' };

                    setOptions($('td-unit-from'), unitCatalog[cat].units, def.from);
                    setOptions($('td-unit-to'), unitCatalog[cat].units, def.to);
                    calcUnit();
                }

                $('td-unit-category').addEventListener('change', refreshUnitSelects);
                $('td-unit-from').addEventListener('change', calcUnit);
                $('td-unit-to').addEventListener('change', calcUnit);
                $('td-unit-value').addEventListener('input', calcUnit);
                $('td-unit-convert').addEventListener('click', calcUnit);
                $('td-unit-swap').addEventListener('click', function () {
                    var a = $('td-unit-from').value;
                    var b = $('td-unit-to').value;
                    $('td-unit-from').value = b;
                    $('td-unit-to').value = a;
                    calcUnit();
                });

                refreshUnitSelects();
            })();

            // Currency converter
            (function initCurrencyConverter() {
                var common = ['USD', 'EUR', 'GBP', 'BDT', 'INR', 'JPY', 'AUD', 'CAD', 'SGD', 'AED'];
                var cached = { base: null, rates: null, updatedUnix: null };

                function setCurrencyOptions(selectEl, selected) {
                    selectEl.innerHTML = '';
                    common.forEach(function (c) {
                        var opt = document.createElement('option');
                        opt.value = c;
                        opt.textContent = c;
                        if (c === selected) opt.selected = true;
                        selectEl.appendChild(opt);
                    });
                }

                function fmtUpdated(unix) {
                    if (!unix) return '—';
                    try {
                        var d = new Date(unix * 1000);
                        return d.toLocaleString();
                    } catch (e) {
                        return '—';
                    }
                }

                async function loadRates(base) {
                    $('td-fx-status').textContent = 'Loading…';
                    var url = '{{ route('tyro-dashboard.examples.widgets.fx', ['base' => 'USD']) }}'.replace('USD', encodeURIComponent(base));
                    var res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    var data = await res.json();
                    if (!res.ok) throw new Error(data && data.error ? data.error : 'Failed to load rates');
                    cached.base = data.base;
                    cached.rates = data.rates;
                    cached.updatedUnix = data.time_last_update_unix || null;
                    $('td-fx-status').textContent = 'Ready (' + cached.base + ')';
                    $('td-fx-updated').textContent = 'Updated: ' + fmtUpdated(cached.updatedUnix);
                }

                function convertFx() {
                    var amount = toNum($('td-fx-amount').value);
                    var from = $('td-fx-from').value;
                    var to = $('td-fx-to').value;

                    if (!cached.rates || !cached.base) {
                        $('td-fx-result').textContent = 'Result: —';
                        $('td-fx-rate').textContent = 'Rate: —';
                        return;
                    }

                    // Rates are relative to cached.base
                    var rFrom = cached.rates[from];
                    var rTo = cached.rates[to];
                    if (!rFrom || !rTo) {
                        $('td-fx-result').textContent = 'Result: —';
                        $('td-fx-rate').textContent = 'Rate: —';
                        return;
                    }

                    var rate = rTo / rFrom;
                    var out = amount * rate;

                    $('td-fx-rate').textContent = 'Rate: 1 ' + from + ' = ' + rate.toFixed(6) + ' ' + to;
                    $('td-fx-result').textContent = 'Result: ' + out.toLocaleString(undefined, { maximumFractionDigits: 2, minimumFractionDigits: 2 }) + ' ' + to;
                }

                setCurrencyOptions($('td-fx-from'), 'USD');
                setCurrencyOptions($('td-fx-to'), 'BDT');
                $('td-fx-status').textContent = 'Loading…';
                $('td-fx-updated').textContent = 'Updated: —';

                $('td-fx-convert').addEventListener('click', async function () {
                    try {
                        var base = $('td-fx-from').value;
                        if (!cached.rates || cached.base !== base) {
                            await loadRates(base);
                        }
                        convertFx();
                    } catch (e) {
                        $('td-fx-status').textContent = String(e && e.message ? e.message : e);
                    }
                });
                $('td-fx-refresh').addEventListener('click', async function () {
                    try {
                        await loadRates($('td-fx-from').value);
                        convertFx();
                    } catch (e) {
                        $('td-fx-status').textContent = String(e && e.message ? e.message : e);
                    }
                });
                $('td-fx-swap').addEventListener('click', function () {
                    var a = $('td-fx-from').value;
                    var b = $('td-fx-to').value;
                    $('td-fx-from').value = b;
                    $('td-fx-to').value = a;
                    cached.base = null;
                    cached.rates = null;
                    cached.updatedUnix = null;
                    $('td-fx-status').textContent = 'Swapped';
                    $('td-fx-updated').textContent = 'Updated: —';
                    $('td-fx-rate').textContent = 'Rate: —';
                    $('td-fx-result').textContent = 'Result: —';
                });
                $('td-fx-amount').addEventListener('input', convertFx);
                $('td-fx-from').addEventListener('change', function () {
                    cached.base = null;
                    cached.rates = null;
                    cached.updatedUnix = null;
                    $('td-fx-status').textContent = 'Base changed';
                    $('td-fx-updated').textContent = 'Updated: —';
                    $('td-fx-rate').textContent = 'Rate: —';
                    $('td-fx-result').textContent = 'Result: —';
                });
                $('td-fx-to').addEventListener('change', convertFx);

                // Auto-load once so it feels ready
                (async function () {
                    try {
                        await loadRates($('td-fx-from').value);
                        convertFx();
                    } catch (e) {
                        $('td-fx-status').textContent = 'FX unavailable';
                    }
                })();
            })();

            // Invoice
            initInvoice();
        });
    })();
</script>
@endpush

@endsection
