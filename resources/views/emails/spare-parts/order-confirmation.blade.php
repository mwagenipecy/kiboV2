<x-mail::message>
# Spare Part Order Confirmation

Hello {{ $order->customer_name }},

Thank you for your order! Your spare part order has been successfully created.

## Order Details

**Order Number:** {{ $order->order_number }}  
**Status:** {{ $order->status_label }}  
**Date:** {{ $order->created_at->format('F d, Y \a\t g:i A') }}

## Vehicle Information

**Make:** {{ $order->vehicleMake->name }}  
**Model:** {{ $order->vehicleModel->name }}  
**Part Name:** {{ $order->part_name }}

@if($order->description)
**Description:** {{ $order->description }}
@endif

## Delivery Information

**Delivery Address:** {{ $order->delivery_address }}  
@if($order->delivery_city)
**City:** {{ $order->delivery_city }}
@endif
@if($order->delivery_region)
**Region:** {{ $order->delivery_region }}
@endif
**Country:** {{ $order->delivery_country }}

## Contact Information

**Name:** {{ $order->contact_name }}  
**Phone:** {{ $order->contact_phone }}  
**Email:** {{ $order->contact_email }}

<x-mail::button :url="$orderUrl">
View Order Details
</x-mail::button>

<x-mail::panel>
**What's Next?**

Your order is now being processed. Our team will review your order and contact you soon with a quotation. You will receive an email notification once your order has been quoted.

If you have any questions or need to make changes to your order, please contact us using the information provided above.
</x-mail::panel>

Thank you for choosing Kibo!

Best regards,<br>
{{ config('app.name') }} Team
</x-mail::message>

