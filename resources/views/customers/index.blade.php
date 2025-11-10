@extends('layouts.app')

@section('title', 'Customer List')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Customer List</h4>
            </div>
        </div>
    </div>

    <!-- Customer List Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                <i class="iconoir-plus-circle me-1"></i>Add New Customer
                            </a>
                        </div>
                        <div class="col-md-6">
                            <form class="d-flex gap-2" method="GET" action="{{ route('customers.index') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by name, ID, or phone..." value="{{ request('search') }}">
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
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Alternate Number</th>
                                    <th>WhatsApp Number</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">UMR0001</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle thumb-sm me-2">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">John Doe</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>123 Main St, Mumbai</td>
                                    <td>9876543210</td>
                                    <td>9123456789</td>
                                    <td>
                                        <a href="https://wa.me/919876543210" target="_blank" class="text-success">
                                            <i class="iconoir-message-text me-1"></i>9876543210
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel1" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="#"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="{{ route('jobsheets.create') }}"><i class="las la-clipboard me-2"></i>Create JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="las la-trash me-2"></i>Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">UMR0002</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="/assets/images/users/avatar-3.jpg" alt="" class="rounded-circle thumb-sm me-2">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Sarah Williams</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>456 Park Ave, Delhi</td>
                                    <td>9988776655</td>
                                    <td>-</td>
                                    <td>
                                        <a href="https://wa.me/919988776655" target="_blank" class="text-success">
                                            <i class="iconoir-message-text me-1"></i>9988776655
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel2" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="#"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="{{ route('jobsheets.create') }}"><i class="las la-clipboard me-2"></i>Create JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="las la-trash me-2"></i>Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">UMR0003</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="/assets/images/users/avatar-5.jpg" alt="" class="rounded-circle thumb-sm me-2">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Michael Brown</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>789 Lake View, Bangalore</td>
                                    <td>9765432198</td>
                                    <td>8876543219</td>
                                    <td>
                                        <a href="https://wa.me/919765432198" target="_blank" class="text-success">
                                            <i class="iconoir-message-text me-1"></i>9765432198
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel3" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="#"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="{{ route('jobsheets.create') }}"><i class="las la-clipboard me-2"></i>Create JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="las la-trash me-2"></i>Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">UMR0004</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="/assets/images/users/avatar-6.jpg" alt="" class="rounded-circle thumb-sm me-2">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Emma Davis</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>321 Hill Road, Pune</td>
                                    <td>9654321987</td>
                                    <td>-</td>
                                    <td>
                                        <a href="https://wa.me/919654321987" target="_blank" class="text-success">
                                            <i class="iconoir-message-text me-1"></i>9654321987
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel4" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="#"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="{{ route('jobsheets.create') }}"><i class="las la-clipboard me-2"></i>Create JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="return confirm('Are you sure?')"><i class="las la-trash me-2"></i>Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">UMR0005</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="/assets/images/users/avatar-8.jpg" alt="" class="rounded-circle thumb-sm me-2">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">Robert Taylor</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>555 Beach Road, Chennai</td>
                                    <td>9543218765</td>
                                    <td>8765432109</td>
                                    <td>
                                        <a href="https://wa.me/919543218765" target="_blank" class="text-success">
                                            <i class="iconoir-message-text me-1"></i>9543218765
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel5" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="#"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="{{ route('jobsheets.create') }}"><i class="las la-clipboard me-2"></i>Create JobSheet</a>
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
                                Showing 1 to 5 of 5 entries
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
@endsection
