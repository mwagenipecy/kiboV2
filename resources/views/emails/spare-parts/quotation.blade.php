<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spare Part Quotation</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #009866 0%, #007a52 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">Quotation for Your Order</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0;">Order #{{ $order->order_number }}</p>
    </div>
    
    <div style="background: #fff; padding: 30px; border: 1px solid #e5e5e5; border-top: none;">
        <p style="margin-bottom: 20px;">Dear {{ $order->customer_name }},</p>
        
        <p>We have reviewed your spare part request and are pleased to provide you with the following quotation:</p>
        
        <div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h3 style="margin: 0 0 15px 0; color: #009866;">Order Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5;"><strong>Part Name:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5; text-align: right;">{{ $order->part_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5;"><strong>Vehicle:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5; text-align: right;">{{ $order->vehicleMake->name ?? 'N/A' }} {{ $order->vehicleModel->name ?? '' }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0;"><strong>Quoted Price:</strong></td>
                    <td style="padding: 12px 0; text-align: right; font-size: 24px; font-weight: bold; color: #009866;">{{ $order->currency }} {{ number_format($order->quoted_price, 2) }}</td>
                </tr>
            </table>
        </div>
        
        @if($order->quotation_notes)
        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
            <strong>Notes from Supplier:</strong>
            <p style="margin: 10px 0 0 0;">{{ $order->quotation_notes }}</p>
        </div>
        @endif
        
        <p style="margin-top: 20px;">To proceed with this order, please log in to your account and confirm the quotation.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('spare-parts.orders') }}" style="display: inline-block; background: #009866; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold;">View My Orders</a>
        </div>
        
        <p style="color: #666; font-size: 14px;">If you have any questions about this quotation, please don't hesitate to contact us.</p>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; border: 1px solid #e5e5e5; border-top: none;">
        <p style="margin: 0; color: #666; font-size: 12px;">© {{ date('Y') }} Kibo. All rights reserved.</p>
    </div>
</body>
</html>

