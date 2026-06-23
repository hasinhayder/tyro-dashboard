@props([
    'variant' => 'info',
    'title' => null,
])

@php
    $variant = in_array((string) $variant, ['success','error','warning','info'], true) ? (string) $variant : 'info';
    $hasTitle = filled($title);
    $message = trim((string) $slot);

    $defaultIcons = [
        'success' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'error' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l5-5m0 5l-5-5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'warning' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>',
        'info' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    ];

    $iconProvided = isset($icon) ? trim((string) $icon) : '';
    $iconSvg = $iconProvided !== '' ? $iconProvided : ($defaultIcons[$variant] ?? '');
@endphp

<div {{ $attributes->merge(['class' => 'alert alert-'.$variant]) }}>
    @if($iconSvg !== '')
        {!! $iconSvg !!}
    @endif
    <div class="alert-content">
        @if($hasTitle)
            <div class="alert-title">{{ $title }}</div>
        @endif
        @if($message !== '')
            <div class="alert-message" style="color: var(--muted-foreground);">{!! $slot !!}</div>
        @endif
    </div>
</div>
