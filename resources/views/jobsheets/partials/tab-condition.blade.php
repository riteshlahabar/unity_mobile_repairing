<!-- Tab 7: Condition -->
<div class="tab-pane" id="condition">
    <fieldset disabled>
        <!-- Device Condition, Water Damage, Physical Damage - All in One Row (Horizontal) -->
<div class="row">
    <!-- Device Condition Checkboxes -->
    <div class="col-md-3">
        <label class="form-label d-block mb-2">Device Condition:</label>
        <div class="btn-group w-100 mb-2" role="group">
            <input type="radio" class="btn-check" name="device_condition" id="condition_fresh" value="fresh" autocomplete="off" checked>
            <label class="btn btn-outline-secondary" for="condition_fresh">Fresh</label>

            <input type="radio" class="btn-check" name="device_condition" id="condition_shop_return" value="shop_return" autocomplete="off">
            <label class="btn btn-outline-secondary" for="condition_shop_return">Shop Return</label>

            <input type="radio" class="btn-check" name="device_condition" id="condition_other" value="other" autocomplete="off">
            <label class="btn btn-outline-secondary" for="condition_other">Other</label>
        </div>
    </div>

    <!-- Water Damage -->
    <div class="col-md-3">
        <label class="form-label d-block mb-2">Water Damage:</label>
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="water_damage" id="water_damage_none" value="none" autocomplete="off" checked>
            <label class="btn btn-outline-secondary" for="water_damage_none">None</label>

            <input type="radio" class="btn-check" name="water_damage" id="water_damage_lite" value="lite" autocomplete="off">
            <label class="btn btn-outline-secondary" for="water_damage_lite">Lite</label>

            <input type="radio" class="btn-check" name="water_damage" id="water_damage_full" value="full" autocomplete="off">
            <label class="btn btn-outline-secondary" for="water_damage_full">Full</label>
        </div>
    </div>

    <!-- Physical Damage -->
    <div class="col-md-3">
        <label class="form-label d-block mb-2">Physical Damage:</label>
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="physical_damage" id="physical_damage_none" value="none" autocomplete="off" checked>
            <label class="btn btn-outline-secondary" for="physical_damage_none">None</label>

            <input type="radio" class="btn-check" name="physical_damage" id="physical_damage_lite" value="lite" autocomplete="off">
            <label class="btn btn-outline-secondary" for="physical_damage_lite">Lite</label>

            <input type="radio" class="btn-check" name="physical_damage" id="physical_damage_full" value="full" autocomplete="off">
            <label class="btn btn-outline-secondary" for="physical_damage_full">Full</label>
        </div>
    </div>
</div>


        <!-- Job Sheet Made By Technician & Location -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Job Sheet Made by Technician <span class="text-danger">*</span></label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle text-start" data-bs-toggle="dropdown">
                            Select Technician <i class="las la-angle-down float-end"></i>
                        </button>
                        <div class="dropdown-menu w-100">
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'technician', 'Technician 1')">Technician 1</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'technician', 'Technician 2')">Technician 2</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'technician', 'Technician 3')">Technician 3</a>
                            <a class="dropdown-item" href="#" onclick="selectOption(event, 'technician', 'Technician 4')">Technician 4</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-primary" href="#"><i class="iconoir-plus me-2"></i>Add New Technician</a>
                        </div>
                    </div>
                    <input type="hidden" id="technician" name="technician" required>
                </div>
            </div>

            <!-- Location -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="location" class="form-label">Location:</label>
                    <input type="text" class="form-control" id="location" name="location" placeholder="Enter location">
                </div>
            </div>
        </div>

        <!-- Delivered Date & Time -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="delivered_date" class="form-label">Delivered Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="delivered_date" name="delivered_date" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="delivered_time" class="form-label">Delivered Time <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" id="delivered_time" name="delivered_time" required>
                </div>
            </div>
        </div>
    </fieldset>
    
    <div class="mt-3">
        <button type="button" class="btn btn-secondary float-start" onclick="prevTab('pattern-tab')">Previous</button>
        <button type="button" class="btn btn-primary float-end" onclick="nextTab('estimate-tab')">Next</button>
    </div>
</div>
