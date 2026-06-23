@props([
    'title' => null,
])

@php
    $titleValue = $title instanceof \Illuminate\View\ComponentSlot ? trim((string) $title) : (string) ($title ?? '');
    $hasDescription = isset($description) && trim((string) $description) !== '';
    $hasActions = isset($actions) && trim((string) $actions) !== '';
    $hasFooter = isset($footer) && trim((string) $footer) !== '';
    $body = trim((string) $slot);
    $showHeader = $titleValue !== '' || $hasDescription || $hasActions;
@endphp

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if($showHeader)
        <div class="card-header">
            <div style="min-width:0;">
                @if($titleValue !== '')
                    <h3 class="card-title" style="margin:0;">{{ $titleValue }}</h3>
                @endif
                @if($hasDescription)
                    <p class="page-description" style="margin-top:{{ $titleValue !== '' ? '0.25rem' : '0' }};">{!! $description !!}</p>
                @endif
            </div>
            @if($hasActions)
                <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">{!! $actions !!}</div>
            @endif
        </div>
    @endif
    @if($body !== '')
        <div class="card-body">{!! $slot !!}</div>
    @endif
    @if($hasFooter)
        <div class="card-footer">{!! $footer !!}</div>
    @endif
</div>
