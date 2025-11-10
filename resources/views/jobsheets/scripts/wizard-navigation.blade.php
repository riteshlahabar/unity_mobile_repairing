<script>
// Wizard Enable/Disable
function enableWizard() {
    document.querySelectorAll('#jobsheet-wizard fieldset').forEach(fieldset => {
        fieldset.removeAttribute('disabled');
    });
    checkMobileTabValidation(); // Check validation after enabling
}

function disableWizard() {
    document.querySelectorAll('#jobsheet-wizard fieldset').forEach(fieldset => {
        fieldset.setAttribute('disabled', 'disabled');
    });
}

// Dropdown Selection with validation check
function selectOption(event, field, value) {
    event.preventDefault();
    document.getElementById(field).value = value;
    const button = event.target.closest('.btn-group').querySelector('button');
    button.innerHTML = value + ' <i class="las la-angle-down float-end"></i>';
    
    // Check if all required mobile fields are filled
    checkMobileTabValidation();
}

// Check Mobile Tab Validation
function checkMobileTabValidation() {
    const company = document.getElementById('company')?.value;
    const model = document.getElementById('model')?.value;
    const color = document.getElementById('color')?.value;
    const series = document.getElementById('series')?.value;
    const nextBtn = document.getElementById('mobileNextBtn');
    
    if (!nextBtn) return; // Exit if button doesn't exist on current page
    
    // Enable next button only if all required fields are filled
    if (company && model && color && series) {
        nextBtn.disabled = false;
        nextBtn.classList.remove('disabled');
    } else {
        nextBtn.disabled = true;
        nextBtn.classList.add('disabled');
    }
}

// Tab Navigation
function nextTab(tabId) {
    const tab = document.getElementById(tabId);
    if (tab) tab.click();
}

function prevTab(tabId) {
    const tab = document.getElementById(tabId);
    if (tab) tab.click();
}

// Initialize - Disable wizard on load and check validation
window.addEventListener('DOMContentLoaded', function() {
    disableWizard();
    checkMobileTabValidation(); // Initial check
});
</script>
