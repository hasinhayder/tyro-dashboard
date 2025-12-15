{{-- More charts --}}
<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Bar chart --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Weekly Sales</h3>
            <span class="badge badge-secondary">Bar chart</span>
        </div>
        <div class="card-body">
            <div style="display:flex; align-items: baseline; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">Total</div>
                    <div style="font-size: 1.75rem; font-weight: 700; letter-spacing: -0.02em;">{{ number_format(collect($charts['weekly_bars'])->sum('value')) }}</div>
                </div>
                <div class="badge-list">
                    <span class="badge badge-primary">{{ count($charts['weekly_bars']) }} days</span>
                </div>
            </div>

            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <div style="display:grid; grid-template-columns: repeat(7, 1fr); gap: 0.625rem; align-items: end; height: 180px;">
                    @foreach($charts['weekly_bars'] as $bar)
                        <div style="display:flex; flex-direction: column; gap: 0.5rem; align-items: stretch;">
                            <div title="{{ $bar['label'] }}: {{ $bar['value'] }}" style="height: 150px; display:flex; align-items:flex-end; position: relative;">
                                <div style="position:absolute; top: 0; left: 0; right: 0; text-align:center; font-size: 0.75rem; color: var(--muted-foreground);">{{ number_format($bar['value']) }}</div>
                                <div style="width: 100%; height: {{ $bar['pct'] }}%; background: var(--foreground); border-radius: 8px; border: 1px solid var(--border);"></div>
                            </div>
                            <div style="font-size: 0.8125rem; color: var(--muted-foreground); text-align:center;">{{ $bar['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Stacked bars (distribution) --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Channel Mix</h3>
            <span class="badge badge-secondary">Stacked bars</span>
        </div>
        <div class="card-body">
            <div style="display:flex; flex-direction: column; gap: 1rem;">
                @foreach($charts['channel_mix'] as $row)
                    <div>
                        <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem; margin-bottom: 0.5rem;">
                            <div style="font-weight: 600;">{{ $row['label'] }}</div>
                            <span class="badge badge-primary">{{ collect($row['segments'])->sum('pct') }}%</span>
                        </div>
                        <div style="height: 12px; width: 100%; background: var(--muted); border-radius: 9999px; overflow:hidden; border: 1px solid var(--border); display:flex;">
                            @foreach($row['segments'] as $segment)
                                <div title="{{ $segment['label'] }}: {{ $segment['pct'] }}%" style="height: 100%; width: {{ $segment['pct'] }}%; background: {{ $segment['color'] }};"></div>
                            @endforeach
                        </div>
                        <div style="display:flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 0.5rem;">
                            @foreach($row['segments'] as $segment)
                                <div style="display:flex; align-items:center; gap: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                                    <span style="width: 10px; height: 10px; border-radius: 9999px; background: {{ $segment['color'] }}; display:inline-block;"></span>
                                    <span>{{ $segment['label'] }} ({{ $segment['pct'] }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
