@extends($isAdmin ? 'tyro-dashboard::layouts.admin' : 'tyro-dashboard::layouts.user')

@section('title', 'Example Mail')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Examples</span>
<span class="breadcrumb-separator">/</span>
<span>Mail</span>
@endsection

@section('content')
@php
    $folders = [
        ['label' => 'Inbox', 'count' => 12, 'badge' => 'badge-primary'],
        ['label' => 'Starred', 'count' => 3, 'badge' => 'badge-secondary'],
        ['label' => 'Drafts', 'count' => 2, 'badge' => 'badge-warning'],
        ['label' => 'Spam', 'count' => 1, 'badge' => 'badge-danger'],
    ];

    $messages = [
        ['from' => 'Billing', 'subject' => 'Your invoice is ready', 'preview' => 'Invoice #4829 attached…', 'tag' => 'Finance', 'tag_badge' => 'badge-primary', 'time' => '5m'],
        ['from' => 'Support', 'subject' => 'Ticket update: #1932', 'preview' => 'We have resolved the issue…', 'tag' => 'Support', 'tag_badge' => 'badge-success', 'time' => '42m'],
        ['from' => 'Marketing', 'subject' => 'Campaign performance', 'preview' => 'CTR is trending upward…', 'tag' => 'Report', 'tag_badge' => 'badge-secondary', 'time' => '2h'],
        ['from' => 'System', 'subject' => 'New login from device', 'preview' => 'We noticed a login from…', 'tag' => 'Security', 'tag_badge' => 'badge-warning', 'time' => 'Yesterday'],
    ];
@endphp

<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Example Mail</h1>
            <p class="page-description" style="font-size: 1rem;">An inbox-style page with folders, search, and message list.</p>
        </div>
        <div style="display:flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="#" class="btn btn-primary btn-sm" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/></svg>
                Compose
            </a>
            <a href="#" class="btn btn-secondary btn-sm" onclick="return false;">Mark all read</a>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Folders</h3>
            <span class="badge badge-secondary">List</span>
        </div>
        <div class="card-body">
            <div style="display:flex; flex-direction: column; gap: 0.5rem;">
                @foreach($folders as $f)
                    <div style="display:flex; align-items:center; justify-content: space-between; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 10px; background: var(--background);">
                        <div style="font-weight: 600;">{{ $f['label'] }}</div>
                        <span class="badge {{ $f['badge'] }}">{{ $f['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 1.0625rem;">Search</h3>
            <span class="badge badge-secondary">Form</span>
        </div>
        <div class="card-body">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Query</label>
                <input class="form-input" placeholder="Search subject, sender, or tag…" value="invoice" />
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Inbox</h3>
        <span class="badge badge-secondary">Table</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Tag</th>
                        <th style="text-align:right;">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $m)
                        <tr>
                            <td style="font-weight: 600;">{{ $m['from'] }}</td>
                            <td>
                                <div style="font-weight: 600;">{{ $m['subject'] }}</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">{{ $m['preview'] }}</div>
                            </td>
                            <td><span class="badge {{ $m['tag_badge'] }}">{{ $m['tag'] }}</span></td>
                            <td style="text-align:right; color: var(--muted-foreground);">{{ $m['time'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
