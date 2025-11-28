@extends('layouts.app')

@section('title', 'JobSheet List')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">JobSheet List</h4>
            </div>
        </div>
    </div>

    <!-- JobSheet List Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <a href="{{ route('jobsheets.create') }}" class="btn btn-primary">
                                <i class="iconoir-plus-circle me-1"></i>Create New JobSheet
                            </a>
                        </div>
                        <div class="col-md-6">
                            <form class="d-flex gap-2" method="GET" action="{{ route('jobsheets.index') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by JobSheet ID, customer name, or phone..."
                                        value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="iconoir-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th>JobSheet ID</th>
                                    <th>Customer</th>
                                    <th>Device</th>
                                    <th>Problem</th>
                                    <th>Status</th>
                                    <th>Cost/Advance</th>
                                    <th>Technician</th>
                                    <th>Received Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobSheets as $job)
                                <tr>
                                    <td><span
                                            class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">{{ $job->jobsheet_id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $job->customer->full_name }}</h6>
                                            <small class="text-muted">{{ $job->customer->customer_id }} |
                                                {{ $job->customer->contact_no }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $job->company }} {{ $job->model }}</strong>
                                            <br><small class="text-muted">{{ $job->color }}, {{ $job->series }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($job->status_dead)
                                        <span class="badge bg-danger">Dead</span>
                                        @endif
                                        @if($job->status_damage)
                                        <span class="badge bg-warning">Damage</span>
                                        @endif
                                        @if($job->status_on)
                                        <span class="badge bg-info">On with Problem</span>
                                        @endif
                                        <br><small>{{ Str::limit($job->problem_description, 20) }}</small>
                                    </td>
                                    <td>
    @if($job->status == 'in_progress')
        <button class="btn btn-sm btn-warning w-100" style="min-width: 100px; border-radius: 15px;">
            <i class="fas fa-spinner me-2"></i>In Progress
        </button>
    @elseif($job->status == 'call_info')
        <button class="btn btn-sm btn-primary w-100" style="min-width: 100px; border-radius: 15px;">
            <i class="fas fa-phone me-2"></i>Call Info
        </button>
    @elseif($job->status == 'approval_pending')
        <button class="btn btn-sm btn-secondary w-100" style="min-width: 100px; border-radius: 15px;">
            <i class="fas fa-hourglass-half me-2"></i>Approval Pending
        </button>
    @elseif($job->status == 'customer_approved')
        <button class="btn btn-sm btn-info w-100" style="min-width: 100px; border-radius: 15px;">
            <i class="fas fa-check-circle me-2"></i>Customer Approved
        </button>
    @elseif($job->status == 'not_okay_return')
        <button class="btn btn-sm btn-dark w-100" style="min-width: 100px; border-radius: 15px;">
            <i class="fas fa-times-circle me-2"></i>Not Okay Return
        </button>
    @elseif($job->status == 'ready')
        <button class="btn btn-sm btn-success w-100" style="min-width: 100px; border-radius: 15px;">
            <i class="fas fa-check-double me-2"></i>Ready
        </button>
    @elseif($job->status == 'return')
        <button class="btn btn-sm btn-warning w-100" style="min-width: 100px; border-radius: 15px;">
            <i class="fas fa-undo me-2"></i>Return
        </button>
    @elseif($job->status == 'delivered')
        <button class="btn btn-sm btn-success w-100" style="min-width: 100px; border-radius: 15px;">
            <i class="fas fa-box me-2"></i>Delivered
        </button>
    @endif
</td>

                                    <td>
                                        <div>
                                            <strong>₹{{ number_format($job->estimated_cost, 0) }}</strong>
                                            <br><small class="text-muted">Adv:
                                                ₹{{ number_format($job->advance, 0) }}</small>
                                            @if($job->status == 'delivered')
                                            <br><small class="text-success">Bal: ₹{{ number_format($job->balance, 0) }}
                                                <span class="badge bg-success-subtle text-success">Paid</span></small>
                                            @else
                                            <br><small class="text-success">Bal:
                                                ₹{{ number_format($job->balance, 0) }}</small>
                                            @endif
                                        </div>
                                    </td>

                                    <td>{{ $job->technician ?? 'Not Assigned' }}</td>
                                    <td>
                                        <small>{{ $job->created_at->format('d-M-Y') }}</small>
                                        <br><small class="text-muted">{{ $job->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel{{ $loop->iteration }}"
                                                data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item"
                                                    href="{{ route('jobsheets.show', $job->jobsheet_id) }}">
                                                    <i class="las la-eye me-2"></i>View Details
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('jobsheets.edit', $job->jobsheet_id) }}">
                                                    <i class="las la-edit me-2"></i>Edit
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('jobsheets.downloadPDF', $job->jobsheet_id) }}">
                                                    <i class="las la-file-pdf me-2"></i>Download PDF
                                                </a>
                                            <a class="dropdown-item" 
   href="{{ route('jobsheets.printLabel', $job->jobsheet_id) }}" 
   target="_blank">
    <i class="las la-tag me-2"></i>Print Label
</a>



                                                <a class="dropdown-item" href="#"
                                                    onclick="printJobsheet(event, '{{ $job->jobsheet_id }}')">
                                                    <i class="las la-print me-2"></i>Print JobSheet
                                                </a>
                                              {{--  <div class="dropdown-divider"></div>
                                                <form action="{{ route('jobsheets.destroy', $job->jobsheet_id) }}"
                                                    method="POST" style="display: inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this jobsheet?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        style="border: none; background: none; width: 100%; text-align: left;">
                                                        <i class="las la-trash me-2"></i>Delete
                                                    </button>
                                                </form> --}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <p class="text-muted mb-0">No jobsheets found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info">
                                Showing {{ $jobSheets->firstItem() ?? 0 }} to
                                {{ $jobSheets->lastItem() ?? 0 }} of {{ $jobSheets->total() }}
                                entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate float-end">
                                {{ $jobSheets->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

// Print JobSheet Function - Calls backend printPDF route
function printJobsheet(event, jobsheetId) {
    event.preventDefault();
    
    // Construct the URL to the printPDF route based on your routes
    const printUrl = `/jobsheets/${jobsheetId}/print`;
    
    // Open PDF in new tab for printing
    const printWindow = window.open(printUrl, '_blank');
    printWindow.focus();
}
</script>

@endsection