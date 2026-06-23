@props([
    'title' => null,
    'description' => null,
])

@php
    $titleValue = $title instanceof \Illuminate\View\ComponentSlot ? trim((string) $title) : (string) ($title ?? '');
    $descValue = $description instanceof \Illuminate\View\ComponentSlot ? trim((string) $description) : (string) ($description ?? '');
    $hasActions = isset($actions) && trim((string) $actions) !== '';
@endphp

<div {{ $attributes->merge(['class' => 'page-header']) }}>
    <div class="page-header-row">
        <div style="min-width:0;">
            @if($titleValue !== '')
                <h1 class="page-title">{{ $titleValue }}</h1>
            @endif
            @if($descValue !== '')
                <p class="page-description">{!! $descValue !!}</p>
            @endif
        </div>
        @if($hasActions)
            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">{!! $actions !!}</div>
        @endif
    </div>
</div>
