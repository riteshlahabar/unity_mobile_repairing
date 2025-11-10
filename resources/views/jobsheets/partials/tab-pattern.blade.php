<!-- Tab 6: Pattern -->
<div class="tab-pane" id="pattern">
    <fieldset disabled>
        <div class="row">
            <!-- Pattern Drawing Area -->
            <div class="col-md-6">
                <label class="form-label">Draw Pattern Lock</label>
                <div class="card">
                    <div class="card-body text-center">
                        <canvas id="patternCanvas" width="300" height="300" style="border: 2px solid #e3e6f0; border-radius: 8px; cursor: crosshair; background-color: #f8f9fc;"></canvas>
                        <div class="mt-3 d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="resetPattern()">
                                <i class="iconoir-refresh me-1"></i>Reset
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="capturePattern()">
                                <i class="iconoir-camera me-1"></i>Capture
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">Draw by connecting dots with your mouse</small>
                    </div>
                </div>
            </div>

            <!-- Captured Pattern Preview -->
            <div class="col-md-6">
                <label class="form-label">Captured Pattern</label>
                <div class="card">
                    <div class="card-body text-center">
                        <div id="patternPreview" style="min-height: 300px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fc; border-radius: 8px; border: 2px dashed #e3e6f0;">
                            <p class="text-muted mb-0">No pattern captured yet</p>
                        </div>
                        <input type="hidden" id="pattern_image" name="pattern_image">
                        <small class="text-muted d-block mt-2">Pattern will appear here after capture</small>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    
    <div class="mt-3">
        <button type="button" class="btn btn-secondary float-start" onclick="prevTab('password-tab')">Previous</button>
        <button type="button" class="btn btn-primary float-end" onclick="nextTab('condition-tab')">Next</button>
    </div>
</div>
