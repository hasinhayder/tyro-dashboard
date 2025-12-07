@extends('tyro-dashboard::layouts.admin')

@section('title', 'Edit Privilege')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.privileges.index') }}">Privileges</a>
<span class="breadcrumb-separator">/</span>
<span>Edit</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Edit Privilege</h1>
            <p class="page-description">Update privilege information and role assignments.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <form action="{{ route('tyro-dashboard.privileges.destroy', $privilege->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this privilege? This action cannot be undone.');" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Privilege
                </button>
            </form>
            <a href="{{ route('tyro-dashboard.privileges.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Privileges
            </a>
        </div>
    </div>
</div>

<div class="card">
    <form action="{{ route('tyro-dashboard.privileges.update', $privilege->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name" class="form-label">Privilege Name</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $privilege->name) }}" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" id="slug" name="slug" class="form-input @error('slug') is-invalid @enderror" value="{{ old('slug', $privilege->slug) }}">
                    <span class="form-hint">Used for programmatic access. Must be unique.</span>
                    @error('slug')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">
                    Description <span class="form-label-optional">(optional)</span>
                </label>
                <textarea id="description" name="description" class="form-textarea @error('description') is-invalid @enderror" rows="3">{{ old('description', $privilege->description) }}</textarea>
                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Assign to Roles</label>
                @if($roles->count())
                <div class="checkbox-list">
                    @foreach($roles as $role)
                    <label class="checkbox-item">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="checkbox-input" {{ in_array($role->id, old('roles', $privilege->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <div class="checkbox-item-content">
                            <div class="checkbox-item-title">{{ $role->name }}</div>
                            <div class="checkbox-item-description">{{ $role->slug }}</div>
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
                        <p class="alert-message">No roles available. <a href="{{ route('tyro-dashboard.roles.create') }}">Create one</a> first.</p>
                    </div>
                </div>
                @endif
                @error('roles')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer" style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('tyro-dashboard.privileges.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
