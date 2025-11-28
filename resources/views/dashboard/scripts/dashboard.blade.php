<script>
// Mark Complete (AJAX)
function markComplete(event, jobsheetId) {
    event.preventDefault();
    
    

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

    // Don't generate OTP yet, just open warranty modal
    openWarrantyModal(jobsheetId);
}


//open warranty modal
function openWarrantyModal(jobsheetId) {
    document.getElementById('warranty_jobsheet_id').value = jobsheetId;

    // Reset payment modal steps here if you want to prepare it but keep it hidden for now.
    document.getElementById('paymentStep').classList.remove('d-none');
    document.getElementById('otpStep').classList.add('d-none');
    document.getElementById('deliveryStep').classList.add('d-none');
    document.getElementById('otpVerified').classList.add('d-none');
    document.getElementById('otp_input').value = '';

    // Show warranty modal first
    const warrantyModal = new bootstrap.Modal(document.getElementById('warrantyModal'));
    warrantyModal.show();
}
// Updated showWarrantyStep with previous jobsheet fetch
function showWarrantyStep() {
    const jobsheetId = document.getElementById('current_jobsheet_id').value;
    document.getElementById('warranty_jobsheet_id').value = jobsheetId;
    
    // Hide payment, show warranty
    document.getElementById('paymentStep').classList.add('d-none');
    document.getElementById('warrantyStep').classList.remove('d-none');
    
    // Reset & load previous jobsheets
    document.getElementById('warranty_months').value = '';
    loadPreviousJobsheets(jobsheetId);
}

// Fetch previous jobsheets with valid warranty
function loadPreviousJobsheets(jobsheetId) {
    const select = document.getElementById('previous_jobsheet_warranty');
    select.innerHTML = '<option value="">Loading...</option>';
    
    fetch(`/jobsheets/${jobsheetId}/previous-warranties`, {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        let options = '<option value="">New Warranty (No Previous)</option>';
        if (data.previous_jobsheets?.length) {
            data.previous_jobsheets.forEach(job => {
                options += `<option value="${job.jobsheet_id}">${job.jobsheet_id} - ${job.warranty} months (Expires: ${job.warranty_expiry_date})</option>`;
            });
        }
        select.innerHTML = options;
    })
    .catch(() => {
        select.innerHTML = '<option value="">Error loading</option>';
    });
}

// Updated submitWarrantyInModal
function submitWarrantyInModal() {
    const jobsheetId = document.getElementById('warranty_jobsheet_id').value;
    const months = document.getElementById('warranty_months').value;
    const prevJobId = document.getElementById('previous_jobsheet_warranty').value;

    // ✅ FIX: Allow previous jobsheet OR new warranty
    if (!months && !prevJobId) {
        alert('Please select warranty period OR previous jobsheet');
        return;
    }

    fetch(`/jobsheets/${jobsheetId}/save-warranty`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            warranty_months: months || null,  // Can be null if using previous
            previous_jobsheet_id: prevJobId || null
        })
    })
    .then(res => {
        if (!res.ok) throw new Error('Network error');
        return res.json();
    })
    .then(data => {
        if (data.success) {
            // Hide warranty, show OTP step
            document.getElementById('warrantyStep').classList.add('d-none');
            document.getElementById('otpStep').classList.remove('d-none');
            document.getElementById('otpVerified').classList.add('d-none');
            
            // Generate OTP
            fetch(`/jobsheets/${jobsheetId}/generate-otp`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Error saving warranty');
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
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // ✅ SUCCESS: Close modal, refresh table, show success
            const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
            modal.hide();
            
        
            updateJobRow(jobsheetId, data.jobsheet);
        
            // Show success notification
            showToast('Mobile delivered successfully!', 'success');
            
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Mark Delivered Error:', error);
        alert('Failed to mark as delivered: ' + (error.message || 'Unknown error'));
    });
}

function showToast(message, type = 'success') {
    const bgClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const toast = document.createElement('div');

    toast.className = `alert ${bgClass} alert-dismissible fade show position-fixed`;
    toast.style.cssText = `
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        min-width: 280px;
        text-align: center;
        z-index: 2000;
    `;

    toast.innerHTML = `
        <div class="fw-bold">${message}</div>
    `;

    document.body.appendChild(toast);

    // Auto remove after 2.5 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hide');
        toast.addEventListener('transitionend', () => toast.remove());
    }, 2500);
}


