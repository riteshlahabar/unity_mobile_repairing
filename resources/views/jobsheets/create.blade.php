@extends('layouts.app')

@section('title', 'Create New JobSheet')

@section('content')
<div class="container-fluid">
    <div style="margin-top: 15px;"></div>

    <!-- Customer Search Section -->
    <div class="row mb-1">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-2">
                    <div class="row align-items-center" id="searchRow">
                        <div class="col-md-8">
                            <form class="d-flex gap-2" id="customerSearchForm">
                                <div class="input-group">
                                    <input type="text" id="customerSearch" class="form-control"
                                        placeholder="Search customer by name, ID, or phone...">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="iconoir-search"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('customers.create') }}" class="btn btn-success">
                                <i class="iconoir-plus-circle me-1"></i>Add New Customer
                            </a>
                        </div>
                    </div>

                    <div class="row align-items-center d-none" id="selectedCustomerRow">
                        <div class="col-md-10">
                            <div class="d-flex align-items-center gap-3">
                                <span id="displayCustomerId" class="badge bg-primary fs-6 px-3 py-2"></span>
                                <h5 class="mb-0" id="displayCustomerName"></h5>
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearCustomer()">
                                <i class="iconoir-refresh me-1"></i>Change
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wizard Form -->
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="#" method="post" id="jobsheet-wizard">
                        @csrf
                        <input type="hidden" id="selected_customer_id" name="customer_id">


                        <!-- Wizard Navigation -->
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab">
                                <a class="nav-link py-2 active" id="mobile-tab" data-bs-toggle="tab"
                                    href="#mobile">Mobile Details</a>
                                <a class="nav-link py-2" id="problem-tab" data-bs-toggle="tab"
                                    href="#problem">Problem</a>
                                <a class="nav-link py-2" id="photo-tab" data-bs-toggle="tab" href="#photo">Upload
                                    Photo</a>
                                <a class="nav-link py-2" id="sim-tab" data-bs-toggle="tab" href="#sim">SIM</a>
                                <a class="nav-link py-2" id="password-tab" data-bs-toggle="tab"
                                    href="#password">Password</a>
                                <a class="nav-link py-2" id="pattern-tab" data-bs-toggle="tab"
                                    href="#pattern">Pattern</a>
                                <a class="nav-link py-2" id="condition-tab" data-bs-toggle="tab"
                                    href="#condition">Condition</a>
                                <a class="nav-link py-2" id="estimate-tab" data-bs-toggle="tab"
                                    href="#estimate">Estimate</a>
                            </div>
                        </nav>


                        <!-- Wizard Content -->
                        <div class="tab-content mt-3" id="nav-tabContent">
                            @include('jobsheets.partials.tab-mobile')
                            @include('jobsheets.partials.tab-problem')
                            @include('jobsheets.partials.tab-photo')
                            @include('jobsheets.partials.tab-sim')
                            @include('jobsheets.partials.tab-password')
                            @include('jobsheets.partials.tab-pattern')
                            @include('jobsheets.partials.tab-condition')
                            @include('jobsheets.partials.tab-estimate')
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="iconoir-check-circle me-2"></i>Success
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="iconoir-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 class="mb-3">JobSheet Created Successfully!</h4>
                <div class="alert alert-info">
                    <p class="mb-1"><strong>Customer:</strong> <span id="success_customer_name"></span></p>
                    <p class="mb-0"><strong>JobSheet ID:</strong> <span id="success_jobsheet_id" class="badge bg-primary fs-6 px-3 py-2"></span></p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" onclick="printLabel()">
                    <i class="iconoir-printer me-1"></i>Print Label
                </button>
                <button type="button" class="btn btn-success" onclick="printJobsheet()">
                    <i class="iconoir-page me-1"></i>Print JobSheet
                </button>
                <a href="{{ route('jobsheets.index') }}" class="btn btn-info">
                    <i class="iconoir-list me-1"></i>JobSheet List
                </a>
                <a href="{{ route('customers.create') }}" class="btn btn-warning">
                    <i class="iconoir-plus-circle me-1"></i>Add Customer
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="iconoir-cancel me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Include all JavaScript modules -->
@include('jobsheets.scripts.customer-search')
@include('jobsheets.scripts.wizard-navigation')
@include('jobsheets.scripts.tab-mobile')
@include('jobsheets.scripts.tab-problem')
@include('jobsheets.scripts.tab-photo')
@include('jobsheets.scripts.tab-pattern')
@include('jobsheets.scripts.tab-condition')
@include('jobsheets.scripts.tab-estimate')
@include('jobsheets.scripts.success-modal')

@endsection
