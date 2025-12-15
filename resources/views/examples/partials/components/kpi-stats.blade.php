{{-- KPI / Stat cards --}}
<div class="stats-grid">
    @foreach($kpis as $kpi)
        <div class="stat-card">
            <div class="stat-icon {{ $kpi['icon_class'] }}">
                {!! $kpi['icon'] !!}
            </div>
            <div class="stat-content">
                <div class="stat-label" style="font-size: 0.9375rem;">{{ $kpi['label'] }}</div>
                <div class="stat-value">{{ $kpi['value'] }}</div>
                <div class="stat-change {{ $kpi['change_class'] }}">
                    {!! $kpi['change_icon'] !!}
                    <span>{{ $kpi['change_text'] }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
