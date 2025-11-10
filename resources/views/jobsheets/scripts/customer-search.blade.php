<script>
// Customer Search and Selection
document.getElementById('customerSearchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    document.getElementById('searchRow').classList.add('d-none');
    document.getElementById('selectedCustomerRow').classList.remove('d-none');
    document.getElementById('displayCustomerId').textContent = 'UMR0001';
    document.getElementById('displayCustomerName').textContent = 'John Doe';
    document.getElementById('selected_customer_id').value = 'UMR0001';
    enableWizard();
});

function clearCustomer() {
    document.getElementById('searchRow').classList.remove('d-none');
    document.getElementById('selectedCustomerRow').classList.add('d-none');
    document.getElementById('selected_customer_id').value = '';
    document.getElementById('customerSearch').value = '';
    document.getElementById('customerSearch').focus();
    disableWizard();
}
</script>
