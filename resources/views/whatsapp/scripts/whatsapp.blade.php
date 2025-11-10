<script>
// Initialize Quill Editor
var quill = new Quill('#editor', {
    theme: 'snow'
});

// Store notification data
let notifications = {
    '001': {
        title: 'Customer Welcome',
        message: '<p><strong>Welcome to our Mobile Repair Service!</strong></p><p>We\'re here to help you with all your device repair needs.</p>'
    },
    '002': {
        title: 'Device Received',
        message: '<p>Your device has been <strong>received</strong> and is being inspected by our technicians.</p>'
    },
    '003': {
        title: 'Thank You Message',
        message: '<p>Thank you for choosing our service. We appreciate your trust! 😊</p>'
    }
};

// Select Title from Dropdown
function selectTitle(event, title) {
    event.preventDefault();
    document.getElementById('notification_title').value = title;
    const button = event.target.closest('.btn-group').querySelector('button');
    button.innerHTML = title + ' <i class="las la-angle-down float-end"></i>';
    
    // Hide custom input if shown
    document.getElementById('custom_title_input').classList.add('d-none');
}

// Show Custom Title Input
function showCustomTitleInput(event) {
    event.preventDefault();
    const customInput = document.getElementById('custom_title_input');
    customInput.classList.remove('d-none');
    customInput.focus();
    customInput.value = '';
    
    customInput.oninput = function() {
        document.getElementById('notification_title').value = this.value;
        const button = document.querySelector('.btn-group button');
        button.innerHTML = (this.value || 'Select Title') + ' <i class="las la-angle-down float-end"></i>';
    };
}

// Form Submission
document.getElementById('notificationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const title = document.getElementById('notification_title').value;
    const messageHTML = quill.root.innerHTML;
    const messageText = quill.getText().trim();
    const submitBtn = document.getElementById('submitBtn');
    const editId = submitBtn.getAttribute('data-edit-id');
    
    if (!title || !messageText) {
        alert('Please fill all required fields!');
        return;
    }
    
    if (editId) {
        notifications[editId] = { title, message: messageHTML };
        
        const row = document.getElementById(`notification-${editId}`);
        if (row) {
            row.cells[1].innerHTML = `<strong>${title}</strong>`;
            const preview = messageText.substring(0, 60) + (messageText.length > 60 ? '...' : '');
            row.cells[2].textContent = preview;
        }
        
        submitBtn.removeAttribute('data-edit-id');
        submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>Save Notification';
    } else {
        const id = String(Math.floor(Math.random() * 999) + 1).padStart(3, '0');
        const today = new Date().toLocaleDateString('en-GB');
        
        notifications[id] = { title, message: messageHTML };
        addNotificationToTable(id, title, messageText, today);
    }
    
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    
    resetForm();
});

function addNotificationToTable(id, title, messagePreview, date) {
    const tbody = document.getElementById('notificationsTableBody');
    const row = document.createElement('tr');
    row.id = `notification-${id}`;
    
    const preview = messagePreview.substring(0, 60) + (messagePreview.length > 60 ? '...' : '');
    
    row.innerHTML = `
        <td><span class="badge bg-primary-subtle text-primary">#${id}</span></td>
        <td><strong>${title}</strong></td>
        <td>${preview}</td>
        <td>${date}</td>
        <td class="text-end">
            <a href="#" class="btn btn-sm btn-soft-primary" onclick="editNotification(event, '${id}')">
                <i class="las la-edit"></i>
            </a>
            <a href="#" class="btn btn-sm btn-soft-danger" onclick="deleteNotification(event, '${id}')">
                <i class="las la-trash"></i>
            </a>
        </td>
    `;
    
    tbody.insertBefore(row, tbody.firstChild);
}

function editNotification(event, id) {
    event.preventDefault();
    
    const notification = notifications[id];
    if (!notification) {
        alert('Notification not found!');
        return;
    }
    
    document.getElementById('notification_title').value = notification.title;
    quill.root.innerHTML = notification.message;
    
    const button = document.querySelector('.btn-group button');
    button.innerHTML = notification.title + ' <i class="las la-angle-down float-end"></i>';
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="iconoir-save me-1"></i>Update Notification';
    submitBtn.setAttribute('data-edit-id', id);
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function deleteNotification(event, id) {
    event.preventDefault();
    
    if (confirm('Are you sure you want to delete this notification?')) {
        const row = document.getElementById(`notification-${id}`);
        if (row) {
            row.remove();
            delete notifications[id];
        }
    }
}

function resetForm() {
    document.getElementById('notificationForm').reset();
    document.getElementById('notification_title').value = '';
    document.getElementById('custom_title_input').classList.add('d-none');
    quill.root.innerHTML = '<p>Hello World!</p><p>Some initial <strong>bold</strong> text</p>';
    
    const button = document.querySelector('.btn-group button');
    button.innerHTML = 'Select Title <i class="las la-angle-down float-end"></i>';
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>Save Notification';
    submitBtn.removeAttribute('data-edit-id');
}
</script>
