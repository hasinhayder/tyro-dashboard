@props([
    'href' => null,
    'variant' => 'default',
    'icon' => null,
])

@php
    $variantKey = in_array((string) $variant, ['default', 'danger'], true) ? (string) $variant : 'default';
    $iconSvg = isset($icon) ? trim((string) $icon) : '';
    $labelContent = trim((string) $slot);
    $classExtra = trim((string) ($attributes->get('class') ?? ''));
    $itemClass = trim('dropdown-item'.($variantKey === 'danger' ? ' dropdown-item-danger' : '').($classExtra !== '' ? ' '.$classExtra : ''));
    $extraAttrs = trim((string) $attributes->except(['class', 'style']));

    $inner = '';
    if ($iconSvg !== '') {
        $inner .= $iconSvg.' ';
    }
    if ($labelContent !== '') {
        $inner .= e($labelContent);
    }

    $tag = filled($href) ? 'a' : 'button';
    $hrefAttr = filled($href) ? ' href="'.e($href).'"' : '';
    $typeAttr = filled($href) ? '' : ' type="button"';
@endphp

<{{ $tag }}{!! $hrefAttr !!}{!! $typeAttr !!} class="{{ $itemClass }}" role="menuitem"@if($extraAttrs !== '') {!! ' '.$extraAttrs !!}@endif>{!! $inner !!}</{{ $tag }}>
