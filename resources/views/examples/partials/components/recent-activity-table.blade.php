{{-- Tables --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 1.0625rem;">Recent Activity</h3>
        <span class="badge badge-secondary">Table + badges</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Actor</th>
                        <th>Status</th>
                        <th style="text-align:right;">When</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activity as $row)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $row['title'] }}</div>
                                <div style="font-size: 0.8125rem; color: var(--muted-foreground);">{{ $row['subtitle'] }}</div>
                            </td>
                            <td>
                                <div class="user-cell" style="gap: 0.625rem; text-decoration: none;">
                                    <div class="user-cell-avatar">{{ strtoupper(substr($row['actor'], 0, 1)) }}</div>
                                    <div class="user-cell-info">
                                        <div class="user-cell-name" style="font-size: 0.9375rem;">{{ $row['actor'] }}</div>
                                        <div class="user-cell-email" style="font-size: 0.8125rem;">{{ $row['actor_meta'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $row['status_badge_class'] }}">{{ $row['status'] }}</span>
                            </td>
                            <td style="text-align:right; color: var(--muted-foreground);">{{ $row['when'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
