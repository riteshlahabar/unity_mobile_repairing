<script>
document.addEventListener('DOMContentLoaded', function () {
    loadCompanies();
    loadModels();
    loadColors();
    loadSeries();
});

/* ----------------------------
  Utility & original handlers
   (kept names so other code still works)
   ----------------------------*/

// Open modal for adding new master data
let currentMasterType = '';
function openAddMasterModal(type) {
    currentMasterType = type.toLowerCase();
    const label = document.getElementById('addMasterModalLabel');
    if (label) label.innerText = 'Add New ' + type;
    const inputLabel = document.getElementById('masterInputLabel');
    if (inputLabel) inputLabel.innerText = type + ' Name *';
    const masterInput = document.getElementById('masterInput');
    if (masterInput) {
        masterInput.value = '';
        masterInput.placeholder = 'Enter ' + type.toLowerCase() + ' name';
    }
    const masterTypeEl = document.getElementById('masterType');
    if (masterTypeEl) masterTypeEl.value = currentMasterType;
    const feedback = document.getElementById('masterFeedback');
    if (feedback) {
        feedback.classList.add('d-none');
        feedback.classList.remove('alert-success', 'alert-danger');
    }
    const saveBtn = document.getElementById('saveMasterBtn');
    if (saveBtn) saveBtn.disabled = false;

    const modalEl = document.getElementById('addMasterModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
        new bootstrap.Modal(modalEl).show();
    }
}

function saveMaster() {
    const type = currentMasterType;
    const value = document.getElementById('masterInput').value.trim();
    if (!value) return;

    let url = '';
    let body = {};
    if (type === 'company') {
        url = "{{ route('mobile.storeCompany') }}";
        body = { company: value };
    } else if (type === 'model') {
        url = "{{ route('mobile.storeModel') }}";
        body = { model: value };
    } else if (type === 'color') {
        url = "{{ route('mobile.storeColor') }}";
        body = { color: value };
    } else if (type === 'series') {
        url = "{{ route('mobile.storeSeries') }}";
        body = { series: value };
    } else {
        return;
    }

    document.getElementById('saveMasterBtn').disabled = true;
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(body)
    })
    .then(response => response.json())
    .then(data => {
        const feedback = document.getElementById('masterFeedback');
        if (data.success) {
            // Show success message
            feedback.classList.remove('d-none', 'alert-danger');
            feedback.classList.add('alert', 'alert-success');
            feedback.innerText = data.message || type.charAt(0).toUpperCase() + type.slice(1) + ' added successfully.';
            
            // Refresh dropdowns after slight delay
            setTimeout(() => {
                if (type == 'company') loadCompanies();
                else if (type == 'model') loadModels();
                else if (type == 'color') loadColors();
                else if (type == 'series') loadSeries();
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('addMasterModal'));
                if (modalInstance) modalInstance.hide();
                document.getElementById('addMasterForm').reset();
                feedback.classList.add('d-none');
                feedback.classList.remove('alert-success');
                document.getElementById('saveMasterBtn').disabled = false;
            }, 1500);
        } else {
            // Show error message (e.g. already exists)
            feedback.classList.remove('d-none', 'alert-success');
            feedback.classList.add('alert', 'alert-danger');
            feedback.innerText = data.message || 'Error: Could not add ' + type + '. It might already exist.';
            document.getElementById('saveMasterBtn').disabled = false;
        }
    })
    .catch(() => {
        const feedback = document.getElementById('masterFeedback');
        feedback.classList.remove('d-none', 'alert-success');
        feedback.classList.add('alert', 'alert-danger');
        feedback.innerText = 'Network error: Unable to save ' + type + '.';
        document.getElementById('saveMasterBtn').disabled = false;
    });
}

// Selection handler for dropdown items
function selectOptionMobile(event, field, value) {
    if (event && typeof event.preventDefault === 'function') event.preventDefault();

    const hiddenInput = document.getElementById(field);
    if (hiddenInput) hiddenInput.value = value;

    const dropdownMenu = document.getElementById(field + 'Dropdown');
    if (!dropdownMenu) return;

    const btnGroup = dropdownMenu.closest('.btn-group');
    if (!btnGroup) return;

    const button = btnGroup.querySelector('button');
    if (button) button.innerHTML = value + ' <i class="las la-angle-down float-end"></i>';

    if (typeof checkMobileTabValidation === 'function') checkMobileTabValidation();
}


/* ----------------------------
  Search: deterministic & debuggable
  ----------------------------*/

