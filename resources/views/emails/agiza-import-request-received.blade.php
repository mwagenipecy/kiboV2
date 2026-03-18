<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Request Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">Import Request Received!</h1>
        <p style="color: #f0fdf4; margin: 10px 0 0 0; font-size: 16px;">We'll help you import your dream car</p>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello <strong>{{ $request->customer_name }}</strong>,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Thank you for submitting your import request! We have received your request and our team will review it shortly.
        </p>
        
        <div style="background: #f9fafb; border-left: 4px solid #10b981; padding: 20px; margin: 25px 0; border-radius: 4px;">
            <h2 style="margin: 0 0 15px 0; color: #10b981; font-size: 18px;">Request Details</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Request Number:</td>
                    <td style="padding: 8px 0; color: #111827;">{{ $request->request_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Vehicle:</td>
                    <td style="padding: 8px 0; color: #111827;">{{ $request->vehicle_make }} {{ $request->vehicle_model }} @if($request->vehicle_year)({{ $request->vehicle_year }})@endif</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Source Country:</td>
                    <td style="padding: 8px 0; color: #111827;">{{ $request->source_country }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Request Type:</td>
                    <td style="padding: 8px 0; color: #111827;">{{ $request->request_type === 'with_link' ? 'With Car Link' : 'Already Contacted Dealer' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Status:</td>
                    <td style="padding: 8px 0; color: #111827;">
                        <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 14px; font-weight: 600;">Pending Review</span>
                    </td>
                </tr>
            </table>
        </div>

        @if($request->vehicle_link)
        <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; margin: 25px 0; border-radius: 4px;">
            <p style="margin: 0; color: #1e40af; font-size: 14px;">
                <strong>Car Listing:</strong><br>
                <a href="{{ $request->vehicle_link }}" style="color: #2563eb; word-break: break-all;">{{ $request->vehicle_link }}</a>
            </p>
        </div>
        @endif
        
        <div style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 15px; margin: 25px 0; border-radius: 4px;">
            <p style="margin: 0; color: #065f46; font-size: 14px;">
                <strong>What's Next?</strong><br>
                Our team will review your request and get back to you within 24-48 hours with a detailed quotation including import costs, duties, and shipping.
            </p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('agiza-import.requests') }}" style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);">
                Track Your Request
            </a>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">
                If you have any questions or need assistance, please don't hesitate to contact our support team.
            </p>
            <p style="font-size: 14px; color: #6b7280; margin: 0;">
                <strong>Email:</strong> support@kibo.com<br>
                <strong>Phone:</strong> +255 XXX XXX XXX
            </p>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center;">
            <p style="font-size: 12px; color: #9ca3af; margin: 0;">
                © {{ date('Y') }} Kibo. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
