<?php

namespace HasinHayder\TyroDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends BaseController
{
    /**
     * Display the dashboard home.
     */
    public function index(Request $request)
    {
        $userModel = $this->getUserModel();
        
        $stats = [];
        
        if ($this->isAdmin()) {
            try {
                // User stats
                $stats['total_users'] = class_exists($userModel) ? $userModel::count() : 0;
                
                // Try to get suspended users count
                if (class_exists($userModel)) {
                    $user = new $userModel;
                    if (method_exists($user, 'isSuspended') || $user->getConnection()->getSchemaBuilder()->hasColumn($user->getTable(), 'suspended_at')) {
                        $stats['suspended_users'] = $userModel::whereNotNull('suspended_at')->count();
                    } else {
                        $stats['suspended_users'] = 0;
                    }
                    $stats['recent_users'] = $userModel::latest()->take(5)->get();
                } else {
                    $stats['suspended_users'] = 0;
                    $stats['recent_users'] = new Collection();
                }
                
                // Role stats
                if (class_exists('\HasinHayder\Tyro\Models\Role')) {
                    $roleModel = '\HasinHayder\Tyro\Models\Role';
                    $stats['total_roles'] = $roleModel::count();
                    
                    $roles = $roleModel::withCount('users')->get();
                    $stats['role_distribution'] = $roles->map(function ($role) {
                        return [
                            'id' => $role->id,
                            'name' => $role->name,
                            'count' => $role->users_count,
                        ];
                    });
                } else {
                    $stats['total_roles'] = 0;
                    $stats['role_distribution'] = new Collection();
                }
                
                // Privilege stats
                if (class_exists('\HasinHayder\Tyro\Models\Privilege')) {
                    $privilegeModel = '\HasinHayder\Tyro\Models\Privilege';
                    $stats['total_privileges'] = $privilegeModel::count();
                } else {
                    $stats['total_privileges'] = 0;
                }
                
            } catch (\Exception $e) {
                // If any error occurs, provide default stats
                $stats = [
                    'total_users' => 0,
                    'total_roles' => 0,
                    'total_privileges' => 0,
                    'recent_users' => new Collection(),
                    'suspended_users' => 0,
                    'role_distribution' => new Collection(),
                ];
            }

            // Return admin dashboard view
            return view('tyro-dashboard::dashboard.admin', $this->getViewData([
                'stats' => $stats,
            ]));
        }

        // Return user dashboard view for non-admin users
        return view('tyro-dashboard::dashboard.user', $this->getViewData([
            'stats' => $stats,
        ]));
    }

