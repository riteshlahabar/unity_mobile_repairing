@extends('layouts.app')

@section('title', 'Revenue Report | Mifty')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Revenue Report</h4>
            </div>
        </div>
    </div>

    <!-- PIN Verification Modal -->
    <div class="modal fade" id="pinModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-lock me-2"></i>Enter PIN to Access Revenue</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">PIN Code <span class="text-danger">*</span></label>
                        <input type="password" class="form-control text-center" id="pinInput" placeholder="Enter 4-digit PIN" maxlength="4">
                    </div>
                    <div id="pinError" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="verifyPin()">
                        <i class="fas fa-check me-2"></i>Verify
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Content (Hidden until PIN verified) -->
    <div id="revenueContent" class="d-none">
        <!-- Profit Cards -->
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Today's Profit</h6>
                        <h3 class="fw-bold">₹<span id="todayProfit">0.00</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Weekly Profit</h6>
                        <h3 class="fw-bold">₹<span id="weeklyProfit">0.00</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="card-title">Monthly Profit</h6>
                        <h3 class="fw-bold">₹<span id="monthlyProfit">0.00</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Yearly Profit</h6>
                        <h3 class="fw-bold">₹<span id="yearlyProfit">0.00</span></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit Details Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jobsheet ID</th>
                                        <th>Customer Name</th>
                                        <th>Estimated Amount</th>
                                        <th>Problem</th>
                                        <th>Service Charge</th>
                                        <th>Spare Parts Charge</th>
                                        <th>Other Charge</th>
                                        <th>Profit</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="profitTableBody">
                                    <!-- Populated by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profit Modal -->
<div class="modal fade" id="editProfitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Profit Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editJobsheetId">
                <div class="mb-3">
                    <label class="form-label">Jobsheet ID</label>
                    <input type="text" class="form-control" id="editJobsheetIdDisplay" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Service Charge (₹)</label>
                    <input type="number" class="form-control" id="editServiceCharge" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="mb-3">
                    <label class="form-label">Spare Parts Charge (₹)</label>
                    <input type="number" class="form-control" id="editSparePartsCharge" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="mb-3">
                    <label class="form-label">Other Charge (₹)</label>
                    <input type="number" class="form-control" id="editOtherCharge" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="alert alert-info">
                    <strong>Total Charges:</strong> ₹<span id="totalCharges">0.00</span><br>
                    <strong>Profit:</strong> ₹<span id="calculatedProfit">0.00</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveProfitData()">
                    <i class="fas fa-save me-2"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>

@include('reports.scripts.revenue')
@endsection
