@if(! empty($checkpoints))
<div class="card">
    <div class="card-body" style="border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
        <strong>{{ count($checkpoints) }} checkpoint{{ count($checkpoints) === 1 ? '' : 's' }}</strong>
        <span style="font-size: 0.84rem; color: var(--muted-foreground);">Locked checkpoints survive "Flush Unlocked".</span>
    </div>
    <div class="table-container cp-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Checkpoint</th>
                    <th>Driver</th>
                    <th>Note</th>
                    <th>Created</th>
                    <th>Size</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($checkpoints as $cp)
                    @php
                        $id = (string) $cp['id'];
                        $name = $cp['name'];
                        $identifier = $id !== '0' ? $id : $name;
                    @endphp
                    <tr>
                        <td>
                            <div class="cp-name">
                                <span class="cp-name-main">
                                    {{ $name }}
                                    @if(! $cp['exists_on_disk'])
                                        <span class="badge badge-danger" title="Snapshot file is missing from disk">missing file</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="cp-driver">{{ $cp['driver'] ?? 'sqlite' }}</span>
                        </td>
                        <td>
                            <span class="cp-note @if(! $cp['note']) cp-note-empty @endif" title="{{ $cp['note'] ?? '' }}">
                                {{ $cp['note'] ?: 'No note' }}
                            </span>
                        </td>
                        <td>{{ \HasinHayder\TyroDashboard\Http\Controllers\CheckpointController::formatDate($cp['created_at'] ?? null) }}</td>
                        <td>{{ $cp['size_for_humans'] }}</td>
                        <td>
                            <div class="cp-actions action-buttons">
                                <button type="button" class="action-btn @if($cp['encrypted']) action-btn-success @endif" title="{{ $cp['encrypted'] ? 'Encrypted' : 'Encrypt in place' }}" data-cp-action="{{ $cp['encrypted'] ? '' : 'encrypt' }}" data-cp-id="{{ $identifier }}" data-cp-name="{{ $name }}" @if($cp['encrypted']) disabled @endif>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </button>
                                <button type="button" class="action-btn" title="Rename" data-cp-action="rename" data-cp-id="{{ $identifier }}" data-cp-name="{{ $name }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button type="button" class="action-btn" title="Edit note" data-cp-action="edit-note" data-cp-id="{{ $identifier }}" data-cp-name="{{ $name }}" data-cp-note="{{ $cp['note'] ?? '' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 12h7m-7 5h10M4 4v16a1 1 0 001 1h14a1 1 0 001-1V4a1 1 0 00-1-1H5a1 1 0 00-1 1z"/>
                                    </svg>
                                </button>
                                <button type="button" class="action-btn @if($cp['flagged']) action-btn-danger @endif" title="{{ $cp['flagged'] ? 'Remove flag' : 'Flag for attention' }}" data-cp-action="toggle-flag" data-cp-id="{{ $identifier }}" data-cp-name="{{ $name }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/>
                                    </svg>
                                </button>
                                <button type="button" class="action-btn @if($cp['locked']) action-btn-primary @endif" title="{{ $cp['locked'] ? 'Unlock' : 'Lock (prevent deletion)' }}" data-cp-action="toggle-lock" data-cp-id="{{ $identifier }}" data-cp-name="{{ $name }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </button>
                                <button type="button" class="action-btn" title="Restore" data-cp-action="restore" data-cp-id="{{ $identifier }}" data-cp-name="{{ $name }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                                <button type="button" class="action-btn action-btn-danger" title="{{ $cp['locked'] ? 'Locked — unlock first' : 'Delete' }}" data-cp-action="delete" data-cp-id="{{ $identifier }}" data-cp-name="{{ $name }}" data-cp-locked="{{ $cp['locked'] ? '1' : '0' }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card">
    <div class="empty-state">
        <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
        </svg>
        <h3 class="empty-state-title">No checkpoints yet</h3>
        <p class="empty-state-description">Create your first checkpoint using the form above.</p>
    </div>
</div>
@endif
