<script>
// Estimate Tab - Balance calculation
function calculateBalance() {
    const estimatedCost = parseFloat(document.getElementById('estimated_cost').value) || 0;
    const advance = parseFloat(document.getElementById('advance').value) || 0;
    const balance = estimatedCost - advance;
    document.getElementById('balance').value = balance >= 0 ? balance.toFixed(2) : '0.00';
}

document.getElementById('estimated_cost').addEventListener('input', calculateBalance);
document.getElementById('advance').addEventListener('input', calculateBalance);

document.getElementById('advance').addEventListener('change', function() {
    const estimatedCost = parseFloat(document.getElementById('estimated_cost').value) || 0;
    const advance = parseFloat(this.value) || 0;
    if (advance > estimatedCost) {
        alert('Advance amount cannot be greater than estimated cost!');
        this.value = estimatedCost;
        calculateBalance();
    }
});

// Enable/Disable Create JobSheet button
document.getElementById('jobsheet_required').addEventListener('change', function() {
    const createBtn = document.getElementById('createJobSheetBtn');
    if (this.checked) {
        createBtn.disabled = false;
        createBtn.classList.remove('disabled');
    } else {
        createBtn.disabled = true;
        createBtn.classList.add('disabled');
    }
});
</script>
