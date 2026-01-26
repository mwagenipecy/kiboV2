<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Has Been Shipped</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #009866 0%, #007a52 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">📦 Your Order Has Been Shipped!</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0;">Order #{{ $order->order_number }}</p>
    </div>
    
    <div style="background: #fff; padding: 30px; border: 1px solid #e5e5e5; border-top: none;">
        <p style="margin-bottom: 20px;">Dear {{ $order->customer_name }},</p>
        
        <p>Your spare part order has been shipped and is on its way to you!</p>
        
        <div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h3 style="margin: 0 0 15px 0; color: #009866;">Shipping Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5;"><strong>Part Name:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5; text-align: right;">{{ $order->part_name }}</td>
                </tr>
                @if($order->tracking_number)
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5;"><strong>Tracking Number:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5; text-align: right; font-family: monospace; font-size: 14px;">{{ $order->tracking_number }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5;"><strong>Shipped On:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e5e5; text-align: right;">{{ $order->shipped_at->format('M d, Y h:i A') }}</td>
                </tr>
                @if($order->estimated_delivery_date)
                <tr>
                    <td style="padding: 8px 0;"><strong>Est. Delivery:</strong></td>
                    <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #009866;">{{ $order->estimated_delivery_date->format('M d, Y') }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        <div style="background: #e3f2fd; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #1565c0;">Delivery Address</h4>
            <p style="margin: 0; color: #333;">
                {{ $order->delivery_address }}<br>
                {{ $order->delivery_city }}, {{ $order->delivery_region }}<br>
                {{ $order->delivery_country }}
            </p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('spare-parts.orders') }}" style="display: inline-block; background: #009866; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold;">Track My Order</a>
        </div>
        
        <p style="color: #666; font-size: 14px;">If you have any questions about your delivery, please don't hesitate to contact us.</p>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; border: 1px solid #e5e5e5; border-top: none;">
        <p style="margin: 0; color: #666; font-size: 12px;">© {{ date('Y') }} Kibo. All rights reserved.</p>
    </div>
</body>
</html>

