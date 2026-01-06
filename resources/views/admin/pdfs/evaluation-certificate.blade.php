<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vehicle Evaluation Certificate</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 40px;
            color: #333;
        }
        .certificate {
            border: 8px solid #10b981;
            padding: 40px;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 10px;
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 16px;
            color: #6b7280;
        }
        .certificate-number {
            text-align: center;
            font-size: 14px;
            color: #10b981;
            font-weight: bold;
            margin-bottom: 30px;
            padding: 10px;
            background: #f0fdf4;
            border-radius: 4px;
        }
        .content {
            margin: 30px 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #10b981;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-bottom: 2px solid #10b981;
            padding-bottom: 5px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            padding: 8px 0;
            width: 40%;
            font-weight: 600;
            color: #4b5563;
        }
        .info-value {
            display: table-cell;
            padding: 8px 0;
            color: #1f2937;
        }
        .valuation-box {
            background: #f0fdf4;
            border: 2px solid #10b981;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
            border-radius: 8px;
        }
        .valuation-label {
            font-size: 14px;
            color: #059669;
            margin-bottom: 5px;
        }
        .valuation-amount {
            font-size: 42px;
            font-weight: bold;
            color: #10b981;
        }
        .notes {
            background: #f9fafb;
            padding: 15px;
            border-left: 4px solid #10b981;
            margin: 20px 0;
            font-size: 12px;
            line-height: 1.6;
        }
        .validity {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
            border-radius: 4px;
        }
        .validity-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
        }
        .validity-date {
            color: #92400e;
            font-size: 16px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }
        .signature-line {
            border-top: 2px solid #000;
            margin-top: 50px;
            padding-top: 10px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(16, 185, 129, 0.05);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">OFFICIAL</div>
    
    <div class="certificate">
        <!-- Header -->
        <div class="header">
            <div class="logo">KIBO</div>
            <div class="title">Vehicle Evaluation Certificate</div>
            <div class="subtitle">Official Valuation Report</div>
        </div>

        <!-- Certificate Number -->
        <div class="certificate-number">
            Certificate No: {{ $order->completion_data['certificate_number'] }}
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Customer Information -->
            <div class="section">
                <div class="section-title">Certificate Issued To</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Customer Name:</div>
                        <div class="info-value">{{ $order->user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $order->user->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date Issued:</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($order->completion_data['issued_at'])->format('F j, Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="section">
                <div class="section-title">Vehicle Details</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Make & Model:</div>
                        <div class="info-value">{{ $order->vehicle->make->name }} {{ $order->vehicle->model->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Year:</div>
                        <div class="info-value">{{ $order->vehicle->year }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Mileage:</div>
                        <div class="info-value">{{ number_format($order->vehicle->mileage) }} miles</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Condition:</div>
                        <div class="info-value">{{ ucfirst($order->vehicle->condition) }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Transmission:</div>
                        <div class="info-value">{{ ucfirst($order->vehicle->transmission) }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Fuel Type:</div>
                        <div class="info-value">{{ ucfirst($order->vehicle->fuel_type) }}</div>
                    </div>
                </div>
            </div>

            <!-- Valuation Amount -->
            <div class="valuation-box">
                <div class="valuation-label">Current Market Valuation</div>
                <div class="valuation-amount">£{{ number_format($order->completion_data['valuation_amount'], 2) }}</div>
            </div>

            <!-- Report Notes -->
            @if(!empty($order->completion_data['report_notes']))
            <div class="section">
                <div class="section-title">Evaluation Notes</div>
                <div class="notes">
                    {{ $order->completion_data['report_notes'] }}
                </div>
            </div>
            @endif

            <!-- Validity Period -->
            <div class="validity">
                <div class="validity-title">Certificate Valid Until</div>
                <div class="validity-date">
                    {{ \Carbon\Carbon::parse($order->completion_data['valid_until'])->format('F j, Y') }}
                </div>
            </div>

            <!-- Signatures -->
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">
                        <strong>{{ $order->completion_data['issued_by'] }}</strong><br>
                        Authorized Evaluator
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        <strong>{{ \Carbon\Carbon::parse($order->completion_data['issued_at'])->format('F j, Y') }}</strong><br>
                        Date of Issue
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>IMPORTANT NOTICE:</strong> This valuation is provided for informational purposes only and is valid for 2 weeks from the date of issue. 
            Market conditions, vehicle condition, and other factors may affect actual selling price. This certificate should not be considered as a 
            guarantee of sale price or vehicle condition. The valuation is based on information provided at the time of assessment.</p>
            <p style="margin-top: 10px;">
                Certificate verification: Visit our website and enter certificate number {{ $order->completion_data['certificate_number'] }}<br>
                For inquiries, contact us at info@kibo.com | www.kibo.com
            </p>
        </div>
    </div>
</body>
</html>

