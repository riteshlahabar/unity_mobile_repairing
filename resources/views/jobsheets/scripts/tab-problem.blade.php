<script>
// Problem Tab - Track device status selections
document.querySelectorAll('input[type="checkbox"][id^="status_"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const statuses = [];
        if (document.getElementById('status_dead')?.checked) statuses.push('Dead');
        if (document.getElementById('status_damage')?.checked) statuses.push('Damage');
        if (document.getElementById('status_on')?.checked) statuses.push('On with Problem');
        console.log('Selected Statuses:', statuses.join(', '));
    });
});
</script>
