<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation for Your Garage Service Order</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #009866; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="color: white; margin: 0;">Kibo Auto</h1>
    </div>
    
    <div style="background-color: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <h2 style="color: #009866; margin-top: 0;">Quotation for Your Service Order</h2>
        
        <p>Hello {{ $order->customer_name }},</p>
        
        <p>Thank you for your garage service request. We have reviewed your order and are pleased to provide you with a quotation.</p>
        
        <div style="background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #009866;">
            <h3 style="margin-top: 0; color: #009866;">Order Details</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Service Type:</strong> {{ ucwords(str_replace('_', ' ', $order->service_type)) }}</p>
            <p><strong>Garage:</strong> {{ $order->agent->company_name ?? $order->agent->name ?? 'N/A' }}</p>
            @if($order->booking_type === 'scheduled' && $order->scheduled_date)
            <p><strong>Scheduled Date:</strong> {{ \Carbon\Carbon::parse($order->scheduled_date)->format('M d, Y') }}
                @if($order->scheduled_time)
                at {{ \Carbon\Carbon::parse($order->scheduled_time)->format('h:i A') }}
                @endif
            </p>
            @endif
        </div>
        
        <div style="background-color: #fef3c7; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f59e0b;">
            <h3 style="margin-top: 0; color: #92400e;">Quotation</h3>
            <p style="font-size: 24px; font-weight: bold; color: #009866; margin: 10px 0;">
                {{ $order->currency }} {{ number_format($order->quoted_price, 2) }}
            </p>
            @if($order->quotation_notes)
            <p style="margin-top: 15px;"><strong>Notes:</strong></p>
            <p style="white-space: pre-line;">{{ $order->quotation_notes }}</p>
            @endif
        </div>
        
        <p>Please review the quotation above. If you have any questions or would like to proceed with the service, please contact us.</p>
        
        <p style="margin-top: 30px;">
            <strong>Contact Information:</strong><br>
            @if($order->agent->phone_number)
            Phone: {{ $order->agent->phone_number }}<br>
            @endif
            @if($order->agent->email)
            Email: {{ $order->agent->email }}
            @endif
        </p>
        
        <p style="margin-top: 30px;">Thank you for choosing Kibo Auto!</p>
        
        <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">
            Best regards,<br>
            <strong>Kibo Auto Team</strong>
        </p>
    </div>
</body>
</html>

