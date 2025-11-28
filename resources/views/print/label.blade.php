<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Print Label - {{ $jobsheet->jobsheet_id }}</title>
    <style>
        @page {
            size: 2.2in 1.18in;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            width: 2.0in;
            height: 1.18in;
            overflow: hidden;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 7pt;
            padding: 1mm 2mm;
            display: block;
            width: 2.0in;
            height: 1.18in;
        }
        .business-name {
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.25px;
            padding-bottom: 1mm;
            margin-bottom: 0.7mm;
            line-height: 1;
        }
        table.main-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-left: 2mm;
        }
        table.main-table td {
            vertical-align: top;
            padding: 0;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .left-col {
            width: 65%;
            padding-left: 1mm;
            padding-right: 1mm;
        }
        .right-col {
            width: 35%;
            text-align: center;
            vertical-align: middle;
        }
        .info-row {
            margin: 0.3mm 0;
            font-size: 6.5pt;
            line-height: 1.1;
        }
        .info-label {
            font-weight: bold;
            min-width: 25px;
            display: inline-block;
        }
        .pattern-img {
            width: 18mm;
            height: 18mm;
            object-fit: contain;
            display: inline-block;
            margin: 0 auto;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="business-name">UNITY MOBILES</div>
    <table class="main-table" role="presentation">
        <tr>
            <td class="left-col" role="cell">
                <div class="info-row"><span class="info-label"></span> JS: {{ $jobsheet->jobsheet_id }}</div>
                <div class="info-row"><span class="info-label"></span> Prb: {{ Str::limit($jobsheet->problem_description, 22, '') }}</div>
                <div class="info-row"><span class="info-label"></span> TCost: Rs. {{ number_format($jobsheet->estimated_cost, 0) }}</div>
                <div class="info-row"><span class="info-label"></span> Ph: {{ $jobsheet->customer->contact_no }}</div>
                <div class="info-row"><span class="info-label"></span> Pass: {{ $jobsheet->device_password ?? '-' }}</div>
            </td>
            <td class="right-col" role="cell">
                @if($jobsheet->pattern_image)
                    @if(str_starts_with($jobsheet->pattern_image, 'data:image'))
                        <img src="{{ $jobsheet->pattern_image }}" class="pattern-img" alt="Pattern" />
                    @else
                        <img src="{{ asset('storage/' . $jobsheet->pattern_image) }}" class="pattern-img" alt="Pattern" />
                    @endif
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
