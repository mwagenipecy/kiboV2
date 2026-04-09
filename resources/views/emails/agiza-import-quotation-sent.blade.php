<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Quotation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <img src="{{ asset('logo/white.png') }}" alt="Kibo" style="height: 50px; width: auto; margin-bottom: 15px;" />
        <h1 style="color: white; margin: 0; font-size: 28px;">Import Quotation Ready!</h1>
        <p style="color: #f0fdf4; margin: 10px 0 0 0; font-size: 16px;">Your import quote is ready for review</p>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello <strong>{{ $request->customer_name }}</strong>,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Great news! We've prepared a quotation for your import request. Here are the details:
        </p>
        
        <div style="background: #f9fafb; border-left: 4px solid #10b981; padding: 20px; margin: 25px 0; border-radius: 4px;">
            <h2 style="margin: 0 0 15px 0; color: #10b981; font-size: 18px;">Request Details</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Request Number:</td>
                    <td style="padding: 8px 0; color: #111827;">{{ $request->request_number }}</td>
                </tr>
                @if($request->vehicle_link)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Listing:</td>
                    <td style="padding: 8px 0; color: #111827;"><a href="{{ $request->vehicle_link }}" style="color: #059669; word-break: break-all;">{{ $request->vehicle_link }}</a></td>
                </tr>
                @endif
                @php $vehicleLabel = trim(($request->vehicle_make ?? '').' '.($request->vehicle_model ?? '')); @endphp
                @if($vehicleLabel !== '')
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Vehicle:</td>
                    <td style="padding: 8px 0; color: #111827;">{{ $vehicleLabel }}@if($request->vehicle_year) ({{ $request->vehicle_year }})@endif</td>
                </tr>
                @endif
                @if(filled($request->source_country))
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Source Country:</td>
                    <td style="padding: 8px 0; color: #111827;">{{ $request->source_country }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; margin: 25px 0; border-radius: 4px;">
            <h2 style="margin: 0 0 15px 0; color: #1e40af; font-size: 18px;">Quotation Breakdown</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #6b7280;">Import Cost:</td>
                    <td style="padding: 8px 0; color: #111827; text-align: right;">{{ number_format($request->quoted_import_cost, 2) }} {{ $request->quote_currency }}</td>
                </tr>
                <tr style="border-top: 2px solid #dbeafe;">
                    <td style="padding: 12px 0; font-weight: bold; color: #111827; font-size: 16px;">Total Cost:</td>
                    <td style="padding: 12px 0; color: #10b981; text-align: right; font-size: 18px; font-weight: bold;">{{ number_format($request->quoted_total_cost, 2) }} {{ $request->quote_currency }}</td>
                </tr>
            </table>
        </div>

        <div style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 15px; margin: 25px 0; border-radius: 4px;">
            <p style="margin: 0; color: #065f46; font-size: 14px;">
                <strong>What's Included:</strong><br>
                • Vehicle purchase assistance<br>
                • Import duties and taxes<br>
                • Shipping and logistics<br>
                • Documentation and clearance<br>
                • Delivery to your location in Tanzania
            </p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('agiza-import.requests') }}" style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);">
                View Full Details
            </a>
        </div>
        
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 25px 0; border-radius: 4px;">
            <p style="margin: 0; color: #92400e; font-size: 14px;">
                <strong>Note:</strong> This quotation is valid for 7 days. Please review and let us know if you'd like to proceed.
            </p>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">
                If you have any questions about this quotation or need clarification, please contact us.
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
