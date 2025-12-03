@extends('tyro-dashboard::layouts.admin')

@section('title', 'Create Privilege')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.privileges.index') }}">Privileges</a>
<span class="breadcrumb-separator">/</span>
<span>Create</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Create Privilege</h1>
            <p class="page-description">Add a new privilege that can be assigned to roles.</p>
        </div>
        <a href="{{ route('tyro-dashboard.privileges.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Privileges
        </a>
    </div>
</div>

<div class="card">
    <form action="{{ route('tyro-dashboard.privileges.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name" class="form-label">Privilege Name</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g., Create Posts">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">
                        Slug <span class="form-label-optional">(auto-generated if empty)</span>
                    </label>
                    <input type="text" id="slug" name="slug" class="form-input @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="e.g., create-posts">
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
                <textarea id="description" name="description" class="form-textarea @error('description') is-invalid @enderror" rows="3" placeholder="Describe what this privilege allows users to do...">{{ old('description') }}</textarea>
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
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="checkbox-input" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
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
            <button type="submit" class="btn btn-primary">Create Privilege</button>
            <a href="{{ route('tyro-dashboard.privileges.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
