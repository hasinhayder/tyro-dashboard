@extends('tyro-dashboard::layouts.app')

@section('title', $privilege->name)

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.privileges.index') }}">Privileges</a>
<span class="breadcrumb-separator">/</span>
<span>{{ $privilege->name }}</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">{{ $privilege->name }}</h1>
            <p class="page-description">
                <code style="padding: 0.25rem 0.5rem; background-color: var(--bg-secondary); border-radius: 0.25rem; font-size: 0.8125rem;">{{ $privilege->slug }}</code>
            </p>
        </div>
        <div class="btn-group">
            <a href="{{ route('tyro-dashboard.privileges.edit', $privilege->id) }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Privilege
            </a>
            <a href="{{ route('tyro-dashboard.privileges.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Privileges
            </a>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Privilege Details -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Details</h3>
        </div>
        <div class="card-body">
            <div style="margin-bottom: 1rem;">
                <label class="form-label" style="margin-bottom: 0.25rem;">Name</label>
                <p style="font-size: 0.875rem; color: var(--text-primary);">{{ $privilege->name }}</p>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label" style="margin-bottom: 0.25rem;">Slug</label>
                <p>
                    <code style="padding: 0.25rem 0.5rem; background-color: var(--bg-secondary); border-radius: 0.25rem; font-size: 0.8125rem;">{{ $privilege->slug }}</code>
                </p>
            </div>
            <div>
                <label class="form-label" style="margin-bottom: 0.25rem;">Description</label>
                <p style="font-size: 0.875rem; color: var(--text-secondary);">{{ $privilege->description ?: 'No description provided.' }}</p>
            </div>
        </div>
    </div>

    <!-- Roles with this Privilege -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Roles with this Privilege ({{ $privilege->roles->count() }})</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($privilege->roles->count())
            <div class="table-container">
                <table class="table">
                    <tbody>
                        @foreach($privilege->roles as $role)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 0.5rem; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center;">
                                        <svg style="width: 14px; height: 14px; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <span style="font-weight: 500;">{{ $role->name }}</span>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('tyro-dashboard.roles.show', $role->id) }}" class="btn btn-sm btn-ghost">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <p class="empty-state-description">No roles have this privilege.</p>
                <a href="{{ route('tyro-dashboard.privileges.edit', $privilege->id) }}" class="btn btn-sm btn-secondary">Assign to Roles</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
