@props([
    'value' => null,
    'label' => null,
    'variant' => 'primary',
    'change' => null,
    'trend' => 'none',
])

@php
    $variant = in_array((string) $variant, ['primary','success','warning','danger','info'], true) ? (string) $variant : 'primary';
    $trend = in_array((string) $trend, ['up','down','none'], true) ? (string) $trend : 'none';

    $defaultIcons = [
        'primary' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>',
        'success' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'warning' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>',
        'danger' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l5-5m0 5l-5-5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'info' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    ];

    $iconProvided = isset($icon) ? trim((string) $icon) : '';
    $iconSvg = $iconProvided !== '' ? $iconProvided : ($defaultIcons[$variant] ?? $defaultIcons['primary']);
    $hasChange = filled($change);
    $changeClass = $trend !== 'none' ? 'stat-change-'.$trend : '';
@endphp

<div {{ $attributes->merge(['class' => 'stat-card']) }}>
    <div class="stat-icon stat-icon-{{ $variant }}">{!! $iconSvg !!}</div>
    @if(filled($label))
        <div class="stat-label">{{ $label }}</div>
    @endif
    @if(filled($value))
        <div class="stat-value">{{ $value }}</div>
    @endif
    @if($hasChange)
        <div class="stat-change {{ $changeClass }}">
            @if($trend === 'up')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17l9-9m0 0H7m9 0v9"/></svg>
            @elseif($trend === 'down')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7l9 9m0 0V7m9 9H7"/></svg>
            @endif
            <span>{{ $change }}</span>
        </div>
    @endif
</div>
