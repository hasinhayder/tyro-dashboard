@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'output' => 'webp',
    'buttonText' => 'Select media',
    'placeholder' => 'Select or paste a media URL',
    'label' => null,
    'width' => '550px',
    'button' => 'secondary',
])

@php
    $fieldId = $id ?: 'tyro-dashboard-media-picker-'.str_replace('.', '-', uniqid('', true));
    $fieldValue = $name ? old($name, $value) : $value;
    $outputMode = in_array((string) $output, ['original', 'thumb', 'webp', 'select'], true) ? (string) $output : 'webp';
    $buttonStyle = in_array((string) $button, ['primary', 'secondary', 'ghost', 'outline', 'outline-btn', 'danger', 'success'], true) ? (string) $button : 'secondary';
@endphp

<div class="tyro-media-picker-field" data-tyro-media-picker-field style="margin-top:5px;">
    @if($label)
        <label class="form-label" for="{{ $fieldId }}">{{ $label }}</label>
    @endif

    <div class="tyro-media-picker-control" style="width: {{ $width }};">
        <input
            {{ $attributes->merge(['class' => 'form-input tyro-media-picker-input']) }}
            type="text"
            @if($name) name="{{ $name }}" @endif
            id="{{ $fieldId }}"
            value="{{ $fieldValue }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            data-tyro-media-picker-input
            data-tyro-media-output="{{ $outputMode }}"
        >
        <button
            type="button"
            class="btn btn-{{ $buttonStyle }} tyro-media-picker-button"
            data-tyro-media-picker-trigger
            data-input-id="{{ $fieldId }}"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 0 1 2.828 0L16 16m-2-2 1.586-1.586a2 2 0 0 1 2.828 0L20 14m-6-6h.01M6 20h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
            </svg>
            {{ $buttonText }}
        </button>
    </div>
</div>

@once
    @push('styles')
        @include('tyro-dashboard::partials.media-styles')
    @endpush

    @push('scripts')
        @include('tyro-dashboard::partials.media-script')
    @endpush
@endonce
