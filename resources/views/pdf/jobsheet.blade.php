<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>JobSheet - {{ $jobSheet->jobsheet_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; }
        .section { margin-bottom: 15px; }
        .section-title { font-size: 14px; font-weight: bold; background: #f0f0f0; padding: 5px; margin-bottom: 10px; }
        .row { display: table; width: 100%; margin-bottom: 5px; }
        .col { display: table-cell; padding: 3px; }
        .col-label { font-weight: bold; width: 30%; }
        .footer { margin-top: 30px; border-top: 1px solid #000; padding-top: 10px; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Unity Mobile Repairing</h1>
        <p>Mobile Repair Service Center</p>
    </div>

    <div class="section">
        <div class="section-title">JobSheet Details</div>
        <div class="row">
            <div class="col col-label">JobSheet ID:</div>
            <div class="col">{{ $jobSheet->jobsheet_id }}</div>
            <div class="col col-label">Date:</div>
            <div class="col">{{ $jobSheet->created_at->format('d-M-Y h:i A') }}</div>
        </div>
        <div class="row">
            <div class="col col-label">Status:</div>
            <div class="col">{{ ucfirst(str_replace('_', ' ', $jobSheet->status)) }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Customer Information</div>
        <div class="row">
            <div class="col col-label">Customer ID:</div>
            <div class="col">{{ $jobSheet->customer->customer_id }}</div>
        </div>
        <div class="row">
            <div class="col col-label">Name:</div>
            <div class="col">{{ $jobSheet->customer->full_name }}</div>
        </div>
        <div class="row">
            <div class="col col-label">Contact:</div>
            <div class="col">{{ $jobSheet->customer->contact_no }}</div>
        </div>
        <div class="row">
            <div class="col col-label">Address:</div>
            <div class="col">{{ $jobSheet->customer->address }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Device Information</div>
        <div class="row">
            <div class="col col-label">Company:</div>
            <div class="col">{{ $jobSheet->company }}</div>
            <div class="col col-label">Model:</div>
            <div class="col">{{ $jobSheet->model }}</div>
        </div>
        <div class="row">
            <div class="col col-label">Color:</div>
            <div class="col">{{ $jobSheet->color }}</div>
            <div class="col col-label">Series:</div>
            <div class="col">{{ $jobSheet->series }}</div>
        </div>
        @if($jobSheet->imei)
        <div class="row">
            <div class="col col-label">IMEI:</div>
            <div class="col">{{ $jobSheet->imei }}</div>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Problem Description</div>
        <p>{{ $jobSheet->problem_description }}</p>
        
        <div class="row">
            <div class="col">
                @if($jobSheet->status_dead) ✓ Dead @endif
                @if($jobSheet->status_damage) ✓ Damage @endif
                @if($jobSheet->status_on) ✓ On with Problem @endif
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Device Condition</div>
        <div class="row">
            <div class="col col-label">Condition:</div>
            <div class="col">{{ ucfirst($jobSheet->device_condition) }}</div>
        </div>
        <div class="row">
            <div class="col col-label">Water Damage:</div>
            <div class="col">{{ ucfirst($jobSheet->water_damage) }}</div>
            <div class="col col-label">Physical Damage:</div>
            <div class="col">{{ ucfirst($jobSheet->physical_damage) }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Cost Details</div>
        <div class="row">
            <div class="col col-label">Estimated Cost:</div>
            <div class="col">₹{{ number_format($jobSheet->estimated_cost, 2) }}</div>
        </div>
        <div class="row">
            <div class="col col-label">Advance Paid:</div>
            <div class="col">₹{{ number_format($jobSheet->advance, 2) }}</div>
        </div>
        <div class="row">
            <div class="col col-label">Balance:</div>
            <div class="col"><strong>₹{{ number_format($jobSheet->balance, 2) }}</strong></div>
        </div>
    </div>

    @if($jobSheet->technician)
    <div class="section">
        <div class="section-title">Service Information</div>
        <div class="row">
            <div class="col col-label">Technician:</div>
            <div class="col">{{ $jobSheet->technician }}</div>
        </div>
        @if($jobSheet->location)
        <div class="row">
            <div class="col col-label">Location:</div>
            <div class="col">{{ $jobSheet->location }}</div>
        </div>
        @endif
    </div>
    @endif

    @if($jobSheet->remarks)
    <div class="section">
        <div class="section-title">Remarks</div>
        <p>{{ $jobSheet->remarks }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for choosing Unity Mobile Repairing</p>
        <p>This is a computer-generated document</p>
    </div>
</body>
</html>
