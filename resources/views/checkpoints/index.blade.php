@extends('tyro-dashboard::layouts.admin')

@section('title', 'Checkpoints')

@section('breadcrumb')
<a href="{{ route($dashboardRoute::name('index')) }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Checkpoints</span>
@endsection

@push('styles')
<style>
    .cp-table .cp-name {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    .cp-table .cp-name-main {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 500;
        color: var(--foreground);
    }
    .cp-table .cp-name-sub {
        font-size: 0.75rem;
        color: var(--muted-foreground);
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }
    .cp-table .cp-driver {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.8rem;
        color: var(--foreground);
    }
    .cp-table .cp-note {
        font-size: 0.875rem;
        color: var(--foreground);
    }
    .cp-table .cp-note-empty {
        color: var(--muted-foreground);
        font-style: italic;
    }
    .cp-table .cp-actions {
        display: flex;
        justify-content: flex-end;
    }
    .cp-actions .action-btn.action-btn-danger { color: var(--destructive, #ef4444) !important; }
    .cp-actions .action-btn.action-btn-primary { color: #16a34a !important; }
    .cp-actions .action-btn.action-btn-primary:hover { background-color: rgba(22, 163, 74, 0.12) !important; }
    .cp-actions .action-btn.action-btn-success { color: var(--success, #22c55e) !important; cursor: default; }
    .cp-actions .action-btn.action-btn-danger svg,
    .cp-actions .action-btn.action-btn-primary svg,
    .cp-actions .action-btn.action-btn-success svg { stroke: currentColor !important; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Checkpoints</h1>
            <p class="page-description">Snapshot your database and restore it instantly. Powered by Tyro Checkpoint.</p>
        </div>
        <div id="cpFlushWrap" style="display: none;">
            <button type="button" class="btn btn-destructive" id="cpFlushBtn" data-cp-action="flush">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2m1 0v14a2 2 0 01-2 2H9a2 2 0 01-2-2V6h10z"/>
                </svg>
                Flush Unlocked
            </button>
        </div>
    </div>
</div>

@if(! $available)
    <div class="alert alert-warning">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div class="alert-content">
            <div class="alert-title">Tyro Checkpoint is not installed.</div>
            <div class="alert-message">Install it with <code>composer require hasinhayder/tyro-checkpoint --dev</code> then <code>php artisan tyro-checkpoint:install</code> to start managing database checkpoints here.</div>
        </div>
    </div>
@else
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Checkpoints</div>
                <div class="stat-value" id="cpStatCount">{{ count($checkpoints) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Disk Used</div>
                <div class="stat-value" id="cpStatSize">{{ $totalSizeForHumans }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Encryption Key</div>
                <div class="stat-value" id="cpStatEncryption">
                    @if($encryptionKeySet)
                        <span class="badge badge-success">Configured</span>
                    @else
                        <span class="badge badge-secondary">Not set</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 1rem;">
        <div class="card-header">
            <h3 class="card-title">Create Checkpoint</h3>
        </div>
        <div class="card-body">
            <form id="cpCreateForm">
                @csrf
                <div class="filters-bar" style="align-items: end;">
                    <div class="form-group" style="margin: 0; flex: 1; min-width: 200px;">
                        <label class="form-label" for="cpName">Name</label>
                        <input type="text" id="cpName" name="name" class="form-input" maxlength="100" placeholder="optional, e.g. before-seed">
                    </div>
                    <div class="form-group" style="margin: 0; flex: 2; min-width: 260px;">
                        <label class="form-label" for="cpNote">Note</label>
                        <input type="text" id="cpNote" name="note" class="form-input" maxlength="500" placeholder="optional description">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label class="form-label" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 400; cursor: pointer;">
                            <input type="checkbox" id="cpEncrypt" name="encrypt" value="1" class="checkbox-input"> Encrypt
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary" id="cpCreateBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="cpListContainer">
        @include('tyro-dashboard::checkpoints._list', ['checkpoints' => $checkpoints])
    </div>
@endif
@endsection


@push('scripts')
@if($available)
<script>
(function () {
    var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var routes = {
        create: '{{ route($dashboardRoute::name("checkpoints.create")) }}',
        restore: '{{ route($dashboardRoute::name("checkpoints.restore")) }}',
        delete: '{{ route($dashboardRoute::name("checkpoints.delete")) }}',
        flush: '{{ route($dashboardRoute::name("checkpoints.flush")) }}',
        note: '{{ route($dashboardRoute::name("checkpoints.note")) }}',
        toggleLock: '{{ route($dashboardRoute::name("checkpoints.toggle-lock")) }}',
        toggleFlag: '{{ route($dashboardRoute::name("checkpoints.toggle-flag")) }}',
        encrypt: '{{ route($dashboardRoute::name("checkpoints.encrypt")) }}'
    };

    function formatBytes(b) {
        if (b < 1024) return b + ' B';
        if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
        if (b < 1073741824) return (b / 1048576).toFixed(1) + ' MB';
        return (b / 1073741824).toFixed(2) + ' GB';
    }

    function post(url, body) {
        var fd = new FormData();
        fd.append('_token', csrf);
        Object.keys(body || {}).forEach(function (k) { fd.append(k, body[k]); });
        return fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: fd
        });
    }

    function applyResponse(resp) {
        if (resp.html !== undefined) {
            var container = document.getElementById('cpListContainer');
            if (container) container.innerHTML = resp.html;
        }
        if (resp.count !== undefined) {
            var c = document.getElementById('cpStatCount');
            if (c) c.textContent = resp.count;
        }
        if (resp.totalSize !== undefined) {
            var s = document.getElementById('cpStatSize');
            if (s) s.textContent = formatBytes(resp.totalSize);
        }
        var flushWrap = document.getElementById('cpFlushWrap');
        if (flushWrap) {
            flushWrap.style.display = (resp.count === undefined ? flushWrap.style.display : (resp.count > 0 ? '' : 'none'));
        }
    }

    function setBusy(btn, busy) {
        if (!btn) return;
        btn.disabled = busy;
        btn.style.opacity = busy ? '0.6' : '';
        btn.style.cursor = busy ? 'wait' : '';
        if (busy) btn.setAttribute('data-cp-busy', 'true');
        else btn.removeAttribute('data-cp-busy');
    }

    function isBusy(btn) {
        return btn && (btn.disabled || btn.getAttribute('data-cp-busy') === 'true');
    }

    function handle(promise, successMsg, btn) {
        setBusy(btn, true);
        promise.then(function (r) {
            return r.json().then(function (d) {
                if (r.ok && d.success) {
                    applyResponse(d);
                    if (successMsg || d.message) showToast(successMsg || d.message, 'success');
                } else {
                    showToast(d.message || 'Request failed.', 'error');
                }
            });
        }).catch(function () { showToast('Network error. Please try again.', 'error'); }).finally(function () { setBusy(btn, false); });
    }

    var createForm = document.getElementById('cpCreateForm');
    if (createForm) {
        createForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = document.getElementById('cpCreateBtn');
            if (isBusy(btn)) return;
            handle(post(routes.create, {
                name: document.getElementById('cpName').value,
                note: document.getElementById('cpNote').value,
                encrypt: document.getElementById('cpEncrypt').checked ? '1' : '0'
            }), 'Checkpoint created.', btn);
            createForm.reset();
        });
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-cp-action]');
        if (!btn) return;
        e.preventDefault();
        if (isBusy(btn)) return;

        var id = btn.getAttribute('data-cp-id');
        var name = btn.getAttribute('data-cp-name') || 'this checkpoint';
        var action = btn.getAttribute('data-cp-action');
        var identifier = id || name;

        if (btn.disabled) return;

        switch (action) {
            case 'restore':
                showConfirm('Restore Checkpoint', 'Restore "' + name + '"? This overwrites the current database with this snapshot. Checkpoints themselves are not modified.', { confirmText: 'Restore' })
                    .then(function (ok) { if (ok) handle(post(routes.restore, { identifier: identifier }), 'Checkpoint restored.', btn); });
                break;
            case 'delete':
                if (btn.getAttribute('data-cp-locked') === '1') {
                    showToast('The checkpoint is locked, so it cannot be deleted. Please unlock it first.', 'error');
                    return;
                }
                showDanger('Delete Checkpoint', 'Permanently delete "' + name + '"? This cannot be undone.', { confirmText: 'Delete' })
                    .then(function (ok) { if (ok) handle(post(routes.delete, { identifier: identifier }), 'Checkpoint deleted.', btn); });
                break;
            case 'flush':
                showDanger('Flush Unlocked Checkpoints', 'Permanently delete ALL unlocked checkpoints? Locked checkpoints are kept.', { confirmText: 'Flush' })
                    .then(function (ok) { if (ok) handle(post(routes.flush, {}), 'Unlocked checkpoints deleted.', btn); });
                break;
            case 'toggle-lock':
                handle(post(routes.toggleLock, { identifier: identifier }), null, btn);
                break;
            case 'toggle-flag':
                handle(post(routes.toggleFlag, { identifier: identifier }), null, btn);
                break;
            case 'encrypt':
                showConfirm('Encrypt Checkpoint', 'Encrypt "' + name + '" in place? An encryption key must be configured.', { confirmText: 'Encrypt' })
                    .then(function (ok) { if (ok) handle(post(routes.encrypt, { identifier: identifier }), 'Checkpoint encrypted.', btn); });
                break;
            case 'edit-note':
                var current = btn.getAttribute('data-cp-note') || '';
                showPrompt('Edit Note', 'Add or update the note for "' + name + '".', current, 'Note (optional)')
                    .then(function (val) {
                        if (val === null) return;
                        handle(post(routes.note, { identifier: identifier, note: val }), 'Note saved.', btn);
                    });
                break;
        }
    });

    var flushWrap = document.getElementById('cpFlushWrap');
    if (flushWrap) flushWrap.style.display = ({{ (int) count($checkpoints) }} > 0 ? '' : 'none');
})();
</script>
@endif
@endpush
