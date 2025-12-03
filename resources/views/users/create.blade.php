@extends('tyro-dashboard::layouts.admin')

@section('title', 'Create User')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.users.index') }}">Users</a>
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
        <a href="{{ route('tyro-dashboard.users.index') }}" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Users
        </a>
    </div>
</div>

<div class="card">
    <form action="{{ route('tyro-dashboard.users.store') }}" method="POST">
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
                <label class="form-label">Assign Roles</label>
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
                @error('roles')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer" style="display: flex; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('tyro-dashboard.users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
