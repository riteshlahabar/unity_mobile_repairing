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
                                    <input type="text" name="search" class="form-control" placeholder="Search by JobSheet ID, customer name, or phone..." value="{{ request('search') }}">
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
                                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">{{ $job->jobsheet_id }}</span></td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $job->customer->full_name }}</h6>
                                            <small class="text-muted">{{ $job->customer->customer_id }} | {{ $job->customer->contact_no }}</small>
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
                                            <button class="btn btn-sm btn-warning w-100" style="min-width: 100px;">
                                                <i class="iconoir-clock me-1"></i>In Progress
                                            </button>
                                        @elseif($job->status == 'completed')
                                            <button class="btn btn-sm btn-success w-100" style="min-width: 100px;">
                                                <i class="iconoir-check-circle me-1"></i>Completed
                                            </button>
                                        @elseif($job->status == 'delivered')
                                            <button class="btn btn-sm btn-info w-100" style="min-width: 100px;">
                                                <i class="iconoir-hourglass me-1"></i>Delivered
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>₹{{ number_format($job->estimated_cost, 0) }}</strong>
                                            <br><small class="text-muted">Adv: ₹{{ number_format($job->advance, 0) }}</small>
                                            <br><small class="text-success">Bal: ₹{{ number_format($job->balance, 0) }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $job->technician ?? 'Not Assigned' }}</td>
                                    <td>
                                        <small>{{ $job->created_at->format('d-M-Y') }}</small>
                                        <br><small class="text-muted">{{ $job->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel{{ $loop->iteration }}" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('jobsheets.show', $job->jobsheet_id) }}"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="{{ route('jobsheets.edit', $job->jobsheet_id) }}"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="#" onclick="downloadPDF('{{ $job->jobsheet_id }}')"><i class="las la-file-pdf me-2"></i>Download PDF</a>
                                                <a class="dropdown-item" href="#" onclick="printLabel('{{ $job->jobsheet_id }}', '{{ $job->customer->full_name }}')"><i class="las la-tag me-2"></i>Print Label</a>
                                                <a class="dropdown-item" href="#" onclick="printJobsheet('{{ $job->jobsheet_id }}')"><i class="las la-print me-2"></i>Print JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('jobsheets.destroy', $job->jobsheet_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this jobsheet?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" style="border: none; background: none; width: 100%; text-align: left;">
                                                        <i class="las la-trash me-2"></i>Delete
                                                    </button>
                                                </form>
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
                                Showing {{ $jobSheets->firstItem() ?? 0 }} to {{ $jobSheets->lastItem() ?? 0 }} of {{ $jobSheets->total() }} entries
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
// Download PDF Function
function downloadPDF(jobsheetId) {
    alert('Downloading PDF for ' + jobsheetId);
    // Example: window.location.href = `/jobsheets/${jobsheetId}/pdf`;
}

// Print Label Function
function printLabel(jobsheetId, customerName) {
    const printWindow = window.open('', '', 'width=400,height=300');
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Label - ${jobsheetId}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
                .label { border: 2px solid #000; padding: 20px; margin: 20px; }
                h2 { margin: 10px 0; }
                .jobsheet-id { font-size: 24px; font-weight: bold; margin: 15px 0; }
                .customer-name { font-size: 18px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="label">
                <h2>Mobile Repair Shop</h2>
                <div class="jobsheet-id">${jobsheetId}</div>
                <div class="customer-name">${customerName}</div>
                <div>${new Date().toLocaleDateString()}</div>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => { printWindow.print(); printWindow.close(); }, 250);
}

// Print JobSheet Function
function printJobsheet(jobsheetId) {
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>JobSheet - ${jobsheetId}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 30px; }
                .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px; }
                .section { margin-bottom: 20px; }
                .info-row { display: flex; margin-bottom: 8px; }
                .info-label { font-weight: bold; width: 150px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Mobile Repair Shop</h1>
                <p>JobSheet ID: ${jobsheetId} | Date: ${new Date().toLocaleDateString()}</p>
            </div>
            <div class="section">
                <h3>JobSheet Details</h3>
                <p>Complete jobsheet information will be printed here...</p>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => { printWindow.print(); printWindow.close(); }, 250);
}
</script>
@endsection
