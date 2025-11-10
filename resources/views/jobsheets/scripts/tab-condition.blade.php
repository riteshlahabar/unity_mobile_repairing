<script>
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
</script>
