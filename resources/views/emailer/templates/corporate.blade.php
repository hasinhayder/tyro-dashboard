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
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f8fafc; color: #1e293b;">
    <div style="background-color: #f8fafc; padding: 36px 16px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; border: 1px solid #cbd5e1; overflow: hidden;">
            <!-- Dark Corporate Header -->
            <tr>
                <td style="background-color: #0f172a; padding: 24px 32px; text-align: left;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td>
                                <span style="font-size: 18px; font-weight: 700; color: #ffffff; letter-spacing: 0.05em; text-transform: uppercase;">{{ $appName }}</span>
                            </td>
                            <td align="right">
                                <span style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Official Notice</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Content Area -->
            <tr>
                <td style="padding: 36px 32px 28px 32px;">
                    <div style="border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 20px;">
                        <h2 style="margin: 0; font-size: 20px; font-weight: 600; color: #0f172a;">{{ $subject }}</h2>
                    </div>

                    <div style="font-size: 15px; line-height: 1.7; color: #334155;">
                        {!! $content !!}
                    </div>

                    <div style="margin-top: 32px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b;">
                        <p style="margin: 0;">This email is an official communication dispatched via {{ $appName }}.</p>
                    </div>
                </td>
            </tr>

            <!-- Subdued Footer -->
            <tr>
                <td style="background-color: #f1f5f9; padding: 16px 32px; font-size: 12px; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0;">
                    &copy; {{ date('Y') }} {{ $appName }}. All corporate rights reserved.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
