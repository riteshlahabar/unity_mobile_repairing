<!-- Tab 4: SIM & Accessories -->
<div class="tab-pane" id="sim">
    <fieldset disabled>
        <div class="row">
            <div class="col-md-12">
                <label class="form-label d-block mb-2">Accessories Available:</label>
                <div class="btn-group mb-3" role="group" aria-label="Accessories checkboxes">
                    <input type="checkbox" class="btn-check" id="accessory_sim_tray" name="accessory_sim_tray" value="1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="accessory_sim_tray">SIM Tray</label>

                    <input type="checkbox" class="btn-check" id="accessory_sim_card" name="accessory_sim_card" value="1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="accessory_sim_card">SIM Card</label>

                    <input type="checkbox" class="btn-check" id="accessory_memory_card" name="accessory_memory_card" value="1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="accessory_memory_card">Memory Card</label>

                    <input type="checkbox" class="btn-check" id="accessory_mobile_cover" name="accessory_mobile_cover" value="1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="accessory_mobile_cover">Mobile Cover</label>
                </div>
            </div>
        </div>

        <!-- Other Accessories Field -->
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="other_accessories" class="form-label">Other:</label>
                    <input type="text" class="form-control" id="other_accessories" name="other_accessories" placeholder="Enter other accessories (if any)">
                </div>
            </div>
        </div>
    </fieldset>
    
    <div class="mt-3">
        <button type="button" class="btn btn-secondary float-start" onclick="prevTab('photo-tab')">Previous</button>
        <button type="button" class="btn btn-primary float-end" onclick="nextTab('password-tab')">Next</button>
    </div>
</div>
