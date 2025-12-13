@extends('tyro-dashboard::layouts.admin')

@section('title', $config['title'])

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>{{ $config['title'] }}</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">{{ $config['title'] }}</h1>
        </div>
        <a href="{{ route('tyro-dashboard.resources.create', $resource) }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.resources.index', $resource) }}" method="GET">
            <div class="filters-bar">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="form-input" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if(request()->has('search'))
                    <a href="{{ route('tyro-dashboard.resources.index', $resource) }}" class="btn btn-ghost">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    @if($items->count())
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    @foreach($config['fields'] as $key => $field)
                        @if(!($field['hide_in_index'] ?? false))
                            <th>{{ $field['label'] }}</th>
                        @endif
                    @endforeach
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    @foreach($config['fields'] as $key => $field)
                        @if(!($field['hide_in_index'] ?? false))
                            <td>
                                @if($field['type'] === 'file')
                                    @if($item->$key)
                                        <a href="{{ Storage::url($item->$key) }}" target="_blank" style="color: var(--primary); text-decoration: none;">View</a>
                                    @else
                                        -
                                    @endif
                                @elseif($field['type'] === 'multiselect' || ($field['type'] === 'checkbox' && isset($field['relationship'])))
                                    @if(isset($field['relationship']))
                                        {{ $item->{$field['relationship']}->pluck($field['option_label'] ?? 'name')->implode(', ') }}
                                    @else
                                        {{ is_array($item->$key) ? implode(', ', $item->$key) : $item->$key }}
                                    @endif
                                @elseif(isset($field['relationship']))
                                    {{ optional($item->{$field['relationship']})->{$field['option_label'] ?? 'name'} ?? '-' }}
                                @elseif($field['type'] === 'boolean')
                                    <span class="badge {{ $item->$key ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $item->$key ? 'Yes' : 'No' }}
                                    </span>
                                @else
                                    {{ Str::limit($item->$key, 50) }}
                                @endif
                            </td>
                        @endif
                    @endforeach
                    <td style="text-align: right;">
                        <div class="table-actions" style="justify-content: flex-end;">
                            <a href="{{ route('tyro-dashboard.resources.edit', [$resource, $item->id]) }}" class="btn btn-icon btn-ghost" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('tyro-dashboard.resources.destroy', [$resource, $item->id]) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-ghost text-danger" title="Delete">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="pagination">
        {{ $items->links() }}
    </div>
    @endif
    @else
    <div class="empty-state">
        <div class="empty-state-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </div>
        <h3>No {{ strtolower($config['title']) }} found</h3>
        <p>Get started by creating a new {{ Str::singular(strtolower($config['title'])) }}.</p>
        <a href="{{ route('tyro-dashboard.resources.create', $resource) }}" class="btn btn-primary">Create {{ Str::singular($config['title']) }}</a>
    </div>
    @endif
</div>
@endsection
