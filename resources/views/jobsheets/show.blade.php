@extends('layouts.app')

@section('title', 'JobSheet Details | Mifty')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">JobSheet Details</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('jobsheets.index') }}">JobSheets</a></li>
                        <li class="breadcrumb-item active">{{ $jobSheet->jobsheet_id }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- JobSheet Header Card -->
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">{{ $jobSheet->jobsheet_id }}</h4>
                            <p class="text-muted mb-0">Created on {{ $jobSheet->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if($jobSheet->status == 'in_progress')
                            <span class="badge bg-warning fs-6 px-3 py-2">
                                <i class="iconoir-clock me-1"></i>In Progress
                            </span>
                            @elseif($jobSheet->status == 'completed')
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="iconoir-check-circle me-1"></i>Completed
                            </span>
                            @elseif($jobSheet->status == 'delivered')
                            <span class="badge bg-info fs-6 px-3 py-2">
                                <i class="iconoir-box me-1"></i>Delivered
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="las la-user me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="fw-bold" width="40%">Customer ID:</td>
                                    <td>{{ $jobSheet->customer->customer_id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Full Name:</td>
                                    <td>{{ $jobSheet->customer->full_name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Contact Number:</td>
                                    <td>{{ $jobSheet->customer->contact_no }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="fw-bold" width="40%">WhatsApp Number:</td>
                                    <td>{{ $jobSheet->customer->whatsapp_no }}</td>
                                </tr>
                                @if($jobSheet->customer->alternate_no)
                                <tr>
                                    <td class="fw-bold">Alternate Number:</td>
                                    <td>{{ $jobSheet->customer->alternate_no }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-bold">Address:</td>
                                    <td>{{ $jobSheet->customer->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="las la-mobile me-2"></i>Device Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="fw-bold" width="40%">Company:</td>
                                    <td>{{ $jobSheet->company }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Model:</td>
                                    <td>{{ $jobSheet->model }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Color:</td>
                                    <td>{{ $jobSheet->color }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Series:</td>
                                    <td>{{ $jobSheet->series }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                @if($jobSheet->imei)
                                <tr>
                                    <td class="fw-bold" width="40%">IMEI:</td>
                                    <td>{{ $jobSheet->imei }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-bold">Device Condition:</td>
                                    <td><span
                                            class="badge bg-primary-subtle text-primary">{{ ucfirst($jobSheet->device_condition) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Water Damage:</td>
                                    <td><span
                                            class="badge bg-info-subtle text-info">{{ ucfirst($jobSheet->water_damage) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Physical Damage:</td>
                                    <td><span
                                            class="badge bg-warning-subtle text-warning">{{ ucfirst($jobSheet->physical_damage) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Problem & Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="las la-exclamation-triangle me-2"></i>Problem Description</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">{{ $jobSheet->problem_description }}</p>

                    <h6 class="mb-2">Device Status:</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($jobSheet->status_dead)
                        <span class="badge bg-danger">Dead</span>
                        @endif
                        @if($jobSheet->status_damage)
                        <span class="badge bg-warning">Damaged</span>
                        @endif
                        @if($jobSheet->status_on)
                        <span class="badge bg-success">On with Problem</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Accessories -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="las la-plug me-2"></i>Accessories</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i
                                        class="las la-{{ $jobSheet->accessory_sim_tray ? 'check-circle text-success' : 'times-circle text-danger' }} me-2"></i>
                                    SIM Tray
                                </li>
                                <li class="mb-2">
                                    <i
                                        class="las la-{{ $jobSheet->accessory_sim_card ? 'check-circle text-success' : 'times-circle text-danger' }} me-2"></i>
                                    SIM Card
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i
                                        class="las la-{{ $jobSheet->accessory_memory_card ? 'check-circle text-success' : 'times-circle text-danger' }} me-2"></i>
                                    Memory Card
                                </li>
                                <li class="mb-2">
                                    <i
                                        class="las la-{{ $jobSheet->accessory_mobile_cover ? 'check-circle text-success' : 'times-circle text-danger' }} me-2"></i>
                                    Mobile Cover
                                </li>
                            </ul>
                        </div>
                    </div>
                    @if($jobSheet->other_accessories)
                    <hr>
                    <p class="mb-0"><strong>Other Accessories:</strong> {{ $jobSheet->other_accessories }}</p>
                    @endif
                </div>
            </div>

            <!-- Security Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="las la-lock me-2"></i>Security Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($jobSheet->device_password)
                            <div class="mb-3">
                                <label class="fw-bold">Device Password:</label>
                                <p class="mb-0">
                                    <span
                                        class="badge bg-secondary fs-6 px-3 py-2">{{ $jobSheet->device_password }}</span>
                                </p>
                            </div>
                            @else
                            <p class="text-muted">No device password provided</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($jobSheet->pattern_image)
                            <div class="mb-3">
                                <label class="fw-bold">Pattern Lock:</label>
                                <div class="mt-2">
                                    @if(str_starts_with($jobSheet->pattern_image, 'data:image'))
                                    {{-- Base64 encoded image --}}
                                    <img src="{{ $jobSheet->pattern_image }}" alt="Pattern Lock"
                                        class="img-fluid border rounded" style="max-width: 200px;">
                                    @else
                                    {{-- File path --}}
                                    <img src="{{ asset('storage/' . $jobSheet->pattern_image) }}" alt="Pattern Lock"
                                        class="img-fluid border rounded" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>
                            @else
                            <p class="text-muted">No pattern lock image provided</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Photos -->
            @if($jobSheet->devicePhotos && $jobSheet->devicePhotos->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="las la-images me-2"></i>Device Photos
                        ({{ $jobSheet->devicePhotos->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($jobSheet->devicePhotos as $photo)
                        <div class="col-md-3 col-sm-6">
                            @php
                            $photoPath = $photo->photo_path;
                            // Check different possible paths
                            if (file_exists(public_path($photoPath))) {
                            $imageUrl = asset($photoPath);
                            } elseif (file_exists(storage_path('app/public/' . $photoPath))) {
                            $imageUrl = asset('storage/' . $photoPath);
                            } elseif (file_exists(storage_path('app/public/device_photos/' . $photoPath))) {
                            $imageUrl = asset('storage/device_photos/' . $photoPath);
                            } else {
                            $imageUrl = asset('storage/' . $photoPath);
                            }
                            @endphp
                            <a href="{{ $imageUrl }}" target="_blank">
                                <img src="{{ $imageUrl }}" alt="Device Photo {{ $loop->iteration }}"
                                    class="img-fluid rounded border"
                                    style="width: 100%; height: 200px; object-fit: cover;"
                                    onerror="this.src='{{ asset('assets/images/no-image.png') }}'; this.onerror=null;">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="las la-images me-2"></i>Device Photos</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">No device photos uploaded</p>
                </div>
            </div>
            @endif


            <!-- Service Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="las la-tools me-2"></i>Service Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                @if($jobSheet->technician)
                                <tr>
                                    <td class="fw-bold" width="40%">Technician:</td>
                                    <td>{{ $jobSheet->technician }}</td>
                                </tr>
                                @endif
                                @if($jobSheet->location)
                                <tr>
                                    <td class="fw-bold">Location:</td>
                                    <td>{{ $jobSheet->location }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                @if($jobSheet->delivered_date)
                                <tr>
                                    <td class="fw-bold" width="40%">Delivered Date:</td>
                                    <td>{{ \Carbon\Carbon::parse($jobSheet->delivered_date)->format('d M Y') }}</td>
                                </tr>
                                @endif
                                @if($jobSheet->delivered_time)
                                <tr>
                                    <td class="fw-bold">Delivered Time:</td>
                                    <td>{{ \Carbon\Carbon::parse($jobSheet->delivered_time)->format('h:i A') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cost Details -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="las la-rupee-sign me-2"></i>Cost Details</h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <td class="fw-bold" width="30%">Estimated Cost:</td>
                <td class="text-end"><h5 class="mb-0">₹{{ number_format($jobSheet->estimated_cost, 2) }}</h5></td>
            </tr>
            <tr>
                <td class="fw-bold">Advance Paid:</td>
                <td class="text-end"><h5 class="mb-0 text-success">₹{{ number_format($jobSheet->advance, 2) }}</h5></td>
            </tr>
            @if($jobSheet->status != 'delivered')
            <tr>
                <td class="fw-bold">Balance:</td>
                <td class="text-end"><h5 class="mb-0 text-warning">₹{{ number_format($jobSheet->balance, 2) }}</h5></td>
            </tr>
            @endif
            @if($jobSheet->status == 'delivered')
                @if($jobSheet->discount > 0)
                <tr>
                    <td class="fw-bold">Discount:</td>
                    <td class="text-end"><h5 class="mb-0 text-danger">- ₹{{ number_format($jobSheet->discount, 2) }}</h5></td>
                </tr>
                <tr class="border-top">
                    <td class="fw-bold">Final Amount Paid:</td>
                    <td class="text-end"><h4 class="mb-0 text-primary">₹{{ number_format($jobSheet->final_amount ?? $jobSheet->balance, 2) }}</h4></td>
                </tr>
                @else
                <tr class="border-top">
                    <td class="fw-bold" colspan="2" class="text-center">
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="iconoir-check-circle me-2"></i>
                            <strong>Balance ₹{{ number_format($jobSheet->balance, 2) }} Paid Successfully</strong>
                        </span>
                    </td>
                </tr>
                @endif
            @endif
        </table>
    </div>
</div>

<!-- Remarks & Terms -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="las la-file-alt me-2"></i>Additional Information</h5>
    </div>
    <div class="card-body">
        @if($jobSheet->remarks)
        <div class="mb-3">
            <h6>Remarks:</h6>
            <p class="text-muted mb-0">{{ $jobSheet->remarks }}</p>
        </div>
        @endif
        
        @if($jobSheet->terms_conditions)
        <div class="mb-3">
            <h6>Terms & Conditions:</h6>
            <div class="alert alert-dark mb-0">
                {{ $jobSheet->terms_conditions }}
            </div>
        </div>
        @endif

        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" 
                   id="jobsheetRequired" 
                   {{ $jobSheet->jobsheet_required ? 'checked' : '' }} 
                   disabled>
            <label class="form-check-label fw-bold" for="jobsheetRequired">
                Without jobsheet mobile not obtained.
            </label>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="card">
    <div class="card-body">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="las la-home me-1"></i>Back to Dashboard
            </a>
            <a href="{{ route('jobsheets.index') }}" class="btn btn-secondary">
                <i class="las la-arrow-left me-1"></i>Back to JobSheet List
            </a>
            <a href="{{ route('jobsheets.edit', $jobSheet->jobsheet_id) }}" class="btn btn-primary">
                <i class="las la-edit me-1"></i>Edit JobSheet
            </a>
            <a href="{{ route('jobsheets.downloadPDF', $jobSheet->jobsheet_id) }}" class="btn btn-success">
                <i class="las la-download me-1"></i>Download PDF
            </a>
            <button type="button" class="btn btn-info" onclick="window.print()">
                <i class="las la-print me-1"></i>Print JobSheet
            </button>
            
            @if($jobSheet->status == 'in_progress')
            <form action="{{ route('jobsheets.markComplete', $jobSheet->jobsheet_id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="las la-check-circle me-1"></i>Mark Complete
                </button>
            </form>
            @endif
            
            @if($jobSheet->status == 'completed')
            <button type="button" class="btn btn-warning" onclick="alert('Use dashboard to mark as delivered')">
                <i class="las la-shipping-fast me-1"></i>Mark Delivered
            </button>
            @endif
        </div>
    </div>
</div>


<style>
@media print {
    .card {
        page-break-inside: avoid;
    }

    .btn,
    .breadcrumb,
    .page-title-box {
        display: none !important;
    }
}
</style>

@endsection