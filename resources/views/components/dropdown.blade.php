@props([
    'align' => 'start',
    'id' => null,
    'title' => 'Options',
])

@php
    $alignKey = in_array((string) $align, ['start', 'center', 'end'], true) ? (string) $align : 'start';
    $dropdownId = filled($id) ? $id : 'tyro-dropdown-'.\Illuminate\Support\Str::random(8);
    $hasTrigger = isset($trigger) && trim((string) $trigger) !== '';
    $menuContent = trim((string) $slot);
    $extraClass = trim((string) ($attributes->get('class') ?? ''));
    $extraAttrs = trim((string) $attributes->except(['class', 'style']));
    $wrapStyle = trim((string) ($attributes->get('style') ?? ''));
    $triggerLabel = trim((string) ($title ?? '')) !== '' ? trim((string) $title) : 'Options';

    $wrapperClass = trim('tyro-dropdown'.($extraClass !== '' ? ' '.$extraClass : ''));
    $wrapperAttrs = ' id="'.e($dropdownId).'" data-align="'.e($alignKey).'" data-dropdown';
    if ($extraAttrs !== '') {
        $wrapperAttrs .= ' '.$extraAttrs;
    }
    if ($wrapStyle !== '') {
        $wrapperAttrs .= ' style="'.e($wrapStyle).'"';
    }
@endphp

<div class="{{ $wrapperClass }}"{!! $wrapperAttrs !!}>
    <div class="tyro-dropdown-trigger" data-dropdown-trigger aria-haspopup="menu" aria-expanded="false" tabindex="0">
        @if($hasTrigger)
            {!! $trigger !!}
        @else
            <button type="button" class="btn btn-secondary btn-sm">
                {{ $triggerLabel }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;margin-left:0.25rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
        @endif
    </div>
    <div class="tyro-dropdown-menu" role="menu">
        {!! $slot !!}
    </div>
</div>

@once
@push('scripts')
<script>
(function () {
    if (window.__tyroDropdownInit) return;
    window.__tyroDropdownInit = true;

    function close(dropdown) {
        if (!dropdown) return;
        dropdown.classList.remove('is-open');
        var t = dropdown.querySelector('[data-dropdown-trigger]');
        if (t) t.setAttribute('aria-expanded', 'false');
    }

    document.addEventListener('click', function (e) {
        var trigger = e.target.closest('[data-dropdown-trigger]');
        if (trigger) {
            var dropdown = trigger.closest('[data-dropdown]');
            if (!dropdown) return;
            var opening = !dropdown.classList.contains('is-open');
            document.querySelectorAll('[data-dropdown].is-open').forEach(function (d) {
                if (d !== dropdown) close(d);
            });
            dropdown.classList.toggle('is-open', opening);
            trigger.setAttribute('aria-expanded', opening ? 'true' : 'false');
            return;
        }
        if (!e.target.closest('[data-dropdown]')) {
            document.querySelectorAll('[data-dropdown].is-open').forEach(close);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[data-dropdown].is-open').forEach(close);
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.tyro-dropdown-menu .dropdown-item')) {
            var dropdown = e.target.closest('[data-dropdown]');
            close(dropdown);
        }
    }, true);
})();
</script>
@endpush
@endonce
