<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; min-width: 100%; }
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; margin: auto !important; }
            .fluid { max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important; }
            .stack-column, .stack-column-center { display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important; }
            .stack-column-center { text-align: center !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; background-color: #f1f5f9; color: #1e293b;">
    <div style="background-color: #f1f5f9; padding: 32px 16px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto;">
            <!-- Brand / Header -->
            <tr>
                <td align="center" style="padding: 0 0 20px 0;">
                    <span style="font-size: 18px; font-weight: 700; letter-spacing: -0.025em; color: #0f172a;">{{ $appName }}</span>
                </td>
            </tr>

            <!-- Main Content Container Card -->
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                        <!-- Accent Top Bar -->
                        <tr>
                            <td height="4" style="background: #000000;"></td>
                        </tr>
                        <tr>
                            <td style="padding: 32px 32px 24px 32px;">
                                <h1 style="margin: 0 0 16px 0; font-size: 22px; font-weight: 700; line-height: 1.3; color: #0f172a;">{{ $subject }}</h1>
                                <div style="font-size: 15px; line-height: 1.65; color: #334155;">
                                    {!! $content !!}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td align="center" style="padding: 24px 0 0 0; font-size: 12px; color: #64748b; line-height: 1.5;">
                    <p style="margin: 0 0 4px 0;">Sent via <strong>{{ $appName }}</strong></p>
                    <p style="margin: 0;">&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
