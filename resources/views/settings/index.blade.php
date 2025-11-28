@extends('layouts.app')
@section('title', 'Settings | Mifty')

@section('content')

<head>
    <!-- other meta tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- other resources -->
</head>
<div class="container-fluid">
    <div class="row mb-1">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-start align-items-center">
                <h4 class="page-title mb-0" style="font-size:20px; font-weight:600;">Settings</h4>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body pt-0">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-link py-2 active" id="BusinessInfo-tab" data-bs-toggle="tab"
                                href="#BusinessInfo" role="tab" aria-controls="BusinessInfo"
                                aria-selected="true">Business Info</a>
                            <a class="nav-link py-2" id="TermsConditions-tab" data-bs-toggle="tab"
                                href="#TermsConditions" role="tab" aria-controls="TermsConditions"
                                aria-selected="false">Terms and Conditions</a>
                            <a class="nav-link py-2" id="Remarks-tab" data-bs-toggle="tab" href="#Remarks" role="tab"
                                aria-controls="Remarks" aria-selected="false">Remarks</a>
                            <a class="nav-link py-2" id="Security-tab" data-bs-toggle="tab" href="#Security" role="tab"
                                aria-controls="Security" aria-selected="false">Security</a>
                        </div>
                    </nav>

                    <div class="tab-content mt-3" id="nav-tabContent">
                        <!-- Business Info -->
                        <div class="tab-pane fade show active" id="BusinessInfo" role="tabpanel"
                            aria-labelledby="BusinessInfo-tab">
                            <form id="businessInfoForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Business Name</label>
                                    <input type="text" name="business_name" class="form-control"
                                        value="{{ old('business_name', $setting->business_name ?? '') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Owner Name</label>
                                    <input type="text" name="owner_name" class="form-control"
                                        value="{{ old('owner_name', $setting->owner_name ?? '') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mobile Number</label>
                                    <input type="text" name="mobile_number" class="form-control"
                                        value="{{ old('mobile_number', $setting->mobile_number ?? '') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $setting->email ?? '') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Address</label>
                                    <textarea name="address"
                                        class="form-control">{{ old('address', $setting->address ?? '') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success float-end">Save Business Info</button>
                            </form>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="tab-pane fade" id="TermsConditions" role="tabpanel"
                            aria-labelledby="TermsConditions-tab">
                            <form id="termsConditionsForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Terms and Conditions</label>
                                    <textarea name="terms_conditions" class="form-control" rows="6"
                                        placeholder="Enter terms and conditions here...">{{ old('terms_conditions', $setting->terms_conditions ?? '') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success float-end">Save Terms & Conditions</button>
                            </form>
                        </div>

                        <!-- Remarks -->
                        <div class="tab-pane fade" id="Remarks" role="tabpanel" aria-labelledby="Remarks-tab">
                            <form id="remarksForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="6"
                                        placeholder="Enter remarks here...">{{ old('remarks', $setting->remarks ?? '') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success float-end">Save Remarks</button>
                            </form>
                        </div>

                        <!-- Security -->
                        <div class="tab-pane fade" id="Security" role="tabpanel" aria-labelledby="Security-tab">
                            <form id="securityForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Change Password</label>
                                    <input type="password" name="current_password" class="form-control mb-2"
                                        placeholder="Current password">
                                    <input type="password" name="new_password" class="form-control mb-2"
                                        placeholder="New password">
                                    <input type="password" name="new_password_confirmation" class="form-control"
                                        placeholder="Confirm new password">
                                </div>
                                <button type="submit" class="btn btn-success float-end">Save Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal for Success Message -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="successModalBody">
                Data saved successfully.
            </div>
        </div>
    </div>
</div>

@include('settings.scripts.setting_script')
@endsection