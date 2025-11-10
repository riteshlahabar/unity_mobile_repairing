<!-- Tab 3: Upload Photo -->
<div class="tab-pane" id="photo">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Upload Device Photos</label>
                <p class="text-muted mb-2">Please click "Upload Photos" button to select images (Max 5 photos)</p>
                
                <!-- Preview Box -->
                <div id="preview-box" class="preview-box d-flex justify-content-start flex-wrap gap-3 rounded border border-dashed border-primary p-3 mb-3" style="min-height: 200px;">
                    <div class="text-center w-100 text-muted" id="emptyState">
                        <i class="iconoir-upload" style="font-size: 48px;"></i>
                        <p class="mb-0">No photos selected</p>
                    </div>
                </div>
                
                <!-- Hidden File Input -->
                <input type="file" id="device_photos" name="device_photos[]" accept="image/*" multiple hidden />
                
                <!-- Upload Button -->
                <label class="btn btn-primary w-100" for="device_photos" style="cursor: pointer;">
                    <i class="iconoir-upload me-2"></i>Upload Photos
                </label>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button type="button" class="btn btn-secondary float-start" onclick="prevTab('problem-tab')">Previous</button>
        <button type="button" class="btn btn-primary float-end" onclick="nextTab('sim-tab')">Next</button>
    </div>
</div>
