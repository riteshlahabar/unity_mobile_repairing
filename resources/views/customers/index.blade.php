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
            <div class="card" style="min-height: calc(100vh - 200px);">
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

                <div class="card-body pt-0" style="display: flex; flex-direction: column; min-height: calc(100vh - 320px);">
                    <div class="table-responsive" style="flex: 1; overflow-y: auto;">
                        <table class="table mb-0 table-centered">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Alternate Number</th>
                                    <th>WhatsApp Number</th>
                                    <th>Total Jobsheets</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">{{ $customer->customer_id }}</span>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">{{ $customer->full_name }}</h6>
                                    </td>
                                    <td>{{ Str::limit($customer->address, 30) }}</td>
                                    <td>{{ $customer->contact_no }}</td>
                                    <td>{{ $customer->alternate_no ?? '-' }}</td>
                                    <td>{{ $customer->whatsapp_no }}</td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info fs-6 px-3 py-2">{{ $customer->jobSheets->count() }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown d-inline-block">
                                            <a class="dropdown-toggle arrow-none" id="dLabel{{ $loop->iteration }}" data-bs-toggle="dropdown" href="#" role="button">
                                                <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('customers.show', $customer->customer_id) }}"><i class="las la-eye me-2"></i>View Details</a>
                                                <a class="dropdown-item" href="{{ route('customers.edit', $customer->customer_id) }}"><i class="las la-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="{{ route('jobsheets.create') }}?customer_id={{ $customer->customer_id }}&customer_name={{ urlencode($customer->full_name) }}"><i class="las la-clipboard me-2"></i>Create JobSheet</a>
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('customers.destroy', $customer->customer_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this customer?');">
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
                                    <td colspan="8" class="text-center py-5">
                                        <div style="padding: 60px 0;">
                                            <i class="las la-user-times" style="font-size: 4rem; color: #ccc;"></i>
                                            <p class="text-muted mb-0 mt-3">No customers found</p>
                                            @if(request('search'))
                                                <small class="text-muted">Try a different search term</small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination - Always at bottom -->
                    <div class="row mt-3" style="margin-top: auto;">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info">
                                Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate float-end">
                                {{ $customers->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Ensure table extends to bottom */
.card {
    display: flex;
    flex-direction: column;
}

.card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.table-responsive {
    flex: 1;
    overflow-y: auto;
    max-height: calc(100vh - 320px);
}

/* Sticky header for scrollable table */
.table-responsive thead th {
    background-color: #f8f9fa;
    position: sticky;
    top: 0;
    z-index: 10;
}

/* Custom scrollbar for table */
.table-responsive::-webkit-scrollbar {
    width: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Ensure pagination stays at bottom */
.row.mt-3 {
    margin-top: auto !important;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}
</style>
@endsection
