<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migration Required - Tyro Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .error-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            padding: 3rem;
            text-align: center;
        }

        .error-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc2626;
        }

        .error-icon svg {
            width: 48px;
            height: 48px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        h1 {
            color: #111827;
            font-size: 1.875rem;
            margin-bottom: 0.5rem;
        }

        .error-status {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .error-message {
            color: #374151;
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 2rem;
        }

        .code-block {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
            margin: 1.5rem 0;
            text-align: left;
            overflow-x: auto;
        }

        .code-block code {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.875rem;
            color: #374151;
            display: block;
        }

        .code-label {
            color: #6b7280;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin: 2rem 0;
        }

        .option-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .footer {
            color: #9ca3af;
            font-size: 0.875rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4v2m-6-4a9 9 0 1118 0 9 9 0 01-18 0z" />
            </svg>
        </div>

        <h1>Database Migration Required</h1>
        <p class="error-status">Error 503 — Service Unavailable</p>

        <p class="error-message">
            The invitation and referral system requires database tables that haven't been created yet.
        </p>

        <p class="error-message" style="font-size: 0.875rem; color: #6b7280;">
            This usually happens when updating Tyro Dashboard to a version with the new invitation system.
        </p>

        <div class="options">
            <div>
                <p class="option-label">Option 1: Run all pending migrations</p>
                <div class="code-block">
                    <code>php artisan migrate</code>
                </div>
            </div>

            <div>
                <p class="option-label">Option 2: Run only Tyro Login migrations</p>
                <div class="code-block">
                    <code>php artisan migrate --path=vendor/hasinhayder/tyro-login/database/migrations</code>
                </div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('tyro-dashboard.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Go Back
            </a>
        </div>

        <p class="footer">
            After running migrations, refresh this page to use the invitation system.
        </p>
    </div>
</body>
</html>
