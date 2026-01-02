<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account Credentials</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Welcome to Kibo!</h1>
        <p style="color: #f0fdf4; margin: 10px 0 0 0; font-size: 16px;">Your registration has been approved</p>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello <strong>{{ $name }}</strong>,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Congratulations! Your {{ ucfirst($registrationType) }} registration has been approved. We've created an account for you to access our platform.
        </p>
        
        <div style="background: #f9fafb; border-left: 4px solid #10b981; padding: 20px; margin: 25px 0; border-radius: 4px;">
            <h2 style="margin: 0 0 15px 0; color: #10b981; font-size: 18px;">Your Login Credentials</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Email:</td>
                    <td style="padding: 8px 0; color: #111827;">{{ $email }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Password:</td>
                    <td style="padding: 8px 0; color: #111827; font-family: 'Courier New', monospace; background: #e5e7eb; padding: 4px 8px; border-radius: 4px; display: inline-block;">{{ $password }}</td>
                </tr>
            </table>
        </div>
        
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 25px 0; border-radius: 4px;">
            <p style="margin: 0; color: #92400e; font-size: 14px;">
                <strong>⚠️ Security Notice:</strong> For your security, please change your password after your first login.
            </p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $loginUrl }}" style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);">
                Login to Your Account
            </a>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">
                If you have any questions or need assistance, please don't hesitate to contact our support team.
            </p>
            <p style="font-size: 14px; color: #6b7280; margin: 0;">
                Best regards,<br>
                <strong style="color: #10b981;">The Kibo Team</strong>
            </p>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p style="margin: 5px 0;">
            This email contains sensitive information. Please keep it secure.
        </p>
        <p style="margin: 5px 0;">
            © {{ date('Y') }} Kibo. All rights reserved.
        </p>
    </div>
</body>
</html>

