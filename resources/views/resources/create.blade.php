@extends('tyro-dashboard::layouts.admin')

@section('title', 'Create ' . Str::singular($config['title']))

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.resources.index', $resource) }}">{{ $config['title'] }}</a>
<span class="breadcrumb-separator">/</span>
<span>Create</span>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Create {{ Str::singular($config['title']) }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.resources.store', $resource) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @foreach($config['fields'] as $key => $field)
                @if(($field['hide_in_form'] ?? false))
                    @continue
                @endif
                
                @if($field['type'] === 'hidden')
                    <input type="hidden" name="{{ $key }}" value="{{ old($key) }}">
                    @continue
                @endif
                
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="{{ $key }}" class="form-label">{{ $field['label'] }}</label>
                    
                    @if($field['type'] === 'textarea')
                        <textarea name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror" rows="5">{{ old($key) }}</textarea>
                    
                    @elseif($field['type'] === 'select')
                        <select name="{{ $key }}" id="{{ $key }}" class="form-select @error($key) is-invalid @enderror">
                            <option value="">Select {{ $field['label'] }}</option>
                            @if(isset($options[$key]))
                                @foreach($options[$key] as $option)
                                    <option value="{{ $option->id }}" {{ old($key) == $option->id ? 'selected' : '' }}>
                                        {{ $option->{$field['option_label'] ?? 'name'} }}
                                    </option>
                                @endforeach
                            @elseif(isset($field['options']))
                                @foreach($field['options'] as $value => $label)
                                    <option value="{{ $value }}" {{ old($key) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        
                    @elseif($field['type'] === 'boolean')
                        <div class="form-check">
                            <input type="checkbox" name="{{ $key }}" id="{{ $key }}" value="1" {{ old($key) ? 'checked' : '' }}>
                            <label for="{{ $key }}">Yes</label>
                        </div>

                    @else
                        <input type="{{ $field['type'] }}" name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror" value="{{ old($key) }}">
                    @endif

                    @error($key)
                        <div class="form-error" style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <div class="form-actions" style="margin-top: 1.5rem;">
                <a href="{{ route('tyro-dashboard.resources.index', $resource) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create {{ Str::singular($config['title']) }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
