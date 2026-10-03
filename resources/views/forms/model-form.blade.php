@php
    $isEdit = $model instanceof \Illuminate\Database\Eloquent\Model;
    $formMethod = strtoupper($method);
    $htmlMethod = in_array($formMethod, ['GET', 'HEAD'], true) ? 'GET' : 'POST';
    $needsMethodSpoof = ! in_array($formMethod, ['GET', 'HEAD', 'POST'], true);
    $needsCsrf = ! in_array($formMethod, ['GET', 'HEAD'], true);
    $formAction = $action ?? request()->url();
    $columnCount = max(1, (int) count($columns));
@endphp

<form {{ $attributes->merge(['method' => $htmlMethod, 'action' => $formAction]) }}>
    @if($needsCsrf)
        @csrf
    @endif

    @if($needsMethodSpoof)
        @method($formMethod)
    @endif

    @if($header)
        <div class="form-header" style="margin-bottom: 1rem;">
            <h3 class="card-title" style="margin: 0;">{{ $header }}</h3>
        </div>
    @endif

    <div class="model-form-grid @if($columnCount === 2) model-form-two-columns @endif" style="display: grid; grid-template-columns: repeat({{ $columnCount }}, minmax(0, 1fr)); gap: 0 1.5rem;">
        @foreach($columns as $columnFields)
        <div class="model-form-column">
            @foreach($columnFields as $key)
            @php
                $field = $fields[$key];
                $type = $field['type'] ?? 'text';
                $label = $field['label'] ?? \Illuminate\Support\Str::headline($key);
                $fieldValue = old($key, $isEdit && $model->offsetExists($key) ? $model->{$key} : ($field['default'] ?? null));

                if ($fieldValue instanceof \Illuminate\Database\Eloquent\Model) {
                    $fieldValue = $fieldValue->getKey();
                }

                $multipleValues = $fieldValue instanceof \Illuminate\Support\Collection
                    ? $fieldValue->all()
                    : (is_array($fieldValue) ? $fieldValue : []);
                $selectedValues = array_map('strval', (array) old($key, $multipleValues));

                $attributeHtml = '';
                foreach (($field['attributes'] ?? []) as $attrName => $attrValue) {
                    $attributeHtml .= ' '.e($attrName).'="'.e($attrValue).'"';
                }

                $isMultiple = (($type === 'select' || $type === 'multiselect') && ($field['multiple'] ?? false)) || $type === 'multiselect';
                $isOptionField = in_array($type, ['select', 'multiselect', 'radio', 'checkbox'], true);
                $selectOptions = null;

                if ($isOptionField) {
                    $selectOptions = \HasinHayder\TyroDashboard\Support\ModelFormFieldResolver::resolveSelectOptions($model, $key, $field);

                    if ($selectOptions === null) {
                        $type = ($type === 'select' && ! $isMultiple) ? 'number' : 'text';
                        $isOptionField = false;
                    }
                }

                $hideLabel = in_array($type, ['boolean', 'media', 'radio', 'checkbox'], true);
            @endphp

            @if($type === 'hidden')
            <input type="hidden" name="{{ $key }}" value="{{ $fieldValue }}">
            @continue
            @endif

            <div class="form-group" style="margin-bottom: 1rem;">
                @if(! $hideLabel)
                <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                @endif

                @if($type === 'media')
                <x-tyro-dashboard::media-picker
                    :name="$key"
                    :value="$fieldValue"
                    :label="$label"
                    :preview="true"
                />

                @elseif($type === 'file')
                <input type="file" name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror" {!! $attributeHtml !!}>

                @elseif($type === 'textarea' || $type === 'markdown')
                <textarea name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror" rows="5" placeholder="{{ $field['placeholder'] ?? '' }}" {{ ($field['readonly'] ?? false) ? 'readonly' : '' }}{!! $attributeHtml !!}>{{ $fieldValue }}</textarea>

                @elseif($type === 'richtext')
                <div class="richtext-wrapper">
                    <div id="editor-{{ $key }}" style="height: 200px; background: #fff;"></div>
                    <textarea name="{{ $key }}" id="{{ $key }}" style="display:none">{{ $fieldValue }}</textarea>
                </div>

                @elseif($isOptionField && $type === 'radio')
                <div class="radio-group">
                    @foreach($selectOptions as $optionValue => $optionLabel)
                    <div class="form-check">
                        <input type="radio" name="{{ $key }}" id="{{ $key }}_{{ $optionValue }}" value="{{ $optionValue }}" {{ in_array((string) $optionValue, $selectedValues, true) ? 'checked' : '' }}>
                        <label for="{{ $key }}_{{ $optionValue }}">{{ $optionLabel }}</label>
                    </div>
                    @endforeach
                </div>

                @elseif($isOptionField && $type === 'checkbox')
                <div class="checkbox-group">
                    @foreach($selectOptions as $optionValue => $optionLabel)
                    <div class="form-check">
                        <input type="checkbox" name="{{ $key }}[]" id="{{ $key }}_{{ $optionValue }}" value="{{ $optionValue }}" {{ in_array((string) $optionValue, $selectedValues, true) ? 'checked' : '' }}>
                        <label for="{{ $key }}_{{ $optionValue }}">{{ $optionLabel }}</label>
                    </div>
                    @endforeach
                </div>

                @elseif($isOptionField && $isMultiple)
                <select name="{{ $key }}[]" id="{{ $key }}" class="form-select @error($key) is-invalid @enderror" multiple {!! $attributeHtml !!}>
                    @foreach($selectOptions as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" {{ in_array((string) $optionValue, $selectedValues, true) ? 'selected' : '' }}>{{ $optionLabel }}</option>
                    @endforeach
                </select>

                @elseif($isOptionField)
                <select name="{{ $key }}" id="{{ $key }}" class="form-select @error($key) is-invalid @enderror" {!! $attributeHtml !!}>
                    <option value="">Select {{ $label }}</option>
                    @foreach($selectOptions as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" {{ (string) $optionValue === (string) old($key, is_array($fieldValue) ? '' : $fieldValue) ? 'selected' : '' }}>{{ $optionLabel }}</option>
                    @endforeach
                </select>

                @elseif($type === 'boolean')
                <div class="form-check">
                    <input type="checkbox" name="{{ $key }}" id="{{ $key }}" value="1" {{ old($key, $fieldValue) ? 'checked' : '' }} {!! $attributeHtml !!}>
                    <label for="{{ $key }}">{{ $label }}</label>
                </div>

                @elseif($type === 'password')
                <input type="password" name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror" placeholder="{{ $isEdit ? 'Leave blank to keep current' : ($field['placeholder'] ?? '') }}" autocomplete="new-password" {{ ($field['readonly'] ?? false) ? 'readonly' : '' }} {!! $attributeHtml !!}>

                @else
                <input type="{{ $type }}" name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror" value="{{ is_array($fieldValue) ? '' : $fieldValue }}" placeholder="{{ $field['placeholder'] ?? '' }}" {{ ($field['readonly'] ?? false) ? 'readonly' : '' }} {!! $attributeHtml !!}>
                @endif

                @if(isset($field['help_text']))
                <div class="form-help-text" style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">{{ $field['help_text'] }}</div>
                @endif

                @error($key)
                @if(config('tyro-dashboard.resource_ui.show_field_errors', true))
                <div class="form-error" style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                @endif
                @enderror
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    @if($submit)
    <div class="form-actions" style="margin-top: 1.5rem;">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    </div>
    @endif
</form>
