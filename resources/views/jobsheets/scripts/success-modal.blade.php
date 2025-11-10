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
    const customerName = document.getElementById('success_customer_name').textContent;
    const jobsheetId = document.getElementById('success_jobsheet_id').textContent;
    
    const printWindow = window.open('', '', 'width=400,height=300');
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Label</title>
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
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}

// Print JobSheet Function (Redirects after print)
function printJobsheet() {
    const customerName = document.getElementById('success_customer_name').textContent;
    const jobsheetId = document.getElementById('success_jobsheet_id').textContent;
    
    const company = document.getElementById('company')?.value || '';
    const model = document.getElementById('model')?.value || '';
    const problem = document.getElementById('problem_description')?.value || '';
    const estimatedCost = document.getElementById('estimated_cost')?.value || '0';
    const advance = document.getElementById('advance')?.value || '0';
    const balance = document.getElementById('balance')?.value || '0';
    
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
                <div class="info-row"><div class="info-label">Customer:</div><div>${customerName}</div></div>
                <div class="info-row"><div class="info-label">Company:</div><div>${company}</div></div>
                <div class="info-row"><div class="info-label">Model:</div><div>${model}</div></div>
                <div class="info-row"><div class="info-label">Problem:</div><div>${problem}</div></div>
                <div class="info-row"><div class="info-label">Estimated Cost:</div><div>₹${estimatedCost}</div></div>
                <div class="info-row"><div class="info-label">Advance:</div><div>₹${advance}</div></div>
                <div class="info-row"><div class="info-label">Balance:</div><div>₹${balance}</div></div>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
        
        // Close modal and redirect after print
        closeModalAndRedirect();
    }, 250);
}

// Also redirect when modal is closed via escape key or backdrop click
document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
    setTimeout(() => {
        window.location.href = "{{ route('jobsheets.index') }}";
    }, 100);
});
</script>
