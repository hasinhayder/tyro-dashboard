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
    $useModernMulti = $isMultiple && $useOptions;

    $isSelected = function ($optValue) use ($selectedValue, $isMultiple) {
        if ($isMultiple) {
            $sel = is_array($selectedValue) ? $selectedValue : array_filter(explode(',', (string) $selectedValue));
            return in_array((string) $optValue, array_map('strval', $sel), true);
        }
        return (string) $optValue === (string) $selectedValue;
    };

    $controlClass = trim(($sizeClass !== '' ? $sizeClass.' ' : '').($hasLeading ? 'has-leading' : ''));
    $multiPlaceholder = filled($placeholder) ? $placeholder : 'Select…';

    $wrapperClass = 'tyro-select form-group';
    $wrapperAttrs = ' style="margin-bottom:0;"';
    if ($useModernMulti) {
        $wrapperClass .= ' tyro-select-multi';
        if ($isInvalid) { $wrapperClass .= ' is-invalid'; }
        if ($isDisabled) { $wrapperClass .= ' is-disabled'; }
        $wrapperAttrs = ' id="'.e($selectId).'" data-multi-select data-multi-name="'.e($nameAttr).'"'.$wrapperAttrs;
    }
    $labelFor = ($hasLabel && !$useModernMulti) ? $selectId : null;
@endphp

<div class="{{ $wrapperClass }}"{{ $wrapperAttrs }}>
    @if($hasLabel)
        <label class="form-label"@if($labelFor) for="{{ $labelFor }}"@endif>
            {{ $label }}@if($isRequired) <span style="color: var(--destructive);">*</span>@endif
        </label>
    @endif

    @if($useModernMulti)
        <button type="button" class="tyro-multi-trigger" data-multi-trigger aria-haspopup="listbox" aria-expanded="false"@if($isDisabled) disabled @endif>
            <span class="tyro-multi-chips" data-multi-chips></span>
            <span class="tyro-multi-placeholder" data-multi-placeholder>{{ $multiPlaceholder }}</span>
            <span class="tyro-multi-count" data-multi-count style="display:none;"></span>
            <span class="tyro-multi-chevron">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </span>
        </button>
        <div class="tyro-multi-menu" role="listbox" data-multi-menu>
            @foreach($options as $optKey => $optVal)
                <label class="tyro-multi-option">
                    <input type="checkbox" name="{{ e($nameAttr) }}[]" value="{{ e($optKey) }}" data-multi-value="{{ e($optKey) }}" data-multi-label="{{ e($optVal) }}"@if($isSelected($optKey)) checked @endif>
                    <span class="tyro-multi-option-text">{{ e($optVal) }}</span>
                    <span class="tyro-multi-option-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                </label>
            @endforeach
            <div class="tyro-multi-empty" data-multi-empty style="display:none;">No options</div>
            <div class="tyro-multi-footer">
                <span class="tyro-multi-selected-count" data-multi-selected-count>0 selected</span>
                <button type="button" data-multi-clear>Clear</button>
            </div>
        </div>
    @else
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
    @endif

    @if($isInvalid && filled($error))
        <p class="form-error">{{ $error }}</p>
    @elseif($hasHint)
        <p class="form-hint">{{ $hint }}</p>
    @endif
</div>

@once
@push('scripts')
<script>
(function () {
    if (window.__tyroMultiSelectInit) return;
    window.__tyroMultiSelectInit = true;

    var closeSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';

    function syncChips(root) {
        var boxes = root.querySelectorAll('[data-multi-value]');
        var chipsHost = root.querySelector('[data-multi-chips]');
        var placeholder = root.querySelector('[data-multi-placeholder]');
        var count = root.querySelector('[data-multi-count]');
        var selCount = root.querySelector('[data-multi-selected-count]');
        var checked = [];
        boxes.forEach(function (b) {
            var row = b.closest('.tyro-multi-option');
            if (row) row.style.display = '';
            if (b.checked) checked.push(b);
        });
        chipsHost.innerHTML = '';
        checked.forEach(function (b) {
            var chip = document.createElement('span');
            chip.className = 'tyro-multi-chip';
            var label = document.createElement('span');
            label.textContent = b.getAttribute('data-multi-label') || b.value;
            var remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'tyro-multi-chip-remove';
            remove.setAttribute('aria-label', 'Remove ' + (b.getAttribute('data-multi-label') || b.value));
            remove.innerHTML = closeSvg;
            remove.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                b.checked = false;
                syncChips(root);
            });
            chip.appendChild(label);
            chip.appendChild(remove);
            chipsHost.appendChild(chip);
        });
        var n = checked.length;
        if (placeholder) placeholder.style.display = n ? 'none' : '';
        if (count) { count.style.display = n ? '' : 'none'; count.textContent = n + ' selected'; }
        if (selCount) selCount.textContent = n + ' selected';
    }

    function close(root) {
        root.classList.remove('is-open');
        var t = root.querySelector('[data-multi-trigger]');
        if (t) t.setAttribute('aria-expanded', 'false');
    }

    function toggle(root) {
        var opening = !root.classList.contains('is-open');
        document.querySelectorAll('[data-multi-select].is-open').forEach(function (r) { if (r !== root) close(r); });
        root.classList.toggle('is-open', opening);
        var t = root.querySelector('[data-multi-trigger]');
        if (t) t.setAttribute('aria-expanded', opening ? 'true' : 'false');
    }

    document.addEventListener('click', function (e) {
        var root = e.target.closest('[data-multi-select]');
        var clearBtn = e.target.closest('[data-multi-clear]');
        if (clearBtn && root) {
            root.querySelectorAll('[data-multi-value]').forEach(function (b) { b.checked = false; });
            syncChips(root);
            return;
        }
        var trigger = e.target.closest('[data-multi-trigger]');
        if (trigger && root) { toggle(root); return; }
        if (root && e.target.closest('.tyro-multi-menu')) { return; }
        if (!e.target.closest('[data-multi-select]')) {
            document.querySelectorAll('[data-multi-select].is-open').forEach(close);
        }
    });

    document.addEventListener('change', function (e) {
        var root = e.target.closest('[data-multi-select]');
        if (root && e.target.matches('[data-multi-value]')) { syncChips(root); }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') document.querySelectorAll('[data-multi-select].is-open').forEach(close);
    });

    document.querySelectorAll('[data-multi-select]').forEach(syncChips);
})();
</script>
@endpush
@endonce
