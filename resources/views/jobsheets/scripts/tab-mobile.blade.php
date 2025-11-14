<script>    
document.addEventListener('DOMContentLoaded', function() {
    loadCompanies();
    loadModels();
    loadColors();
    loadSeries();
});

// Validation for Mobile tab enabling Next button
function checkMobileTabValidation() {
    const company = document.getElementById('company')?.value;
    const model = document.getElementById('model')?.value;
    const color = document.getElementById('color')?.value;
    const series = document.getElementById('series')?.value;
    const nextBtn = document.getElementById('mobileNextBtn');

    if (!nextBtn) return; // Exit if Next button does not exist

    // Enable Next button only if all required hidden inputs have values
    if (company && model && color && series) {
        nextBtn.disabled = false;
        nextBtn.classList.remove('disabled');
        console.log('✅ Mobile tab validated');
    } else {
        nextBtn.disabled = true;
        nextBtn.classList.add('disabled');
        console.log('❌ Mobile tab incomplete');
    }
}

// Modified selectOption function for mobile tab with validation
function selectOptionMobile(event, field, value) {
    event.preventDefault();

    // Update the hidden input
    document.getElementById(field).value = value;

    // Update the dropdown toggle button text
    const dropdownMenu = document.getElementById(field + 'Dropdown');
    if (!dropdownMenu) return;

    const btnGroup = dropdownMenu.closest('.btn-group');
    if (!btnGroup) return;

    const button = btnGroup.querySelector('button');
    if (button) {
        button.innerHTML = value + ' <i class="las la-angle-down float-end"></i>';
    }

    // Check the validation on each selection
    checkMobileTabValidation();
}


let currentMasterType = '';

function openAddMasterModal(type) {
    currentMasterType = type.toLowerCase();
    document.getElementById('addMasterModalLabel').innerText = 'Add New ' + type;
    document.getElementById('masterInputLabel').innerText = type + ' Name *';
    document.getElementById('masterInput').value = '';
    document.getElementById('masterInput').placeholder = 'Enter ' + type.toLowerCase() + ' name';
    document.getElementById('masterType').value = currentMasterType;
    const feedback = document.getElementById('masterFeedback');
    feedback.classList.add('d-none');
    feedback.classList.remove('alert-success', 'alert-danger');
    document.getElementById('saveMasterBtn').disabled = false;

    new bootstrap.Modal(document.getElementById('addMasterModal')).show();
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

// Example dropdown loaders — make sure these functions populate dropdowns and update hidden inputs correctly

function loadCompanies() {
    fetch("{{ route('mobile.fetchCompanies') }}")
    .then(response => response.json())
    .then(data => {
        const dropdown = document.getElementById('companyDropdown');
        dropdown.innerHTML = '';
        data.forEach(company => {
            const item = document.createElement('li');
            item.className = 'dropdown-item';
            item.role = 'button';
            item.innerText = company;
            item.onclick = (e) => selectOption(e, 'company', company);
            dropdown.appendChild(item);
        });
        const divider = document.createElement('li');
        divider.className = 'dropdown-divider';
        dropdown.appendChild(divider);
        const addNew = document.createElement('li');
        addNew.className = 'dropdown-item text-primary';
        addNew.setAttribute('role', 'button');
        addNew.innerHTML = '<i class="iconoir-plus me-2"></i>Add New Company';
        addNew.onclick = () => openAddMasterModal('Company');
        dropdown.appendChild(addNew);
    });
}

function loadModels() {
    fetch("{{ route('mobile.fetchModels') }}")
    .then(response => response.json())
    .then(data => {
        const dropdown = document.getElementById('modelDropdown');
        dropdown.innerHTML = '';
        data.forEach(model => {
            const item = document.createElement('li');
            item.className = 'dropdown-item';
            item.role = 'button';
            item.innerText = model;
            item.onclick = (e) => selectOption(e, 'model', model);
            dropdown.appendChild(item);
        });
        const divider = document.createElement('li');
        divider.className = 'dropdown-divider';
        dropdown.appendChild(divider);
        const addNew = document.createElement('li');
        addNew.className = 'dropdown-item text-primary';
        addNew.setAttribute('role', 'button');
        addNew.innerHTML = '<i class="iconoir-plus me-2"></i>Add New Model';
        addNew.onclick = () => openAddMasterModal('Model');
        dropdown.appendChild(addNew);
    });
}

function loadColors() {
    fetch("{{ route('mobile.fetchColors') }}")
    .then(response => response.json())
    .then(data => {
        const dropdown = document.getElementById('colorDropdown');
        dropdown.innerHTML = '';
        data.forEach(color => {
            const item = document.createElement('li');
            item.className = 'dropdown-item';
            item.role = 'button';
            item.innerText = color;
            item.onclick = (e) => selectOption(e, 'color', color);
            dropdown.appendChild(item);
        });
        const divider = document.createElement('li');
        divider.className = 'dropdown-divider';
        dropdown.appendChild(divider);
        const addNew = document.createElement('li');
        addNew.className = 'dropdown-item text-primary';
        addNew.setAttribute('role', 'button');
        addNew.innerHTML = '<i class="iconoir-plus me-2"></i>Add New Color';
        addNew.onclick = () => openAddMasterModal('Color');
        dropdown.appendChild(addNew);
    });
}

function loadSeries() {
    fetch("{{ route('mobile.fetchSeries') }}")
    .then(response => response.json())
    .then(data => {
        const dropdown = document.getElementById('seriesDropdown');
        dropdown.innerHTML = '';
        data.forEach(series => {
            const item = document.createElement('li');
            item.className = 'dropdown-item';
            item.role = 'button';
            item.innerText = series;
            item.onclick = (e) => selectOption(e, 'series', series);
            dropdown.appendChild(item);
        });
        const divider = document.createElement('li');
        divider.className = 'dropdown-divider';
        dropdown.appendChild(divider);
        const addNew = document.createElement('li');
        addNew.className = 'dropdown-item text-primary';
        addNew.setAttribute('role', 'button');
        addNew.innerHTML = '<i class="iconoir-plus me-2"></i>Add New Series';
        addNew.onclick = () => openAddMasterModal('Series');
        dropdown.appendChild(addNew);
    });
}

document.getElementById('imei').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

</script>
