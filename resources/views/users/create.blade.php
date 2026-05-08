@extends('tyro-dashboard::layouts.admin')

@section('title', 'Create User')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route($dashboardRoute::name('users.index')) }}">Users</a>
<span class="breadcrumb-separator">/</span>
<span>Create</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Create User</h1>
            <p class="page-description">Add a new user to the system.</p>
        </div>
        <a href="{{ route($dashboardRoute::name('users.index')) }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Users
        </a>
    </div>
</div>

<div class="card">
    <form action="{{ route($dashboardRoute::name('users.store')) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="John Doe">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="john@example.com">
                    @error('email')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror" required placeholder="••••••••">
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required placeholder="••••••••">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" style="margin-bottom: 0.75rem; display: block;">Assign Roles</label>
                <div class="role-grid">
                    @foreach($roles as $role)
                    <label class="role-card">
                        <div class="role-card-header">
                            <div class="role-title">{{ $role->name }}</div>
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="role-checkbox" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                        </div>
                        <div class="role-desc">{{ $role->slug }}</div>
                    </label>
                    @endforeach
                </div>
                @error('roles')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer" style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route($dashboardRoute::name('users.index')) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('styles')
<style>
    .role-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.75rem;
    }
    .role-card {
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        background-color: var(--card);
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
    }
    .role-card:hover {
        border-color: var(--primary);
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05);
    }
    .role-card:has(.role-checkbox:checked) {
        border-color: color-mix(in srgb, var(--primary), transparent 50%);
        background-color: color-mix(in srgb, var(--primary), transparent 96%);
        box-shadow: inset 0 0 0 1px color-mix(in srgb, var(--primary), transparent 50%);
    }
    .role-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.25rem;
    }
    .role-title {
        font-weight: 600;
        font-size: 0.8125rem;
        color: var(--foreground);
    }
    .role-checkbox {
        width: 1rem;
        height: 1rem;
        border-radius: 4px;
        border: 1px solid var(--border);
        accent-color: var(--primary);
        cursor: pointer;
    }
    .role-desc {
        font-size: 0.75rem;
        color: var(--muted-foreground);
    }
</style>
@endpush
@endsection
