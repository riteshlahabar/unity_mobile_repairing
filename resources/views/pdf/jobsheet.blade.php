<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>JobSheet - {{ $jobSheet->jobsheet_id }}</title>
    <style>
    @page {
        margin-top: 3.1in;
        margin-right: 1.5in;
        margin-bottom: 2in;
        margin-left: 1in;
    }

    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 10px;
        margin: 0;
        padding: 0;
    }

    .jobsheet-id {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 15px;
        padding: 8px;
        border: 2px solid #000;
        display: inline-block;
        width: 200px;
        margin-left: calc(50% - 100px);
    }

    .main-container {
        display: table;
        width: 100%;
        margin-bottom: 10px;
    }

    .left-column {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-right: 15px;
    }

    .right-column {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        padding-left: 15px;
    }

    .info-row {
        margin-bottom: 4px;
        display: flex;
    }

    .info-label {
        font-weight: bold;
        min-width: 45%;
        display: inline-block;
    }

    .info-value {
        display: inline-block;
    }

    .section-title {
        font-size: 11px;
        font-weight: bold;
        margin-top: 12px;
        margin-bottom: 6px;
        text-decoration: underline;
    }

    .qr-box {
        text-align: center;
        margin-top: 10px;
    }

    .date-box {
        text-align: center;
        margin-bottom: 10px;
    }

    .checkbox-item {
        display: inline-block;
        margin-right: 15px;
    }

    .sign-row {
        display: table;
        width: 100%;
        margin-top: 25px;
    }

    .signature-box {
        display: table-cell;
        width: 50%;
        text-align: center;
        vertical-align: bottom;
    }

    .checkbox-row {
        margin-top: 10px;
        margin-bottom: 15px;
    }
    </style>
</head>

<body>

    <!-- JobSheet ID - Centered -->
    <div class="jobsheet-id">
        {{ $jobSheet->jobsheet_id }}
    </div>

    <!-- Two Column Layout -->
    <div class="main-container">
        
        <!-- LEFT COLUMN -->
        <div class="left-column">
            
            <!-- Customer Information -->
            <div class="info-row">
                <span class="info-label">Customer Name</span>
                <span class="info-value">: {{ $jobSheet->customer->full_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact No.</span>
                <span class="info-value">: {{ $jobSheet->customer->contact_no }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Address</span>
                <span class="info-value">: {{ $jobSheet->customer->address }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Customer ID</span>
                <span class="info-value">: {{ $jobSheet->customer->customer_id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Alternate No.</span>
                <span class="info-value">: {{ $jobSheet->customer->alternate_no ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">WhatsApp No.</span>
                <span class="info-value">: {{ $jobSheet->customer->whatsapp_no ?? '-' }}</span>
            </div>

            <!-- Device Information -->
            <div class="section-title">Device Info.</div>
            <div class="info-row">
                <span class="info-label">Company</span>
                <span class="info-value">: {{ $jobSheet->company }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Series</span>
                <span class="info-value">: {{ $jobSheet->series }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Model</span>
                <span class="info-value">: {{ $jobSheet->model }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Color</span>
                <span class="info-value">: {{ $jobSheet->color }}</span>
            </div>

            <!-- Problem Description -->
            <div class="section-title">Problem Description</div>
            <div style="margin-bottom: 6px;">{{ $jobSheet->problem_description }}</div>
            <div>
                <span class="checkbox-item">Dead @if($jobSheet->status_dead) &#x2713; @else &#x2717; @endif</span>
                <span class="checkbox-item">Damage @if($jobSheet->status_damage) &#x2713; @else &#x2717; @endif</span>
                <span class="checkbox-item">On with Problem @if($jobSheet->status_on) &#x2713; @else &#x2717; @endif</span>
            </div>

            <!-- Service Info -->
            <div class="section-title">Service Info.</div>
            <div class="info-row">
                <span class="info-label">Jobsheet Made by</span>
                <span class="info-value">: {{ $jobSheet->technician ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Location</span>
                <span class="info-value">: {{ $jobSheet->location ?? '-' }}</span>
            </div>

            <!-- Cost Details -->
            <div class="section-title">Cost Details</div>
            <div class="info-row">
                <span class="info-label">Estimate Cost</span>
                <span class="info-value">: Rs. {{ number_format($jobSheet->estimated_cost, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Advance</span>
                <span class="info-value">: Rs. {{ number_format($jobSheet->advance, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Balance</span>
                <span class="info-value">: Rs. {{ number_format($jobSheet->balance, 2) }}</span>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="right-column">
            
            <!-- Date -->
            <div class="date-box">
                <span class="Date-label">Date: </span>
                <strong>{{ $jobSheet->created_at->format('d/m/Y') }}</strong>
            </div>

            
            <!-- QR Code -->
<div class="qr-box">
    <div style="margin-bottom: 5px; font-size: 10px; font-weight: bold;">Scan & Pay with any UPI App</div>
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/qrcodes/QR_Code.jpg'))) }}" alt="QR" style="width: 100px; height: 100px; border: 1px solid #000;">
    <div style="margin-top: 5px; font-size: 9px;">UPI ID : Q483834401@ybl</div>
</div>

            <!-- Device Details -->
            <div class="section-title">Device Details</div>
            <div class="info-row">
                <span class="info-label">Condition</span>
                <span class="info-value">: {{ ucfirst($jobSheet->device_condition) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Water Damage</span>
                <span class="info-value">: {{ ucfirst($jobSheet->water_damage) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Physical Damage</span>
                <span class="info-value">: {{ ucfirst($jobSheet->physical_damage) }}</span>
            </div>

            <!-- Accessories Info -->
            <div class="section-title">Accessories Info.</div>
            <div>
                <span class="checkbox-item">Sim Tray @if($jobSheet->accessory_sim_tray) &#x2713; @else &#x2717; @endif</span>
                <span class="checkbox-item">Sim Card @if($jobSheet->accessory_sim_card) &#x2713; @else &#x2717; @endif</span>
            </div>
            <div>
                <span class="checkbox-item">Me. Card @if($jobSheet->accessory_memory_card) &#x2713; @else &#x2717; @endif</span>
                <span class="checkbox-item">Mo. Cover @if($jobSheet->accessory_mobile_cover) &#x2713; @else &#x2717; @endif</span>
            </div>

            <!-- Security Info -->
            <div class="section-title">Security Info.</div>
            <div class="info-row">
                <span class="info-label">Device Password</span>
                <span class="info-value">: {{ $jobSheet->device_password ?? '-' }}</span>
            </div>
            @if($jobSheet->pattern_image)
            <div class="info-row">
                <span class="info-label">Device Pattern</span>
            </div>
            <div style="text-align: center; margin-top: 5px;">
                <img src="{{ $jobSheet->pattern_image }}" alt="Pattern" style="max-width: 100px; height: auto; border: 1px solid #ccc;">
            </div>
            @endif

        </div>

    </div>

   
    <!-- Signatures 
    <div class="sign-row" style="margin-top: 50px;">
    <div class="signature-box" style="text-align: left;">
        Customer Sign.
    </div>
    <div class="signature-box" style="text-align: right;">
        For {{ $businessInfo->business_name ?? 'Business Name' }}
    </div>
</div>
-->

</body>

</html>
