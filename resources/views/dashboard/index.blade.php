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
            <div class="card bg-info text-white">
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
            <div class="card bg-primary text-white">
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
            <tbody>
                @forelse($recentJobSheets as $job)
                <tr>
                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">{{ $job->customer->customer_id }}</span></td>
                    <td>{{ $job->customer->full_name }}</td>
                    <td>{{ $job->company }} {{ $job->model }}</td>
                    <td>{{ Str::limit($job->problem_description, 30) }}</td>
                    <td><strong class="text-success">₹{{ number_format($job->balance, 0) }}</strong></td>
                    <td>{{ $job->technician ?? 'Not Assigned' }}</td>
                    <td>
                        @if($job->status == 'in_progress')
                            <button type="button" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                                <span class="me-1">In Progress</span>
                            </button>
                        @elseif($job->status == 'completed')
                            <button type="button" class="btn btn-success btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                                <span class="me-1">Completed</span>
                            </button>
                        @elseif($job->status == 'delivered')
                            <button type="button" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                                <span class="me-1">Delivered</span>
                            </button>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <a class="dropdown-toggle arrow-none" id="dLabel{{ $loop->iteration }}" data-bs-toggle="dropdown" href="#" role="button">
                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('jobsheets.show', $job->jobsheet_id) }}"><i class="las la-eye me-2"></i>View Details</a>
                                @if($job->status == 'in_progress')
                                    <form action="{{ route('jobsheets.markComplete', $job->jobsheet_id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="las la-check-circle me-2"></i>Mark Complete</button>
                                    </form>
                                @endif
                                @if($job->status == 'completed')
                                    <form action="{{ route('jobsheets.markDelivered', $job->jobsheet_id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="las la-shipping-fast me-2"></i>Mark Delivered</button>
                                    </form>
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

@endsection
