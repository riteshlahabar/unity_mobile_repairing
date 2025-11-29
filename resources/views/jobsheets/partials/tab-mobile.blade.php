<!-- Tab 1: Mobile Details -->
<div class="tab-pane active" id="mobile">
    <fieldset id="wizardFieldset">
        <div class="row">
            <!-- Company Dropdown -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Company <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown" aria-expanded="false" id="companyDropdownBtn">
                            Select Company <i class="las la-angle-down float-end"></i>
                        </button>
                        <ul class="dropdown-menu w-100 dropdown-search-menu" id="companyDropdown" role="menu">
                            <li class="px-2 py-2 border-bottom">
                                <input type="text" class="form-control form-control-sm company-search" placeholder="Search..." autocomplete="off">
                            </li>
                        </ul>
                    </div>
                    <input type="hidden" id="company" name="company" required>
                </div>
            </div>
            
            <!-- Series Dropdown -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Series <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown" aria-expanded="false" id="seriesDropdownBtn">
                            Select Series <i class="las la-angle-down float-end"></i>
                        </button>
                        <ul class="dropdown-menu w-100 dropdown-search-menu" id="seriesDropdown" role="menu">
                            <li class="px-2 py-2 border-bottom">
                                <input type="text" class="form-control form-control-sm series-search" placeholder="Search..." autocomplete="off">
                            </li>
                        </ul>
                    </div>
                    <input type="hidden" id="series" name="series" required>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- Model Dropdown -->
        <div class="row">
=======
            <!-- Model Dropdown -->
            <div class="row">
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown" aria-expanded="false" id="modelDropdownBtn">
                            Select Model <i class="las la-angle-down float-end"></i>
                        </button>
<<<<<<< HEAD
                        <ul class="dropdown-menu w-100 dropdown-search-menu" id="modelDropdown" role="menu">
                            <li class="px-2 py-2 border-bottom">
                                <input type="text" class="form-control form-control-sm model-search" placeholder="Search..." autocomplete="off">
                            </li>
                        </ul>
                    </div>
                    <input type="hidden" id="model" name="model" required>
                </div>
            </div>
        
            <!-- Color Dropdown -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Color <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown" aria-expanded="false" id="colorDropdownBtn">
                            Select Color <i class="las la-angle-down float-end"></i>
                        </button>
                        <ul class="dropdown-menu w-100 dropdown-search-menu" id="colorDropdown" role="menu">
                            <li class="px-2 py-2 border-bottom">
                                <input type="text" class="form-control form-control-sm color-search" placeholder="Search..." autocomplete="off">
                            </li>
                        </ul>
                    </div>
                    <input type="hidden" id="color" name="color" required>
                </div>
            </div>
        </div>

        <!-- IMEI Number + Excel Upload Row -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="imei" class="form-label">
                        IMEI Number <small class="text-muted">(Optional)</small>
                    </label>
                    <input type="text" class="form-control" id="imei" name="imei" placeholder="Enter IMEI number" maxlength="15">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label d-block">&nbsp;</label>
                    <div class="input-group">
                        <span class="input-group-text" title="Attach Excel file">
                            <i class="las la-paperclip"></i>
                        </span>
                        <input type="file" class="form-control" id="bulkMasterFile" accept=".xlsx,.xls" aria-label="Upload Excel File">
                        <button type="button" class="btn btn-outline-success" onclick="uploadBulkMaster()">Upload</button>
                    </div>
                    <small class="text-muted">Upload Excel with Columns: company, series, model, color</small>
                    <div id="bulkMasterUploadFeedback" class="alert d-none mt-1 mb-0 p-1" style="font-size:13px;"></div>
                </div>
            </div>
=======
                        <ul class="dropdown-menu w-100" id="modelDropdown" role="menu">
                            <!-- Dynamically loaded -->
                        </ul>
                    </div>
                    <input type="hidden" id="model" name="model" required>
                </div>
            </div>
        
            <!-- Color Dropdown -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Color <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown" aria-expanded="false" id="colorDropdownBtn">
                            Select Color <i class="las la-angle-down float-end"></i>
                        </button>
                        <ul class="dropdown-menu w-100" id="colorDropdown" role="menu">
                            <!-- Dynamically loaded -->
                        </ul>
                    </div>
                    <input type="hidden" id="color" name="color" required>
                </div>
            </div>
            </div>

            

        <!-- IMEI Number + Excel Upload Row -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="imei" class="form-label">
                IMEI Number <small class="text-muted">(Optional)</small>
            </label>
            <input type="text" class="form-control" id="imei" name="imei" placeholder="Enter IMEI number" maxlength="15">
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label d-block">&nbsp;</label> <!-- Empty label for alignment -->
            <div class="input-group">
                <span class="input-group-text" title="Attach Excel file">
                    <i class="las la-paperclip"></i>
                </span>
                <input type="file" class="form-control" id="bulkMasterFile" accept=".xlsx,.xls" aria-label="Upload Excel File">
                <button type="button" class="btn btn-outline-success" onclick="uploadBulkMaster()">Upload</button>
            </div>
            <small class="text-muted">Upload Excel with Columns: company, series, model, color</small>
            <div id="bulkMasterUploadFeedback" class="alert d-none mt-1 mb-0 p-1" style="font-size:13px;"></div>
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
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addMasterModalLabel">Add New</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addMasterForm" novalidate>
                    <div class="mb-3">
                        <label for="masterInput" class="form-label" id="masterInputLabel"></label>
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
<<<<<<< HEAD

<style>
/* Dropdown search styling */
.dropdown-search-menu {
    max-height: 400px !important;
    overflow-y: auto !important;
}

/* Search input styling */
.dropdown-menu input.dropdown-search-input,
.dropdown-menu .company-search,
.dropdown-menu .series-search,
.dropdown-menu .model-search,
.dropdown-menu .color-search {
    pointer-events: auto !important;
    width: 100% !important;
}

.dropdown-menu input:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
    outline: none !important;
}

/* Choice item styling - FIXED */
.dropdown-menu .choice-item {
    cursor: pointer;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 0.5rem 1rem !important;
    min-height: 38px;
}

.dropdown-menu .choice-item span {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dropdown-menu .choice-item .edit-icon {
    flex-shrink: 0;
    margin-left: 0.5rem;
    min-width: 20px;
}

.dropdown-menu .choice-item:hover {
    background-color: #f8f9fa !important;
}

/* Hide divider and add-new when searching */
.dropdown-menu .dropdown-divider {
    margin: 0.5rem 0 !important;
}

/* Ensure hidden items stay hidden */
.dropdown-menu .choice-item[style*="display: none"] {
    display: none !important;
    height: 0 !important;
    padding: 0 !important;
}

/* Smooth scrolling */
.dropdown-search-menu {
    scroll-behavior: smooth;
}
</style>
=======
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
