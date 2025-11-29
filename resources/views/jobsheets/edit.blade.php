@extends('layouts.app')

@section('title', 'Edit JobSheet | Mifty')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Edit JobSheet - {{ $jobSheet->jobsheet_id }}</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('jobsheets.index') }}">JobSheets</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form action="{{ route('jobsheets.update', $jobSheet->jobsheet_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Customer Information (Read-only) -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-user me-2"></i>Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Customer ID</label>
                                    <input type="text" class="form-control" value="{{ $jobSheet->customer->customer_id }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" value="{{ $jobSheet->customer->full_name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" value="{{ $jobSheet->customer->contact_no }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Device Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-mobile me-2"></i>Device Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Company <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="company" value="{{ $jobSheet->company }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Model <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="model" value="{{ $jobSheet->model }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Color <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="color" value="{{ $jobSheet->color }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Series <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="series" value="{{ $jobSheet->series }}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">IMEI (Optional)</label>
                                    <input type="text" class="form-control" name="imei" value="{{ $jobSheet->imei }}" maxlength="15">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Problem Description -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-exclamation-triangle me-2"></i>Problem Description</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Problem Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="problem_description" rows="3" required>{{ $jobSheet->problem_description }}</textarea>
                        </div>
                        
                        <label class="form-label">Device Status</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status_dead" id="status_dead" {{ $jobSheet->status_dead ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_dead">Dead</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status_damage" id="status_damage" {{ $jobSheet->status_damage ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_damage">Damaged</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status_on" id="status_on" {{ $jobSheet->status_on ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_on">On with Problem</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Device Condition -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-cog me-2"></i>Device Condition</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Device Condition <span class="text-danger">*</span></label>
                                    <select class="form-select" name="device_condition" required>
                                        <option value="fresh" {{ $jobSheet->device_condition == 'fresh' ? 'selected' : '' }}>Fresh</option>
                                        <option value="shop_return" {{ $jobSheet->device_condition == 'shop_return' ? 'selected' : '' }}>Shop Return</option>
                                        <option value="other" {{ $jobSheet->device_condition == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Water Damage <span class="text-danger">*</span></label>
                                    <select class="form-select" name="water_damage" required>
                                        <option value="none" {{ $jobSheet->water_damage == 'none' ? 'selected' : '' }}>None</option>
                                        <option value="lite" {{ $jobSheet->water_damage == 'lite' ? 'selected' : '' }}>Lite</option>
                                        <option value="full" {{ $jobSheet->water_damage == 'full' ? 'selected' : '' }}>Full</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Physical Damage <span class="text-danger">*</span></label>
                                    <select class="form-select" name="physical_damage" required>
                                        <option value="none" {{ $jobSheet->physical_damage == 'none' ? 'selected' : '' }}>None</option>
                                        <option value="lite" {{ $jobSheet->physical_damage == 'lite' ? 'selected' : '' }}>Lite</option>
                                        <option value="full" {{ $jobSheet->physical_damage == 'full' ? 'selected' : '' }}>Full</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accessories -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-plug me-2"></i>Accessories</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="accessory_sim_tray" id="accessory_sim_tray" {{ $jobSheet->accessory_sim_tray ? 'checked' : '' }}>
                                    <label class="form-check-label" for="accessory_sim_tray">SIM Tray</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="accessory_sim_card" id="accessory_sim_card" {{ $jobSheet->accessory_sim_card ? 'checked' : '' }}>
                                    <label class="form-check-label" for="accessory_sim_card">SIM Card</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="accessory_memory_card" id="accessory_memory_card" {{ $jobSheet->accessory_memory_card ? 'checked' : '' }}>
                                    <label class="form-check-label" for="accessory_memory_card">Memory Card</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="accessory_mobile_cover" id="accessory_mobile_cover" {{ $jobSheet->accessory_mobile_cover ? 'checked' : '' }}>
                                    <label class="form-check-label" for="accessory_mobile_cover">Mobile Cover</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Other Accessories</label>
                            <input type="text" class="form-control" name="other_accessories" value="{{ $jobSheet->other_accessories }}">
                        </div>
                    </div>
                </div>

                <!-- Security Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-lock me-2"></i>Security Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Device Password</label>
                                    <input type="text" class="form-control" name="device_password" value="{{ $jobSheet->device_password }}">
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mb-0"><small>Note: Pattern lock image cannot be edited. Create a new jobsheet to change it.</small></p>
                    </div>
                </div>

                <!-- Service Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-tools me-2"></i>Service Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Technician</label>
                                    <input type="text" class="form-control" name="technician" value="{{ $jobSheet->technician }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <input type="text" class="form-control" name="location" value="{{ $jobSheet->location }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cost Details -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="las la-rupee-sign me-2"></i>Cost Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Estimated Cost <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="estimated_cost" id="estimated_cost" value="{{ $jobSheet->estimated_cost }}" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Advance Paid <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="advance" id="advance" value="{{ $jobSheet->advance }}" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Balance</label>
                                    <input type="number" class="form-control" id="balance" value="{{ $jobSheet->balance }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="las la-sticky-note me-2"></i>Notes</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Notes (Optional)</label>
            <textarea class="form-control" name="notes" rows="4" placeholder="Add any additional notes or remarks...">{{ $jobSheet->notes }}</textarea>
        </div>
    </div>
</div>


               
                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-save me-1"></i>Update JobSheet
                            </button>
                            <a href="{{ route('jobsheets.show', $jobSheet->jobsheet_id) }}" class="btn btn-secondary">
                                <i class="las la-times me-1"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
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
                <h4 class="mt-3">JobSheet Updated Successfully!</h4>
                <p class="text-muted mb-0">JobSheet ID: {{ $jobSheet->jobsheet_id }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="{{ route('jobsheets.show', $jobSheet->jobsheet_id) }}" class="btn btn-primary">
                    <i class="las la-eye me-1"></i>View JobSheet
                </a>
                <a href="{{ route('jobsheets.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    });
</script>
@endif


<script>
// Auto-calculate balance
document.getElementById('estimated_cost').addEventListener('input', calculateBalance);
document.getElementById('advance').addEventListener('input', calculateBalance);

function calculateBalance() {
    const estimated = parseFloat(document.getElementById('estimated_cost').value) || 0;
    const advance = parseFloat(document.getElementById('advance').value) || 0;
    const balance = Math.max(0, estimated - advance);
    document.getElementById('balance').value = balance.toFixed(2);
}
</script>

@endsection
