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
    'size' => 'default',
    'preview' => false,
    'preview_position' => null,
    'previewPosition' => null,
    'preview_width' => null,
    'previewWidth' => null,
    'preview_height' => null,
    'previewHeight' => null,
    'circle' => false,
    'full_url' => false,
    'fullUrl' => null,
])

@php
    $fieldId = $id ?: 'tyro-dashboard-media-picker-'.str_replace('.', '-', uniqid('', true));
    $fieldValue = $name ? old($name, $value) : $value;
    $outputMode = in_array((string) $output, ['original', 'thumb', 'webp', 'select'], true) ? (string) $output : 'webp';
    $buttonStyle = in_array((string) $button, ['primary', 'secondary', 'ghost', 'outline', 'outline-btn', 'danger', 'success'], true) ? (string) $button : 'secondary';
    $pickerSize = in_array((string) $size, ['default', 'medium', 'small'], true) ? (string) $size : 'default';
    $showPreview = filter_var($preview, FILTER_VALIDATE_BOOL);
    $rawPreviewPosition = $preview_position ?? $previewPosition ?? $attributes->get('preview_position') ?? $attributes->get('preview-position') ?? 'top';
    $rawPreviewWidth = $preview_width ?? $previewWidth ?? $attributes->get('preview_width') ?? $attributes->get('preview-width') ?? '100px';
    $rawPreviewHeight = $preview_height ?? $previewHeight ?? $attributes->get('preview_height') ?? $attributes->get('preview-height');
    $previewPosition = in_array((string) $rawPreviewPosition, ['top', 'bottom', 'left', 'right'], true) ? (string) $rawPreviewPosition : 'top';
    $previewStyles = [];
    $previewHasFixedHeight = filled($rawPreviewHeight);

    if ($rawPreviewWidth) {
        $previewStyles[] = 'width: '.$rawPreviewWidth;
    }

    if ($previewHasFixedHeight) {
        $previewStyles[] = 'height: '.$rawPreviewHeight;
    }

    $rawFullUrl = $fullUrl ?? $full_url ?? false;
    $fullUrl = filter_var($rawFullUrl, FILTER_VALIDATE_BOOL);
    if ($fullUrl) {
        if ($fieldValue && !str_starts_with($fieldValue, 'http://') && !str_starts_with($fieldValue, 'https://')) {
            $fieldValue = \Illuminate\Support\Facades\Storage::url($fieldValue);
        }
    }

    $previewSrc = $fieldValue;
    if ($fieldValue && !str_starts_with($fieldValue, 'http://') && !str_starts_with($fieldValue, 'https://')) {
        $previewSrc = \Illuminate\Support\Facades\Storage::url($fieldValue);
    }
@endphp

<div class="tyro-media-picker-field" data-tyro-media-picker-field style="margin-top:5px; margin-bottom:0.85rem;">
    @if($label)
        <label class="form-label" for="{{ $fieldId }}">{{ $label }}</label>
    @endif

    <div
        class="tyro-media-picker-control size-{{ $pickerSize }} {{ $showPreview ? 'has-preview preview-'.$previewPosition : '' }}"
        style="width: {{ $width }};"
        data-tyro-media-preview-position="{{ $previewPosition }}"
    >
        @if($showPreview)
            <div
                class="tyro-media-picker-preview {{ filled($fieldValue) ? 'has-image' : '' }} {{ $previewHasFixedHeight ? 'has-fixed-height' : '' }} {{ $circle ? 'circle' : '' }}"
                style="{{ implode('; ', $previewStyles) }};{{ $circle ? ' border-radius: 50%; overflow: hidden;' : '' }}"
                data-tyro-media-picker-preview
                data-tyro-media-picker-trigger
                data-input-id="{{ $fieldId }}"
                role="button"
                tabindex="0"
                aria-label="Open media picker"
            >
                <img
                    src="{{ $previewSrc }}"
                    alt=""
                    data-tyro-media-picker-preview-img
                    style="{{ filled($fieldValue) ? '' : 'display:none;' }}"
                    onerror="this.style.display='none';var pe=this.parentElement.querySelector('[data-tyro-media-picker-preview-empty]');if(pe){pe.style.display='';}this.parentElement.classList.remove('has-image');"
                >
                <span class="tyro-media-picker-preview-placeholder {{ $circle ? 'circle' : '' }}" data-tyro-media-picker-preview-empty style="{{ filled($fieldValue) ? 'display:none;' : '' }}" aria-label="No media selected">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m7 15 3-3 2.5 2.5L15 12l2 3" />
                        <circle cx="8.5" cy="9.5" r="1" />
                    </svg>
                </span>
            </div>
        @endif

        <input
            {{ $attributes->merge(['class' => 'form-input tyro-media-picker-input']) }}
            type="{{ $showPreview ? 'hidden' : 'text' }}"
            @if($name) name="{{ $name }}" @endif
            id="{{ $fieldId }}"
            value="{{ $fieldValue }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            data-tyro-media-picker-input
            data-tyro-media-output="{{ $outputMode }}"
            data-tyro-media-full-url="{{ $fullUrl ? 'true' : 'false' }}"
        >
        <div class="tyro-media-picker-actions">
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
            @if($showPreview)
                <button
                    type="button"
                    class="btn btn-destructive tyro-media-picker-delete"
                    data-tyro-media-picker-delete
                    data-input-id="{{ $fieldId }}"
                    style="{{ filled($fieldValue) ? '' : 'display:none;' }}"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2m3 0v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m5 4v6m4-6v6" />
                    </svg>
                    Remove
                </button>
            @endif
        </div>
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
