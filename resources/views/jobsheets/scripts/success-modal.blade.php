<script>
// Form Submission and Success Modal
document.getElementById('jobsheet-wizard').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate required fields
    const customerId = document.getElementById('selected_customer_id').value;
    if (!customerId) {
        alert('Please select a customer first');
        return;
    }

    const company = document.getElementById('company').value;
    const model = document.getElementById('model').value;
    const color = document.getElementById('color').value;
    const series = document.getElementById('series').value;
    const problemDescription = document.getElementById('problem_description').value;
    const deviceCondition = document.querySelector('input[name="device_condition"]:checked')?.value;
    const waterDamage = document.querySelector('input[name="water_damage"]:checked')?.value;
    const physicalDamage = document.querySelector('input[name="physical_damage"]:checked')?.value;
    const estimatedCost = document.getElementById('estimated_cost').value;

    if (!company || !model || !color || !series || !problemDescription || !deviceCondition || !waterDamage || !physicalDamage || !estimatedCost) {
        alert('Please fill all required fields');
        return;
    }

    // Show loading state
    const submitBtn = document.getElementById('createJobSheetBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

    // Prepare form data
    const formData = new FormData(this);

    // Submit via AJAX
    fetch('{{ route("jobsheets.store") }}', {
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
        console.log('JobSheet Response:', data);
        
        if (data.success) {
            // Get customer name
            const customerName = document.getElementById('displayCustomerName').textContent;
            
            // Set success modal data
            document.getElementById('success_customer_name').textContent = customerName;
            document.getElementById('success_jobsheet_id').textContent = data.jobsheet_id;
            
            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Reset submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>Create JobSheet';
        } else {
            throw new Error(data.message || 'Failed to create jobsheet');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating jobsheet: ' + error.message);
        
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>Create JobSheet';
    });
});

// Close Modal and Redirect to JobSheet List
function closeModalAndRedirect() {
    const successModal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
    successModal.hide();
    
    // Redirect to jobsheet list page after modal is closed
    setTimeout(() => {
        window.location.href = "{{ route('jobsheets.index') }}";
    }, 300);
}

// Print Label Function (Popup remains open)
function printLabel() {
    // Get the JobSheet ID from the success modal
    const jobsheetId = document.getElementById('success_jobsheet_id').textContent.trim();

    if (!jobsheetId) {
        alert('JobSheet ID not found. Cannot print label.');
        return;
    }

    // Open the print label route in a new window/tab
    window.open(`/jobsheets/${encodeURIComponent(jobsheetId)}/print-label`, '_blank');
}



// Print JobSheet Function (in success modal)
function printJobsheet() {
    const jobsheetId = document.getElementById('success_jobsheet_id').textContent;
    
    // Construct the URL to the printPDF route
    const printUrl = `/jobsheets/${jobsheetId}/print`;
    
    // Open PDF in new tab for printing
    const printWindow = window.open(printUrl, '_blank');
    printWindow.focus();
    
    // Optional: Close modal and redirect after a short delay
    setTimeout(() => {
        closeModalAndRedirect();
    }, 500);
}


// Also redirect when modal is closed via escape key or backdrop click
document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
    setTimeout(() => {
        window.location.href = "{{ route('jobsheets.index') }}";
    }, 100);
});
</script>
