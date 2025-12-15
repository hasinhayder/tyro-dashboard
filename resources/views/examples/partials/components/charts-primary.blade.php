<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Line chart (SVG) --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Traffic (Last 14 days)</h3>
            <span class="badge badge-secondary">SVG chart</span>
        </div>
        <div class="card-body">
            <div style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Total</div>
                    <div style="font-size: 1.75rem; font-weight: 700; letter-spacing: -0.02em;">{{ number_format($charts['traffic_total']) }}</div>
                </div>
                <div class="badge-list">
                    <span class="badge badge-primary">Unique</span>
                    <span class="badge badge-success">+{{ $charts['traffic_growth_pct'] }}%</span>
                </div>
            </div>

            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <svg viewBox="0 0 600 180" width="100%" height="180" preserveAspectRatio="none" style="display:block; color: var(--primary);">
                    <g opacity="0.35" stroke="currentColor" style="color: var(--muted-foreground);">
                        <path d="M0 150 H600" />
                        <path d="M0 110 H600" />
                        <path d="M0 70 H600" />
                        <path d="M0 30 H600" />
                    </g>
                    <path d="{{ $charts['traffic_area_path'] }}" fill="currentColor" opacity="0.12"></path>
                    <path d="{{ $charts['traffic_line_path'] }}" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <div style="display:flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                    <span>{{ $charts['traffic_range_label_left'] }}</span>
                    <span>{{ $charts['traffic_range_label_right'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts (Tabbed) --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Charts</h3>
            <span class="badge badge-secondary">Tabbed</span>
        </div>
        <div class="card-body">
            <div data-td-tabset>
                <div class="tabs" style="margin-bottom: 1rem;">
                    <a href="#" class="tab-link active" data-td-tab="donut" onclick="return false;">Donut</a>
                    <a href="#" class="tab-link" data-td-tab="pie" onclick="return false;">Pie</a>
                    <a href="#" class="tab-link" data-td-tab="double-line" onclick="return false;">Double line</a>
                    <a href="#" class="tab-link" data-td-tab="horizontal-bars" onclick="return false;">Horizontal bars</a>
                    <a href="#" class="tab-link" data-td-tab="area" onclick="return false;">Area</a>
                </div>

                {{-- Donut --}}
                <div data-td-tab-panel="donut">
                    <div style="display: grid; grid-template-columns: 140px 1fr; gap: 1.25rem; align-items: center;">
                        <div style="display:flex; align-items:center; justify-content:center;">
                            <svg viewBox="0 0 42 42" width="132" height="132" style="display:block;">
                                <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="var(--border)" stroke-width="6"></circle>
                                @php($offset = 25)
                                @foreach($charts['status_donut'] as $slice)
                                    <circle
                                        cx="21" cy="21" r="15.915"
                                        fill="transparent"
                                        stroke="currentColor"
                                        stroke-width="6"
                                        stroke-dasharray="{{ $slice['pct'] }} {{ 100 - $slice['pct'] }}"
                                        stroke-dashoffset="{{ $offset }}"
                                        stroke-linecap="round"
                                        style="color: {{ $slice['color'] }};"
                                    ></circle>
                                    @php($offset -= $slice['pct'])
                                @endforeach
                            </svg>
                        </div>

                        <div>
                            <div style="display:flex; flex-direction:column; gap: 0.625rem;">
                                @foreach($charts['status_donut'] as $slice)
                                    <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem;">
                                        <div style="display:flex; align-items:center; gap: 0.5rem; min-width: 0;">
                                            <span style="width: 10px; height: 10px; border-radius: 9999px; background: {{ $slice['color'] }}; display:inline-block;"></span>
                                            <span style="font-size: 0.9375rem; color: var(--foreground); white-space: nowrap; overflow:hidden; text-overflow: ellipsis;">{{ $slice['label'] }}</span>
                                        </div>
                                        <div style="font-size: 0.9375rem; color: var(--muted-foreground);">{{ $slice['count'] }} ({{ $slice['pct'] }}%)</div>
                                    </div>
                                @endforeach
                            </div>
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); display:flex; justify-content: space-between;">
                                <span style="font-size: 0.875rem; color: var(--muted-foreground);">Total</span>
                                <strong style="font-size: 0.9375rem;">{{ $charts['status_total'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pie --}}
                <div data-td-tab-panel="pie" style="display:none;">
                    <div style="display: grid; grid-template-columns: 140px 1fr; gap: 1.25rem; align-items: center;">
                        <div style="display:flex; align-items:center; justify-content:center;">
                            <svg viewBox="0 0 70 70" width="132" height="132" style="display:block;">
                                <circle cx="35" cy="35" r="15.915" fill="transparent" stroke="var(--border)" stroke-width="32"></circle>
                                @php($offset = 25)
                                @foreach($charts['status_pie'] as $slice)
                                    <circle
                                        cx="35" cy="35" r="15.915"
                                        fill="transparent"
                                        stroke="currentColor"
                                        stroke-width="32"
                                        stroke-dasharray="{{ $slice['pct'] }} {{ 100 - $slice['pct'] }}"
                                        stroke-dashoffset="{{ $offset }}"
                                        stroke-linecap="butt"
                                        style="color: {{ $slice['color'] }};"
                                    ></circle>
                                    @php($offset -= $slice['pct'])
                                @endforeach
                            </svg>
                        </div>

                        <div>
                            <div style="display:flex; flex-direction:column; gap: 0.625rem;">
                                @foreach($charts['status_pie'] as $slice)
                                    <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem;">
                                        <div style="display:flex; align-items:center; gap: 0.5rem; min-width: 0;">
                                            <span style="width: 10px; height: 10px; border-radius: 9999px; background: {{ $slice['color'] }}; display:inline-block;"></span>
                                            <span style="font-size: 0.9375rem; color: var(--foreground); white-space: nowrap; overflow:hidden; text-overflow: ellipsis;">{{ $slice['label'] }}</span>
                                        </div>
                                        <div style="font-size: 0.9375rem; color: var(--muted-foreground);">{{ $slice['count'] }} ({{ $slice['pct'] }}%)</div>
                                    </div>
                                @endforeach
                            </div>
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); display:flex; justify-content: space-between;">
                                <span style="font-size: 0.875rem; color: var(--muted-foreground);">Total</span>
                                <strong style="font-size: 0.9375rem;">{{ $charts['status_total'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Double line --}}
                <div data-td-tab-panel="double-line" style="display:none;">
                    <div style="display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <div style="font-size: 0.875rem; color: var(--muted-foreground);">Total</div>
                            <div style="font-size: 1.75rem; font-weight: 700; letter-spacing: -0.02em;">{{ number_format($charts['compare_total']) }}</div>
                        </div>
                        <div class="badge-list">
                            <span class="badge badge-primary">{{ $charts['compare_line_a_label'] }}</span>
                            <span class="badge badge-secondary">{{ $charts['compare_line_b_label'] }}</span>
                            <span class="badge badge-success">+{{ $charts['compare_growth_pct'] }}%</span>
                        </div>
                    </div>

                    <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                        <svg viewBox="0 0 600 180" width="100%" height="180" preserveAspectRatio="none" style="display:block;">
                            <g opacity="0.35" stroke="currentColor" style="color: var(--muted-foreground);">
                                <path d="M0 150 H600" />
                                <path d="M0 110 H600" />
                                <path d="M0 70 H600" />
                                <path d="M0 30 H600" />
                            </g>
                            <path d="{{ $charts['compare_line_b_path'] }}" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.45" style="color: var(--muted-foreground);"></path>
                            <path d="{{ $charts['compare_line_a_path'] }}" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="color: var(--foreground);"></path>
                        </svg>
                        <div style="display:flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                            <span>{{ $charts['compare_range_label_left'] }}</span>
                            <span>{{ $charts['compare_range_label_right'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Horizontal bar --}}
                <div data-td-tab-panel="horizontal-bars" style="display:none;">
                    <div style="display:flex; flex-direction: column; gap: 0.875rem;">
                        @foreach($charts['horizontal_bars'] as $row)
                            <div>
                                <div style="display:flex; justify-content: space-between; gap: 1rem; margin-bottom: 0.5rem;">
                                    <div style="font-weight: 600;">{{ $row['label'] }}</div>
                                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">{{ number_format($row['value']) }}</div>
                                </div>
                                <div style="height: 12px; width: 100%; background: var(--muted); border-radius: 9999px; overflow:hidden; border: 1px solid var(--border);">
                                    <div style="height: 100%; width: {{ $row['pct'] }}%; background: {{ $row['color'] }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Area --}}
                <div data-td-tab-panel="area" style="display:none;">
                    <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                        <svg viewBox="0 0 600 180" width="100%" height="180" preserveAspectRatio="none" style="display:block;">
                            <defs>
                                <linearGradient id="td-wave-a" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="{{ $charts['wave_a_color'] }}" stop-opacity="0.55" />
                                    <stop offset="100%" stop-color="{{ $charts['wave_a_color'] }}" stop-opacity="0" />
                                </linearGradient>
                                <linearGradient id="td-wave-b" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="{{ $charts['wave_b_color'] }}" stop-opacity="0.55" />
                                    <stop offset="100%" stop-color="{{ $charts['wave_b_color'] }}" stop-opacity="0" />
                                </linearGradient>
                            </defs>

                            <g opacity="0.35" stroke="currentColor" style="color: var(--muted-foreground);">
                                <path d="M0 150 H600" />
                                <path d="M0 110 H600" />
                                <path d="M0 70 H600" />
                                <path d="M0 30 H600" />
                            </g>

                            <g stroke="currentColor" stroke-dasharray="4 6" opacity="0.25" style="color: var(--muted-foreground);">
                                <path d="M 100 20 V 170" />
                                <path d="M 200 20 V 170" />
                                <path d="M 300 20 V 170" />
                                <path d="M 400 20 V 170" />
                                <path d="M 500 20 V 170" />
                            </g>

                            <path d="{{ $charts['wave_a_area_path'] }}" fill="url(#td-wave-a)"></path>
                            <path d="{{ $charts['wave_b_area_path'] }}" fill="url(#td-wave-b)"></path>

                            <path d="{{ $charts['wave_a_line_path'] }}" fill="none" stroke="{{ $charts['wave_a_color'] }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="{{ $charts['wave_b_line_path'] }}" fill="none" stroke="{{ $charts['wave_b_color'] }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>

                            @foreach($charts['wave_points'] as $p)
                                @php($labelWidth = 12 + (strlen($p['label']) * 7))
                                @php($labelX = max(6, min(600 - $labelWidth - 6, $p['x'] - ($labelWidth / 2))))
                                @php($labelY = max(8, $p['y'] - 28))
                                <g>
                                    <rect x="{{ $labelX }}" y="{{ $labelY }}" width="{{ $labelWidth }}" height="20" rx="10" fill="var(--foreground)" opacity="0.85"></rect>
                                    <text x="{{ $labelX + ($labelWidth / 2) }}" y="{{ $labelY + 14 }}" text-anchor="middle" font-size="11" font-weight="600" fill="var(--background)">{{ $p['label'] }}</text>
                                    <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4.2" fill="{{ $charts['wave_b_color'] }}" stroke="var(--background)" stroke-width="2"></circle>
                                </g>
                            @endforeach
                        </svg>
                        <div style="display:flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                            <span>{{ $charts['wave_range_label_left'] }}</span>
                            <span>{{ $charts['wave_range_label_right'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
