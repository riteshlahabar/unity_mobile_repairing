<!-- Tab 8: Estimate -->
<div class="tab-pane" id="estimate">
    <fieldset disabled>
        <!-- Estimated Cost, Advance, Balance -->
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="estimated_cost" class="form-label">Estimated Cost (₹) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="estimated_cost" name="estimated_cost" placeholder="Enter estimated cost" required>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="advance" class="form-label">Advance (₹)</label>
                    <input type="number" class="form-control" id="advance" name="advance" placeholder="Enter advance amount">
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="balance" class="form-label">Balance (₹)</label>
                    <input type="number" class="form-control bg-light" id="balance" name="balance" placeholder="Auto calculated" readonly>
                </div>
            </div>
        </div>

        <!-- Notes (Single text field) -->
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <input type="text" class="form-control" id="notes" name="notes" placeholder="Enter any additional notes...">
                </div>
            </div>
        </div>

        <!-- Remarks (Readonly from database) -->
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control bg-light" id="remarks" name="remarks" rows="4" readonly style="overflow-y: auto;">{{ $remarks ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions (Readonly from database) -->
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                    <textarea class="form-control bg-light" id="terms_conditions" name="terms_conditions" rows="4" readonly style="overflow-y: auto;">{{ $termsConditions ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Checkbox -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="jobsheet_required" name="jobsheet_required" value="1">
                    <label class="form-check-label" for="jobsheet_required">
                        <strong>I agree to the terms and conditions.</strong>
                    </label>
                </div>
            </div>
        </div>
    </fieldset>
    
    <div class="mt-3">
        <button type="button" class="btn btn-secondary float-start" onclick="prevTab('condition-tab')">Previous</button>
        <button type="submit" class="btn btn-success float-end" id="createJobSheetBtn" disabled>
            <i class="iconoir-check-circle me-1"></i>Create JobSheet
        </button>
    </div>
</div>
