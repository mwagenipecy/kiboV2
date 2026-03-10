<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitation Request Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #009866; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="color: white; margin: 0;">Kibo Auto</h1>
    </div>

    <div style="background-color: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <h2 style="color: #009866; margin-top: 0;">Request received</h2>

        <p>Hello {{ $visitation->name }},</p>

        <p>We have received your request to visit the vehicle. Our team will contact you shortly to arrange a suitable time and location.</p>

        <div style="background-color: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #009866;">
            <p style="margin: 0 0 8px;"><strong>Vehicle:</strong> {{ $visitation->vehicle->make?->name }} {{ $visitation->vehicle->model?->name }} ({{ $visitation->vehicle->year }})</p>
            @if($visitation->visit_reason)
            <p style="margin: 0 0 8px;"><strong>Reason:</strong> {{ $visitation->visit_reason }}</p>
            @endif
        </div>

        <p>You will receive another email once your visitation is scheduled with the date, time, and location.</p>

        <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
            Best regards,<br>
            <strong>Kibo Auto Team</strong>
        </p>
    </div>
</body>
</html>
