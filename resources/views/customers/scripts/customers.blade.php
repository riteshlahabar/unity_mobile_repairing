<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('customerForm');
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const existingCustomerModal = new bootstrap.Modal(document.getElementById('existingCustomerModal'));
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

    // Show existing customer modal
    function showExistingCustomerModal(customer) {
        document.getElementById('existing_customer_id').textContent = customer.customer_id;
        document.getElementById('existing_customer_name').textContent = customer.full_name;
        document.getElementById('existing_customer_contact').textContent = customer.contact_no;
        document.getElementById('existing_customer_address').textContent = customer.address;
        
        // Set jobsheet creation link with customer data
        const createJobSheetLink = document.getElementById('createJobSheetExistingLink');
        createJobSheetLink.href = '{{ route("jobsheets.create") }}?customer_id=' + customer.customer_id + '&customer_name=' + encodeURIComponent(customer.full_name);
        
        existingCustomerModal.show();
    }

    // Reset button functionality
    resetBtn.addEventListener('click', function() {
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
        });
        document.getElementById('customer_id_display').classList.add('d-none');
        document.getElementById('submitBtn').disabled = false;
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

        // Validate Contact Number (FIRST)
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

        // If form is valid, submit via AJAX
        if (isValid) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

            // Prepare form data
            const formData = new FormData(form);

            // AJAX Request
            fetch('{{ route("customers.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new TypeError("Server didn't return JSON!");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show Customer ID in form
                    document.getElementById('display_customer_id').textContent = data.customer_id;
                    document.getElementById('customer_id_display').classList.remove('d-none');

                    // Show Customer ID in modal
                    document.getElementById('modal_customer_id').textContent = data.customer_id;
                    
                    // Set jobsheet creation link with customer data
                    const createJobSheetLink = document.getElementById('createJobSheetLink');
                    createJobSheetLink.href = '{{ route("jobsheets.create") }}?customer_id=' + data.customer_id + '&customer_name=' + encodeURIComponent(data.customer.full_name);

                    // Reset submit button
                    submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>Submit';

                    // Show success modal
                    successModal.show();
                    
                } else {
                    throw new Error(data.message || 'Unknown error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                if (error.errors && error.errors.contact_no) {
                    alert('This contact number is already registered!');
                    contactNo.classList.add('is-invalid');
                } else {
                    alert('Error: ' + error.message);
                }
                
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>Submit';
            });
        }
    });

    // Auto-copy contact to WhatsApp
    document.getElementById('contact_no').addEventListener('blur', function() {
        const whatsappField = document.getElementById('whatsapp_no');
        if (whatsappField.value === '' && this.value !== '') {
            whatsappField.value = this.value;
        }
    });

    // Only allow numbers for phone fields
    const numberInputs = ['contact_no', 'alternate_no', 'whatsapp_no'];
    numberInputs.forEach(inputId => {
        document.getElementById(inputId).addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
});
</script>
