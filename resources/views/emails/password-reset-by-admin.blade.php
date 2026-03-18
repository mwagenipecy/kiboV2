<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <img src="{{ asset('logo/white.png') }}" alt="Kibo" style="height: 50px; width: auto; margin-bottom: 15px;" />
        <h1 style="color: white; margin: 0; font-size: 28px;">Password Reset</h1>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello <strong>{{ $name }}</strong>,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Your password has been reset by an administrator. Below are your new login credentials:
        </p>
        
        <div style="background: #f9fafb; border-left: 4px solid #f59e0b; padding: 20px; margin: 25px 0; border-radius: 5px;">
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #6b7280;"><strong>Email:</strong></p>
            <p style="margin: 0 0 20px 0; font-size: 16px; color: #111827;">{{ $email }}</p>
            
            <p style="margin: 0 0 10px 0; font-size: 14px; color: #6b7280;"><strong>New Password:</strong></p>
            <p style="margin: 0; font-size: 16px; color: #111827; font-family: 'Courier New', monospace; background: white; padding: 10px; border-radius: 5px; border: 1px solid #e5e7eb;">{{ $password }}</p>
        </div>
        
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 25px 0; border-radius: 5px;">
            <p style="margin: 0; font-size: 14px; color: #92400e;">
                <strong>⚠️ Security Notice:</strong> We recommend changing this password after logging in.
            </p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/admin') }}" style="display: inline-block; background: #f59e0b; color: white; padding: 14px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px;">
                Login to Your Account
            </a>
        </div>
        
        <p style="font-size: 14px; color: #6b7280; margin-top: 30px;">
            If you did not request this password reset, please contact the system administrator immediately.
        </p>
        
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #9ca3af; text-align: center; margin: 0;">
            © {{ date('Y') }} Kibo. All rights reserved.
        </p>
    </div>
</body>
</html>
