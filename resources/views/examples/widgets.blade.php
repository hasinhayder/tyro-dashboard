@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@push('styles')
<style>
.btn{
    padding: 0.68rem 1rem;
}
</style>
@endpush

@section('title', 'Widgets')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Examples</span>
<span class="breadcrumb-separator">/</span>
<span>Widgets</span>
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Widgets</h1>
            <p class="page-description" style="font-size: 1rem;">Copy-ready interactive dashboard widgets where the UI and business logic live mostly in JavaScript.</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-secondary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Back to Dashboard
            </a>
            <a href="{{ route('tyro-dashboard.components') }}" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 6a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2V6z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2v-3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 15a2 2 0 012-2h3a2 2 0 012 2v3a2 2 0 01-2 2h-3a2 2 0 01-2-2v-3z" />
                </svg>
                Components
            </a>
        </div>
    </div>
</div>

@include('tyro-dashboard::examples.partials.widgets.alert-note')

<div class="grid-2" style="margin-bottom: 1.5rem;">
    @include('tyro-dashboard::examples.partials.widgets.roi-calculator')
    @include('tyro-dashboard::examples.partials.widgets.emi-calculator')
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    @include('tyro-dashboard::examples.partials.widgets.qr-generator')
    @include('tyro-dashboard::examples.partials.widgets.password-generator')
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    @include('tyro-dashboard::examples.partials.widgets.bmi-calculator')
    @include('tyro-dashboard::examples.partials.widgets.flight-tracker')

</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    @include('tyro-dashboard::examples.partials.widgets.daily-comics')
    @include('tyro-dashboard::examples.partials.widgets.weather-check')
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    @include('tyro-dashboard::examples.partials.widgets.nearest-restaurants')
    @include('tyro-dashboard::examples.partials.widgets.weather-forecast')
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    @include('tyro-dashboard::examples.partials.widgets.stock-viewer')
    @include('tyro-dashboard::examples.partials.widgets.image-finder')
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    @include('tyro-dashboard::examples.partials.widgets.unit-converter')
    @include('tyro-dashboard::examples.partials.widgets.currency-converter')
</div>

@include('tyro-dashboard::examples.partials.widgets.invoice-builder')

@push('scripts')
@include('tyro-dashboard::examples.partials.widgets.scripts')
@endpush

@endsection
