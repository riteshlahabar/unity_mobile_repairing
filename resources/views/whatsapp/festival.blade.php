@extends('layouts.app')

@section('title', 'Create Festival Message | Mifty')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Create Festival Message</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('festival-messages.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Message Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" placeholder="e.g., Diwali Wishes 2025" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="message" rows="8" placeholder="Type your festival message here..." required></textarea>
                            <small class="text-muted">Use {{customer_name}} to personalize with customer's name</small>
                        </div>

                        <div class="alert alert-info">
                            <strong>Note:</strong> This message will be sent to all {{ $totalCustomers }} customers in your database.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save me-1"></i>Create Message
                            </button>
                            <a href="{{ route('festival-messages.index') }}" class="btn btn-secondary">
                                <i class="las la-times me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sample Messages</h5>
                    
                    <div class="mb-3">
                        <strong>Diwali:</strong>
                        <p class="text-muted small">Dear {{customer_name}}, Wishing you and your family a very Happy Diwali! May this festival of lights bring joy, prosperity and happiness to your life. - Unity Mobile Repairing</p>
                    </div>

                    <div class="mb-3">
                        <strong>New Year:</strong>
                        <p class="text-muted small">Happy New Year {{customer_name}}! May 2025 bring you success, good health and happiness. Thank you for being our valued customer. - Unity Mobile Repairing</p>
                    </div>

                    <div class="mb-3">
                        <strong>Holi:</strong>
                        <p class="text-muted small">Dear {{customer_name}}, Wishing you a colorful and joyful Holi! May your life be filled with vibrant colors of happiness. - Unity Mobile Repairing</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
