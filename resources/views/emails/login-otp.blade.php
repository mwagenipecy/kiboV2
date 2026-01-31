<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Verification Code</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-content {
            padding: 40px 24px;
        }
        .header {
            text-align: center;
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            text-decoration: none;
        }
        .main-content {
            padding: 32px 24px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
        }
        .text {
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 16px;
        }
        .otp-container {
            display: flex;
            gap: 8px;
            margin: 24px 0;
            justify-content: center;
        }
        .otp-digit {
            font-size: 32px;
            font-weight: 700;
            color: #059669;
            letter-spacing: 2px;
        }
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #111827;
            }
            .greeting {
                color: #e5e7eb;
            }
            .text {
                color: #d1d5db;
            }
            .otp-digit {
                color: #10b981;
            }
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 24px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
        .footer {
            padding: 24px;
            border-top: 1px solid #e5e7eb;
            margin-top: 32px;
        }
        .footer-text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }
        .footer-link {
            color: #2563eb;
            text-decoration: none;
        }
        .footer-link:hover {
            text-decoration: underline;
        }
        .footer-copyright {
            margin-top: 12px;
            color: #6b7280;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            .email-content {
                padding: 24px 16px;
            }
            .main-content {
                padding: 24px 16px;
            }
            .otp-digit {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-content">
            <header class="header">
                <a href="{{ config('app.url') }}" class="logo">
                    Kibo
                </a>
            </header>

            <main class="main-content">
                <h2 class="greeting">Hi {{ $user->name }},</h2>

                <p class="text">
                    This is your verification code:
                </p>

                <div class="otp-container">
                    @foreach($otpDigits as $digit)
                        <div class="otp-digit">{{ $digit }}</div>
                    @endforeach
                </div>

                <p class="text">
                    This code will only be valid for the next 5 minutes. If the code does not work, you can use this login verification link:
                </p>
                
                <div style="text-align: center;">
                    <a href="{{ config('app.url') }}/login" class="button">
                        Verify email
                    </a>
                </div>
                
                <p class="text" style="margin-top: 32px;">
                    Thanks, <br>
                    Kibo Team
                </p>
            </main>

            <footer class="footer">
                <p class="footer-text">
                    This email was sent to <a href="mailto:{{ $user->email }}" class="footer-link">{{ $user->email }}</a>. 
                    If you'd rather not receive this kind of email, you can <a href="{{ config('app.url') }}/unsubscribe" class="footer-link">unsubscribe</a> or <a href="{{ config('app.url') }}/preferences" class="footer-link">manage your email preferences</a>.
                </p>

                <p class="footer-copyright">© {{ date('Y') }} Kibo. All Rights Reserved.</p>
            </footer>
        </div>
    </div>
</body>
</html>

