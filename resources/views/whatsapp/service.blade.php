@extends('layouts.app')

@section('title', 'Service Notification | Mifty')

@section('content')
<div class="container-fluid">   
    
    <!-- Page Title with spacing -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Service Notification</h4>
            </div>
        </div>
    </div>

    <!-- Compact Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="notificationForm">
                        @csrf
                        <div class="row align-items-start">
                            <!-- Title Dropdown - Left Side -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Message Title <span class="text-danger">*</span></label>
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown">
                                            Select Title <i class="las la-angle-down float-end"></i>
                                        </button>
                                        <div class="dropdown-menu w-100">
                                            <a class="dropdown-item" href="#" onclick="selectTitle(event, 'Customer Welcome')">Customer Welcome</a>
                                            <a class="dropdown-item" href="#" onclick="selectTitle(event, 'Device Received')">Device Received</a>
                                            <a class="dropdown-item" href="#" onclick="selectTitle(event, 'Repair Completed')">Repair Completed</a>
                                            <a class="dropdown-item" href="#" onclick="selectTitle(event, 'Thank You Message')">Thank You Message</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-primary" href="#" onclick="showCustomTitleInput(event)">
                                                <i class="iconoir-plus me-2"></i>Add Custom Title
                                            </a>
                                        </div>
                                    </div>
                                    <input type="hidden" id="notification_title" name="notification_title" required>
                                    <!-- Custom Title Input (Hidden by default) -->
                                    <input type="text" class="form-control mt-2 d-none" id="custom_title_input" placeholder="Enter custom title">
                                </div>
                            </div>

                            <!-- Editor - Right Side -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Message <span class="text-danger">*</span></label>
                                    <!-- Snow Editor container -->
                                    <div id="snow-editor" style="height: 120px;">
                                       
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="iconoir-check-circle me-1"></i>Save Notification
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="iconoir-restart me-1"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Message Preview</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="notificationsTableBody">
                                <tr id="notification-001">
                                    <td><strong>Customer Welcome</strong></td>
                                    <td>Welcome to our Mobile Repair Service! We're here to help...</td>
                                    <td>10-Nov-2025</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-soft-primary" onclick="editNotification(event, '001')">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-soft-danger" onclick="deleteNotification(event, '001')">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="iconoir-check-circle me-2"></i>Success</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="iconoir-check-circle text-success" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Notification Saved Successfully!</h4>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Quill Editor CSS -->
<link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.snow.css') }}">

<!-- Quill Editor JS -->
<script src="{{ asset('assets/libs/quill/quill.js') }}"></script>

<script>
// Initialize Quill Editor
var snowEditor = new Quill('#snow-editor', {
    theme: 'snow'
});

// Store notification data
let notifications = {
    '001': {
        title: 'Customer Welcome',
        message: '<p><strong>Welcome to our Mobile Repair Service!</strong></p><p>We\'re here to help you with all your device repair needs.</p>'
    }
};

// Select Title from Dropdown
function selectTitle(event, title) {
    event.preventDefault();
    document.getElementById('notification_title').value = title;
    const button = event.target.closest('.btn-group').querySelector('button');
    button.innerHTML = title + ' <i class="las la-angle-down float-end"></i>';
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
    const messageHTML = snowEditor.root.innerHTML;
    const messageText = snowEditor.getText().trim();
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
            row.cells[0].innerHTML = `<strong>${title}</strong>`;
            const preview = messageText.substring(0, 60) + (messageText.length > 60 ? '...' : '');
            row.cells[1].textContent = preview;
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
    snowEditor.root.innerHTML = notification.message;
    
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
    snowEditor.root.innerHTML = '';
    
    const button = document.querySelector('.btn-group button');
    button.innerHTML = 'Select Title <i class="las la-angle-down float-end"></i>';
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="iconoir-check-circle me-1"></i>Save Notification';
    submitBtn.removeAttribute('data-edit-id');
}
</script>
@endsection
