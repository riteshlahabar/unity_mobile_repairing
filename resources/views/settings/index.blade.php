@extends('layouts.app')
@section('title', 'Settings | Mifty')

@section('content')
<div class="container-fluid">
    <div class="row mb-1"><!-- reduced mb from 3 to 1 to minimize space -->
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
                    <form method="POST" action="{{ route('setting.update') }}" id="custom-step"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-link py-2 active" id="BusinessInfo-tab" data-bs-toggle="tab" href="#BusinessInfo"
                                    role="tab" aria-controls="BusinessInfo" aria-selected="true">Business Info</a>
                                <a class="nav-link py-2" id="TermsConditions-tab" data-bs-toggle="tab" href="#TermsConditions" role="tab"
                                    aria-controls="TermsConditions" aria-selected="false">Terms and Conditions</a>
                                <a class="nav-link py-2" id="Remarks-tab" data-bs-toggle="tab" href="#Remarks" role="tab"
                                    aria-controls="Remarks" aria-selected="false">Remarks</a>
                                <a class="nav-link py-2" id="LogoUpdate-tab" data-bs-toggle="tab" href="#LogoUpdate" role="tab"
                                    aria-controls="LogoUpdate" aria-selected="false">Logo Update</a>
                                <a class="nav-link py-2" id="ProfilePictureUpdate-tab" data-bs-toggle="tab" href="#ProfilePictureUpdate" role="tab"
                                    aria-controls="ProfilePictureUpdate" aria-selected="false">Profile Picture Update</a>
                                <a class="nav-link py-2" id="UnitySignature-tab" data-bs-toggle="tab" href="#UnitySignature" role="tab"
                                    aria-controls="UnitySignature" aria-selected="false">Unity Signature</a>
                                <a class="nav-link py-2" id="Security-tab" data-bs-toggle="tab" href="#Security" role="tab"
                                    aria-controls="Security" aria-selected="false">Security</a>
                            </div>
                        </nav>

                        <div class="tab-content mt-3" id="nav-tabContent">

                            <!-- Business Info -->
                            <div class="tab-pane fade show active" id="BusinessInfo" role="tabpanel"
                                aria-labelledby="BusinessInfo-tab">
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
                                    <input type="text" name="mobile" class="form-control"
                                        value="{{ old('mobile', $setting->mobile ?? '') }}">
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
                                <button type="button" class="btn btn-primary mt-2 float-end"
                                    data-next="TermsConditions-tab">Next</button>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="tab-pane fade" id="TermsConditions" role="tabpanel" aria-labelledby="TermsConditions-tab">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Terms and Conditions</label>
                                    <textarea name="terms_conditions" class="form-control" rows="6"
                                        placeholder="Enter terms and conditions here...">{{ old('terms_conditions', $setting->terms_conditions ?? '') }}</textarea>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="consent_checkbox" class="form-check-input"
                                        id="consentCheckbox" {{ !empty($setting->consent_checkbox) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="consentCheckbox">I agree to the terms and
                                        conditions</label>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        data-prev="BusinessInfo-tab">Previous</button>
                                    <button type="button" class="btn btn-primary float-end"
                                        data-next="Remarks-tab">Next</button>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="tab-pane fade" id="Remarks" role="tabpanel" aria-labelledby="Remarks-tab">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="6"
                                        placeholder="Enter remarks here...">{{ old('remarks', $setting->remarks ?? '') }}</textarea>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        data-prev="TermsConditions-tab">Previous</button>
                                    <button type="button" class="btn btn-primary float-end"
                                        data-next="LogoUpdate-tab">Next</button>
                                </div>
                            </div>

                            <!-- Logo Update -->
                            <div class="tab-pane fade" id="LogoUpdate" role="tabpanel" aria-labelledby="LogoUpdate-tab">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload Logo</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        data-prev="Remarks-tab">Previous</button>
                                    <button type="button" class="btn btn-primary float-end"
                                        data-next="ProfilePictureUpdate-tab">Next</button>
                                </div>
                            </div>

                            <!-- Profile Picture Update -->
                            <div class="tab-pane fade" id="ProfilePictureUpdate" role="tabpanel" aria-labelledby="ProfilePictureUpdate-tab">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload Profile Picture</label>
                                    <input type="file" name="profile_picture" class="form-control" accept="image/*">
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        data-prev="LogoUpdate-tab">Previous</button>
                                    <button type="button" class="btn btn-primary float-end"
                                        data-next="UnitySignature-tab">Next</button>
                                </div>
                            </div>

                            <!-- Unity Signature -->
                            <div class="tab-pane fade" id="UnitySignature" role="tabpanel" aria-labelledby="UnitySignature-tab">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload Unity Signature</label>
                                    <input type="file" name="unity_signature" class="form-control" accept="image/*">
                                </div>
                                @if(!empty($setting->unity_signature))
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Current Unity Signature</label><br>
                                    <img src="{{ asset('storage/' . $setting->unity_signature) }}" alt="Unity Signature"
                                        style="max-width: 300px;">
                                </div>
                                @endif
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        data-prev="ProfilePictureUpdate-tab">Previous</button>
                                    <button type="button" class="btn btn-primary float-end"
                                        data-next="Security-tab">Next</button>
                                </div>
                            </div>

                            <!-- Security -->
                            <div class="tab-pane fade" id="Security" role="tabpanel" aria-labelledby="Security-tab">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Change Password</label>
                                    <input type="password" name="current_password" class="form-control mb-2"
                                        placeholder="Current password">
                                    <input type="password" name="new_password" class="form-control mb-2"
                                        placeholder="New password">
                                    <input type="password" name="new_password_confirmation" class="form-control"
                                        placeholder="Confirm new password">
                                </div>
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        data-prev="UnitySignature-tab">Previous</button>
                                    <button type="submit" class="btn btn-primary float-end">Save All</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var hash = window.location.hash;
        if (hash) {
            var triggerTab = document.querySelector('a.nav-link[href="' + hash + '"]');
            if (triggerTab) {
                var tab = new bootstrap.Tab(triggerTab);
                tab.show();
            }
        }
    });
</script>

@endsection
