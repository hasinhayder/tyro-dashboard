@extends('tyro-dashboard::layouts.app')

@section('title', 'Media Categories')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route($dashboardRoute::name('media')) }}">Media Library</a>
<span class="breadcrumb-separator">/</span>
<span>Categories</span>
@endsection

@push('styles')
@include('tyro-dashboard::partials.media-styles')
<style>
    .cat-table-wrap {
        overflow-x: auto;
        border-radius: 0.75rem;
        border: 1px solid var(--border);
        background: var(--card);
    }
    .cat-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    .cat-table th {
        padding: 0.85rem 1.15rem;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted-foreground);
        background: var(--muted);
        border-bottom: 1px solid var(--border);
        text-align: left;
    }
    .cat-table td {
        padding: 1rem 1.15rem;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .cat-table tr:last-child td {
        border-bottom: none;
    }
    .cat-table tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }
    .cat-name-link {
        font-weight: 600;
        color: var(--foreground);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: color 0.15s;
    }
    .cat-name-link:hover {
        color: var(--primary);
    }
    .cat-slug {
        font-size: 0.75rem;
        color: var(--muted-foreground);
        font-family: monospace;
    }
    .cat-desc {
        font-size: 0.8125rem;
        color: var(--muted-foreground);
        margin-top: 0.25rem;
        max-width: 480px;
    }
    .cat-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--muted);
        color: var(--foreground);
        text-decoration: none;
        border: 1px solid var(--border);
        transition: all 0.15s;
    }
    .cat-count-badge:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(var(--primary-rgb, 59, 130, 246), 0.1);
    }
    .cat-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .cat-toast {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        padding: 0.85rem 1.25rem;
        background: var(--card);
        border: 1px solid var(--primary);
        border-radius: 0.75rem;
        color: var(--foreground);
        font-size: 0.875rem;
        font-weight: 500;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        z-index: 2000;
        display: none;
        align-items: center;
        gap: 0.5rem;
    }
    .cat-toast.is-active {
        display: flex;
        animation: catToastFadeIn 0.2s ease-out;
    }
    @keyframes catToastFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Media Categories</h1>
            <p class="page-description">Organize and group your media library into reusable collections.</p>
        </div>
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            <a href="{{ route($dashboardRoute::name('media')) }}" class="btn btn-secondary" style="white-space:nowrap;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;display:inline;vertical-align:-2px;margin-right:4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Media Library
            </a>
            <button type="button" class="btn btn-primary" onclick="openCreateCategoryModal()" style="white-space:nowrap;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;display:inline;vertical-align:-2px;margin-right:4px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Category
            </button>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <div class="stats-bar" style="display:flex;gap:2rem;flex-wrap:wrap;">
            <div class="stat-item">Total Categories: <strong>{{ $totalCategories }}</strong></div>
            <div class="stat-item">Categorized Media: <strong>{{ $categorizedMediaCount }}</strong></div>
        </div>
    </div>
</div>

<!-- Search toolbar -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <form action="{{ route($dashboardRoute::name('media.categories.index')) }}" method="GET">
            <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
                <div class="search-box" style="flex:1;min-width:240px;position:relative;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;left:0.85rem;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--muted-foreground);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="form-input" style="padding-left:2.5rem;" placeholder="Search categories by name, slug or description…" value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-secondary">Search</button>
                @if(request()->filled('search'))
                    <a href="{{ route($dashboardRoute::name('media.categories.index')) }}" class="btn btn-ghost">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Categories Table -->
