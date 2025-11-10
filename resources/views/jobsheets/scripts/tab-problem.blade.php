<script>
// Problem Tab Validation
function checkProblemTabValidation() {
    const problemDescription = document.getElementById('problem_description')?.value.trim();
    const nextBtn = document.getElementById('problemNextBtn');
    
    if (!nextBtn) return; // Exit if button doesn't exist
    
    // Enable next button only if problem description is filled
    if (problemDescription && problemDescription.length > 0) {
        nextBtn.disabled = false;
        nextBtn.classList.remove('disabled');
    } else {
        nextBtn.disabled = true;
        nextBtn.classList.add('disabled');
    }
}

// Track device status selections
document.querySelectorAll('input[type="checkbox"][id^="status_"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const statuses = [];
        if (document.getElementById('status_dead')?.checked) statuses.push('Dead');
        if (document.getElementById('status_damage')?.checked) statuses.push('Damage');
        if (document.getElementById('status_on')?.checked) statuses.push('On with Problem');
        console.log('Selected Statuses:', statuses.join(', '));
    });
});

// Problem Description Input Validation
document.getElementById('problem_description')?.addEventListener('input', function() {
    checkProblemTabValidation();
});

// Initialize validation on page load
window.addEventListener('DOMContentLoaded', function() {
    checkProblemTabValidation();
});
</script>
