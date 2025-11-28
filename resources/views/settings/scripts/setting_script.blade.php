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

            try {
                const response = await fetch(route, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
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
</script>