    /**
     * Display a copy-ready components showcase page.
     */
    public function components(Request $request)
    {
        $kpis = [
            [
                'label' => 'Monthly Revenue',
                'value' => '$48,230',
                'icon_class' => 'stat-icon-success',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V6m0 12v-2m6-6a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>',
                'change_class' => 'stat-change-up',
                'change_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17l9-9m0 0H7m9 0v9"/></svg>',
                'change_text' => '+12.4% vs last month',
            ],
            [
                'label' => 'New Signups',
                'value' => '1,284',
                'icon_class' => 'stat-icon-primary',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8.5 11a4 4 0 100-8 4 4 0 000 8z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20 8v6"/><path stroke-linecap="round" stroke-linejoin="round" d="M23 11h-6"/></svg>',
                'change_class' => 'stat-change-up',
                'change_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17l9-9m0 0H7m9 0v9"/></svg>',
                'change_text' => '+6.1% this week',
            ],
            [
                'label' => 'Open Tickets',
                'value' => '42',
                'icon_class' => 'stat-icon-warning',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 16h6"/><path stroke-linecap="round" stroke-linejoin="round" d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9l-7-7z"/></svg>',
                'change_class' => 'stat-change-down',
                'change_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7l9 9m0 0V7m0 9H7"/></svg>',
                'change_text' => '-3 since yesterday',
            ],
            [
                'label' => 'Error Rate',
                'value' => '0.18%',
                'icon_class' => 'stat-icon-danger',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/><path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>',
                'change_class' => 'stat-change-up',
                'change_icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17l9-9m0 0H7m9 0v9"/></svg>',
                'change_text' => '+0.03% today',
            ],
        ];

        $charts = [
            'traffic_total' => 128430,
            'traffic_growth_pct' => 9.2,
            'traffic_line_path' => 'M 0 140 C 60 130, 90 115, 120 110 C 170 100, 190 85, 240 90 C 290 95, 320 70, 360 60 C 400 52, 440 64, 480 50 C 520 38, 560 35, 600 30',
            'traffic_area_path' => 'M 0 140 C 60 130, 90 115, 120 110 C 170 100, 190 85, 240 90 C 290 95, 320 70, 360 60 C 400 52, 440 64, 480 50 C 520 38, 560 35, 600 30 L 600 170 L 0 170 Z',
            'traffic_range_label_left' => '14 days ago',
            'traffic_range_label_right' => 'Today',
            'status_total' => 812,
            'status_donut' => [
                ['label' => '200 OK', 'count' => 642, 'pct' => 79, 'color' => 'var(--success)'],
                ['label' => '4xx', 'count' => 132, 'pct' => 16, 'color' => 'var(--warning)'],
                ['label' => '5xx', 'count' => 38, 'pct' => 5, 'color' => 'var(--destructive)'],
            ],
            'weekly_bars' => [
                ['label' => 'Mon', 'value' => 420, 'pct' => 42],
                ['label' => 'Tue', 'value' => 610, 'pct' => 61],
                ['label' => 'Wed', 'value' => 510, 'pct' => 51],
                ['label' => 'Thu', 'value' => 820, 'pct' => 82],
                ['label' => 'Fri', 'value' => 760, 'pct' => 76],
                ['label' => 'Sat', 'value' => 540, 'pct' => 54],
                ['label' => 'Sun', 'value' => 690, 'pct' => 69],
            ],
            'channel_mix' => [
                [
                    'label' => 'Search',
                    'segments' => [
                        ['label' => 'Organic', 'pct' => 32, 'color' => 'var(--foreground)'],
                        ['label' => 'Paid', 'pct' => 18, 'color' => 'var(--info)'],
                    ],
                ],
                [
                    'label' => 'Social',
                    'segments' => [
                        ['label' => 'Direct', 'pct' => 14, 'color' => 'var(--success)'],
                        ['label' => 'Campaign', 'pct' => 9, 'color' => 'var(--warning)'],
                    ],
                ],
                [
                    'label' => 'Email',
                    'segments' => [
                        ['label' => 'Newsletter', 'pct' => 10, 'color' => 'var(--success)'],
                        ['label' => 'Lifecycle', 'pct' => 6, 'color' => 'var(--foreground)'],
                    ],
                ],
                [
                    'label' => 'Other',
                    'segments' => [
                        ['label' => 'Referrals', 'pct' => 7, 'color' => 'var(--info)'],
                        ['label' => 'Unknown', 'pct' => 4, 'color' => 'var(--destructive)'],
                    ],
                ],
            ],
            'sparklines' => [
                ['label' => 'Latency (p95)', 'value' => '182ms', 'badge_class' => 'badge-success', 'badge_text' => 'Good', 'path' => 'M 0 18 C 12 18, 24 14, 36 13 C 48 12, 60 9, 72 10 C 84 11, 96 8, 108 7 C 120 6, 132 8, 144 6'],
                ['label' => 'Conversions', 'value' => '3.8%', 'badge_class' => 'badge-primary', 'badge_text' => 'Stable', 'path' => 'M 0 14 C 14 16, 28 12, 42 11 C 56 10, 70 13, 84 9 C 98 5, 112 7, 126 8 C 138 9, 150 7, 162 6'],
                ['label' => 'Errors', 'value' => '0.18%', 'badge_class' => 'badge-warning', 'badge_text' => 'Watch', 'path' => 'M 0 6 C 14 5, 28 7, 42 8 C 56 9, 70 8, 84 10 C 98 12, 112 13, 126 14 C 138 15, 150 16, 162 17'],
            ],
        ];

        $progress = [
            [
                'title' => 'Onboarding Flow',
                'subtitle' => 'Forms, validation, and email confirmation',
                'pct' => 72,
                'meta' => 'ETA: 4 days',
                'badge_class' => 'badge-success',
                'badge_text' => 'On Track',
                'bar_color' => 'var(--success)',
            ],
            [
                'title' => 'Admin Audit Log',
                'subtitle' => 'Events, filters, and export',
                'pct' => 44,
                'meta' => 'ETA: 1 week',
                'badge_class' => 'badge-warning',
                'badge_text' => 'At Risk',
                'bar_color' => 'var(--warning)',
            ],
            [
                'title' => 'Billing Webhooks',
                'subtitle' => 'Retries, alerting, and idempotency',
                'pct' => 18,
                'meta' => 'ETA: 2 weeks',
                'badge_class' => 'badge-primary',
                'badge_text' => 'Planned',
                'bar_color' => 'var(--foreground)',
            ],
        ];

        $infoCards = [
            [
                'eyebrow' => 'Usage',
                'title' => 'Storage nearing limit',
                'description' => 'Upgrade your plan or clean up old artifacts to avoid failed uploads.',
                'badge' => 'Action needed',
                'badge_class' => 'badge-warning',
            ],
            [
                'eyebrow' => 'Security',
                'title' => '2FA adoption improved',
                'description' => 'Rolling 30-day adoption is trending upward across teams.',
                'badge' => 'Healthy',
                'badge_class' => 'badge-success',
            ],
            [
                'eyebrow' => 'Operations',
                'title' => 'Background jobs stable',
                'description' => 'No failed jobs in the last 24 hours. Average runtime is within target.',
                'badge' => 'OK',
                'badge_class' => 'badge-primary',
            ],
        ];

        $activity = [
            [
                'title' => 'User invited',
                'subtitle' => 'Invite email sent successfully',
                'actor' => 'System',
                'actor_meta' => 'Automation',
                'status' => 'Delivered',
                'status_badge_class' => 'badge-success',
                'when' => '2m ago',
            ],
            [
                'title' => 'Role updated',
                'subtitle' => '“Manager” permissions changed',
                'actor' => 'Admin',
                'actor_meta' => 'admin@example.com',
                'status' => 'Applied',
                'status_badge_class' => 'badge-primary',
                'when' => '28m ago',
            ],
            [
                'title' => 'Data export',
                'subtitle' => 'CSV export requested',
                'actor' => 'Jane Doe',
                'actor_meta' => 'jane@example.com',
                'status' => 'Queued',
                'status_badge_class' => 'badge-warning',
                'when' => '1h ago',
            ],
            [
                'title' => 'Webhook failure',
                'subtitle' => 'Stripe event retry scheduled',
                'actor' => 'Worker',
                'actor_meta' => 'queue:payments',
                'status' => 'Retrying',
                'status_badge_class' => 'badge-danger',
                'when' => '3h ago',
            ],
        ];

        return view('tyro-dashboard::dashboard.components', $this->getViewData([
            'kpis' => $kpis,
            'charts' => $charts,
            'progress' => $progress,
            'infoCards' => $infoCards,
            'activity' => $activity,
        ]));
    }
}
