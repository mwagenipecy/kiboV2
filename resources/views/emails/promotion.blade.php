<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subjectLine ?? 'Update from Kibo' }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; -webkit-font-smoothing: antialiased;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);">
                    <!-- Header with logo and tagline -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #009866 0%, #007a52 100%); padding: 32px 40px; text-align: center;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 auto;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $appUrl }}" target="_blank" style="text-decoration: none; display: inline-block;">
                                            <img src="{{ $logoUrl }}" alt="Kibo" width="120" height="40" style="display: block; margin: 0 auto; max-height: 40px; width: auto; border: 0; outline: none;" />
                                        </a>
                                        <p style="margin: 12px 0 0 0; color: rgba(255,255,255,0.95); font-size: 15px;">Automotive &amp; Mobility Platform</p>
                                        <p style="margin: 4px 0 0 0; color: rgba(255,255,255,0.8); font-size: 13px;">Cars · Trucks · Leasing · Spare Parts · More</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Hero CTA strip -->
                    <tr>
                        <td style="padding: 0; background: #f8fafc; border-bottom: 1px solid #e5e7eb;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 20px 40px; text-align: center;">
                                        <a href="{{ $appUrl }}" target="_blank" style="display: inline-block; background: #009866; color: #ffffff !important; padding: 12px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">Visit our website</a>
                                        &nbsp;&nbsp;
                                        <a href="{{ $appUrl }}/login" target="_blank" style="display: inline-block; background: #ffffff; color: #009866 !important; padding: 12px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; border: 2px solid #009866;">Login</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 32px 40px 20px 40px;">
                            <p style="margin: 0; font-size: 16px; line-height: 1.6; color: #374151;">Hello <strong style="color: #111827;">{{ $recipientName }}</strong>,</p>
                        </td>
                    </tr>
                    <!-- Body content -->
                    <tr>
                        <td style="padding: 0 40px 24px 40px;">
                            <div style="font-size: 15px; line-height: 1.7; color: #4b5563;">
                                {!! $bodyHtml !!}
                            </div>
                        </td>
                    </tr>
                    <!-- Main CTA -->
                    <tr>
                        <td style="padding: 8px 40px 32px 40px; text-align: center;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tr>
                                    <td style="border-radius: 8px; background: linear-gradient(135deg, #009866 0%, #007a52 100%);">
                                        <a href="{{ $appUrl }}" target="_blank" style="display: inline-block; color: #ffffff !important; padding: 14px 36px; text-decoration: none; font-weight: 700; font-size: 16px;">Go to Kibo →</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Footer with links -->
                    <tr>
                        <td style="padding: 24px 40px 28px 40px; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                            <p style="margin: 0 0 12px 0; font-size: 14px; color: #6b7280;">
                                Best regards,<br>
                                <strong style="color: #009866;">The Kibo Team</strong>
                            </p>
                            <p style="margin: 0 0 14px 0; font-size: 13px;">
                                <a href="{{ $appUrl }}" target="_blank" style="color: #009866; text-decoration: none;">Website</a>
                                &nbsp;·&nbsp;
                                <a href="{{ $appUrl }}/login" target="_blank" style="color: #009866; text-decoration: none;">Login</a>
                                &nbsp;·&nbsp;
                                <a href="{{ $appUrl }}/cars" target="_blank" style="color: #009866; text-decoration: none;">Browse cars</a>
                                &nbsp;·&nbsp;
                                <a href="{{ $appUrl }}/trucks" target="_blank" style="color: #009866; text-decoration: none;">Browse trucks</a>
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                                © {{ date('Y') }} Kibo. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
