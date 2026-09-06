<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin: 0; padding: 24px 16px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #1e293b; background-color: #ffffff;">
    <div style="max-width: 580px; margin: 0 auto;">
        <div style="padding-bottom: 12px; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; color: #64748b; letter-spacing: 0.05em; text-transform: uppercase;">
            {{ $appName }}
        </div>
        <h1 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 600; color: #0f172a;">{{ $subject }}</h1>
        <div style="font-size: 15px; color: #1e293b; line-height: 1.65;">
            {!! $content !!}
        </div>
        <div style="margin-top: 36px; padding-top: 16px; border-top: 1px solid #f1f5f9; font-size: 12px; color: #94a3b8;">
            <p style="margin: 0;">Sent directly from {{ $appName }}</p>
        </div>
    </div>
</body>
</html>
