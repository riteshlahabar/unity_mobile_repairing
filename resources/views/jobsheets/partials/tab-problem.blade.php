<!-- Tab 2: Problem -->
<div class="tab-pane" id="problem">
    <fieldset disabled>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="problem_description" class="form-label">Problem: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="problem_description" name="problem_description" placeholder="Enter problem description" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <label class="form-label d-block mb-2">Device Status:</label>
                <div class="btn-group mb-2" role="group">
                    <input type="checkbox" class="btn-check" id="status_dead" name="status_dead" value="1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="status_dead">Dead</label>

                    <input type="checkbox" class="btn-check" id="status_damage" name="status_damage" value="1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="status_damage">Damage</label>

                    <input type="checkbox" class="btn-check" id="status_on" name="status_on" value="1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="status_on">On with Problem</label>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="mt-3">
        <button type="button" class="btn btn-secondary float-start" onclick="prevTab('mobile-tab')">Previous</button>
        <button type="button" class="btn btn-primary float-end" onclick="nextTab('photo-tab')">Next</button>
    </div>
</div>
