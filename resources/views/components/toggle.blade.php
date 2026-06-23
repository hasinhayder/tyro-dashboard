@props([
    'name' => null,
    'id' => null,
    'value' => '1',
    'checked' => false,
    'disabled' => false,
    'label' => null,
])

@php
    $isChecked = filter_var($checked, FILTER_VALIDATE_BOOL);
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOL);
    $toggleId = filled($id) ? $id : 'tyro-toggle-'.\Illuminate\Support\Str::random(8);
    $labelText = trim((string) ($label ?? ''));
    $wrapStyle = (string) ($attributes->get('style') ?? '');
    $passthrough = trim((string) $attributes->except(['style']));

    $labelAttrs = '';
    if ($wrapStyle !== '') {
        $labelAttrs .= ' style="'.e($wrapStyle).'"';
    }
    if ($isDisabled) {
        $labelAttrs .= ' aria-disabled="true"';
    }

    $inputAttrs = '';
    if (filled($name)) {
        $inputAttrs .= ' name="'.e($name).'"';
    }
    $inputAttrs .= ' value="'.e($value).'"';
    if ($isChecked) {
        $inputAttrs .= ' checked';
    }
    if ($isDisabled) {
        $inputAttrs .= ' disabled';
    }
    $inputAttrs .= ' role="switch" aria-checked="'.($isChecked ? 'true' : 'false').'"';
    if ($passthrough !== '') {
        $inputAttrs .= ' '.$passthrough;
    }
@endphp

<label class="toggle-label" for="{{ $toggleId }}"{!! $labelAttrs !!}>
    <input type="checkbox" class="toggle-input" id="{{ $toggleId }}"{!! $inputAttrs !!}>
    <span class="toggle-slider"></span>
    @if($labelText !== '')
        <span class="toggle-text">{{ $labelText }}</span>
    @endif
</label>
