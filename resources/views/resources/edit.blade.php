@extends('tyro-dashboard::layouts.app')

@section('title', 'Edit ' . Str::singular($config['title']))

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.resources.index', $resource) }}">{{ $config['title'] }}</a>
<span class="breadcrumb-separator">/</span>
<span>Edit</span>
@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach($config['fields'] as $key => $field)
        @if (($field['type'] ?? '') === 'richtext')
            (function () {
                var key = '{{ $key }}';
                if (document.getElementById('editor-' + key)) {
                    var quill = new Quill('#editor-' + key, {
                        theme: 'snow'
                    });
                    var textarea = document.getElementById(key);

                    // Set initial content
                    if (textarea.value) {
                        quill.root.innerHTML = textarea.value;
                    }

                    quill.on('text-change', function () {
                        textarea.value = quill.root.innerHTML;
                    });
                }
            })();
        @endif
        @endforeach

        // Markdown editors
        @foreach($config['fields'] as $key => $field)
        @if (($field['type'] ?? '') === 'markdown')
            (function () {
                var key = '{{ $key }}';
                var textarea = document.getElementById(key);

                if (textarea) {
                    new EasyMDE({
                        element: textarea,
                        spellChecker: false,
                        status: false,
                        toolbar: [
                            "bold",
                            "italic",
                            "heading",
                            "|",
                            "quote",
                            "unordered-list",
                            "ordered-list",
                            "|",
                            "link",
                            "image",
                            "|",
                            "preview",
                        ]
                    });
                }
            })();
        @endif
        @endforeach
    });
</script>
@endpush

