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

        <!-- Remarks/Notes -->
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks/Notes</label>
                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Any additional notes..."></textarea>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                    <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="4" placeholder="Enter terms and conditions...">1. Warranty period: 30 days from delivery date
2. Data backup is customer's responsibility
3. Any liquid damage voids warranty
4. Payment must be made at time of delivery</textarea>
                </div>
            </div>
        </div>

        <!-- Checkbox -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="jobsheet_required" name="jobsheet_required" value="1">
                    <label class="form-check-label" for="jobsheet_required">
                        <strong>Without jobsheet mobile not obtained.</strong>
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
