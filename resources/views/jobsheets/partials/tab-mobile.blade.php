<!-- Tab 1: Mobile Details -->
<div class="tab-pane active" id="mobile">
    <fieldset id="wizardFieldset" disabled>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Company <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown">
                            Select Company <i class="las la-angle-down float-end"></i>
                        </button>
                        <div class="dropdown-menu w-100">
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'company', 'Apple')">Apple</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'company', 'Samsung')">Samsung</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'company', 'OnePlus')">OnePlus</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'company', 'Xiaomi')">Xiaomi</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'company', 'Oppo')">Oppo</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'company', 'Vivo')">Vivo</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'company', 'Realme')">Realme</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-primary" href="#"><i class="iconoir-plus me-2"></i>Add New Company</a>
                        </div>
                    </div>
                    <input type="hidden" id="company" name="company" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Model <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown">
                            Select Model <i class="las la-angle-down float-end"></i>
                        </button>
                        <div class="dropdown-menu w-100">
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'model', 'iPhone 14 Pro')">iPhone 14 Pro</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'model', 'iPhone 13')">iPhone 13</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'model', 'Galaxy S23')">Galaxy S23</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'model', 'OnePlus 11')">OnePlus 11</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-primary" href="#"><i class="iconoir-plus me-2"></i>Add New Model</a>
                        </div>
                    </div>
                    <input type="hidden" id="model" name="model" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Color</label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown">
                            Select Color <i class="las la-angle-down float-end"></i>
                        </button>
                        <div class="dropdown-menu w-100">
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'color', 'Black')">Black</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'color', 'White')">White</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'color', 'Blue')">Blue</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'color', 'Gold')">Gold</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'color', 'Silver')">Silver</a>
                        </div>
                    </div>
                    <input type="hidden" id="color" name="color">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Series</label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown">
                            Select Series <i class="las la-angle-down float-end"></i>
                        </button>
                        <div class="dropdown-menu w-100">
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'series', '64GB')">64GB</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'series', '128GB')">128GB</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'series', '256GB')">256GB</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'series', '512GB')">512GB</a>
                        </div>
                    </div>
                    <input type="hidden" id="series" name="series">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="imei" class="form-label">IMEI Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="imei" name="imei" placeholder="Enter IMEI number" maxlength="15" required>
                </div>
            </div>
        </div>
    </fieldset>

    <div class="mt-3">
        <button type="button" class="btn btn-primary float-end" onclick="nextTab('problem-tab')">Next</button>
    </div>
</div>
