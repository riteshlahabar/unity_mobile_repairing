@extends('layouts.app')

@section('title', 'Add New Customer')

@section('content')
<div class="container-fluid">
    <!-- Page Title - CENTERED -->
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="page-title-box text-center mb-3">
                <h4 class="page-title mb-0">Add New Customer</h4>
            </div>
        </div>
    </div>

    <!-- Form - Centered -->
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form id="customerForm">
                        @csrf
                        
                        <!-- Customer ID will be shown after submit -->
                        <div class="mb-3 d-none" id="customer_id_display">
                            <label class="form-label">Customer ID</label>
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="iconoir-check-circle me-2 fs-4"></i>
                                <div>
                                    <strong>Customer ID: <span id="display_customer_id"></span></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Number - FIRST FIELD -->
                        <div class="mb-3">
                            <label for="contact_no" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="contact_no" name="contact_no" placeholder="Enter 10-digit mobile number" maxlength="10" required>
                            <div class="invalid-feedback">Please enter valid 10-digit mobile number</div>
                            
                        </div>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="full_name" name="full_name" placeholder="Enter full name" required>
                            <div class="invalid-feedback">Please fill full name</div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter complete address" required></textarea>
                            <div class="invalid-feedback">Please fill full address</div>
                        </div>

                        <!-- Alternate Number -->
                        <div class="mb-3">
                            <label for="alternate_no" class="form-label">Alternate Number</label>
                            <input class="form-control" type="text" id="alternate_no" name="alternate_no" placeholder="Enter alternate number (optional)" maxlength="10">
                            <small class="text-muted">Optional - 10 digits</small>
                            <div class="invalid-feedback">Please enter valid 10-digit mobile number</div>
                        </div>

                        <!-- WhatsApp Number -->
                        <div class="mb-3">
                            <label for="whatsapp_no" class="form-label">WhatsApp Number <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="whatsapp_no" name="whatsapp_no" placeholder="Enter WhatsApp number" maxlength="10" required>
                            <div class="invalid-feedback">Please enter valid 10-digit WhatsApp number</div>
                        </div>

                        <!-- Submit and Reset Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="iconoir-check-circle me-1"></i>Submit
                            </button>
                            <button type="reset" class="btn btn-secondary" id="resetBtn">
                                <i class="iconoir-refresh me-1"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="iconoir-check-circle me-2"></i>Success
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="iconoir-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h4 class="mb-3">Customer Created Successfully!</h4>
                <div class="alert alert-info">
                    <strong>Customer ID:</strong>
                    <h3 class="mb-0 mt-2"><span id="modal_customer_id" class="badge bg-primary"></span></h3>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="#" id="createJobSheetLink" class="btn btn-primary">
                    <i class="iconoir-plus-circle me-1"></i>Create JobSheet
                </a>                
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Customer Already Exists Modal -->
<div class="modal fade" id="existingCustomerModal" tabindex="-1" aria-labelledby="existingCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="existingCustomerModalLabel">
                    <i class="iconoir-warning-triangle me-2"></i>Customer Already Exists
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>This contact number is already registered!</strong>
                </div>
                <div class="customer-info">
                    <p><strong>Customer ID:</strong> <span id="existing_customer_id" class="badge bg-primary"></span></p>
                    <p><strong>Name:</strong> <span id="existing_customer_name"></span></p>
                    <p><strong>Contact:</strong> <span id="existing_customer_contact"></span></p>
                    <p><strong>Address:</strong> <span id="existing_customer_address"></span></p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="#" id="createJobSheetExistingLink" class="btn btn-primary">
                    <i class="iconoir-plus-circle me-1"></i>Create New JobSheet
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('customers.scripts.customers')

@endsection
