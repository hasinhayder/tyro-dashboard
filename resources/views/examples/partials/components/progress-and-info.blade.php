<div class="grid-2" style="margin-bottom: 1.5rem;">
    {{-- Progress bars --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Project Progress</h3>
            <span class="badge badge-secondary">Progress bars</span>
        </div>
        <div class="card-body">
            <div style="display:flex; flex-direction: column; gap: 1rem;">
                @foreach($progress as $item)
                    <div>
                        <div style="display:flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 0.5rem;">
                            <div style="min-width:0;">
                                <div style="font-size: 0.9375rem; font-weight: 600; color: var(--foreground); white-space: nowrap; overflow:hidden; text-overflow: ellipsis;">{{ $item['title'] }}</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">{{ $item['subtitle'] }}</div>
                            </div>
                            <span class="badge {{ $item['badge_class'] }}">{{ $item['badge_text'] }}</span>
                        </div>

                        <div style="height: 10px; width: 100%; background: var(--muted); border-radius: 9999px; overflow:hidden; border: 1px solid var(--border);">
                            <div style="height: 100%; width: {{ $item['pct'] }}%; background: {{ $item['bar_color'] }};"></div>
                        </div>
                        <div style="display:flex; justify-content: space-between; margin-top: 0.375rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                            <span>{{ $item['pct'] }}% complete</span>
                            <span>{{ $item['meta'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Info cards / quick insights --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Info Cards</h3>
            <span class="badge badge-secondary">Layouts</span>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                @foreach($infoCards as $c)
                    <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--background);">
                        <div style="display:flex; align-items:flex-start; justify-content: space-between; gap: 1rem;">
                            <div style="min-width: 0;">
                                <div style="font-size: 0.875rem; color: var(--muted-foreground);">{{ $c['eyebrow'] }}</div>
                                <div style="font-size: 1.0625rem; font-weight: 700; letter-spacing: -0.01em; color: var(--foreground);">{{ $c['title'] }}</div>
                            </div>
                            <span class="badge {{ $c['badge_class'] }}">{{ $c['badge'] }}</span>
                        </div>
                        <div style="margin-top: 0.5rem; font-size: 0.9375rem; color: var(--muted-foreground);">{{ $c['description'] }}</div>
                        <div style="display:flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.875rem;">
                            <a href="#" class="btn btn-ghost btn-sm" onclick="return false;">Secondary action</a>
                            <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">View details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
