<script>
    // Load data on page load
document.addEventListener('DOMContentLoaded', function() {    
    loadTechnicians();
});
// Condition Tab Validation
function checkConditionTabValidation() {
    const deviceCondition = document.querySelector('input[name="device_condition"]:checked')?.value;
    const waterDamage = document.querySelector('input[name="water_damage"]:checked')?.value;
    const physicalDamage = document.querySelector('input[name="physical_damage"]:checked')?.value;
    const technician = document.getElementById('technician')?.value;
    const location = document.getElementById('location')?.value.trim();
    const deliveredDate = document.getElementById('delivered_date')?.value;
    const deliveredTime = document.getElementById('delivered_time')?.value;
    const nextBtn = document.getElementById('conditionNextBtn');
    
    if (!nextBtn) return; // Exit if button doesn't exist
    
    // Enable next button only if all required fields are filled
    if (deviceCondition && waterDamage && physicalDamage && technician && location && deliveredDate && deliveredTime) {
        nextBtn.disabled = false;
        nextBtn.classList.remove('disabled');
    } else {
        nextBtn.disabled = true;
        nextBtn.classList.add('disabled');
    }
}

// Modified selectOption function for condition tab
function selectOptionWithValidation(event, field, value) {
    event.preventDefault();
    document.getElementById(field).value = value;
    const button = event.target.closest('.btn-group').querySelector('button');
    button.innerHTML = value + ' <i class="las la-angle-down float-end"></i>';
    
    // Check validation after selection
    checkConditionTabValidation();
}

// Track condition selections
document.querySelectorAll('input[name="water_damage"], input[name="physical_damage"], input[name="device_condition"]').forEach(radio => {
    radio.addEventListener('change', function() {
        console.log('Device Condition:', document.querySelector('input[name="device_condition"]:checked')?.value);
        console.log('Water Damage:', document.querySelector('input[name="water_damage"]:checked')?.value);
        console.log('Physical Damage:', document.querySelector('input[name="physical_damage"]:checked')?.value);
    });
});

// Set default date and time, then check validation
window.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const deliveredDate = document.getElementById('delivered_date');
    if (deliveredDate) deliveredDate.value = today;
    
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const deliveredTime = document.getElementById('delivered_time');
    if (deliveredTime) deliveredTime.value = `${hours}:${minutes}`;
    
    // Initial validation check
    checkConditionTabValidation();
});

function saveTechnician() {
    const technicianName = document.getElementById('technicianName').value.trim();
    if (!technicianName) return;

    const saveButton = document.querySelector('#addTechnicianModal .btn-primary');
    saveButton.disabled = true;

    fetch("{{ route('mobile.storeTechnician') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ technician: technicianName })
    })
    .then(response => response.json())
    .then(data => {
        const feedbackElem = document.createElement('div');
        feedbackElem.classList.add('alert', 'mt-3');

        if (data.success) {
            // Show success message
            feedbackElem.classList.add('alert-success');
            feedbackElem.innerText = data.message || 'Technician added successfully.';
            
            loadTechnicians();  // Reload dropdown here if you have this function

            // Close modal after short delay
            setTimeout(() => {
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('addTechnicianModal'));
                if (modalInstance) modalInstance.hide();
                document.getElementById('addTechnicianForm').reset();
                feedbackElem.remove();
            }, 1500);
        } else {
            // Show error message (like already exists)
            feedbackElem.classList.add('alert-danger');
            feedbackElem.innerText = data.message || 'Error: Could not add technician.';
        }

        // Append or replace feedback
        let modalBody = document.querySelector('#addTechnicianModal .modal-body');
        let existingFeedback = modalBody.querySelector('.alert');
        if (existingFeedback) {
            existingFeedback.replaceWith(feedbackElem);
        } else {
            modalBody.appendChild(feedbackElem);
        }

        saveButton.disabled = false;
    })
    .catch(() => {
        const modalBody = document.querySelector('#addTechnicianModal .modal-body');
        const feedbackElem = document.createElement('div');
        feedbackElem.classList.add('alert', 'alert-danger', 'mt-3');
        feedbackElem.innerText = 'Network error: Unable to save technician.';
        let existingFeedback = modalBody.querySelector('.alert');
        if (existingFeedback) {
            existingFeedback.replaceWith(feedbackElem);
        } else {
            modalBody.appendChild(feedbackElem);
        }
        saveButton.disabled = false;
    });
}
// Load Technicians
function loadTechnicians() {
    fetch("{{ route('mobile.fetchTechnicians') }}")
        .then(response => response.json())
        .then(data => {
            const dropdown = document.getElementById('technicianDropdown');
            dropdown.innerHTML = '';
            data.forEach(technician => {
                const item = document.createElement('a');
                item.className = 'dropdown-item';
                item.href = '#';
                item.textContent = technician;
                item.onclick = function(e) { selectOptionWithValidation(e, 'technician', technician); };
                dropdown.appendChild(item);
            });
            const divider = document.createElement('div');
            divider.className = 'dropdown-divider';
            dropdown.appendChild(divider);
            const addNew = document.createElement('a');
            addNew.className = 'dropdown-item text-primary';
            addNew.href = '#';
            addNew.setAttribute('data-bs-toggle', 'modal');
            addNew.setAttribute('data-bs-target', '#addTechnicianModal');
            addNew.innerHTML = '<i class="iconoir-plus me-2"></i>Add New Technician';
            dropdown.appendChild(addNew);
        });
}
</script>
