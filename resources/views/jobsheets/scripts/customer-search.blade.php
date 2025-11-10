<script>
// Customer Search with AJAX
document.getElementById('customerSearchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const searchQuery = document.getElementById('customerSearch').value.trim();
    
    if (searchQuery === '') {
        alert('Please enter a search term');
        return;
    }

    console.log('Searching for:', searchQuery);

    // Perform AJAX search
    fetch(`{{ route('customers.search') }}?query=${encodeURIComponent(searchQuery)}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new TypeError("Response is not JSON!");
        }
        
        return response.json();
    })
    .then(customers => {
        console.log('Search results:', customers);
        
        // Check if customers is an array
        if (!Array.isArray(customers)) {
            console.error('Expected array, got:', typeof customers);
            throw new TypeError('Search did not return an array of customers');
        }
        
        if (customers.length === 0) {
            alert('No customers found. Please try a different search term.');
            return;
        }

        // If single result, auto-select
        if (customers.length === 1) {
            selectCustomer(customers[0]);
        } else {
            // Show selection modal for multiple results
            showCustomerSelectionModal(customers);
        }
    })
    .catch(error => {
        console.error('Search Error:', error);
        alert('An error occurred while searching: ' + error.message + '\nPlease check the console for details.');
    });
});

function selectCustomer(customer) {
    console.log('Selecting customer:', customer);
    
    document.getElementById('searchRow').classList.add('d-none');
    document.getElementById('selectedCustomerRow').classList.remove('d-none');
    document.getElementById('displayCustomerId').textContent = customer.customer_id;
    document.getElementById('displayCustomerName').textContent = customer.full_name;
    document.getElementById('selected_customer_id').value = customer.customer_id;
    
    // ✅ ENABLE WIZARD AFTER CUSTOMER SELECTION
    enableWizard();
    console.log('✅ Customer selected:', customer.customer_id);
}

function showCustomerSelectionModal(customers) {
    console.log('Showing modal for customers:', customers);
    
    // Verify customers is an array
    if (!Array.isArray(customers) || customers.length === 0) {
        alert('No customers to display');
        return;
    }
    
    // Create a Bootstrap modal dynamically
    let modalHtml = `
        <div class="modal fade" id="customerSelectionModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group">
    `;

    customers.forEach(customer => {
        // Escape HTML to prevent XSS
        const customerName = String(customer.full_name || '').replace(/"/g, '&quot;');
        const customerId = String(customer.customer_id || '').replace(/"/g, '&quot;');
        const contactNo = String(customer.contact_no || '');
        const address = String(customer.address || '').substring(0, 50);
        
        modalHtml += `
            <a href="#" class="list-group-item list-group-item-action" onclick="event.preventDefault(); selectCustomerFromModal('${customerId}', '${customerName}');">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${customer.full_name}</h6>
                    <small class="badge bg-primary">${customer.customer_id}</small>
                </div>
                <p class="mb-1">${contactNo}</p>
                <small class="text-muted">${address}...</small>
            </a>
        `;
    });

    modalHtml += `
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('customerSelectionModal');
    if (existingModal) existingModal.remove();

    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('customerSelectionModal'));
    modal.show();
}

function selectCustomerFromModal(customerId, customerName) {
    console.log('Selected from modal:', customerId, customerName);
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('customerSelectionModal'));
    if (modal) modal.hide();
    
    selectCustomer({ customer_id: customerId, full_name: customerName });
}

function clearCustomer() {
    document.getElementById('searchRow').classList.remove('d-none');
    document.getElementById('selectedCustomerRow').classList.add('d-none');
    document.getElementById('selected_customer_id').value = '';
    document.getElementById('customerSearch').value = '';
    document.getElementById('customerSearch').focus();
    
    // ✅ DISABLE WIZARD WHEN CUSTOMER IS CLEARED
    disableWizard();
    console.log('🔒 Customer cleared - wizard disabled');
}
</script>