// Update job row in table (WITHOUT reordering)
function updateJobRow(jobsheetId, jobsheet) {
    const row = document.getElementById(`job-${jobsheetId}`);
    if (!row) return;

    // Update status button cell (7th cell - index 6)
    const statusCell = row.cells[6];
    let statusHtml = '';

    if (jobsheet.status === 'in_progress') {
        statusHtml = `<button type="button" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px; border-radius: 20px;">
            <i class="iconoir-clock me-2"></i>
            <span>In Progress</span>
        </button>`;
    } else if (jobsheet.status === 'call_info') {
        statusHtml = `<button type="button" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px; border-radius: 20px;">
            <i class="iconoir-phone me-2"></i>
            <span>Call Info</span>
        </button>`;
    } else if (jobsheet.status === 'approval_pending') {
        statusHtml = `<button type="button" class="btn btn-secondary btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px; border-radius: 20px;">
            <i class="iconoir-hourglass me-2"></i>
            <span>Approval Pending</span>
        </button>`;
    } else if (jobsheet.status === 'customer_approved') {
        statusHtml = `<button type="button" class="btn btn-info btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px; border-radius: 20px;">
            <i class="iconoir-check-circle me-2"></i>
            <span>Customer Approved</span>
        </button>`;
    } else if (jobsheet.status === 'not_okay_return') {
    statusHtml = `<button type="button" class="btn btn-dark btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px; border-radius: 20px;">
        <i class="fas fa-times-circle me-2"></i>
        <span>Not Okay Return</span>
    </button>`;
} else if (jobsheet.status === 'ready') {
    statusHtml = `<button type="button" class="btn btn-success btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px; border-radius: 20px;">
        <i class="fas fa-check-double me-2"></i>
        <span>Ready</span>
    </button>`;
    } else if (jobsheet.status === 'return') {
        statusHtml = `<button type="button" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px; border-radius: 20px;">
            <i class="iconoir-undo me-2"></i>
            <span>Return</span>
        </button>`;
    } else if (jobsheet.status === 'delivered') {
        statusHtml = `
<button type="button" class="btn btn-success btn-sm d-flex align-items-center justify-content-center" style="min-width: 140px; border-radius: 20px;">
    <i class="fas fa-box me-2"></i>
    <span>Delivered</span>
</button>`;
    } else {
        statusHtml = `<span>${jobsheet.status}</span>`;
    }

    statusCell.innerHTML = statusHtml;
}

// ===== ADD THE NEW CODE BELOW THIS LINE =====

// Handle Status Selection for In Progress Jobs
function selectStatus(event, jobsheetId, newStatus) {
    event.preventDefault();

    // Validate status parameter
    if (!newStatus || newStatus === 'undefined') {
        alert('Invalid status selected');
        return;
    }

    const statusDisplayMap = {
        'in_progress': 'In Progress',
        'call_info': 'Call Info',
        'approval_pending': 'Approval Pending',
        'customer_approved': 'Customer Approved',
        'not_okay_return': 'Not Okay Return',
        'ready': 'Ready',
        'return': 'Return',
        'delivered': 'Delivered'
    };

    // if (!confirm(`Change status to "${statusDisplayMap[newStatus]}"?`)) {
    //     return;
    // }

    fetch(`/jobsheets/${jobsheetId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateJobRow(jobsheetId, { status: newStatus });
            //alert(`Status updated to "${statusDisplayMap[newStatus]}" and WhatsApp message sent.`);
        } else {
            alert('Failed to update status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
        alert('Error updating status');
    });
}

// Refresh specific job sheet row in table




</script>
