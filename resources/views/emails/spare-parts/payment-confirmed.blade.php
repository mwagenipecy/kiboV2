<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #009866 0%, #007a52 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <div style="background: white; width: 60px; height: 60px; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
            <svg style="width: 30px; height: 30px; color: #009866;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 style="color: white; margin: 0; font-size: 24px;">Payment Confirmed!</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0;">Order #{{ $order->order_number }}</p>
    </div>
    
    <div style="background: #fff; padding: 30px; border: 1px solid #e5e5e5; border-top: none;">
        <p style="margin-bottom: 20px;">Dear {{ $order->customer_name }},</p>
        
        <p>Great news! Your payment has been verified and your order is now being prepared.</p>
        
        <div style="background: #d4edda; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center;">
            <h3 style="margin: 0 0 10px 0; color: #155724;">Payment Verified Successfully</h3>
            <p style="margin: 0; color: #155724; font-size: 24px; font-weight: bold;">{{ $order->currency }} {{ number_format($order->quoted_price, 2) }}</p>
        </div>
        
        <div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h3 style="margin: 0 0 15px 0; color: #333;">What's Next?</h3>
            <ul style="margin: 0; padding-left: 20px;">
                <li style="margin-bottom: 10px;">Your spare part is being prepared for shipment</li>
                <li style="margin-bottom: 10px;">You will receive a shipping notification with tracking details</li>
                @if($order->estimated_delivery_date)
                <li>Estimated delivery: <strong>{{ $order->estimated_delivery_date->format('M d, Y') }}</strong></li>
                @endif
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('spare-parts.orders') }}" style="display: inline-block; background: #009866; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold;">Track My Order</a>
        </div>
        
        <p style="color: #666; font-size: 14px;">Thank you for your order. We appreciate your business!</p>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; border: 1px solid #e5e5e5; border-top: none;">
        <p style="margin: 0; color: #666; font-size: 12px;">© {{ date('Y') }} Kibo. All rights reserved.</p>
    </div>
</body>
</html>

