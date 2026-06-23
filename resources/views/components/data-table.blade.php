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
                        <th{!! $col['thClass'] !== '' ? ' class="'.e($col['thClass']).'"' : '' !!}{!! $col['align'] !== '' ? ' style="text-align:'.e($col['align']).';"' : '' !!}>{{ $col['label'] }}</th>
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
                                $alignStyle = $col['align'] !== '' ? ' text-align:'.e($col['align']).';' : '';
                            @endphp
                            <td{!! $col['class'] !== '' || $col['align'] !== '' ? ' class="'.e($col['class']).'"' : '' !!}{!! $alignStyle !== '' ? ' style="'.$alignStyle.'"' : '' !!}>{!! $cellValue !!}</td>
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
    <div class="empty-state" style="padding:2rem;">
        <p class="empty-state-description">{{ $empty }}</p>
    </div>
@endif

@if($hasTitle)
    </div>
</div>
@endif
