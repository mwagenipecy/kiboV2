<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Exchange Quotation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #009866; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="margin: 0;">Car Exchange Quotation</h1>
    </div>
    
    <div style="background-color: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <p>Dear {{ $quotation->exchangeRequest->customer_name }},</p>
        
        <p>Thank you for your car exchange request. We have reviewed your request and are pleased to provide you with the following quotation:</p>
        
        <div style="background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h2 style="color: #009866; margin-top: 0;">Quotation Details</h2>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Your Current Vehicle Valuation:</td>
                    <td style="padding: 8px 0; text-align: right;">{{ number_format($quotation->current_vehicle_valuation, 2) }} {{ $quotation->currency }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Desired Vehicle Price:</td>
                    <td style="padding: 8px 0; text-align: right;">{{ number_format($quotation->desired_vehicle_price, 2) }} {{ $quotation->currency }}</td>
                </tr>
                <tr style="border-top: 2px solid #e5e7eb;">
                    <td style="padding: 8px 0; font-weight: bold; color: #009866;">Amount to Pay:</td>
                    <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #009866;">
                        {{ number_format($quotation->price_difference, 2) }} {{ $quotation->currency }}
                    </td>
                </tr>
            </table>
        </div>

        @if($quotation->offeredVehicle)
        <div style="background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="color: #009866; margin-top: 0;">Vehicle Offered</h3>
            <p><strong>{{ $quotation->offeredVehicle->make->name }} {{ $quotation->offeredVehicle->model->name }}</strong></p>
            <p>Year: {{ $quotation->offeredVehicle->year }}</p>
            <p>Price: {{ number_format($quotation->offeredVehicle->price, 2) }} {{ $quotation->offeredVehicle->currency }}</p>
        </div>
        @endif

        @if($quotation->message)
        <div style="background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="color: #009866; margin-top: 0;">Message from Dealer</h3>
            <p>{{ $quotation->message }}</p>
        </div>
        @endif

        <div style="background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="color: #009866; margin-top: 0;">Dealer Information</h3>
            <p><strong>{{ $quotation->entity->name }}</strong></p>
            @if($quotation->entity->phone)
                <p>Phone: {{ $quotation->entity->phone }}</p>
            @endif
            @if($quotation->entity->email)
                <p>Email: {{ $quotation->entity->email }}</p>
            @endif
            @if($quotation->entity->address)
                <p>Address: {{ $quotation->entity->address }}</p>
            @endif
        </div>

        <p style="margin-top: 30px;">If you have any questions or would like to proceed with this exchange, please contact us using the information above.</p>
        
        <p>Best regards,<br>{{ $quotation->entity->name }}</p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #6b7280; font-size: 12px;">
        <p>This is an automated email from Kibo Auto Exchange System.</p>
    </div>
</body>
</html>

