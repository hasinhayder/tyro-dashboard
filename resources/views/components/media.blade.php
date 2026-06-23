@props([
    'id' => null,
    'media' => null,
    'variant' => 'webp',
    'width' => null,
    'height' => null,
    'rounded' => 'none',
    'circle' => false,
    'alt' => null,
    'loading' => 'lazy',
    'showTitle' => false,
])

@php
    if ($media instanceof \HasinHayder\TyroDashboard\Models\Media) {
        $mediaRecord = $media;
    } else {
        $mediaRecord = filled($id) ? \HasinHayder\TyroDashboard\Models\Media::find($id) : null;
    }

    $imgUrl = null;
    if ($mediaRecord && $mediaRecord->is_image) {
        $variantKey = in_array((string) $variant, ['original','webp','thumb','thumbnail'], true) ? (string) $variant : 'webp';
        $path = match ($variantKey) {
            'webp' => $mediaRecord->webp_path ?? $mediaRecord->path,
            'thumb', 'thumbnail' => $mediaRecord->thumbnail_path ?? $mediaRecord->path,
            default => $mediaRecord->path,
        };
        $disk = $mediaRecord->disk ?? config('tyro-dashboard.uploads.disk', 'public');
        $imgUrl = \Illuminate\Support\Facades\Storage::disk($disk)->url($path);
    }

    $isCircle = filter_var($circle, FILTER_VALIDATE_BOOL);
    $rawRounded = strtolower(trim((string) $rounded));
    $roundedMap = ['none' => '0', 'sm' => '4px', 'md' => '8px', 'lg' => '16px', 'full' => '9999px'];
    if (array_key_exists($rawRounded, $roundedMap)) {
        $radius = $roundedMap[$rawRounded];
    } else {
        $radius = (string) $rounded;
    }
    if ($isCircle && $rawRounded === 'none') {
        $radius = '9999px';
    }

    $styles = [];
    if (filled($width)) {
        $styles[] = 'width:'.(ctype_digit((string) $width) ? $width.'px' : $width);
    }
    if (filled($height)) {
        $styles[] = 'height:'.(ctype_digit((string) $height) ? $height.'px' : $height);
    }
    $styles[] = 'border-radius:'.$radius;
    $computedStyle = implode('; ', $styles);

    $userStyle = (string) ($attributes->get('style') ?? '');
    $finalStyle = trim($computedStyle.($userStyle !== '' ? ' '.$userStyle : ''));
    $altText = filled($alt) ? $alt : (filled($mediaRecord?->alt_text) ? $mediaRecord->alt_text : ($mediaRecord?->filename ?? ''));
    $loadingValue = in_array((string) $loading, ['lazy','eager','auto'], true) ? (string) $loading : 'lazy';
    $isShowTitle = filter_var($showTitle, FILTER_VALIDATE_BOOL);
    $titleText = $isShowTitle ? trim((string) $altText) : '';
@endphp

@if($imgUrl)
<div @if($isShowTitle) class="tyro-media-figure" style="display:inline-flex;flex-direction:column;gap:0.4rem;max-width:100%;" @endif>
    <img
        src="{{ $imgUrl }}"
        alt="{{ $altText }}"
        loading="{{ $loadingValue }}"
        {{ $attributes->except('style') }}
        @if($finalStyle !== '') style="{{ $finalStyle }}" @endif
    >
    @if($titleText !== '')
        <div class="tyro-media-title" style="font-size:0.8125rem;font-weight:600;color:var(--foreground);text-align:center;line-height:1.3;overflow:hidden;text-overflow:ellipsis;">{{ $titleText }}</div>
    @endif
</div>
@endif
