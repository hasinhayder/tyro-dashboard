@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'label' => null,
    'placeholder' => null,
    'help' => null,
    'showError' => true,
    'height' => '300px',
    'toolbar' => 'bold,italic,heading,|,quote,unordered-list,ordered-list,|,link,image,|,preview',
    'spellChecker' => false,
    'status' => false,
    'output' => 'select',
    'fullUrl' => true,
    'columns' => null,
    'options' => [],
])

@php
    $fieldId = $id ?: ($name ? 'tyro-dashboard-editor-'.str_replace(['.', '[', ']'], '-', $name) : 'tyro-dashboard-editor-'.uniqid('', true));
    $fieldValue = $name ? old($name, $value) : $value;

    // Picker output mode: select|webp|original|thumb (invalid -> select).
    $outputMode = in_array((string) $output, ['select', 'webp', 'original', 'thumb'], true) ? (string) $output : 'select';

    // fullUrl defaults to TRUE here (opposite of <x-media-picker>): Markdown and
    // email rendering need absolute URLs, so the picker is asked for the full URL.
    $useFullUrl = filter_var($fullUrl, FILTER_VALIDATE_BOOL);

    // Normalize the toolbar prop (comma string or array) to a JSON-ready array.
    if (is_string($toolbar)) {
        $toolbarItems = array_values(array_filter(array_map('trim', explode(',', $toolbar)), fn ($item) => $item !== ''));
    } else {
        $toolbarItems = is_array($toolbar) ? array_values($toolbar) : [];
    }

    $jsonFlags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    $pickerColumns = (is_numeric($columns) && (int) $columns > 0) ? (int) $columns : null;
    $spellCheckerValue = filter_var($spellChecker, FILTER_VALIDATE_BOOL);
    $renderError = filter_var($showError, FILTER_VALIDATE_BOOL);
@endphp

<div class="tyro-editor-field" data-tyro-editor-field>
    @if($label)
        <label class="form-label" for="{{ $fieldId }}">{{ $label }}</label>
    @endif

    <textarea
        {{ $attributes->merge(['class' => 'form-input']) }}
        @if($name) name="{{ $name }}" @endif
        id="{{ $fieldId }}"
        placeholder="{{ $placeholder }}"
        data-tyro-editor
        data-height="{{ $height }}"
        data-output="{{ $outputMode }}"
        data-full-url="{{ $useFullUrl ? 'true' : 'false' }}"
        data-toolbar="{{ json_encode($toolbarItems, $jsonFlags) }}"
        data-options="{{ json_encode($options, $jsonFlags) }}"
        data-spellchecker="{{ $spellCheckerValue ? 'true' : 'false' }}"
        data-status="{{ json_encode($status, $jsonFlags) }}"
        @if($pickerColumns) data-columns="{{ $pickerColumns }}" @endif
    >{{ $fieldValue }}</textarea>

    @if($help)
        <div class="form-help-text" style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">{{ $help }}</div>
    @endif

    @if($name && $renderError)
        @error($name)
        <div class="form-error" style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
        @enderror
    @endif
</div>

@once('tyro-media-assets')
    @push('styles')
        @include('tyro-dashboard::partials.media-styles')
    @endpush

    @push('scripts')
        @include('tyro-dashboard::partials.media-script')
    @endpush
@endonce

@once('tyro-editor-assets')
    @push('styles')
        <link rel="stylesheet" href="{{ config('tyro-dashboard.editor.css_url', 'https://cdn.jsdelivr.net/npm/easymde@2.20.0/dist/easymde.min.css') }}">
        @include('tyro-dashboard::partials.editor-styles')
    @endpush

    @push('scripts')
        <script src="{{ config('tyro-dashboard.editor.js_url', 'https://cdn.jsdelivr.net/npm/easymde@2.20.0/dist/easymde.min.js') }}"></script>
        @include('tyro-dashboard::partials.editor-script')
    @endpush
@endonce
