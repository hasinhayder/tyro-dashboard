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
        @if(!($isReadonly ?? false))
        <a href="{{ route('tyro-dashboard.resources.create', $resource) }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New
        </a>
        @endif
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.resources.index', $resource) }}" method="GET">
            <div class="filters-bar" style="display: flex; gap: 10px; align-items: center; justify-content: space-between;">
                <div class="search-box" style="display: flex; gap: 10px; align-items: center;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="form-input" placeholder="Search..." value="{{ request('search') }}">
                    <!-- <button type="submit" class="btn btn-secondary">Filter</button> -->
                    @if(request()->has('search'))
                    <a href="{{ route('tyro-dashboard.resources.index', $resource) }}" class="btn btn-ghost">Clear Search</a>
                    @endif
                </div>
                <div class="dropdown" style="position: relative; display: flex; gap: 10px;">
                    <button type="button" class="btn btn-ghost text-danger" id="clearColumnFiltersBtn" style="display: none;">
                        Clear Filters
                    </button>
                    <button type="button" class="btn btn-secondary" id="filterColumnsBtn" style="display: flex; align-items: center; gap: 5px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                        Filter Columns
                    </button>
                    <div class="dropdown-menu" id="filterColumnsDropdown" style="display: none; position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ddd; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 4px; min-width: 200px; padding: 0.5rem; z-index: 1000; margin-top: 4px;">
                        @foreach($config['fields'] as $key => $field)
                        @if(!($field['hide_in_index'] ?? false))
                        <label style="display: flex; align-items: center; padding: 4px 8px; cursor: pointer; color: #333; user-select: none;">
                            <input type="checkbox" class="column-toggle" data-target="{{ $key }}" checked style="margin-right: 8px;">
                            {{ $field['label'] }}
                        </label>
                        @endif
                        @endforeach
                    </div>
                    
                </div>




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
                    <th data-col="{{ $key }}">{{ $field['label'] }}</th>
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
                    <td data-col="{{ $key }}">
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
                        @elseif(($field['type'] === 'select' || $field['type'] === 'radio') && isset($field['options']))
                        {{ $field['options'][$item->$key] ?? $item->$key }}
                        @elseif(isset($field['relationship']))
                        {{ optional($item->{$field['relationship']})->{$field['option_label'] ?? 'name'} ?? '-' }}
                        @elseif($field['type'] === 'boolean')
                        <span class="badge {{ $item->$key ? 'badge-success' : 'badge-secondary' }}">
                            {{ $item->$key ? 'Yes' : 'No' }}
                        </span>
                        @elseif($field['type'] === 'richtext')
                        {{ Str::limit(strip_tags($item->$key), 50) }}
                        @else
                        {{ Str::limit($item->$key, 50) }}
                        @endif
                    </td>
                    @endif
                    @endforeach
                    <td style="text-align: right;">
                        <div class="table-actions" style="justify-content: flex-end;">
                            <a href="{{ route('tyro-dashboard.resources.show', [$resource, $item->id]) }}" class="btn btn-icon btn-ghost" title="View">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @if(!($isReadonly ?? false))
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
                            @endif
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
        @if(!($isReadonly ?? false))
        <a href="{{ route('tyro-dashboard.resources.create', $resource) }}" class="btn btn-primary">Create {{ Str::singular($config['title']) }}</a>
        @endif
    </div>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resourceName = '{{ $resource }}';
        const storageKey = 'tyro_hidden_cols_' + resourceName;
        const dropdownBtn = document.getElementById('filterColumnsBtn');
        const dropdownMenu = document.getElementById('filterColumnsDropdown');
        const clearFiltersBtn = document.getElementById('clearColumnFiltersBtn');
        const checkboxes = document.querySelectorAll('.column-toggle');

        // Toggle dropdown
        dropdownBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isVisible = dropdownMenu.style.display === 'block';
            dropdownMenu.style.display = isVisible ? 'none' : 'block';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!dropdownMenu.contains(e.target) && e.target !== dropdownBtn) {
                dropdownMenu.style.display = 'none';
            }
        });

        // Prevent closing when clicking inside dropdown
        dropdownMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        // Load saved state
        const loadState = () => {
            const savedState = localStorage.getItem(storageKey);
            if (savedState) {
                const hiddenCols = JSON.parse(savedState);
                if (hiddenCols.length > 0) {
                    checkboxes.forEach(cb => {
                        const colKey = cb.dataset.target;
                        if (hiddenCols.includes(colKey)) {
                            cb.checked = false;
                            toggleColumn(colKey, false);
                        } else {
                            cb.checked = true;
                            toggleColumn(colKey, true);
                        }
                    });
                    updateClearButtonVisibility();
                }
            }
        };

        // Save state
        const saveState = () => {
            const hiddenCols = [];
            checkboxes.forEach(cb => {
                if (!cb.checked) {
                    hiddenCols.push(cb.dataset.target);
                }
            });
            localStorage.setItem(storageKey, JSON.stringify(hiddenCols));
            updateClearButtonVisibility();
        };

        // Update Clear Button Visibility
        const updateClearButtonVisibility = () => {
            const hasHiddenCols = Array.from(checkboxes).some(cb => !cb.checked);
            clearFiltersBtn.style.display = hasHiddenCols ? 'inline-block' : 'none';
        };

        // Toggle column visibility
        const toggleColumn = (colKey, show) => {
            const cells = document.querySelectorAll(`[data-col="${colKey}"]`);
            cells.forEach(cell => {
                cell.style.display = show ? '' : 'none';
            });
        };

        // Clear Filters
        clearFiltersBtn.addEventListener('click', function () {
            checkboxes.forEach(cb => {
                cb.checked = true;
                toggleColumn(cb.dataset.target, true);
            });
            localStorage.removeItem(storageKey);
            updateClearButtonVisibility();
        });

        // Handle checkbox changes
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                toggleColumn(this.dataset.target, this.checked);
                saveState();
            });
        });

        // Initial load
        loadState();
    });
</script>
@endsection