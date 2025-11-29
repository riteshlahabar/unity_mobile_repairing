@extends('layouts.app')

@section('title', 'Festival Messages | Mifty')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center mb-2">
                <h4 class="page-title mb-0">Festival Messages</h4>
            </div>
        </div>
    </div>

    <!-- Send Message Card -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card mb-0">
                <div class="card-body p-3">
                    <form id="festivalMessageForm">
                        @csrf

                        <div class="row">
                            <!-- Left Side -->
                            <div class="col-md-4">
                                <!-- Send To Options -->
                                <div class="mb-2">
                                    <label class="form-label fw-bold mb-1">Send To <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="send_to" id="send_to_all" value="all" checked>
                                            <label class="form-check-label" for="send_to_all">
                                                All Customers ({{ $totalCustomers ?? 0 }})
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="send_to" id="send_to_selected" value="selected">
                                            <label class="form-check-label" for="send_to_selected">
                                                Selected Customers
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date Range Selection -->
                                <div class="mb-2 d-none" id="dateRangeDiv">
                                    <label class="form-label fw-bold mb-1">Select Date Range</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label mb-1" style="font-size: 12px;">From Date</label>
                                            <input type="date" class="form-control form-control-sm" id="from_date" name="from_date">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label mb-1" style="font-size: 12px;">To Date</label>
                                            <input type="date" class="form-control form-control-sm" id="to_date" name="to_date">
                                        </div>
                                    </div>
                                    <!-- Badge below date range -->
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary btn-sm">
                                            Filtered Customers <span class="badge bg-light text-dark" id="dateRangeCount">0</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <button type="submit" class="btn btn-success w-100" id="sendBtn">
                                        <i class="las la-paper-plane me-1"></i>Send Messages
                                    </button>
                                    <button type="button" class="btn btn-secondary w-100" onclick="resetForm()">
                                        <i class="las la-redo me-1"></i>Reset
                                    </button>
                                </div>
                            </div>

                            <!-- Right Side: Message Editor -->
                            <div class="col-md-8">
                                <!-- Campaign Name input -->
                                <div class="mb-2">
                                    <label for="campaign_name" class="form-label fw-bold mb-1">Campaign Name</label>
                                    <input type="text" id="campaign_name" name="campaign_name" 
                                           class="form-control form-control-sm" 
                                           placeholder="Enter campaign name (e.g. Diwali, Dasara)" 
                                           maxlength="255">
                                </div>

                                <!-- Festival Message Editor -->
                                <div class="mb-2">
                                    <label class="form-label fw-bold mb-1">Festival Message <span class="text-danger">*</span></label>
                                    <div id="editor" style="height: 220px; border: 1px solid #dee2e6; border-radius: 4px; overflow: hidden;"></div>
                                    <textarea id="message" name="message" class="d-none"></textarea>
                                    <input type="hidden" id="sent_date" name="sent_date" value="{{ date('Y-m-d') }}">
                                    <small class="text-muted">Character count: <span id="charCount">0</span>/1000</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Message History Table -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-header py-2">
                    <h5 class="card-title mb-0"><i class="las la-history me-2"></i>Message History</h5>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Campaign</th>
                                    <th>Message</th>
                                    <th>Total</th>
                                    <th>Sent</th>
                                    <th>Failed</th>
                                    <th>Status</th>
                                    <th>Sent At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($messages as $msg)
                                <tr>
                                    <td>{{ $loop->iteration + ($messages->currentPage()-1) * $messages->perPage() }}</td>
                                    <!-- Campaign name -->
                                    <td><strong>{{ $msg->campaign_name ?? 'Unnamed' }}</strong></td>
                                    <!-- Message preview (strip tags and limit) -->
                                    <td>{!! Str::limit(strip_tags($msg->message ?? ''), 60) !!}</td>
                                    <!-- Totals -->
                                    <td>{{ number_format($msg->total_customers ?? 0) }}</td>
                                    <td>{{ number_format($msg->message_sent ?? 0) }}</td>
                                    <td>{{ number_format($msg->failed_messages ?? 0) }}</td>
                                    <!-- Status badge -->
                                    <td>
                                        @if($msg->status === 'sent')
                                            <span class="badge bg-success">Sent</span>
                                        @elseif($msg->status === 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @elseif($msg->status === 'partial')
                                            <span class="badge bg-warning">Partial</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($msg->status ?? 'Pending') }}</span>
                                        @endif
                                    </td>
                                    <!-- Sent date/time -->
                                    <td>
                                        @if($msg->sent_date)
                                            <small>{{ \Carbon\Carbon::parse($msg->sent_date)->format('d-M-Y') }}</small><br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($msg->sent_date)->format('h:i A') }}</small>
                                        @else
                                            <small class="text-muted">--</small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3">
                                        <p class="text-muted mb-0">No campaigns yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-2">
                        {{ $messages->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Include Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

@include('whatsapp.scripts.festival_script')

@endsection
