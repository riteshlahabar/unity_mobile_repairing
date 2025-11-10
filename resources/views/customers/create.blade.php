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

                        <!-- Contact Number -->
                        <div class="mb-3">
                            <label for="contact_no" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="contact_no" name="contact_no" placeholder="Enter 10-digit mobile number" maxlength="10" required>
                            <div class="invalid-feedback">Please enter valid 10-digit mobile number</div>
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

<!-- Modal for Success Message - CENTERED -->
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
                <a href="{{ route('jobsheets.create') }}" class="btn btn-primary">
                    <i class="iconoir-plus-circle me-1"></i>Create JobSheet
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-success">
                    <i class="iconoir-list me-1"></i>View Customer List
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('customerForm');
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const resetBtn = document.getElementById('resetBtn');

    // Reset button functionality
    resetBtn.addEventListener('click', function() {
        // Clear all validation classes
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
        });
        
        // Hide customer ID display if shown
        document.getElementById('customer_id_display').classList.add('d-none');
        
        // Enable submit button
        document.getElementById('submitBtn').disabled = false;
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Remove all previous validation
        form.classList.remove('was-validated');
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });

        let isValid = true;

        // Validate Full Name
        const fullName = document.getElementById('full_name');
        if (fullName.value.trim() === '') {
            fullName.classList.add('is-invalid');
            isValid = false;
        }

        // Validate Address
        const address = document.getElementById('address');
        if (address.value.trim() === '') {
            address.classList.add('is-invalid');
            isValid = false;
        }

        // Validate Contact Number
        const contactNo = document.getElementById('contact_no');
        const mobilePattern = /^[6-9][0-9]{9}$/;
        if (!mobilePattern.test(contactNo.value.trim())) {
            contactNo.classList.add('is-invalid');
            isValid = false;
        }

        // Validate Alternate Number (if filled)
        const alternateNo = document.getElementById('alternate_no');
        if (alternateNo.value.trim() !== '' && !mobilePattern.test(alternateNo.value.trim())) {
            alternateNo.classList.add('is-invalid');
            isValid = false;
        }

        // Validate WhatsApp Number
        const whatsappNo = document.getElementById('whatsapp_no');
        if (!mobilePattern.test(whatsappNo.value.trim())) {
            whatsappNo.classList.add('is-invalid');
            isValid = false;
        }

        // If form is valid
        if (isValid) {
            // Generate Customer ID
            const randomNum = Math.floor(Math.random() * 9999) + 1;
            const customerId = 'UMR' + String(randomNum).padStart(4, '0');

            // Show Customer ID in form
            document.getElementById('display_customer_id').textContent = customerId;
            document.getElementById('customer_id_display').classList.remove('d-none');

            // Show Customer ID in modal
            document.getElementById('modal_customer_id').textContent = customerId;

            // Disable submit button
            document.getElementById('submitBtn').disabled = true;

            // Show success modal
            successModal.show();

            // Here you can add AJAX call to save data to database
            /*
            fetch('{{ route('customers.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    customer_id: customerId,
                    full_name: fullName.value,
                    address: address.value,
                    contact_no: contactNo.value,
                    alternate_no: alternateNo.value,
                    whatsapp_no: whatsappNo.value
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            });
            */
        }
    });

    // Auto-copy contact to WhatsApp
    document.getElementById('contact_no').addEventListener('blur', function() {
        const whatsappField = document.getElementById('whatsapp_no');
        if (whatsappField.value === '' && this.value !== '') {
            whatsappField.value = this.value;
        }
    });

    // Only allow numbers
    const numberInputs = ['contact_no', 'alternate_no', 'whatsapp_no'];
    numberInputs.forEach(inputId => {
        document.getElementById(inputId).addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
});
</script>
@endsection
