@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'label' => null,
    'placeholder' => null,
    'options' => null,
    'icon' => null,
    'size' => 'md',
    'variant' => 'default',
    'disabled' => false,
    'required' => false,
    'multiple' => false,
    'hint' => null,
    'error' => null,
])

@php
    $selectId = filled($id) ? $id : 'tyro-select-'.\Illuminate\Support\Str::random(8);
    $nameAttr = filled($name) ? (string) $name : null;
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOL);
    $isRequired = filter_var($required, FILTER_VALIDATE_BOOL);
    $isMultiple = filter_var($multiple, FILTER_VALIDATE_BOOL);

    $sizeKey = in_array((string) $size, ['sm', 'md', 'lg'], true) ? (string) $size : 'md';
    $sizeClass = $sizeKey === 'sm' ? 'tyro-select-sm' : ($sizeKey === 'lg' ? 'tyro-select-lg' : '');

    $isInvalid = in_array((string) $variant, ['error', 'invalid'], true) || filled($error);
    $iconSvg = isset($icon) ? trim((string) $icon) : '';
    $hasLeading = $iconSvg !== '';
    $hasLabel = filled($label);
    $hasHint = filled($hint);

    $selectedValue = old($nameAttr ?? '', $value);

    $extraClass = trim((string) ($attributes->get('class') ?? ''));
    $selectExtraAttrs = trim((string) $attributes->except(['class', 'style']));
    $selectClass = trim('form-select'.($extraClass !== '' ? ' '.$extraClass : '').($isInvalid ? ' is-invalid' : ''));

    $selectAttrs = ' id="'.e($selectId).'" class="'.e($selectClass).'"';
    if ($nameAttr !== null) {
        $selectAttrs .= ' name="'.e($nameAttr).($isMultiple ? '[]' : '').'"';
    }
    if ($isDisabled) { $selectAttrs .= ' disabled'; }
    if ($isRequired) { $selectAttrs .= ' required'; }
    if ($isMultiple) { $selectAttrs .= ' multiple'; }
    if ($selectExtraAttrs !== '') { $selectAttrs .= ' '.$selectExtraAttrs; }

    $slotOptions = trim((string) $slot);
    $useOptions = is_iterable($options) && !empty($options);

    $isSelected = function ($optValue) use ($selectedValue, $isMultiple) {
        if ($isMultiple) {
            $sel = is_array($selectedValue) ? $selectedValue : array_filter(explode(',', (string) $selectedValue));
            return in_array((string) $optValue, array_map('strval', $sel), true);
        }
        return (string) $optValue === (string) $selectedValue;
    };

    $controlClass = trim(($sizeClass !== '' ? $sizeClass.' ' : '').($hasLeading ? 'has-leading' : ''));
@endphp

<div class="tyro-select form-group" style="margin-bottom:0;">
    @if($hasLabel)
        <label class="form-label" for="{{ $selectId }}">
            {{ $label }}@if($isRequired) <span style="color: var(--destructive);">*</span>@endif
        </label>
    @endif

    <div class="tyro-select-control {{ $controlClass }}">
        @if($hasLeading)
            <span class="tyro-select-leading">{!! $iconSvg !!}</span>
        @endif
        <select{!! $selectAttrs !!}>
            @if(filled($placeholder) && !$isMultiple)
                <option value=""@if($isSelected('')) selected @endif disabled hidden>{{ $placeholder }}</option>
            @endif
            @if($useOptions)
                @foreach($options as $optKey => $optVal)
                    <option value="{{ e($optKey) }}"@if($isSelected($optKey)) selected @endif>{{ e($optVal) }}</option>
                @endforeach
            @elseif($slotOptions !== '')
                {!! $slot !!}
            @endif
        </select>
    </div>

    @if($isInvalid && filled($error))
        <p class="form-error">{{ $error }}</p>
    @elseif($hasHint)
        <p class="form-hint">{{ $hint }}</p>
    @endif
</div>
