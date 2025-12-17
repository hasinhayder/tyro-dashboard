@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@section('title', 'Dashboard Components')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Components</span>
<span class="breadcrumb-separator">/</span>
<span>Dashboard Components</span>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
@endpush



@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Dashboard Components</h1>
            <p class="page-description" style="font-size: 1rem;">Copy-ready building blocks: cards, charts, progress, tables, tabs, dropdowns, forms, and rich text.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-secondary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Back to Dashboard
            </a>
            <a href="{{ route('tyro-dashboard.profile') }}" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
            </a>
        </div>
    </div>
</div>

@include('tyro-dashboard::examples.partials.components.alerts')
@include('tyro-dashboard::examples.partials.components.kpi-stats')
@include('tyro-dashboard::examples.partials.components.charts-primary')
@include('tyro-dashboard::examples.partials.components.charts-secondary')
@include('tyro-dashboard::examples.partials.components.progress-and-info')
@include('tyro-dashboard::examples.partials.components.tabs-section')
@include('tyro-dashboard::examples.partials.components.form-and-richtext')
@include('tyro-dashboard::examples.partials.components.recent-activity-table')

@push('scripts')
@include('tyro-dashboard::examples.partials.components.scripts')
@endpush
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.scrollTo(0, 0);
    });
</script>
@endpush
