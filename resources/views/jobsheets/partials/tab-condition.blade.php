<!-- Tab 7: Condition -->
<div class="tab-pane" id="condition">
    <fieldset disabled>
        <!-- Device Condition, Water Damage, Physical Damage - All in One Row (Horizontal) -->
        <div class="row">
            <!-- Device Condition -->
            <div class="col-md-3">
                <label class="form-label d-block mb-2">Device Condition: <span class="text-danger">*</span></label>
                <div class="btn-group w-100 mb-2" role="group">
                    <input type="radio" class="btn-check" name="device_condition" id="condition_fresh" value="fresh" autocomplete="off" onchange="checkConditionTabValidation()">
                    <label class="btn btn-outline-secondary" for="condition_fresh">Fresh</label>

                    <input type="radio" class="btn-check" name="device_condition" id="condition_shop_return" value="shop_return" autocomplete="off" onchange="checkConditionTabValidation()">
                    <label class="btn btn-outline-secondary" for="condition_shop_return">Shop Return</label>

                    <input type="radio" class="btn-check" name="device_condition" id="condition_other" value="other" autocomplete="off" onchange="checkConditionTabValidation()">
                    <label class="btn btn-outline-secondary" for="condition_other">Other</label>
                </div>
            </div>

            <!-- Water Damage -->
            <div class="col-md-3">
                <label class="form-label d-block mb-2">Water Damage: <span class="text-danger">*</span></label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="water_damage" id="water_damage_none" value="none" autocomplete="off" onchange="checkConditionTabValidation()">
                    <label class="btn btn-outline-secondary" for="water_damage_none">None</label>

                    <input type="radio" class="btn-check" name="water_damage" id="water_damage_lite" value="lite" autocomplete="off" onchange="checkConditionTabValidation()">
                    <label class="btn btn-outline-secondary" for="water_damage_lite">Lite</label>

                    <input type="radio" class="btn-check" name="water_damage" id="water_damage_full" value="full" autocomplete="off" onchange="checkConditionTabValidation()">
                    <label class="btn btn-outline-secondary" for="water_damage_full">Full</label>
                </div>
            </div>

            <!-- Physical Damage -->
            <div class="col-md-3">
                <label class="form-label d-block mb-2">Physical Damage: <span class="text-danger">*</span></label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="physical_damage" id="physical_damage_none" value="none" autocomplete="off" onchange="checkConditionTabValidation()">
                    <label class="btn btn-outline-secondary" for="physical_damage_none">None</label>

                    <input type="radio" class="btn-check" name="physical_damage" id="physical_damage_lite" value="lite" autocomplete="off" onchange="checkConditionTabValidation()">
                    <label class="btn btn-outline-secondary" for="physical_damage_lite">Lite</label>

                    <input type="radio" class="btn-check" name="physical_damage" id="physical_damage_full" value="full" autocomplete="off" onchange="checkConditionTabValidation()">
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
                        <div class="dropdown-menu w-100" id="technicianDropdown">
                            <!-- Technicians will be loaded here dynamically -->
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#addTechnicianModal"><i class="iconoir-plus me-2"></i>Add New Technician</a>
                        </div>
                    </div>
                    <input type="hidden" id="technician" name="technician" required>
                </div>
            </div>

            <!-- Location -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="location" class="form-label">Box Location: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="location" name="location" placeholder="Enter location" required oninput="checkConditionTabValidation()">
                </div>
            </div>
        </div>

        <!-- Delivered Date & Time -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="delivered_date" class="form-label">Delivered Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="delivered_date" name="delivered_date" required onchange="checkConditionTabValidation()">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="delivered_time" class="form-label">Delivered Time <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" id="delivered_time" name="delivered_time" required onchange="checkConditionTabValidation()">
                </div>
            </div>
        </div>
    </fieldset>
    
    <div class="mt-3">
        <button type="button" class="btn btn-secondary float-start" onclick="prevTab('pattern-tab')">Previous</button>
        <button type="button" class="btn btn-primary float-end" id="conditionNextBtn" onclick="nextTab('estimate-tab')" disabled>Next</button>
    </div>
</div>

<!-- Add Technician Modal -->
<div class="modal fade" id="addTechnicianModal" tabindex="-1" aria-labelledby="addTechnicianModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTechnicianModalLabel">Add New Technician</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTechnicianForm">
                    <div class="mb-3">
                        <label for="technicianName" class="form-label">Technician Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="technicianName" name="technicianName" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveTechnician()">Save</button>
            </div>
        </div>
    </div>
</div>

