<script>
function showSuccessModal(message) {
    const modalBody = document.getElementById('successModalBody');
    modalBody.textContent = message || "Data saved successfully.";
    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', () => {
    const forms = [
        {formId: 'businessInfoForm', route: "{{ route('setting.updateBusinessInfo') }}"},
        {formId: 'termsConditionsForm', route: "{{ route('setting.updateTermsConditions') }}"},
        {formId: 'remarksForm', route: "{{ route('setting.updateRemarks') }}"},
        {formId: 'securityForm', route: "{{ route('setting.updateSecurity') }}"}
    ];

    forms.forEach(({formId, route}) => {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            formData.append('_method', 'PUT');

            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            // ✅ PATCH ONLY: Safe CSRF token extraction
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                              form.querySelector('input[name="_token"]')?.value;
            
            if (!csrfToken) {
                alert('Security token missing. Please refresh the page.');
                submitButton.disabled = false;
                return;
            }

            try {
                const response = await fetch(route, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showSuccessModal(data.message);

                    if(formId === 'securityForm'){
                        form.reset(); // clear passwords after save
                    }
                } else {
                    alert(data.message || "An error occurred. Please check your input.");
                }
            } catch (err) {
                console.error('Error saving data:', err);
                alert("Error saving data. Please try again.");
            } finally {
                submitButton.disabled = false;
            }
        });
    });
});

// Change PIN form handler
$('#changePinForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: "{{ route('setting.changePin') }}",
        type: 'POST',
        data: $(this).serialize(),
        beforeSend: function() {
            $('#changePinForm button[type="submit"]').prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i>Changing...');
        },
        success: function(response) {
            showSuccessModal(response.message || 'PIN changed successfully!');
            $('#changePinForm')[0].reset();
        },
        error: function(xhr) {
            let errorMsg = 'An error occurred';
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                errorMsg = Object.values(errors)[0][0];
            }
            showErrorModal(errorMsg);
        },
        complete: function() {
            $('#changePinForm button[type="submit"]').prop('disabled', false).html('Change PIN');
        }
    });
});

// Helper functions (add if not exists)
function showSuccessModal(message) {
    $('#successModalBody').text(message);
    $('#successModal').modal('show');
}

function showErrorModal(message) {
    alert(message); // Or create error modal
}
</script>
