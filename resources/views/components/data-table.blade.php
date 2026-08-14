@props([
    'collection' => null,
    'columns' => [],
    'striped' => false,
    'hover' => true,
    'variant' => 'default', // default, bordered, compact, minimal
    'responsive' => true,
    'title' => null,
    'description' => null,
    'empty' => 'No records found.',
    'emptyTitle' => null,
    'showHeader' => true,
])

@php
    $isStriped = filter_var($striped, FILTER_VALIDATE_BOOL);
    $isHover = filter_var($hover, FILTER_VALIDATE_BOOL);
    $isResponsive = filter_var($responsive, FILTER_VALIDATE_BOOL);
    $showHead = filter_var($showHeader, FILTER_VALIDATE_BOOL);
    $tableVariant = in_array((string) $variant, ['default', 'bordered', 'compact', 'minimal'], true) ? (string) $variant : 'default';

    $tableClass = 'table';
    if ($tableVariant !== 'default') {
        $tableClass .= ' table-'.$tableVariant;
    }
    if ($isStriped) {
        $tableClass .= ' table-striped';
    }
    if ($isHover) {
        $tableClass .= ' table-hover';
    } else {
        $tableClass .= ' table-no-hover';
    }

    $hasColumns = is_array($columns) && count($columns) > 0;
    $hasCollection = $collection !== null && (is_object($collection) && method_exists($collection, 'count') ? $collection->count() > 0 : count($collection) > 0);

    // Normalise columns: string value uses index as key when associative
    $normalisedColumns = [];
    if ($hasColumns) {
        foreach ($columns as $index => $col) {
            if (is_string($col)) {
                // ['name' => 'Name'] or ['name'] — both produce a string col
                // When index is a string, it's the data key and col is the label
                // When index is numeric, both key and label are the string value
                $key = is_string($index) ? $index : $col;
                $label = $col;
                $normalisedColumns[] = [
                    'label' => $label,
                    'key' => $key,
                    'format' => null,
                    'class' => '',
                    'thClass' => '',
                    'align' => '',
                ];
            } elseif (is_array($col)) {
                $normalisedColumns[] = [
                    'label' => $col['label'] ?? '',
                    'key' => $col['key'] ?? $index,
                    'format' => $col['format'] ?? null,
                    'class' => $col['class'] ?? '',
                    'thClass' => $col['thClass'] ?? '',
                    'align' => $col['align'] ?? '',
                ];
            }
        }
    }

    $titleValue = $title instanceof \Illuminate\View\ComponentSlot ? trim((string) $title) : (string) ($title ?? '');
    $hasDescription = isset($description) && trim((string) $description) !== '';
    $hasActions = isset($actions) && trim((string) $actions) !== '';
    $hasBodySlot = ! empty(trim((string) $slot));
    $hasTitle = $titleValue !== '' || $hasDescription || $hasActions;
@endphp

@if($hasTitle)
<div class="card">
    <div class="card-header">
        <div style="min-width:0;">
            @if($titleValue !== '')
                <h3 class="card-title" style="margin:0;">{{ $titleValue }}</h3>
            @endif
            @if($hasDescription)
                <p class="page-description" style="margin-top:{{ $titleValue !== '' ? '0.25rem' : '0' }};">{{ $description }}</p>
            @endif
        </div>
        @if($hasActions)
            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">{!! $actions !!}</div>
        @endif
    </div>
    <div class="card-body" style="padding:0;">
@endif

@if($hasCollection && $hasColumns && ! $hasBodySlot)
    @if($isResponsive)
    <div class="table-container">
    @endif
        <table class="{{ $tableClass }}">
            @if($showHead)
            <thead>
                <tr>
                    @foreach($normalisedColumns as $col)
                        <th scope="col"{!! $col['thClass'] !== '' ? ' class="'.e($col['thClass']).'"' : '' !!}{!! $col['align'] !== '' ? ' style="text-align:'.e($col['align']).';"' : '' !!}>{{ $col['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            @endif
            <tbody>
                @foreach($collection as $item)
                    <tr>
                        @foreach($normalisedColumns as $col)
                            @php
                                $cellValue = '';
                                if (isset($col['format']) && is_callable($col['format'])) {
                                    $cellValue = $col['format']($item);
                                } elseif (filled($col['key'])) {
                                    $cellValue = data_get($item, $col['key']) ?? '';
                                }
                                $cellClass = trim((string) $col['class']);
                                $cellAttrs = '';
                                if ($cellClass !== '') {
                                    $cellAttrs .= ' class="'.e($cellClass).'"';
                                }
                                if ($col['align'] !== '') {
                                    $cellAttrs .= ' style="text-align:'.e($col['align']).';"';
                                }
                            @endphp
                            <td{!! $cellAttrs !!}>{!! $cellValue !!}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @if($isResponsive)
    </div>
    @endif
@elseif($hasBodySlot)
    @if($isResponsive)
    <div class="table-container">
    @endif
        <table class="{{ $tableClass }}">
            {!! $slot !!}
        </table>
    @if($isResponsive)
    </div>
    @endif
@else
    <div class="empty-state" style="padding:2.5rem 1.5rem;">
        <div class="empty-state-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 5.9A2 2 0 0 0 7.93 5H5a2 2 0 0 0-2 2Z" />
            </svg>
        </div>
        @if(filled($emptyTitle))
            <h3 class="empty-state-title">{{ $emptyTitle }}</h3>
        @endif
        <p class="empty-state-description" style="margin-bottom:0;">{{ $empty }}</p>
    </div>
@endif

@if($hasTitle)
    </div>
</div>
@endif
