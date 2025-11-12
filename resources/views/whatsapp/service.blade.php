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
                                            <a class="dropdown-item" href="#" onclick="selectTitle(event, 'OTP Notification')">OTP Notification</a>
                                            <a class="dropdown-item" href="#" onclick="selectTitle(event, 'Thank You Message')">Thank You Message</a>
                                        </div>
                                    </div>
                                    <input type="hidden" id="notification_title" name="notification_title" required>
                                </div>
                            </div>

                            <!-- Editor - Right Side -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Message <span class="text-danger">*</span></label>
                                    <!-- Snow Editor container -->
                                    <div id="snow-editor" style="height: 120px;"></div>
                                    <input type="hidden" id="notification_message" name="notification_message">
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
                                @forelse($notifications as $notification)
                                <tr id="notification-{{ $notification->id }}">
                                    <td><strong>{{ $notification->title }}</strong></td>
                                    <td>{{ Str::limit(strip_tags($notification->message), 60) }}</td>
                                    <td>{{ $notification->created_at->format('d-M-Y') }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-soft-primary" onclick='editNotification(event, {{ $notification->id }}, @json($notification->title), @json($notification->message))'>
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-soft-danger" onclick="deleteNotification(event, {{ $notification->id }})">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="text-muted mb-0">No notifications yet. Create your first notification above.</p>
                                    </td>
                                </tr>
                                @endforelse
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

<!-- Include WhatsApp Scripts -->
@include('whatsapp.scripts.service_script')

@endsection
