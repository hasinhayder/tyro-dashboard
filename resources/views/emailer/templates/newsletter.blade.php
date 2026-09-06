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
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f4f4f5; color: #1e293b;">
    <div style="background-color: #f4f4f5; padding: 36px 16px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e4e4e7; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06);">
            <!-- Solid Black Header -->
            <tr>
                <td style="background-color: #000000; padding: 32px 32px; text-align: center;">
                    <div style="display: inline-block; background: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 9999px; color: #ffffff; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">
                        Announcement &amp; Update
                    </div>
                    <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.02em;">{{ $appName }}</h1>
                </td>
            </tr>

            <!-- Newsletter Content Card -->
            <tr>
                <td style="padding: 36px 32px 28px 32px;">
                    <h2 style="margin: 0 0 16px 0; font-size: 21px; font-weight: 700; color: #09090b; line-height: 1.35;">{{ $subject }}</h2>
                    <div style="font-size: 15px; line-height: 1.7; color: #334155;">
                        {!! $content !!}
                    </div>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="background-color: #fafafa; padding: 20px 32px; text-align: center; border-top: 1px solid #e4e4e7; font-size: 12px; color: #71717a;">
                    <p style="margin: 0 0 4px 0;">Sent with care by <strong>{{ $appName }}</strong></p>
                    <p style="margin: 0; color: #a1a1aa;">&copy; {{ date('Y') }} {{ $appName }}. Stay inspired.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
