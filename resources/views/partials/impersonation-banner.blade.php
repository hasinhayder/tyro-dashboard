@if(session('impersonator_id'))
    @php
        $userModel = config('tyro-dashboard.user_model') ?: 'App\\Models\\User';
        $impersonator = $userModel::find(session('impersonator_id'));
        $currentUser = auth()->user();
    @endphp
    <div class="impersonation-banner">
        <div class="impersonation-banner-content">
            <div class="impersonation-banner-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="impersonation-banner-text">
                <strong>Impersonation Mode:</strong>
                You are currently logged in as <strong>{{ $currentUser->name }}</strong> ({{ $currentUser->email }}).
                Originally logged in as <strong>{{ $impersonator->name ?? 'Admin' }}</strong>.
            </div>
            <form action="{{ route('tyro-dashboard.leave-impersonation') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="impersonation-banner-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Leave Impersonation
                </button>
            </form>
        </div>
    </div>

    <style>
        .impersonation-banner {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-bottom: 2px solid #f59e0b;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .impersonation-banner-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .impersonation-banner-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(245, 158, 11, 0.2);
            border-radius: 50%;
            flex-shrink: 0;
        }

        .impersonation-banner-icon svg {
            width: 20px;
            height: 20px;
            color: #d97706;
        }

        .impersonation-banner-text {
            flex: 1;
            color: #78350f;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .impersonation-banner-text strong {
            color: #92400e;
            font-weight: 600;
        }

        .impersonation-banner-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #fff;
            color: #92400e;
            border: 1px solid #d97706;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .impersonation-banner-btn:hover {
            background: #fffbeb;
            border-color: #b45309;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .impersonation-banner-btn svg {
            width: 16px;
            height: 16px;
        }

        @media (max-width: 768px) {
            .impersonation-banner {
                padding: 0.75rem 1rem;
            }

            .impersonation-banner-content {
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .impersonation-banner-text {
                flex-basis: 100%;
                order: 2;
            }

            .impersonation-banner-btn {
                flex-shrink: 0;
                order: 3;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endif
