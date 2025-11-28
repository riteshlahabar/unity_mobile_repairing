<script>
// Initialize Quill Editor
var snowEditor = new Quill('#snow-editor', {
    theme: 'snow'
});

let currentEditId = null;

// Select Title from Dropdown
function selectTitle(event, title) {
    event.preventDefault();
    document.getElementById('notification_title').value = title;
    const button = event.target.closest('.btn-group').querySelector('button');
    button.innerHTML = title + ' <i class="las la-angle-down float-end"></i>';
}

// Form Submission
document.getElementById('notificationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const title = document.getElementById('notification_title').value;
    const messageHTML = snowEditor.root.innerHTML;
    const messageText = snowEditor.getText().trim();
    const submitBtn = document.getElementById('submitBtn');
    
    if (!title || !messageText) {
        alert('Please fill all required fields!');
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    const formData = new FormData();
    formData.append('notification_title', title);
    formData.append('notification_message', messageHTML);
    formData.append('_token', '{{ csrf_token() }}');
    
    const url = currentEditId 
        ? `{{ url('whatsapp/notifications') }}/${currentEditId}/update`
        : '{{ route('whatsapp.notifications.store') }}';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Reload page after modal closes
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            alert('Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = currentEditId 
                ? '<i class="bi bi-save me-1"></i>Update Notification'
                : '<i class="iconoir-check-circle me-1"></i>Save Notification';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = currentEditId 
            ? '<i class="bi bi-save me-1"></i>Update Notification'
            : '<i class="iconoir-check-circle me-1"></i>Save Notification';
    });
});

function editNotification(event, id, title, message) {
    event.preventDefault();
    
    console.log('Edit clicked:', id, title);
    
    currentEditId = id;
    document.getElementById('notification_title').value = title;
    snowEditor.root.innerHTML = message;
    
    const button = document.querySelector('.btn-group button');
    button.innerHTML = title + ' <i class="las la-angle-down float-end"></i>';
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="bi bi-save me-1"></i>Update Notification';
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function deleteNotification(event, id) {
    event.preventDefault();
    
    if (!confirm('Are you sure you want to delete this notification?')) {
        return;
    }
    
    fetch(`{{ url('whatsapp/notifications') }}/${id}/delete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById(`notification-${id}`);
            if (row) {
                row.remove();
            }
            
            // Check if table is empty
            const tbody = document.getElementById('notificationsTableBody');
            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <p class="text-muted mb-0">No notifications yet. Create your first notification above.</p>
                        </td>
                    </tr>
                `;
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting.');
    });
}

function resetForm() {
    currentEditId = null;
    document.getElementById('notificationForm').reset();
    document.getElementById('notification_title').value = '';
    snowEditor.root.innerHTML = '';
    
    const button = document.querySelector('.btn-group button');
    button.innerHTML = 'Select Title <i class="las la-angle-down float-end"></i>';
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>Save Notification';
    submitBtn.disabled = false;
}
</script>
