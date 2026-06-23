@props([
    'user' => null,
    'size' => 'md',
    'alt' => null,
])

@php
    $sizeStyle = '';
    $sizeKey = strtolower((string) $size);
    $presetSizes = ['sm' => 32, 'md' => 36, 'lg' => 48];
    if (isset($presetSizes[$sizeKey])) {
        if ($sizeKey !== 'md') {
            $px = $presetSizes[$sizeKey];
            $sizeStyle = 'width:'.$px.'px; height:'.$px.'px; font-size:'.(int) round($px * 0.375).'px;';
        }
    } else {
        $raw = (string) $size;
        if (preg_match('/^(\d+(?:\.\d+)?)/', $raw, $m)) {
            $num = (float) $m[1];
            $sizeStyle = 'width:'.$num.'px; height:'.$num.'px; font-size:'.(int) round($num * 0.375).'px;';
        } elseif (trim($raw) !== '') {
            $sizeStyle = 'width:'.$raw.'; height:'.$raw.';';
        }
    }

    $userModel = config('tyro-dashboard.user_model', 'App\\Models\\User');
    if (is_object($user)) {
        $resolvedUser = $user;
    } else {
        $resolvedUser = null;
        if (filled($user)) {
            $resolvedUser = $userModel::find($user);
        }
    }

    if ($resolvedUser) {
        $photoEnabled = (bool) config('tyro-dashboard.features.profile_photo_upload', false);
        $gravatarEnabled = (bool) config('tyro-dashboard.features.gravatar', false);
        $hasPhoto = $photoEnabled && filled($resolvedUser->profile_photo_path ?? null);
        $useGravatar = $gravatarEnabled && filled($resolvedUser->use_gravatar ?? null) && filled($resolvedUser->email ?? null);
        $hasImage = $hasPhoto || $useGravatar;
        $altText = filled($alt) ? $alt : ($resolvedUser->name ?? null);
        $initial = strtoupper(mb_substr((string) ($resolvedUser->name ?? ''), 0, 1));
        if ($initial === '') {
            $initial = '?';
        }
    } else {
        $hasImage = false;
        $altText = filled($alt) ? $alt : null;
        $initial = '?';
    }

    $innerContent = trim((string) $slot);
    $userStyle = (string) ($attributes->get('style') ?? '');
    $finalStyle = trim($sizeStyle.($hasImage ? ' background:none; padding:0;' : '').($userStyle !== '' ? ' '.$userStyle : ''));
@endphp

<div {{ $attributes->except('style')->merge(['class' => 'user-avatar']) }}@if($finalStyle !== '') style="{{ $finalStyle }}"@endif>
    @if($innerContent !== '')
        {!! $slot !!}
    @elseif($hasImage)
        <img src="{{ $resolvedUser->profile_photo_url }}" alt="{{ $altText }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
    @else
        {{ $initial }}
    @endif
</div>