@if($categories->count() > 0)
    <div class="cat-table-wrap">
        <table class="cat-table">
            <thead>
                <tr>
                    <th scope="col">Category</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Media</th>
                    @if($isAdmin)
                    <th scope="col">Creator</th>
                    @endif
                    <th scope="col">Created</th>
                    <th scope="col" style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr id="category-row-{{ $category->id }}">
                    <td>
                        <a href="{{ route($dashboardRoute::name('media'), ['category' => $category->id]) }}" class="cat-name-link" title="View media in this category">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;color:var(--primary);flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                            </svg>
                            <span data-cat-name>{{ $category->name }}</span>
                        </a>
                        @if($category->description)
                            <div class="cat-desc" data-cat-desc>{{ $category->description }}</div>
                        @else
                            <div class="cat-desc" data-cat-desc style="display:none;"></div>
                        @endif
                    </td>
                    <td>
                        <span class="cat-slug" data-cat-slug>{{ $category->slug }}</span>
                    </td>
                    <td>
                        <a href="{{ route($dashboardRoute::name('media'), ['category' => $category->id]) }}" class="cat-count-badge" id="cat-badge-{{ $category->id }}" title="View files in this category">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                            <span id="cat-count-{{ $category->id }}">{{ $category->media_count }}</span> {{ \Illuminate\Support\Str::plural('file', $category->media_count) }}
                        </a>
                    </td>
                    @if($isAdmin)
                    <td>
                        <span style="font-size:0.8125rem;color:var(--muted-foreground);">
                            {{ $category->creator?->name ?? 'System' }}
                        </span>
                    </td>
                    @endif
                    <td>
                        <span style="font-size:0.8125rem;color:var(--muted-foreground);">
                            {{ $category->created_at?->diffForHumans() }}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <div class="cat-actions" style="justify-content:flex-end;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openAddMediaPicker({{ $category->id }}, '{{ addslashes($category->name) }}')" title="Add media to this category">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:13px;height:13px;margin-right:3px;vertical-align:-1px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Add Media
                            </button>
                            <a href="{{ route($dashboardRoute::name('media'), ['category' => $category->id]) }}" class="btn btn-secondary btn-sm" title="View media in this category">
                                View
                            </a>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="openEditCategoryModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->slug) }}', '{{ addslashes($category->description ?? '') }}')" title="Edit category">
                                Edit
                            </button>
                            <button type="button" class="btn btn-ghost btn-sm" style="color:var(--destructive);" onclick="confirmDeleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')" title="Delete category">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $categories->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body" style="text-align:center;padding:3.5rem 1.5rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:48px;height:48px;margin:0 auto 1rem;opacity:0.4;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
            </svg>
            <h3 style="font-size:1.125rem;font-weight:600;margin-bottom:0.35rem;">No categories found</h3>
            <p style="color:var(--muted-foreground);font-size:0.875rem;max-width:400px;margin:0 auto 1.5rem;">
                @if(request()->filled('search'))
                    No categories matched your search "{{ request('search') }}".
                @else
                    Categories let you organize, filter, and batch manage media files easily.
                @endif
            </p>
            @if(request()->filled('search'))
                <a href="{{ route($dashboardRoute::name('media.categories.index')) }}" class="btn btn-secondary">Clear Search</a>
            @else
                <button type="button" class="btn btn-primary" onclick="openCreateCategoryModal()">Create First Category</button>
            @endif
        </div>
    </div>
@endif

<!-- Create Category Modal -->
<div class="modal-overlay" id="createCategoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">New Media Category</h3>
            <button type="button" class="modal-close" onclick="closeModal('createCategoryModal')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route($dashboardRoute::name('media.categories.store')) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="createCatName">Name <span style="color:var(--destructive)">*</span></label>
                    <input type="text" id="createCatName" name="name" class="form-input" required placeholder="e.g. Logos, Hero Banners, Blog Posts" maxlength="150" autocomplete="off">
                </div>
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="createCatSlug">Slug <span style="font-size:0.75rem;color:var(--muted-foreground);">(optional)</span></label>
                    <input type="text" id="createCatSlug" name="slug" class="form-input" placeholder="e.g. hero-banners" maxlength="180" autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="form-label" for="createCatDesc">Description <span style="font-size:0.75rem;color:var(--muted-foreground);">(optional)</span></label>
                    <textarea id="createCatDesc" name="description" class="form-input" rows="3" placeholder="Brief note about the purpose of this category..." maxlength="1000"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createCategoryModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal-overlay" id="editCategoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Category</h3>
            <button type="button" class="modal-close" onclick="closeModal('editCategoryModal')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="editCatName">Name <span style="color:var(--destructive)">*</span></label>
                    <input type="text" id="editCatName" name="name" class="form-input" required maxlength="150" autocomplete="off">
                </div>
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="editCatSlug">Slug <span style="font-size:0.75rem;color:var(--muted-foreground);">(optional)</span></label>
                    <input type="text" id="editCatSlug" name="slug" class="form-input" maxlength="180" autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="form-label" for="editCatDesc">Description <span style="font-size:0.75rem;color:var(--muted-foreground);">(optional)</span></label>
                    <textarea id="editCatDesc" name="description" class="form-input" rows="3" maxlength="1000"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editCategoryModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteCategoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title" style="color:var(--destructive);">Delete Category</h3>
            <button type="button" class="modal-close" onclick="closeModal('deleteCategoryModal')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="deleteCategoryForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <p style="margin-bottom:0.75rem;">Are you sure you want to delete category <strong id="deleteCategoryName"></strong>?</p>
                <div style="font-size:0.8125rem;color:var(--muted-foreground);padding:0.75rem;background:var(--muted);border-radius:0.5rem;">
                    <strong>Note:</strong> Only the category grouping will be removed. None of the media files inside this category will be deleted from your library.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('deleteCategoryModal')">Cancel</button>
                <button type="submit" class="btn btn-destructive">Delete Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast notification -->
