<script>
// Wizard Enable/Disable Functions
function enableWizard() {
    // Remove disabled attribute from all fieldsets
    document.querySelectorAll('#jobsheet-wizard fieldset').forEach(fieldset => {
        fieldset.removeAttribute('disabled');
    });
    
    // Check mobile tab validation after enabling
    checkMobileTabValidation();
    
    console.log('✅ Wizard enabled');
}

function disableWizard() {
    // Add disabled attribute to all fieldsets
    document.querySelectorAll('#jobsheet-wizard fieldset').forEach(fieldset => {
        fieldset.setAttribute('disabled', 'disabled');
    });
    
    // Disable all Next buttons
    const nextButtons = document.querySelectorAll('[id$="NextBtn"]');
    nextButtons.forEach(btn => {
        if (btn) {
            btn.disabled = true;
            btn.classList.add('disabled');
        }
    });
    
    console.log('🔒 Wizard disabled');
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
        console.log('✅ Mobile tab validated');
    } else {
        nextBtn.disabled = true;
        nextBtn.classList.add('disabled');
        console.log('❌ Mobile tab incomplete');
    }
}

// Tab Navigation
function nextTab(tabId) {
    const tab = document.getElementById(tabId);
    if (tab) {
        tab.click();
        console.log('→ Moving to tab:', tabId);
    }
}

function prevTab(tabId) {
    const tab = document.getElementById(tabId);
    if (tab) {
        tab.click();
        console.log('← Moving back to tab:', tabId);
    }
}

// Initialize on page load
window.addEventListener('DOMContentLoaded', function() {
    // Check if customer is already selected (from URL params)
    const urlParams = new URLSearchParams(window.location.search);
    const customerId = urlParams.get('customer_id');
    
    if (!customerId) {
        // No customer selected - disable wizard
        disableWizard();
        console.log('⚠️ No customer selected - wizard disabled');
    }
    // If customer exists, enableWizard() will be called from the main script
});
</script>