// Create the search input and attach it to the specific dropdown element
// This returns the wrapper <li> so populateDropdown can append it.
function createDropdownSearchInputFor(dropdown) {
    const li = document.createElement('li');
    li.className = 'px-2 dropdown-search-container';

    const input = document.createElement('input');
    // predictable id so you can inspect: "<dropdownId>-search"
    input.type = 'text';
    input.className = 'form-control form-control-sm mb-2 dropdown-search';
    input.placeholder = 'Search...';

    // Add debug line and call filterDropdown with the dropdown itself
    input.addEventListener('input', (ev) => {
        const term = input.value;
        console.debug(`[DEBUG] Search input "${input.id}" input event. dropdown="${dropdown ? dropdown.id : 'unknown'}" term="${term}"`);
        filterDropdown(dropdown, term);
    });

    // Make ID predictable (if dropdown has id)
    if (dropdown && dropdown.id) {
        input.id = `${dropdown.id}-search`;
    }

    li.appendChild(input);
    return li;
}

// Filter function uses data-value on li.choice-item for robust comparisons
function filterDropdown(dropdown, searchTerm) {
    if (!dropdown) {
        console.warn('[DEBUG] filterDropdown called with null dropdown');
        return;
    }
    const term = (searchTerm || '').trim().toLowerCase();

    // select only choice items (we add this class when creating items)
    const choices = dropdown.querySelectorAll('li.choice-item');
    // keep search box and add-new/divider visible (they are not choice-item)
    console.debug(`[DEBUG] filterDropdown("${dropdown.id}") term="${term}" items=${choices.length}`);

    choices.forEach(li => {
        const value = li.getAttribute('data-value') || '';
        const show = term === '' ? true : value.indexOf(term) !== -1;
        li.style.display = show ? '' : 'none';
    });
}

/* ----------------------------
  Dropdown item creation & editing
  ----------------------------*/

// normalize server item => display text
function getDisplayText(item) {
    if (typeof item === 'string') return item;
    if (!item) return '';
    return item.name || item.company || item.model || item.color || item.series || String(item);
}

// Create a dropdown item with inline edit functionality.
// Note: we add class "choice-item" and data-value (lowercase) for search.
function createDropdownItem(rawItem, field, onEditSave) {
    const item = document.createElement('li');
    item.className = 'dropdown-item d-flex justify-content-between align-items-center choice-item';
    item.role = 'button';

    const displayText = getDisplayText(rawItem);
    const span = document.createElement('span');
    span.className = 'dropdown-text';
    span.textContent = displayText;

    // Save a lowercase copy for fast search comparisons
    item.setAttribute('data-value', (displayText || '').toLowerCase());

    const editIcon = document.createElement('i');
    editIcon.className = 'las la-edit edit-icon';
    editIcon.style.cursor = 'pointer';
    editIcon.setAttribute('tabindex', '0');
    editIcon.setAttribute('role', 'button');
    editIcon.title = 'Edit';

    // Stop propagation for edit icon
    editIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        // if editing - trigger save path; else enter edit mode
        if (item.classList.contains('editing')) {
            // save
            saveEditFlow(item, field, displayText, onEditSave);
        } else {
            enterEditFlow(item, field, displayText, onEditSave);
        }
    });

    item.appendChild(span);
    item.appendChild(editIcon);

    item.addEventListener('click', (e) => {
        if (item.classList.contains('editing')) return;
        selectOptionMobile(e, field, span.textContent);
    });

    return item;
}

/* Enter edit mode */
function enterEditFlow(item, field, oldValueString, onEditSave) {
    if (!item) return;
    item.classList.add('editing');

    const span = item.querySelector('.dropdown-text');
    const editIcon = item.querySelector('.edit-icon');

    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control form-control-sm dropdown-edit-input';
    input.value = (span && span.textContent) ? span.textContent : '';

    // Enter -> save, Escape -> cancel
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            saveEditFlow(item, field, oldValueString, onEditSave);
        } else if (e.key === 'Escape') {
            cancelEditFlow(item, oldValueString);
        }
    });

    if (span) item.replaceChild(input, span);
    else item.insertBefore(input, editIcon);

    if (editIcon) {
        editIcon.className = 'las la-check edit-icon';
        editIcon.style.cursor = 'pointer';
    }

    input.focus();
    input.select();
}

/* Cancel edit (restore old display) */
function cancelEditFlow(item, oldValueString) {
    if (!item) return;
    const editIcon = item.querySelector('.edit-icon');
    const currentInput = item.querySelector('input.dropdown-edit-input');
    const restoredSpan = document.createElement('span');
    restoredSpan.className = 'dropdown-text';
    restoredSpan.textContent = oldValueString || '';
    if (currentInput) item.replaceChild(restoredSpan, currentInput);
    item.classList.remove('editing');
    if (editIcon) {
        editIcon.className = 'las la-edit edit-icon';
        editIcon.style.cursor = 'pointer';
    }
    // Update data-value attribute back to oldValueString
    item.setAttribute('data-value', (oldValueString || '').toLowerCase());
}

