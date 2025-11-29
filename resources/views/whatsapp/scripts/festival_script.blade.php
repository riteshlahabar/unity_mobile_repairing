<script>
// Initialize Quill Editor
var quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Enter your festival message here...',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['link'],
            ['clean']
        ]
    }
});

// Update hidden textarea and character count
quill.on('text-change', function() {
    var plainText = quill.getText().trim();
    var html = quill.root.innerHTML.trim();
    document.getElementById('message').value = html;
    document.getElementById('charCount').textContent = plainText.length;
});

// Show/hide date range div based on radio
document.querySelectorAll('input[name="send_to"]').forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === 'selected') {
            document.getElementById('dateRangeDiv').classList.remove('d-none');
        } else {
            document.getElementById('dateRangeDiv').classList.add('d-none');
            document.getElementById('dateRangeCount').textContent = '0';
            // Clear date fields when switching to 'all'
            document.getElementById('from_date').value = '';
            document.getElementById('to_date').value = '';
        }
    });
});

// Auto-update customer count when dates change
document.getElementById('from_date').addEventListener('change', updateDateRangeCount);
document.getElementById('to_date').addEventListener('change', updateDateRangeCount);

function updateDateRangeCount() {
    const fromDate = document.getElementById('from_date').value;
    const toDate = document.getElementById('to_date').value;

    // Only fetch if BOTH dates are selected
    if (fromDate && toDate) {
        if (fromDate > toDate) {
            alert('From Date cannot be greater than To Date');
            document.getElementById('to_date').value = '';
            document.getElementById('dateRangeCount').textContent = '0';
            return;
        }

        // Fetch customer count automatically
        fetch('/whatsapp/festival/count-by-date', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                from_date: fromDate, 
                to_date: toDate 
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('dateRangeCount').textContent = data.count;
            
            if (data.count === 0) {
                alert('No customers found in the selected date range');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load customer count');
            document.getElementById('dateRangeCount').textContent = '0';
        });
    } else {
        document.getElementById('dateRangeCount').textContent = '0';
    }
}

// Reset form
function resetForm() {
    document.getElementById('festivalMessageForm').reset();
    quill.setText('');
    document.getElementById('charCount').textContent = '0';
    document.getElementById('dateRangeDiv').classList.add('d-none');
    document.getElementById('dateRangeCount').textContent = '0';
}

// Submit form
document.getElementById('festivalMessageForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const sendBtn = document.getElementById('sendBtn');
    const formData = new FormData(this);

    // Get message from editor
    const message = quill.getText().trim();
    if (!message) {
        alert('Please enter a message');
        return;
    }

    // Validate date range if selected
    const sendTo = formData.get('send_to');
    if (sendTo === 'selected') {
        const fromDate = document.getElementById('from_date').value;
        const toDate = document.getElementById('to_date').value;

        if (!fromDate || !toDate) {
            alert('Please select a valid date range');
            return;
        }

        const count = parseInt(document.getElementById('dateRangeCount').textContent);
        if (count === 0) {
            alert('No customers found in selected date range');
            return;
        }
    }

    // Calculate count for confirmation
    const count = sendTo === 'all' ? parseInt('{{ $totalCustomers }}', 10) : parseInt(document.getElementById('dateRangeCount').textContent || '0', 10);

    
    if (!confirm(`Send this message to ${count} customers?`)) {
        return;
    }

    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="las la-spinner la-spin me-1"></i>Sending...';

    // Prepare payload - ONLY include dates if send_to is 'selected'
    document.getElementById('message').value = quill.root.innerHTML.trim();
    const messageHtml = document.getElementById('message').value;

    let sentDateInput = document.getElementById('sent_date');
    let sentDateValue = sentDateInput ? sentDateInput.value : null;
    if (!sentDateValue) {
        const today = new Date().toISOString().slice(0,10);
        sentDateValue = today;
        if (sentDateInput) sentDateInput.value = today;
    }

    // Build payload conditionally
    const data = {
        message: messageHtml,
        send_to: sendTo,
        sent_date: sentDateValue
    };
    
    const campaignNameInput = document.getElementById('campaign_name').value.trim();
if (campaignNameInput.length > 0) {
    data.campaign_name = campaignNameInput;
}

    // Only include dates if send_to is 'selected'
    if (sendTo === 'selected') {
        data.from_date = document.getElementById('from_date').value;
        data.to_date = document.getElementById('to_date').value;
    }

    fetch('/whatsapp/festival/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(resp => {
        if (resp.success) {
            alert(resp.message);
            window.location.reload();
        } else {
            alert('Error: ' + (resp.message || 'Unknown error'));
        }
    })
    .catch(e => {
        alert('Failed to send messages');
        console.error(e);
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="las la-paper-plane me-1"></i>Send Messages';
    });
});
</script>
