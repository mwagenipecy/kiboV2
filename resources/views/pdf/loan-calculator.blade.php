<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Loan Calculation Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1f2937;
            background: #fff;
        }

        .page {
            padding: 40px;
        }

        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #059669;
            padding-bottom: 20px;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
            text-align: right;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #059669;
            letter-spacing: -1px;
        }

        .tagline {
            color: #6b7280;
            font-size: 10px;
            margin-top: 3px;
        }

        .report-title {
            font-size: 14px;
            color: #374151;
            font-weight: bold;
        }

        .report-date {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* Highlight Box */
        .highlight-box {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
        }

        .highlight-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .highlight-value {
            font-size: 32px;
            font-weight: bold;
        }

        .highlight-subtext {
            font-size: 11px;
            opacity: 0.8;
            margin-top: 5px;
        }

        /* Section */
        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #d1fae5;
        }

        /* Two Column Layout */
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .column {
            display: table-cell;
            vertical-align: top;
            width: 48%;
        }

        .column:first-child {
            padding-right: 15px;
        }

        .column:last-child {
            padding-left: 15px;
        }

        /* Info Box */
        .info-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .info-box-title {
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            font-size: 12px;
        }

        /* Info Row */
        .info-row {
            display: table;
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px dashed #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            display: table-cell;
            color: #6b7280;
            width: 50%;
        }

        .info-value {
            display: table-cell;
            text-align: right;
            font-weight: 600;
            color: #1f2937;
            width: 50%;
        }

        .info-value.positive {
            color: #059669;
        }

        .info-value.negative {
            color: #dc2626;
        }

        /* Summary Box */
        .summary-box {
            background: #ecfdf5;
            border: 2px solid #059669;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }

        .summary-row {
            display: table;
            width: 100%;
        }

        .summary-label {
            display: table-cell;
            font-weight: bold;
            color: #047857;
        }

        .summary-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            color: #047857;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #059669;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }

        th:not(:first-child) {
            text-align: right;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }

        td:not(:first-child) {
            text-align: right;
        }

        tr:nth-child(even) {
            background: #f9fafb;
        }

        tr:hover {
            background: #ecfdf5;
        }

        /* Comparison Table */
        .comparison-table th {
            background: #1f2937;
        }

        .comparison-table .selected {
            background: #d1fae5;
            font-weight: bold;
        }

        /* Warning Box */
        .warning-box {
            background: #fffbeb;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 12px 15px;
            margin-top: 20px;
        }

        .warning-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
            font-size: 11px;
        }

        .warning-text {
            color: #78350f;
            font-size: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 9px;
        }

        .footer-note {
            margin-top: 10px;
            font-style: italic;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Chart visualization */
        .chart-bar {
            height: 20px;
            border-radius: 4px;
            margin: 5px 0;
        }

        .chart-principal {
            background: #059669;
        }

        .chart-interest {
            background: #dc2626;
        }

        .chart-legend {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .legend-item {
            display: table-cell;
            width: 50%;
            font-size: 10px;
        }

        .legend-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
            vertical-align: middle;
        }

        .legend-dot.principal {
            background: #059669;
        }

        .legend-dot.interest {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="logo">KIBO</div>
                <div class="tagline">Vehicle Marketplace</div>
            </div>
            <div class="header-right">
                <div class="report-title">Loan Calculation Report</div>
                <div class="report-date">Generated: {{ $generatedAt }}</div>
            </div>
        </div>

        <!-- Monthly Payment Highlight -->
        <div class="highlight-box">
            <div class="highlight-label">Your Estimated Monthly Payment</div>
            <div class="highlight-value">{{ $currency }} {{ number_format($monthlyPayment, 0) }}</div>
            <div class="highlight-subtext">per month for {{ $loanTerm }} months</div>
        </div>

        <!-- Two Column Layout -->
        <div class="two-column">
            <!-- Loan Details -->
            <div class="column">
                <div class="info-box">
                    <div class="info-box-title">Loan Details</div>
                    <div class="info-row">
                        <span class="info-label">Vehicle Price</span>
                        <span class="info-value">{{ $currency }} {{ number_format($vehiclePrice, 0) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Down Payment</span>
                        <span class="info-value positive">- {{ $currency }} {{ number_format($downPayment, 0) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Loan Amount</span>
                        <span class="info-value">{{ $currency }} {{ number_format($loanAmount, 0) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Loan Term</span>
                        <span class="info-value">{{ $loanTerm }} months ({{ $loanTerm / 12 }} years)</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Annual Interest Rate</span>
                        <span class="info-value">{{ number_format($interestRate, 2) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="column">
                <div class="info-box">
                    <div class="info-box-title">Payment Summary</div>
                    <div class="info-row">
                        <span class="info-label">Total Principal</span>
                        <span class="info-value">{{ $currency }} {{ number_format($loanAmount, 0) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Interest</span>
                        <span class="info-value negative">+ {{ $currency }} {{ number_format($totalInterest, 0) }}</span>
                    </div>
                    @if($processingFee > 0)
                    <div class="info-row">
                        <span class="info-label">Processing Fee</span>
                        <span class="info-value negative">+ {{ $currency }} {{ number_format($processingFee, 0) }}</span>
                    </div>
                    @endif
                    @if($registrationFee > 0)
                    <div class="info-row">
                        <span class="info-label">Registration Fee</span>
                        <span class="info-value negative">+ {{ $currency }} {{ number_format($registrationFee, 0) }}</span>
                    </div>
                    @endif
                </div>
                <div class="summary-box">
                    <div class="summary-row">
                        <span class="summary-label">Total Repayment</span>
                        <span class="summary-value">{{ $currency }} {{ number_format($totalPayment + $processingFee + $registrationFee, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interest vs Principal Visualization -->
        <div class="section">
            <div class="section-title">Principal vs Interest Breakdown</div>
            @php
                $principalPercent = ($loanAmount / $totalPayment) * 100;
                $interestPercent = ($totalInterest / $totalPayment) * 100;
            @endphp
            <div style="background: #f3f4f6; border-radius: 8px; overflow: hidden; height: 30px;">
                <div style="background: #059669; height: 100%; width: {{ $principalPercent }}%; float: left;"></div>
                <div style="background: #dc2626; height: 100%; width: {{ $interestPercent }}%; float: left;"></div>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <span class="legend-dot principal"></span>
                    Principal: {{ number_format($principalPercent, 1) }}% ({{ $currency }} {{ number_format($loanAmount, 0) }})
                </div>
                <div class="legend-item" style="text-align: right;">
                    <span class="legend-dot interest"></span>
                    Interest: {{ number_format($interestPercent, 1) }}% ({{ $currency }} {{ number_format($totalInterest, 0) }})
                </div>
            </div>
        </div>

        <!-- Loan Term Comparison -->
        @if(count($comparisonResults) > 0)
        <div class="section">
            <div class="section-title">Loan Term Comparison</div>
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Term</th>
                        <th>Monthly Payment</th>
                        <th>Total Interest</th>
                        <th>Total Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comparisonResults as $result)
                    <tr class="{{ $result['selected'] ? 'selected' : '' }}">
                        <td>{{ $result['term'] }} months ({{ $result['termYears'] }} yrs) {{ $result['selected'] ? '✓' : '' }}</td>
                        <td>{{ $currency }} {{ number_format($result['monthlyPayment'], 0) }}</td>
                        <td>{{ $currency }} {{ number_format($result['totalInterest'], 0) }}</td>
                        <td>{{ $currency }} {{ number_format($result['totalPayment'], 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($includeInsurance && $monthlyInsurance > 0)
        <!-- Insurance Info -->
        <div class="info-box" style="margin-top: 15px;">
            <div class="info-box-title">Insurance Estimate</div>
            <div class="info-row">
                <span class="info-label">Annual Insurance Rate</span>
                <span class="info-value">{{ number_format($insuranceRate, 1) }}%</span>
            </div>
            <div class="info-row">
                <span class="info-label">Monthly Insurance</span>
                <span class="info-value">{{ $currency }} {{ number_format($monthlyInsurance, 0) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Monthly (Loan + Insurance)</span>
                <span class="info-value" style="font-weight: bold; color: #059669;">{{ $currency }} {{ number_format($monthlyPayment + $monthlyInsurance, 0) }}</span>
            </div>
        </div>
        @endif

        <!-- Warning -->
        <div class="warning-box">
            <div class="warning-title">⚠️ Important Disclaimer</div>
            <div class="warning-text">
                This calculation is for estimation purposes only. Actual loan terms, interest rates, and monthly payments may vary based on your credit history, lender policies, and current market conditions. Please consult with a financial advisor or lending institution for accurate quotes.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>© {{ date('Y') }} KIBO Vehicle Marketplace. All rights reserved.</div>
            <div class="footer-note">This document is computer-generated and does not constitute a binding financial agreement.</div>
        </div>
    </div>

    <!-- Page 2: Amortization Schedule -->
    @if(count($amortizationSchedule) > 0)
    <div class="page-break"></div>
    <div class="page">
        <div class="header">
            <div class="header-left">
                <div class="logo">KIBO</div>
                <div class="tagline">Vehicle Marketplace</div>
            </div>
            <div class="header-right">
                <div class="report-title">Amortization Schedule</div>
                <div class="report-date">First 12 Months</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Monthly Payment Breakdown - First Year</div>
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Payment</th>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Remaining Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($amortizationSchedule as $row)
                    <tr>
                        <td>{{ $row['month'] }}</td>
                        <td>{{ $currency }} {{ number_format($row['payment'], 0) }}</td>
                        <td style="color: #059669;">{{ $currency }} {{ number_format($row['principal'], 0) }}</td>
                        <td style="color: #dc2626;">{{ $currency }} {{ number_format($row['interest'], 0) }}</td>
                        <td>{{ $currency }} {{ number_format($row['balance'], 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- First Year Summary -->
        @php
            $firstYearPrincipal = collect($amortizationSchedule)->sum('principal');
            $firstYearInterest = collect($amortizationSchedule)->sum('interest');
            $firstYearTotal = collect($amortizationSchedule)->sum('payment');
        @endphp
        <div class="summary-box" style="margin-top: 20px;">
            <div class="info-row">
                <span class="info-label">First Year Principal Paid</span>
                <span class="info-value positive">{{ $currency }} {{ number_format($firstYearPrincipal, 0) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">First Year Interest Paid</span>
                <span class="info-value negative">{{ $currency }} {{ number_format($firstYearInterest, 0) }}</span>
            </div>
            <div class="info-row" style="border-top: 2px solid #059669; padding-top: 10px;">
                <span class="info-label" style="font-weight: bold;">First Year Total Payments</span>
                <span class="info-value" style="font-size: 14px;">{{ $currency }} {{ number_format($firstYearTotal, 0) }}</span>
            </div>
        </div>

        <div class="footer">
            <div>© {{ date('Y') }} KIBO Vehicle Marketplace. All rights reserved.</div>
            <div class="footer-note">Page 2 of 2 - Amortization Schedule</div>
        </div>
    </div>
    @endif
</body>
</html>

