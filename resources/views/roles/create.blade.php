@extends('tyro-dashboard::layouts.admin')

@section('title', 'Create Role')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.roles.index') }}">Roles</a>
<span class="breadcrumb-separator">/</span>
<span>Create</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Create Role</h1>
            <p class="page-description">Add a new role with associated privileges.</p>
        </div>
        <a href="{{ route('tyro-dashboard.roles.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Roles
        </a>
    </div>
</div>

<div class="card">
    <form action="{{ route('tyro-dashboard.roles.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g., Editor">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">
                        Slug <span class="form-label-optional">(auto-generated if empty)</span>
                    </label>
                    <input type="text" id="slug" name="slug" class="form-input @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="e.g., editor">
                    <span class="form-hint">Used for programmatic access. Must be unique.</span>
                    @error('slug')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Assign Privileges</label>
                @if($privileges->count())
                <div class="checkbox-list">
                    @foreach($privileges as $privilege)
                    <label class="checkbox-item">
                        <input type="checkbox" name="privileges[]" value="{{ $privilege->id }}" class="checkbox-input" {{ in_array($privilege->id, old('privileges', [])) ? 'checked' : '' }}>
                        <div class="checkbox-item-content">
                            <div class="checkbox-item-title">{{ $privilege->name }}</div>
                            <div class="checkbox-item-description">{{ $privilege->slug }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="alert-content">
                        <p class="alert-message">No privileges available. <a href="{{ route('tyro-dashboard.privileges.create') }}">Create one</a> first.</p>
                    </div>
                </div>
                @endif
                @error('privileges')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer" style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">Create Role</button>
            <a href="{{ route('tyro-dashboard.roles.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
