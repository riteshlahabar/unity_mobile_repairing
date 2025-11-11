@extends('layouts.app')

@section('title', 'Dashboard | Mifty')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Dashboard</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/">Mifty</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <!-- New Request Card - Blue -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1 text-truncate">
                            <p class="text-white mb-0 fw-semibold fs-14">New Request</p>
                        </div>
                    </div>
                    <h3 class="mt-2 mb-0 fw-bold">{{ $newRequestCount }}</h3>
                </div>
            </div>
        </div>

        <!-- In Progress Card - Warning/Orange -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1 text-truncate">
                            <p class="text-white mb-0 fw-semibold fs-14">In Progress</p>
                        </div>
                    </div>
                    <h3 class="mt-2 mb-0 fw-bold">{{ $inProgressCount }}</h3>
                </div>
            </div>
        </div>

        <!-- Completed Card - Success/Green -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1 text-truncate">
                            <p class="text-white mb-0 fw-semibold fs-14">Completed</p>
                        </div>
                    </div>
                    <h3 class="mt-2 mb-0 fw-bold">{{ $completedCount }}</h3>
                </div>
            </div>
        </div>

        <!-- Delivered Card - Info/Cyan -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1 text-truncate">
                            <p class="text-white mb-0 fw-semibold fs-14">Delivered</p>
                        </div>
                    </div>
                    <h3 class="mt-2 mb-0 fw-bold">{{ $deliveredTodayCount }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-body pt-0">
    <div class="table-responsive">
        <table class="table mb-0 table-centered">
            <thead class="table-light">
                <tr>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Device</th>
                    <th>Issue</th>
                    <th>Balance</th>
                    <th>Technician</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody id="jobsheetsTableBody">
                @forelse($recentJobSheets as $job)
                <tr id="job-{{ $job->jobsheet_id }}">
                    <td><span
                            class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">{{ $job->customer->customer_id }}</span>
                    </td>
                    <td>{{ $job->customer->full_name }}</td>
                    <td>{{ $job->company }} {{ $job->model }}</td>
                    <td>{{ Str::limit($job->problem_description, 30) }}</td>
                    <td>
                        @if($job->status == 'delivered')
                        <strong class="text-success">₹{{ number_format($job->balance, 0) }}</strong>
                        <span class="badge bg-success-subtle text-success ms-1">Paid</span>
                        @else
                        <strong class="text-success">₹{{ number_format($job->balance, 0) }}</strong>
                        @endif
                    </td>

                    <td>{{ $job->technician ?? 'Not Assigned' }}</td>
                    <td>
                        @if($job->status == 'in_progress')
                        <button type="button"
                            class="btn btn-warning btn-sm d-flex align-items-center justify-content-center"
                            style="min-width: 140px;">
                            <i class="iconoir-clock me-1"></i>
                            <span>In Progress</span>
                        </button>
                        @elseif($job->status == 'completed')
                        <button type="button"
                            class="btn btn-success btn-sm d-flex align-items-center justify-content-center"
                            style="min-width: 140px;">
                            <i class="iconoir-check-circle me-1"></i>
                            <span>Completed</span>
                        </button>
                        @elseif($job->status == 'delivered')
                        <button type="button"
                            class="btn btn-info btn-sm d-flex align-items-center justify-content-center"
                            style="min-width: 140px;">
                            <i class="iconoir-hourglass me-1"></i>
                            <span>Delivered</span>
                        </button>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <a class="dropdown-toggle arrow-none" id="dLabel{{ $loop->iteration }}"
                                data-bs-toggle="dropdown" href="#" role="button">
                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('jobsheets.show', $job->jobsheet_id) }}"><i
                                        class="las la-eye me-2"></i>View Details</a>
                                @if($job->status == 'in_progress')
                                <a href="#" class="dropdown-item"
                                    onclick="markComplete(event, '{{ $job->jobsheet_id }}')">
                                    <i class="las la-check-circle me-2"></i>Mark Complete
                                </a>
                                @endif
                                @if($job->status == 'completed')
                                <a href="#" class="dropdown-item"
                                    onclick="openPaymentModal(event, '{{ $job->jobsheet_id }}', {{ $job->balance }})">
                                    <i class="las la-shipping-fast me-2"></i>Mark Delivered
                                </a>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <p class="text-muted mb-0">No jobsheets available</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Payment & OTP Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="iconoir-wallet me-2"></i>Payment & Delivery</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="current_jobsheet_id">

                <!-- Step 1: Payment Confirmation -->
                <div id="paymentStep">
                    <h6 class="mb-3">Step 1: Confirm Payment</h6>
                    <div class="mb-3">
                        <label class="form-label">Amount to Collect</label>
                        <h4 class="text-primary" id="balance_amount">₹0</h4>
                    </div>
                    <button type="button" class="btn btn-success w-100" onclick="processPayment()">
                        <i class="iconoir-check-circle me-2"></i>Confirm Payment
                    </button>
                </div>

                <!-- Step 2: OTP Verification -->
                <div id="otpStep" class="d-none">
                    <h6 class="mb-3">Step 2: OTP Verification</h6>
                    <p class="text-muted">OTP has been sent to customer's WhatsApp</p>
                    <div class="mb-3">
                        <label class="form-label">Enter OTP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-center" id="otp_input" maxlength="6"
                            placeholder="Enter 6-digit OTP">
                    </div>
                    <button type="button" class="btn btn-primary w-100" onclick="verifyOTP()">
                        <i class="iconoir-lock me-2"></i>Verify OTP
                    </button>
                    <div id="otpVerified" class="alert alert-success mt-3 d-none">
                        <i class="iconoir-check-circle me-2"></i>OTP Verified Successfully!
                    </div>
                </div>

                <!-- Step 3: Mark Delivered -->
                <div id="deliveryStep" class="d-none">
                    <h6 class="mb-3">Step 3: Complete Delivery</h6>
                    <button type="button" class="btn btn-info w-100" onclick="markDelivered()">
                        <i class="iconoir-box me-2"></i>Mark as Delivered
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dashboard.scripts.dashboard')

@endsection