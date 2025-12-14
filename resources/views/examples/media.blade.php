@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@section('title', 'Example Media')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Examples</span>
<span class="breadcrumb-separator">/</span>
<span>Media</span>
@endsection

@section('content')
@php
    $items = [
        ['title' => 'Hero Banner', 'type' => 'Image', 'badge' => 'badge-primary', 'size' => '1.2 MB', 'updated' => '5m ago'],
        ['title' => 'Product Demo', 'type' => 'Video', 'badge' => 'badge-secondary', 'size' => '48 MB', 'updated' => '1h ago'],
        ['title' => 'Brand Kit', 'type' => 'Zip', 'badge' => 'badge-warning', 'size' => '6.8 MB', 'updated' => 'Yesterday'],
        ['title' => 'Screenshots', 'type' => 'Folder', 'badge' => 'badge-success', 'size' => '—', 'updated' => '2d ago'],
        ['title' => 'Press Logo', 'type' => 'SVG', 'badge' => 'badge-primary', 'size' => '24 KB', 'updated' => 'Last week'],
        ['title' => 'Export', 'type' => 'CSV', 'badge' => 'badge-secondary', 'size' => '320 KB', 'updated' => 'Last week'],
    ];
@endphp

<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Example Media</h1>
            <p class="page-description" style="font-size: 1rem;">A media library style page with simple cards + table.</p>
        </div>
        <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="#" class="btn btn-primary btn-sm" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/></svg>
                Upload
            </a>
            <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">New folder</a>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Quick Stats</h3>
            <span class="badge badge-secondary">Cards</span>
        </div>
        <div class="card-body">
            <div style="display:flex; flex-direction: column; gap: 0.75rem;">
                <div style="display:flex; justify-content: space-between;">
                    <span style="color: var(--muted-foreground);">Total items</span>
                    <strong>128</strong>
                </div>
                <div style="display:flex; justify-content: space-between;">
                    <span style="color: var(--muted-foreground);">Storage used</span>
                    <strong>3.2 GB</strong>
                </div>
                <div style="display:flex; justify-content: space-between;">
                    <span style="color: var(--muted-foreground);">Uploads today</span>
                    <strong>14</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Gallery</h3>
            <span class="badge badge-secondary">Grid</span>
        </div>
        <div class="card-body">
            <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
                @foreach(range(1, 6) as $i)
                    <div style="border: 1px solid var(--border); border-radius: 10px; background: var(--muted); overflow:hidden;">
                        <div style="height: 88px; background: var(--muted); display:flex; align-items:center; justify-content:center; border-bottom: 1px solid var(--border);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px; color: var(--muted-foreground);"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4-4a3 3 0 014 0l4 4"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 20h20"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v12H4z"/></svg>
                        </div>
                        <div style="padding: 0.75rem;">
                            <div style="font-weight: 600; font-size: 0.9375rem;">Asset {{ $i }}</div>
                            <div style="font-size: 0.8125rem; color: var(--muted-foreground);">Placeholder item</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Files</h3>
        <span class="badge badge-secondary">Table</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th style="text-align:right;">Size</th>
                        <th style="text-align:right;">Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $it)
                        <tr>
                            <td style="font-weight: 600;">{{ $it['title'] }}</td>
                            <td><span class="badge {{ $it['badge'] }}">{{ $it['type'] }}</span></td>
                            <td style="text-align:right;">{{ $it['size'] }}</td>
                            <td style="text-align:right; color: var(--muted-foreground);">{{ $it['updated'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
