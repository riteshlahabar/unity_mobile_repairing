<script>
// Wizard Enable/Disable
function enableWizard() {
    document.querySelectorAll('#jobsheet-wizard fieldset').forEach(fieldset => {
        fieldset.removeAttribute('disabled');
    });
}

function disableWizard() {
    document.querySelectorAll('#jobsheet-wizard fieldset').forEach(fieldset => {
        fieldset.setAttribute('disabled', 'disabled');
    });
}

// Dropdown Selection
function selectOption(event, field, value) {
    event.preventDefault();
    document.getElementById(field).value = value;
    const button = event.target.closest('.btn-group').querySelector('button');
    button.innerHTML = value + ' <i class="las la-angle-down float-end"></i>';
}

// Tab Navigation
function nextTab(tabId) {
    document.getElementById(tabId).click();
}

function prevTab(tabId) {
    document.getElementById(tabId).click();
}

// Initialize - Disable wizard on load
window.addEventListener('DOMContentLoaded', function() {
    disableWizard();
});
</script>
