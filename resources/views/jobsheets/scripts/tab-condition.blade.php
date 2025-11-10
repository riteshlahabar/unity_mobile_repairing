<script>
// Condition Tab - Track selections
document.querySelectorAll('input[name="water_damage"], input[name="physical_damage"]').forEach(radio => {
    radio.addEventListener('change', function() {
        console.log('Water Damage:', document.querySelector('input[name="water_damage"]:checked')?.value);
        console.log('Physical Damage:', document.querySelector('input[name="physical_damage"]:checked')?.value);
    });
});

// Set default date and time
window.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const deliveredDate = document.getElementById('delivered_date');
    if (deliveredDate) deliveredDate.value = today;
    
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const deliveredTime = document.getElementById('delivered_time');
    if (deliveredTime) deliveredTime.value = `${hours}:${minutes}`;
});
</script>
