@props([
    'variant' => 'primary',
])

@php
    $variant = in_array((string) $variant, ['primary','success','warning','danger','secondary','info'], true) ? (string) $variant : 'primary';
@endphp

<span {{ $attributes->merge(['class' => 'badge badge-'.$variant]) }}>
    {{ $slot }}
</span>