/* Save edit flow */
function saveEditFlow(item, field, oldValueString, onEditSave) {
    if (!item) return;
    const input = item.querySelector('input.dropdown-edit-input');
    const editIcon = item.querySelector('.edit-icon');
    if (!input) return;

    const newValue = input.value.trim();
    if (!newValue) {
        alert('Value cannot be empty.');
        input.focus();
        return;
    }

    if (editIcon) {
        editIcon.classList.add('disabled');
        editIcon.style.pointerEvents = 'none';
    }

    if (typeof onEditSave === 'function') {
        onEditSave(newValue, (success, updatedValue, message) => {
            if (editIcon) {
                editIcon.classList.remove('disabled');
                editIcon.style.pointerEvents = 'auto';
            }
            if (success) {
                const finalValue = updatedValue || newValue;
                const newSpan = document.createElement('span');
                newSpan.className = 'dropdown-text';
                newSpan.textContent = finalValue;
                const currentInput = item.querySelector('input.dropdown-edit-input');
                if (currentInput) item.replaceChild(newSpan, currentInput);
                item.classList.remove('editing');
                if (editIcon) {
                    editIcon.className = 'las la-edit edit-icon';
                    editIcon.style.cursor = 'pointer';
                }
                // update data-value attribute for search
                item.setAttribute('data-value', (finalValue || '').toLowerCase());
                if (message) alert(message);
            } else {
                alert(message || 'Failed to update. Please try again.');
                // keep focus on input
                const stillInput = item.querySelector('input.dropdown-edit-input');
                if (stillInput) stillInput.focus();
            }
        });
    } else {
        // no server call
        const newSpan = document.createElement('span');
        newSpan.className = 'dropdown-text';
        newSpan.textContent = newValue;
        const currentInput = item.querySelector('input.dropdown-edit-input');
        if (currentInput) item.replaceChild(newSpan, currentInput);
        item.classList.remove('editing');
        if (editIcon) {
            editIcon.className = 'las la-edit edit-icon';
            editIcon.style.cursor = 'pointer';
        }
        item.setAttribute('data-value', (newValue || '').toLowerCase());
        alert('Updated successfully!');
    }
}

/* ----------------------------
  AJAX saving (keeps your original API shape, improved parsing)
  ----------------------------*/
function saveEditedDropdownValue(type, oldValue, newValue, done) {
    let url, body;

    switch (type) {
        case 'company':
            url = "{{ route('mobile.updateCompany') }}";
            body = { old_company: oldValue, company: newValue };
            break;
        case 'model':
            url = "{{ route('mobile.updateModel') }}";
            body = { old_model: oldValue, model: newValue };
            break;
        case 'color':
            url = "{{ route('mobile.updateColor') }}";
            body = { old_color: oldValue, color: newValue };
            break;
        case 'series':
            url = "{{ route('mobile.updateSeries') }}";
            body = { old_series: oldValue, series: newValue };
            break;
        default:
            done(false, null, 'Invalid type');
            return;
    }

    fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(body)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(txt => {
                try {
                    const parsed = JSON.parse(txt);
                    throw new Error(parsed.message || JSON.stringify(parsed));
                } catch (e) {
                    throw new Error(txt ? txt.substring(0, 1000) : 'Network/server error');
                }
            });
        }
        return response.json();
    })
    .then(data => {
        done(Boolean(data.success), data.updatedValue || newValue, data.message || 'Updated successfully.');
    })
    .catch(err => {
        const msg = err && err.message ? err.message : 'Network or server error';
        console.error('saveEditedDropdownValue error:', err);
        done(false, null, msg);
    });
}

/* ----------------------------
  Loaders & populate (search input wired to correct dropdown)
  ----------------------------*/

function loadCompanies() {
    fetch("{{ route('mobile.fetchCompanies') }}", { credentials: 'same-origin', headers: {'Accept': 'application/json'} })
    .then(response => response.json())
    .then(data => populateDropdown('companyDropdown', 'Company', data, 'company'))
    .catch(err => console.error('Error loading companies:', err));
}

function loadModels() {
    fetch("{{ route('mobile.fetchModels') }}", { credentials: 'same-origin', headers: {'Accept': 'application/json'} })
    .then(response => response.json())
    .then(data => populateDropdown('modelDropdown', 'Model', data, 'model'))
    .catch(err => console.error('Error loading models:', err));
}

