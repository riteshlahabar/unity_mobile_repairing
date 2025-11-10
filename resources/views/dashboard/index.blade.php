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
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                            <i class="iconoir-dollar-circle fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 text-truncate">
                            <p class="text-dark mb-0 fw-semibold fs-14">New Request</p>
                        </div>
                    </div>
                    <h3 class="mt-2 mb-0 fw-bold">8365.00</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-info-subtle text-info thumb-md rounded-circle">
                            <i class="iconoir-cart fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 text-truncate">
                            <p class="text-dark mb-0 fw-semibold fs-14">In Progress</p>
                        </div>
                    </div>
                    <h3 class="mt-2 mb-0 fw-bold">865</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-warning-subtle text-warning thumb-md rounded-circle">
                            <i class="iconoir-percentage-circle fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 text-truncate">
                            <p class="text-dark mb-0 fw-semibold fs-14">Completed</p>
                        </div>
                    </div>
                    <h3 class="mt-2 mb-0 fw-bold">155</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger thumb-md rounded-circle">
                            <i class="iconoir-hexagon-dice fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 text-truncate">
                            <p class="text-dark mb-0 fw-semibold fs-14">Delivered</p>
                            <p class="mb-0 text-truncate text-muted"><span class="text-success">2.7%</span> Increase</p>
                        </div>
                    </div>
                    <h3 class="mt-2 mb-0 fw-bold">10</h3>
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
                <tr>
                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 fs-6 px-3 py-2">#RM001</span></td>
                    <td>
                        <img src="/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle thumb-sm me-2 d-inline">
                        John Doe
                    </td>
                    <td>iPhone 14 Pro</td>
                    <td>Screen Broken</td>
                    <td><strong class="text-success">₹3,000</strong></td>
                    <td>
                        <img src="/assets/images/users/avatar-2.jpg" alt="" class="rounded-circle thumb-xs me-1" title="Mike Johnson">
                        Mike Johnson
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                            <span class="me-1">In Progress</span>
                            <span class="badge bg-light text-dark">2</span>
                        </button>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <a class="dropdown-toggle arrow-none" id="dLabel1" data-bs-toggle="dropdown" href="#" role="button">
                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                <a class="dropdown-item" href="#"><i class="las la-check-circle me-2"></i>Mark Complete</a>
                                <a class="dropdown-item" href="#"><i class="las la-shipping-fast me-2"></i>Mark Delivered</a>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">#RM002</span></td>
                    <td>
                        <img src="/assets/images/users/avatar-3.jpg" alt="" class="rounded-circle thumb-sm me-2 d-inline">
                        Sarah Williams
                    </td>
                    <td>Samsung Galaxy S23</td>
                    <td>Battery Issue</td>
                    <td><strong class="text-success">₹1,500</strong></td>
                    <td>
                        <img src="/assets/images/users/avatar-4.jpg" alt="" class="rounded-circle thumb-xs me-1" title="David Lee">
                        David Lee
                    </td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                            <span class="me-1">Pending</span>
                            <span class="badge bg-light text-dark">1</span>
                        </button>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <a class="dropdown-toggle arrow-none" id="dLabel2" data-bs-toggle="dropdown" href="#" role="button">
                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                <a class="dropdown-item" href="#"><i class="las la-check-circle me-2"></i>Mark Complete</a>
                                <a class="dropdown-item" href="#"><i class="las la-shipping-fast me-2"></i>Mark Delivered</a>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">#RM003</span></td>
                    <td>
                        <img src="/assets/images/users/avatar-5.jpg" alt="" class="rounded-circle thumb-sm me-2 d-inline">
                        Michael Brown
                    </td>
                    <td>OnePlus 11</td>
                    <td>Charging Port</td>
                    <td><strong class="text-success">₹0</strong></td>
                    <td>
                        <img src="/assets/images/users/avatar-2.jpg" alt="" class="rounded-circle thumb-xs me-1" title="Mike Johnson">
                        Mike Johnson
                    </td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                            <span class="me-1">Completed</span>
                            <span class="badge bg-light text-dark">3</span>
                        </button>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <a class="dropdown-toggle arrow-none" id="dLabel3" data-bs-toggle="dropdown" href="#" role="button">
                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                <a class="dropdown-item" href="#"><i class="las la-check-circle me-2"></i>Mark Complete</a>
                                <a class="dropdown-item" href="#"><i class="las la-shipping-fast me-2"></i>Mark Delivered</a>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">#RM004</span></td>
                    <td>
                        <img src="/assets/images/users/avatar-6.jpg" alt="" class="rounded-circle thumb-sm me-2 d-inline">
                        Emma Davis
                    </td>
                    <td>Pixel 7 Pro</td>
                    <td>Software Glitch</td>
                    <td><strong class="text-success">₹1,300</strong></td>
                    <td>
                        <img src="/assets/images/users/avatar-7.jpg" alt="" class="rounded-circle thumb-xs me-1" title="James Wilson">
                        James Wilson
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                            <span class="me-1">In Progress</span>
                            <span class="badge bg-light text-dark">1</span>
                        </button>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <a class="dropdown-toggle arrow-none" id="dLabel4" data-bs-toggle="dropdown" href="#" role="button">
                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                <a class="dropdown-item" href="#"><i class="las la-check-circle me-2"></i>Mark Complete</a>
                                <a class="dropdown-item" href="#"><i class="las la-shipping-fast me-2"></i>Mark Delivered</a>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">#RM005</span></td>
                    <td>
                        <img src="/assets/images/users/avatar-8.jpg" alt="" class="rounded-circle thumb-sm me-2 d-inline">
                        Robert Taylor
                    </td>
                    <td>Xiaomi 13 Pro</td>
                    <td>Camera Not Working</td>
                    <td><strong class="text-success">₹2,200</strong></td>
                    <td>
                        <img src="/assets/images/users/avatar-4.jpg" alt="" class="rounded-circle thumb-xs me-1" title="David Lee">
                        David Lee
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                            <span class="me-1">Waiting Parts</span>
                            <span class="badge bg-light text-dark">5</span>
                        </button>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <a class="dropdown-toggle arrow-none" id="dLabel5" data-bs-toggle="dropdown" href="#" role="button">
                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                <a class="dropdown-item" href="#"><i class="las la-check-circle me-2"></i>Mark Complete</a>
                                <a class="dropdown-item" href="#"><i class="las la-shipping-fast me-2"></i>Mark Delivered</a>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td><span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">#RM006</span></td>
                    <td>
                        <img src="/assets/images/users/avatar-9.jpg" alt="" class="rounded-circle thumb-sm me-2 d-inline">
                        Lisa Anderson
                    </td>
                    <td>iPhone 13</td>
                    <td>Water Damage</td>
                    <td><strong class="text-success">₹0</strong></td>
                    <td>
                        <img src="/assets/images/users/avatar-2.jpg" alt="" class="rounded-circle thumb-xs me-1" title="Mike Johnson">
                        Mike Johnson
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                            <span class="me-1">Delivered</span>
                            <span class="badge bg-light text-dark">7</span>
                        </button>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <a class="dropdown-toggle arrow-none" id="dLabel6" data-bs-toggle="dropdown" href="#" role="button">
                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                <a class="dropdown-item" href="#"><i class="las la-check-circle me-2"></i>Mark Complete</a>
                                <a class="dropdown-item" href="#"><i class="las la-shipping-fast me-2"></i>Mark Delivered</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