@section('content')
<div class="page-header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('tyro-dashboard.resources.index', $resource) }}" class="btn btn-ghost" title="Back to {{ $config['title'] }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="page-title">Edit {{ Str::singular($config['title']) }}</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.resources.update', [$resource, $item->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @foreach($config['fields'] as $key => $field)
            @if(($field['hide_in_form'] ?? false) || ($field['hide_in_edit'] ?? false))
            @continue
            @endif

            @if($field['type'] === 'hidden')
            <input type="hidden" name="{{ $key }}" value="{{ old($key, $item->$key) }}">
            @continue
            @endif

            @if($field['type'] === 'password')
            {{-- For password, don't show value, and maybe handle updating differently --}}
            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="{{ $key }}" class="form-label">{{ $field['label'] }} <small>(Leave blank to keep current)</small></label>
                <input type="password" name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror">
                @error($key)
                @if(config('tyro-dashboard.resource_ui.show_field_errors', true))
                <div class="form-error" style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                @endif
                @enderror
            </div>
            @continue
            @endif

            <div class="form-group" style="margin-bottom: 1rem;">
                <label for="{{ $key }}" class="form-label">{{ $field['label'] }}</label>

                @if($field['type'] === 'textarea')
                <textarea name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror" rows="5" placeholder="{{ $field['placeholder'] ?? '' }}" {{ ($field['readonly'] ?? false) ? 'readonly' : '' }} @if(isset($field['attributes'])) @foreach($field['attributes'] as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach @endif>{{ old($key, $item->$key) }}</textarea>

                @elseif($field['type'] === 'richtext')
                <div class="richtext-wrapper">
                    <div id="editor-{{ $key }}" style="height: 200px; background: #fff;"></div>
                    <textarea name="{{ $key }}" id="{{ $key }}" style="display:none">{{ old($key, $item->$key) }}</textarea>
                </div>

                @elseif($field['type'] === 'markdown')
                <textarea name="{{ $key }}" id="{{ $key }}" class="@error($key) is-invalid @enderror" placeholder="{{ $field['placeholder'] ?? '' }}">{{ old($key, $item->$key) }}</textarea>

                @elseif($field['type'] === 'select')
                @if($field['multiple'] ?? false)
                <select name="{{ $key }}[]" id="{{ $key }}" class="form-select @error($key) is-invalid @enderror" multiple>
                    @if(isset($options[$key]))
                    @foreach($options[$key] as $option)
                    <option value="{{ $option->id }}" {{ in_array($option->id, old($key, $selectedValues[$key] ?? [])) ? 'selected' : '' }}>
                        {{ $option->{$field['option_label'] ?? 'name'} }}
                    </option>
                    @endforeach
                    @elseif(isset($field['options']))
                    @foreach($field['options'] as $value => $label)
                    @php
                    $optionValue = is_int($value) ? $label : $value;
                    $optionLabel = $label;
                    @endphp
                    <option value="{{ $optionValue }}" {{ in_array($optionValue, old($key, $selectedValues[$key] ?? [])) ? 'selected' : '' }}>
                        {{ $optionLabel }}
                    </option>
                    @endforeach
                    @endif
                </select>
                @else
                <select name="{{ $key }}" id="{{ $key }}" class="form-select @error($key) is-invalid @enderror">
                    <option value="">Select {{ $field['label'] }}</option>
                    @if(isset($options[$key]))
                    @foreach($options[$key] as $option)
                    <option value="{{ $option->id }}" {{ old($key, $item->$key) == $option->id ? 'selected' : '' }}>
                        {{ $option->{$field['option_label'] ?? 'name'} }}
                    </option>
                    @endforeach
                    @elseif(isset($field['options']))
                    @foreach($field['options'] as $value => $label)
                    @php
                    $optionValue = is_int($value) ? $label : $value;
                    $optionLabel = $label;
                    @endphp
                    <option value="{{ $optionValue }}" {{ old($key, $item->$key) == $optionValue ? 'selected' : '' }}>
                        {{ $optionLabel }}
                    </option>
                    @endforeach
                    @endif
                </select>
                @endif

                @elseif($field['type'] === 'multiselect')
                <select name="{{ $key }}[]" id="{{ $key }}" class="form-select @error($key) is-invalid @enderror" multiple>
                    @if(isset($options[$key]))
                    @foreach($options[$key] as $option)
                    <option value="{{ $option->id }}" {{ in_array($option->id, old($key, $selectedValues[$key] ?? ($item->$key ?? []))) ? 'selected' : '' }}>
                        {{ $option->{$field['option_label'] ?? 'name'} }}
                    </option>
                    @endforeach
                    @elseif(isset($field['options']))
                    @foreach($field['options'] as $value => $label)
                    @php
                    $optionValue = is_int($value) ? $label : $value;
                    $optionLabel = $label;
                    @endphp
                    <option value="{{ $optionValue }}" {{ in_array($optionValue, old($key, $item->$key ?? [])) ? 'selected' : '' }}>
                        {{ $optionLabel }}
                    </option>
                    @endforeach
                    @endif
                </select>

                @elseif($field['type'] === 'radio')
                <div class="radio-group">
                    @if(isset($options[$key]))
                    @foreach($options[$key] as $option)
                    <div class="form-check">
                        <input type="radio" name="{{ $key }}" id="{{ $key }}_{{ $option->id }}" value="{{ $option->id }}" {{ old($key, $item->$key) == $option->id ? 'checked' : '' }}>
                        <label for="{{ $key }}_{{ $option->id }}">{{ $option->{$field['option_label'] ?? 'name'} }}</label>
                    </div>
                    @endforeach
                    @elseif(isset($field['options']))
                    @foreach($field['options'] as $value => $label)
                    @php
                    $optionValue = is_int($value) ? $label : $value;
                    $optionLabel = $label;
                    @endphp
                    <div class="form-check">
                        <input type="radio" name="{{ $key }}" id="{{ $key }}_{{ $optionValue }}" value="{{ $optionValue }}" {{ old($key, $item->$key) == $optionValue ? 'checked' : '' }}>
                        <label for="{{ $key }}_{{ $optionValue }}">{{ $optionLabel }}</label>
                    </div>
                    @endforeach
                    @endif
                </div>

                @elseif($field['type'] === 'checkbox' && (isset($options[$key]) || isset($field['options'])))
                <div class="checkbox-group">
                    @if(isset($options[$key]))
                    @foreach($options[$key] as $option)
                    <div class="form-check">
                        <input type="checkbox" name="{{ $key }}[]" id="{{ $key }}_{{ $option->id }}" value="{{ $option->id }}" {{ in_array($option->id, old($key, $selectedValues[$key] ?? ($item->$key ?? []))) ? 'checked' : '' }}>
                        <label for="{{ $key }}_{{ $option->id }}">{{ $option->{$field['option_label'] ?? 'name'} }}</label>
                    </div>
                    @endforeach
                    @elseif(isset($field['options']))
                    @foreach($field['options'] as $value => $label)
                    @php
                    $optionValue = is_int($value) ? $label : $value;
                    $optionLabel = $label;
                    @endphp
                    <div class="form-check">
                        <input type="checkbox" name="{{ $key }}[]" id="{{ $key }}_{{ $optionValue }}" value="{{ $optionValue }}" {{ in_array($optionValue, old($key, $item->$key ?? [])) ? 'checked' : '' }}>
                        <label for="{{ $key }}_{{ $optionValue }}">{{ $optionLabel }}</label>
                    </div>
                    @endforeach
                    @endif
                </div>

                @elseif($field['type'] === 'file')
                @php
                    $displayImage = $field['display_image'] ?? false;
                    $displayImagePosition = $field['display_image_position'] ?? 'top';
                    $isImage = !empty($item->$key) && $displayImage && preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $item->$key);
                @endphp
                
                @if($isImage && $displayImagePosition === 'top')
                <div style="margin-bottom: 0.5rem;">
                    <img src="{{ Storage::url($item->$key) }}" alt="Current image" style="width: 200px; height: auto; border: 1px solid var(--border); border-radius: 4px;">
                </div>
                @endif
                
                <input type="file" name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror" {{ ($field['readonly'] ?? false) ? 'readonly' : '' }} @if(isset($field['attributes'])) @foreach($field['attributes'] as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach @endif>
                @if(!empty($item->$key))
                <div style="margin-top: 0.5rem;">
                    <small>Current file: <a href="{{ Storage::url($item->$key) }}" target="_blank">{{ basename($item->$key) }}</a></small>
                </div>
                @endif
                
                @if($isImage && $displayImagePosition === 'bottom')
                <div style="margin-top: 0.5rem;">
                    <img src="{{ Storage::url($item->$key) }}" alt="Current image" style="width: 200px; height: auto; border: 1px solid var(--border); border-radius: 4px;">
                </div>
                @endif

                @elseif($field['type'] === 'boolean')
                <div class="form-check">
                    <input type="checkbox" name="{{ $key }}" id="{{ $key }}" value="1" {{ old($key, $item->$key) ? 'checked' : '' }}>
                    <label for="{{ $key }}">Yes</label>
                </div>

                @else
                <input type="{{ $field['type'] }}" name="{{ $key }}" id="{{ $key }}" class="form-input @error($key) is-invalid @enderror" value="{{ old($key, $item->$key) }}" placeholder="{{ $field['placeholder'] ?? '' }}" {{ ($field['readonly'] ?? false) ? 'readonly' : '' }} @if(isset($field['attributes'])) @foreach($field['attributes'] as $attr => $value) {{ $attr }}="{{ $value }}" @endforeach @endif>
                @endif

                @if(isset($field['help_text']))
                <div class="form-help-text" style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">{{ $field['help_text'] }}</div>
                @endif

                @error($key)
                @if(config('tyro-dashboard.resource_ui.show_field_errors', true))
                <div class="form-error" style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                @endif
                @enderror
            </div>
            @endforeach

            <div class="form-actions" style="margin-top: 1.5rem;">
                <a href="{{ route('tyro-dashboard.resources.index', $resource) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update {{ Str::singular($config['title']) }}</button>
            </div>
        </form>
    </div>
</div>
@endsection