<div class="cat-toast" id="catToast">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;color:var(--primary);">
        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
    </svg>
    <span id="catToastText"></span>
</div>

@include('tyro-dashboard::partials.media-script')

<script>
    const categoryUpdateUrlBase = "{{ rtrim(route($dashboardRoute::name('media.categories.index')), '/') }}/";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function showToast(message) {
        const toast = document.getElementById('catToast');
        const text = document.getElementById('catToastText');
        if (!toast || !text) return;
        text.textContent = message;
        toast.classList.add('is-active');
        setTimeout(() => toast.classList.remove('is-active'), 3200);
    }

    function openCreateCategoryModal() {
        openModal('createCategoryModal');
        setTimeout(() => document.getElementById('createCatName')?.focus(), 50);
    }

    function openEditCategoryModal(id, name, slug, description) {
        const form = document.getElementById('editCategoryForm');
        if (!form) return;

        form.action = `${categoryUpdateUrlBase}${id}`;
        document.getElementById('editCatName').value = name;
        document.getElementById('editCatSlug').value = slug;
        document.getElementById('editCatDesc').value = description;

        openModal('editCategoryModal');
        setTimeout(() => document.getElementById('editCatName')?.focus(), 50);
    }

    function confirmDeleteCategory(id, name) {
        const form = document.getElementById('deleteCategoryForm');
        const nameEl = document.getElementById('deleteCategoryName');
        if (!form) return;

        form.action = `${categoryUpdateUrlBase}${id}`;
        if (nameEl) nameEl.textContent = name;
        openModal('deleteCategoryModal');
    }

    function openAddMediaPicker(categoryId, categoryName) {
        if (!window.TyroDashboardMediaPicker) {
            alert('Media picker is not available.');
            return;
        }

        window.TyroDashboardMediaPicker.openMultiSelect({
            title: `Add Media to "${categoryName}"`,
            subtitle: 'Select multiple images from your library and click "Add Selected Media".',
            confirmLabel: 'Add to Category',
            onConfirm: async (selectedIds, picker) => {
                if (!selectedIds.length) return;

                const addMediaUrl = `${categoryUpdateUrlBase}${categoryId}/add-media`;
                try {
                    const response = await fetch(addMediaUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ media_ids: selectedIds })
                    });

                    const data = await response.json();
                    if (data.success) {
                        picker.close();
                        showToast(data.message || 'Media added to category successfully.');

                        // Update count badge dynamically
                        const countEl = document.getElementById(`cat-count-${categoryId}`);
                        const badgeEl = document.getElementById(`cat-badge-${categoryId}`);
                        if (countEl && data.total_media_count !== undefined) {
                            countEl.textContent = data.total_media_count;
                        }
                    } else {
                        alert(data.message || 'Could not add media to category.');
                    }
                } catch (error) {
                    alert('An error occurred while adding media.');
                }
            }
        });
    }
</script>
@endsection
