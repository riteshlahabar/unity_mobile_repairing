<script>
// Mark Complete (AJAX)
function markComplete(event, jobsheetId) {
    event.preventDefault();
    
    if (!confirm('Mark this jobsheet as completed?')) {
        return;
    }

    fetch(`/jobsheets/${jobsheetId}/mark-complete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update row without page reload
            updateJobRow(jobsheetId, data.jobsheet);
            alert('JobSheet marked as completed!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark as complete');
    });
}

// Open Payment Modal
function openPaymentModal(event, jobsheetId, balance) {
    event.preventDefault();
    
    document.getElementById('current_jobsheet_id').value = jobsheetId;
    document.getElementById('balance_amount').textContent = '₹' + balance.toFixed(2);
    
    // Reset steps
    document.getElementById('paymentStep').classList.remove('d-none');
    document.getElementById('otpStep').classList.add('d-none');
    document.getElementById('deliveryStep').classList.add('d-none');
    document.getElementById('otpVerified').classList.add('d-none');
    document.getElementById('otp_input').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

// Process Payment & Send OTP
function processPayment() {
    const jobsheetId = document.getElementById('current_jobsheet_id').value;
    
    fetch(`/jobsheets/${jobsheetId}/generate-otp`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Move to OTP step
            document.getElementById('paymentStep').classList.add('d-none');
            document.getElementById('otpStep').classList.remove('d-none');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send OTP');
    });
}

// Verify OTP
function verifyOTP() {
    const jobsheetId = document.getElementById('current_jobsheet_id').value;
    const otp = document.getElementById('otp_input').value;

    if (!otp || otp.length !== 6) {
        alert('Please enter valid 6-digit OTP');
        return;
    }

    fetch(`/jobsheets/${jobsheetId}/verify-otp`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ otp: otp })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('otpVerified').classList.remove('d-none');
            document.getElementById('deliveryStep').classList.remove('d-none');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to verify OTP');
    });
}

// Mark Delivered
function markDelivered() {
    const jobsheetId = document.getElementById('current_jobsheet_id').value;

    fetch(`/jobsheets/${jobsheetId}/mark-delivered`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
            modal.hide();
            
            // Update row (no reordering)
            updateJobRow(jobsheetId, data.jobsheet);
            
            alert('Device delivered successfully! Thank you message sent to customer.');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark as delivered');
    });
}

// Update job row in table (WITHOUT reordering)
function updateJobRow(jobsheetId, jobsheet) {
    const row = document.getElementById(`job-${jobsheetId}`);
    if (!row) return;

    // Update status button
    const statusCell = row.cells[6];
    let statusHtml = '';
    
    if (jobsheet.status === 'in_progress') {
        statusHtml = `
            <button type="button" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                <i class="iconoir-clock me-1"></i>
                <span>In Progress</span>
            </button>
        `;
    } else if (jobsheet.status === 'completed') {
        statusHtml = `
            <button type="button" class="btn btn-success btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                <i class="iconoir-check-circle me-1"></i>
                <span>Completed</span>
            </button>
        `;
    } else if (jobsheet.status === 'delivered') {
        statusHtml = `
            <button type="button" class="btn btn-info btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px;">
                <i class="iconoir-box me-1"></i>
                <span>Delivered</span>
            </button>
        `;
    }
    
    statusCell.innerHTML = statusHtml;

    // Update action dropdown
    const actionCell = row.cells[7];
    let actionHtml = `
        <div class="dropdown d-inline-block">
            <a class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button">
                <i class="las la-ellipsis-v fs-20 text-muted"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="/jobsheets/${jobsheetId}"><i class="las la-eye me-2"></i>View Details</a>
    `;
    
    if (jobsheet.status === 'in_progress') {
        actionHtml += `
                <a href="#" class="dropdown-item" onclick="markComplete(event, '${jobsheetId}')">
                    <i class="las la-check-circle me-2"></i>Mark Complete
                </a>
        `;
    } else if (jobsheet.status === 'completed') {
        actionHtml += `
                <a href="#" class="dropdown-item" onclick="openPaymentModal(event, '${jobsheetId}', ${jobsheet.balance})">
                    <i class="las la-shipping-fast me-2"></i>Mark Delivered
                </a>
        `;
    }
    
    actionHtml += `
            </div>
        </div>
    `;
    
    actionCell.innerHTML = actionHtml;
    
    // NO reordering - rows stay in original position
}
</script>
