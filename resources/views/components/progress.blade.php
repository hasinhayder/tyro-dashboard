@props([
    'value' => 0,
    'variant' => 'primary',
    'label' => null,
    'showLabel' => false,
    'height' => '8px',
])

@php
    $variant = in_array((string) $variant, ['primary','success','warning','error','info'], true) ? (string) $variant : 'primary';
    $value = max(0, min(100, (int) $value));
    $showLabel = filter_var($showLabel, FILTER_VALIDATE_BOOL);
    $hasLabel = filled($label);
    $trackHeight = ctype_digit((string) $height) ? $height.'px' : $height;
@endphp

<div {{ $attributes->merge(['class' => 'progress-wrapper']) }}>
    @if($hasLabel || $showLabel)
        <div style="display:flex; justify-content:space-between; align-items:center; gap:0.75rem; margin-bottom:0.375rem;">
            <span style="font-size:0.8125rem; font-weight:500; color: var(--muted-foreground); min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $label }}</span>
            @if($showLabel)
                <span style="font-size:0.8125rem; font-weight:500; color: var(--muted-foreground); flex-shrink:0;">{{ $value }}%</span>
            @endif
        </div>
    @endif
    <div class="progress-track" role="progressbar" aria-valuenow="{{ $value }}" aria-valuemin="0" aria-valuemax="100" style="height: {{ $trackHeight }};">
        <div class="progress-bar progress-bar-{{ $variant }}" style="width: {{ $value }}%; height: 100%;"></div>
    </div>
</div>
