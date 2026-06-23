@props([
    'name' => null,
    'id' => null,
    'value' => '1',
    'checked' => false,
    'indeterminate' => false,
    'disabled' => false,
    'label' => null,
    'color' => 'default',
])

@php
    $isChecked = filter_var($checked, FILTER_VALIDATE_BOOL);
    $isIndeterminate = filter_var($indeterminate, FILTER_VALIDATE_BOOL);
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOL);
    $checkboxId = filled($id) ? $id : 'tyro-checkbox-'.\Illuminate\Support\Str::random(8);
    $labelText = trim((string) ($label ?? ''));
    $colorKey = in_array((string) $color, ['default', 'primary', 'success', 'warning', 'danger', 'info', 'secondary'], true) ? (string) $color : 'default';
    $inputClass = 'checkbox-input'.($colorKey !== 'default' ? ' checkbox-input-'.$colorKey : '');
    $wrapStyle = (string) ($attributes->get('style') ?? '');
    $passthrough = trim((string) $attributes->except(['style']));

    $ariaChecked = $isIndeterminate ? 'mixed' : ($isChecked ? 'true' : 'false');

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
    if ($isChecked && ! $isIndeterminate) {
        $inputAttrs .= ' checked';
    }
    if ($isIndeterminate) {
        $inputAttrs .= ' data-indeterminate="true"';
    }
    if ($isDisabled) {
        $inputAttrs .= ' disabled';
    }
    $inputAttrs .= ' aria-checked="'.e($ariaChecked).'"';
    if ($passthrough !== '') {
        $inputAttrs .= ' '.$passthrough;
    }
@endphp

<label class="checkbox-label" for="{{ $checkboxId }}"{!! $labelAttrs !!}>
    <input type="checkbox" class="{{ $inputClass }}" id="{{ $checkboxId }}"{!! $inputAttrs !!}>
    @if($labelText !== '')
        <span class="checkbox-text">{{ $labelText }}</span>
    @endif
</label>
