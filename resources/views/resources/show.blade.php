@extends('tyro-dashboard::layouts.admin')

@section('title', $config['title'] . ' Details')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.resources.index', $resource) }}">{{ $config['title'] }}</a>
<span class="breadcrumb-separator">/</span>
<span>Details</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <h1 class="page-title">{{ Str::singular($config['title']) }} Details</h1>
        <div>
            @if(!($isReadonly ?? false))
            <a href="{{ route('tyro-dashboard.resources.edit', [$resource, $item->id]) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('tyro-dashboard.resources.destroy', [$resource, $item->id]) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="details-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach($config['fields'] as $key => $field)
                <div class="detail-item">
                    <div class="detail-label" style="font-weight: 500; color: var(--text-secondary); margin-bottom: 0.25rem;">{{ $field['label'] }}</div>
                    <div class="detail-value" style="font-size: 1rem; color: var(--text-primary);">
                        @if($field['type'] === 'file')
                            @if($item->$key)
                                <a href="{{ Storage::url($item->$key) }}" target="_blank" style="color: var(--primary); text-decoration: none;">View File</a>
                            @else
                                -
                            @endif
                        @elseif($field['type'] === 'multiselect' || ($field['type'] === 'checkbox' && isset($field['relationship'])))
                             @if(isset($field['relationship']))
                                 {{ $item->{$field['relationship']}->pluck($field['option_label'] ?? 'name')->implode(', ') }}
                             @else
                                 {{ is_array($item->$key) ? implode(', ', $item->$key) : $item->$key }}
                             @endif
                        @elseif(($field['type'] === 'select' || $field['type'] === 'radio') && isset($field['options']))
                            {{ $field['options'][$item->$key] ?? $item->$key }}
                        @elseif(isset($field['relationship']))
                            {{ optional($item->{$field['relationship']})->{$field['option_label'] ?? 'name'} ?? '-' }}
                        @elseif($field['type'] === 'boolean')
                            <span class="badge {{ $item->$key ? 'badge-success' : 'badge-secondary' }}">
                                {{ $item->$key ? 'Yes' : 'No' }}
                            </span>
                        @elseif($field['type'] === 'textarea')
                            <div style="white-space: pre-wrap;">{{ $item->$key }}</div>
                        @elseif($field['type'] === 'richtext')
                            <div class="richtext-content">{!! $item->$key !!}</div>
                        @else
                            {{ $item->$key }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
