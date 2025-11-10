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
                                <!-- JobSheet 1 -->
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">JS0001</span></td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">John Doe</h6>
                                            <small class="text-muted">UMR0001 | 9876543210</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Apple iPhone 14 Pro</strong>
                                            <br><small class="text-muted">Black, 256GB</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">Dead</span>
                                        <span class="badge bg-warning">Damage</span>
                                        <br><small>Screen Broken</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning w-100" style="min-width: 100px;">
                                            <i class="iconoir-clock me-1"></i>In Progress
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>₹5,000</strong>
                                            <br><small class="text-muted">Adv: ₹2,000</small>
                                            <br><small class="text-success">Bal: ₹3,000</small>
                                        </div>
                                    </td>
                                    <td>Technician 1</td>
                                    <td>
                                        <small>08-Nov-2025</small>
                                        <br><small class="text-muted">02:30 PM</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel1" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="#"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="#" onclick="printLabel('JS0001', 'John Doe')"><i class="las la-tag me-2"></i>Print Label</a>
                                                <a class="dropdown-item" href="#" onclick="printJobsheet('JS0001')"><i class="las la-print me-2"></i>Print JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="las la-trash me-2"></i>Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- JobSheet 2 -->
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">JS0002</span></td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">Sarah Williams</h6>
                                            <small class="text-muted">UMR0002 | 9988776655</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Samsung Galaxy S23</strong>
                                            <br><small class="text-muted">White, 128GB</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">On with Problem</span>
                                        <br><small>Battery Issue</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info w-100" style="min-width: 100px;">
                                            <i class="iconoir-hourglass me-1"></i>Pending
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>₹2,500</strong>
                                            <br><small class="text-muted">Adv: ₹1,000</small>
                                            <br><small class="text-success">Bal: ₹1,500</small>
                                        </div>
                                    </td>
                                    <td>Technician 2</td>
                                    <td>
                                        <small>07-Nov-2025</small>
                                        <br><small class="text-muted">11:00 AM</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel2" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="#"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="#" onclick="printLabel('JS0002', 'Sarah Williams')"><i class="las la-tag me-2"></i>Print Label</a>
                                                <a class="dropdown-item" href="#" onclick="printJobsheet('JS0002')"><i class="las la-print me-2"></i>Print JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="las la-trash me-2"></i>Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- JobSheet 3 -->
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">JS0003</span></td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">Michael Brown</h6>
                                            <small class="text-muted">UMR0003 | 9765432198</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>OnePlus 11</strong>
                                            <br><small class="text-muted">Black, 256GB</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">Dead</span>
                                        <br><small>Charging Port</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success w-100" style="min-width: 100px;">
                                            <i class="iconoir-check-circle me-1"></i>Completed
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>₹3,000</strong>
                                            <br><small class="text-muted">Adv: ₹3,000</small>
                                            <br><small class="text-success">Bal: ₹0</small>
                                        </div>
                                    </td>
                                    <td>Technician 1</td>
                                    <td>
                                        <small>06-Nov-2025</small>
                                        <br><small class="text-muted">03:45 PM</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel3" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="#"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="#" onclick="printLabel('JS0003', 'Michael Brown')"><i class="las la-tag me-2"></i>Print Label</a>
                                                <a class="dropdown-item" href="#" onclick="printJobsheet('JS0003')"><i class="las la-print me-2"></i>Print JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="las la-trash me-2"></i>Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- JobSheet 4 -->
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">JS0004</span></td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">Emma Davis</h6>
                                            <small class="text-muted">UMR0004 | 9654321987</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Pixel 7 Pro</strong>
                                            <br><small class="text-muted">Blue, 128GB</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Damage</span>
                                        <br><small>Software Glitch</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger w-100" style="min-width: 100px;">
                                            <i class="iconoir-box me-1"></i>Waiting Parts
                                        </button>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>₹1,800</strong>
                                            <br><small class="text-muted">Adv: ₹500</small>
                                            <br><small class="text-success">Bal: ₹1,300</small>
                                        </div>
                                    </td>
                                    <td>Technician 3</td>
                                    <td>
                                        <small>08-Nov-2025</small>
                                        <br><small class="text-muted">10:15 AM</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel4" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="#"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="#" onclick="printLabel('JS0004', 'Emma Davis')"><i class="las la-tag me-2"></i>Print Label</a>
                                                <a class="dropdown-item" href="#" onclick="printJobsheet('JS0004')"><i class="las la-print me-2"></i>Print JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="las la-trash me-2"></i>Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info">
                                Showing 1 to 4 of 4 entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate float-end">
                                <ul class="pagination">
                                    <li class="paginate_button page-item previous disabled">
                                        <a href="#" class="page-link">Previous</a>
                                    </li>
                                    <li class="paginate_button page-item active">
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li class="paginate_button page-item next disabled">
                                        <a href="#" class="page-link">Next</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
