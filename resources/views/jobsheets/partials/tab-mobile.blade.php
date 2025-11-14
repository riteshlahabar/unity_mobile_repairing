<!-- Tab 1: Mobile Details -->
<div class="tab-pane active" id="mobile">
    <fieldset id="wizardFieldset">
        <div class="row">
            <!-- Company Dropdown -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Company <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Company <i class="las la-angle-down float-end"></i>
                        </button>
                        <ul class="dropdown-menu w-100" id="companyDropdown" role="menu">
                            <!-- Dynamically loaded -->
                        </ul>
                    </div>
                    <input type="hidden" id="company" name="company" required>
                </div>
            </div>

            <!-- Model Dropdown -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Model <i class="las la-angle-down float-end"></i>
                        </button>
                        <ul class="dropdown-menu w-100" id="modelDropdown" role="menu">
                            <!-- Dynamically loaded -->
                        </ul>
                    </div>
                    <input type="hidden" id="model" name="model" required>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Color Dropdown -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Color <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Color <i class="las la-angle-down float-end"></i>
                        </button>
                        <ul class="dropdown-menu w-100" id="colorDropdown" role="menu">
                            <!-- Dynamically loaded -->
                        </ul>
                    </div>
                    <input type="hidden" id="color" name="color" required>
                </div>
            </div>

            <!-- Series Dropdown -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Series <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Series <i class="las la-angle-down float-end"></i>
                        </button>
                        <ul class="dropdown-menu w-100" id="seriesDropdown" role="menu">
                            <!-- Dynamically loaded -->
                        </ul>
                    </div>
                    <input type="hidden" id="series" name="series" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="imei" class="form-label">IMEI Number <small class="text-muted">(Optional)</small></label>
                    <input type="text" class="form-control" id="imei" name="imei" placeholder="Enter IMEI number" maxlength="15">
                </div>
            </div>
        </div>
    </fieldset>

    <div class="mt-3">
        <button type="button" class="btn btn-primary float-end" id="mobileNextBtn" onclick="nextTab('problem-tab')" disabled>Next</button>
    </div>
</div>

<!-- Reusable Master Modal -->
<div class="modal fade" id="addMasterModal" tabindex="-1" aria-labelledby="addMasterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMasterModalLabel">Add New</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addMasterForm" novalidate>
                    <div class="mb-3">
                        <label for="masterInput" class="form-label" id="masterInputLabel"></label>
                        <!-- Removed required attribute -->
                        <input type="text" class="form-control" id="masterInput" name="masterInput">
                    </div>
                    <input type="hidden" id="masterType" name="masterType">
                </form>
                <div id="masterFeedback" class="alert d-none" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCancelMaster">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveMasterBtn" onclick="saveMaster()">Save</button>
            </div>
        </div>
    </div>
</div>

