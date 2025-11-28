<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('customerForm');

    let successModal = null;
    const successModalEl = document.getElementById('successModal');
    if (successModalEl) {
        successModal = new bootstrap.Modal(successModalEl);
    }

    let existingCustomerModal = null;
    const existingCustomerModalEl = document.getElementById('existingCustomerModal');
    if (existingCustomerModalEl) {
        existingCustomerModal = new bootstrap.Modal(existingCustomerModalEl);
    }

    const resetBtn = document.getElementById('resetBtn');
    const contactNoField = document.getElementById('contact_no');
    let checkTimeout = null;
    let existingCustomer = null;

    // Check contact number on blur (when user leaves the field)
    contactNoField.addEventListener('blur', function() {
        const contactNo = this.value.trim();
        const mobilePattern = /^[6-9][0-9]{9}$/;
        
        if (mobilePattern.test(contactNo)) {
            checkCustomerExists(contactNo);
        }
    });

    // Real-time check while typing (with debounce)
    contactNoField.addEventListener('input', function() {
        const contactNo = this.value.replace(/[^0-9]/g, '');
        this.value = contactNo;
        
        clearTimeout(checkTimeout);
        
        if (contactNo.length === 10) {
            checkTimeout = setTimeout(() => {
                checkCustomerExists(contactNo);
            }, 500);
        }
    });

    // Function to check if customer exists
    function checkCustomerExists(contactNo) {
        fetch('{{ route("customers.checkContact") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ contact_no: contactNo })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                existingCustomer = data.customer;
                showExistingCustomerModal(data.customer);
            }
        })
        .catch(error => {
            console.error('Error checking contact:', error);
        });
    }

    // Show existing customer modal safely
    function showExistingCustomerModal(customer) {
        if (!existingCustomerModal) return;

        document.getElementById('existing_customer_id').textContent = customer.customer_id;
        document.getElementById('existing_customer_name').textContent = customer.full_name;
        document.getElementById('existing_customer_contact').textContent = customer.contact_no;
        document.getElementById('existing_customer_address').textContent = customer.address;
        
        const createJobSheetLink = document.getElementById('createJobSheetExistingLink');
        if (createJobSheetLink) {
            createJobSheetLink.href = '{{ route("jobsheets.create") }}?customer_id=' + customer.customer_id + '&customer_name=' + encodeURIComponent(customer.full_name);
        }
        
        existingCustomerModal.show();
    }

    // Reset button functionality
    resetBtn.addEventListener('click', function() {
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
        });
        const customerIdDisplay = document.getElementById('customer_id_display');
        if (customerIdDisplay) {
            customerIdDisplay.classList.add('d-none');
        }
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        existingCustomer = null;
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Check if customer already exists
        if (existingCustomer) {
            showExistingCustomerModal(existingCustomer);
            return;
        }

        // Remove all previous validation
        form.classList.remove('was-validated');
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });

        let isValid = true;

        // Validate Contact Number
        const contactNo = document.getElementById('contact_no');
        const mobilePattern = /^[6-9][0-9]{9}$/;
        if (!mobilePattern.test(contactNo.value.trim())) {
            contactNo.classList.add('is-invalid');
            isValid = false;
        }

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

        if (!isValid) return;

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        
        const isEditMode = form.hasAttribute('data-customer-id') && form.getAttribute('data-customer-id').trim() !== '';
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + (isEditMode ? 'Updating...' : 'Submitting...');

        const formData = new FormData(form);

        // ✅ FIX: For PUT requests, always send as POST with _method=PUT
        const url = isEditMode 
            ? '{{ url("customers") }}/' + form.getAttribute('data-customer-id') 
            : '{{ route("customers.store") }}';

        // Always use POST method, Laravel will handle _method field for spoofing
        fetch(url, {
            method: 'POST',  // ✅ Changed from PUT to POST
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData  // FormData will include the _method=PUT field
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show Customer ID in form (only for create)
                if (!isEditMode) {
                    const customerIdDisplay = document.getElementById('customer_id_display');
                    if (customerIdDisplay) {
                        customerIdDisplay.classList.remove('d-none');
                    }
                    const displayCustomerId = document.getElementById('display_customer_id');
                    if (displayCustomerId) {
                        displayCustomerId.textContent = data.customer_id;
                    }
                }

                // Show modal_customer_id if modal present
                const modalCustomerIdSpan = document.getElementById('modal_customer_id');
                if (modalCustomerIdSpan) {
                    modalCustomerIdSpan.textContent = data.customer_id;
                }
                
                // Update createJobSheetLink href
                const createJobSheetLink = document.getElementById('createJobSheetLink');
                if (createJobSheetLink) {
                    createJobSheetLink.href = '{{ route("jobsheets.create") }}?customer_id=' + data.customer_id + '&customer_name=' + encodeURIComponent(data.customer.full_name);
                }

                if (successModal) {
                    successModal.show();
                }

                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>' + (isEditMode ? 'Update' : 'Submit');

                if (!isEditMode) {
                    form.reset();
                }
            } else {
                throw new Error(data.message || 'Unknown error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Handle validation errors
            if (error.errors) {
                let messages = [];
                for (const key in error.errors) {
                    messages.push(...error.errors[key]);
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) input.classList.add('is-invalid');
                }
                alert('Validation errors:\n' + messages.join('\n'));
            } else if (error.message) {
                alert('Error: ' + error.message);
            } else {
                alert('An error occurred. Please try again.');
            }

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>' + (isEditMode ? 'Update' : 'Submit');
        });
    });

    // Auto-copy contact to WhatsApp if WhatsApp is empty
    document.getElementById('contact_no').addEventListener('blur', function() {
        const whatsappField = document.getElementById('whatsapp_no');
        if (whatsappField.value === '' && this.value !== '') {
            whatsappField.value = this.value;
        }
    });

    // Only allow numbers for phone fields
    const numberInputs = ['contact_no', 'alternate_no', 'whatsapp_no'];
    numberInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    });
});


</script>