function loadColors() {
    fetch("{{ route('mobile.fetchColors') }}", { credentials: 'same-origin', headers: {'Accept': 'application/json'} })
    .then(response => response.json())
    .then(data => populateDropdown('colorDropdown', 'Color', data, 'color'))
    .catch(err => console.error('Error loading colors:', err));
}

function loadSeries() {
    fetch("{{ route('mobile.fetchSeries') }}", { credentials: 'same-origin', headers: {'Accept': 'application/json'} })
    .then(response => response.json())
    .then(data => populateDropdown('seriesDropdown', 'Series', data, 'series'))
    .catch(err => console.error('Error loading series:', err));
}

// Populate dropdown: attach the search input (for this dropdown) and items
function populateDropdown(dropdownId, displayName, items, field) {
    const dropdown = document.getElementById(dropdownId);
    if (!dropdown) {
        console.warn(`[DEBUG] populateDropdown: element not found: ${dropdownId}`);
        return;
    }
    console.debug(`[DEBUG] populateDropdown("${dropdownId}") items length:`, Array.isArray(items) ? items.length : 'not-array');

    dropdown.innerHTML = '';

    // Create search input bound to this dropdown
    const searchLi = createDropdownSearchInputFor(dropdown);
    dropdown.appendChild(searchLi);

    // Items: create choice-item LIs with data-value for filtering
    if (Array.isArray(items)) {
        items.forEach(rawItem => {
            const itemNode = createDropdownItem(rawItem, field, (newVal, done) => {
                const oldValueString = getDisplayText(rawItem);
                // call existing saveEditedDropdownValue
                saveEditedDropdownValue(field, oldValueString, newVal, done);
            });
            dropdown.appendChild(itemNode);
        });
    } else {
        console.warn(`[DEBUG] populateDropdown: items not an array for ${dropdownId}`, items);
    }

    const divider = document.createElement('li');
    divider.className = 'dropdown-divider';
    dropdown.appendChild(divider);

    const addNew = document.createElement('li');
    addNew.className = 'dropdown-item text-primary';
    addNew.setAttribute('role', 'button');
    addNew.innerHTML = `<i class="iconoir-plus me-2"></i>Add New ${displayName}`;
    addNew.onclick = () => openAddMasterModal(displayName);
    dropdown.appendChild(addNew);

    // debug: ensure search input exists and is focusable
    const searchInput = dropdown.querySelector('.dropdown-search');
    console.debug(`[DEBUG] populateDropdown("${dropdownId}") search input id:`, searchInput ? searchInput.id : 'not-found');
}

const searchInput = dropdown.querySelector('.dropdown-search');
if (searchInput) {
    console.log(`[TEST] Search input found for ${dropdownId}:`, searchInput);
    // Force-add another listener to confirm events fire
    searchInput.addEventListener('input', (e) => {
        console.log('[TEST] Manual test listener fired, value:', e.target.value);
    });
} else {
    console.error(`[TEST] No search input found in ${dropdownId}`);
}

function uploadBulkMaster() {
    const fileInput = document.getElementById('bulkMasterFile');
    const feedback = document.getElementById('bulkMasterUploadFeedback');
    feedback.classList.add('d-none');
    feedback.classList.remove('alert-success', 'alert-danger');
    feedback.textContent = '';

    // Validate file
    if (!fileInput || !fileInput.files.length) {
        feedback.textContent = 'Please select an Excel file.';
        feedback.classList.remove('d-none');
        feedback.classList.add('alert-danger');
        return;
    }
    const file = fileInput.files[0];
    if (!file.name.match(/\.(xlsx|xls)$/i)) {
        feedback.textContent = 'File must be .xlsx or .xls';
        feedback.classList.remove('d-none');
        feedback.classList.add('alert-danger');
        return;
    }

    // Prepare data
    let formData = new FormData();
    formData.append('file', file);

    // Send via AJAX to Laravel route (define this route in web.php/controller)
    fetch("{{ route('mobile.bulkMasterUpload') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            feedback.textContent = data.message || 'File uploaded and data imported successfully.';
            feedback.classList.remove('d-none', 'alert-danger');
            feedback.classList.add('alert-success');
            // Optionally reload dropdowns
            loadCompanies();
            loadModels();
            loadColors();
            loadSeries();
        } else {
            feedback.textContent = data.message || 'Upload failed or file format invalid.';
            feedback.classList.remove('d-none', 'alert-success');
            feedback.classList.add('alert-danger');
        }
    })
    .catch(() => {
        feedback.textContent = 'Network or server error during file upload.';
        feedback.classList.remove('d-none', 'alert-success');
        feedback.classList.add('alert-danger');
    });
}

</script>
