<script>
function showSuccessModal(message) {
    const modalBody = document.getElementById('successModalBody');
    modalBody.textContent = message || "Data saved successfully.";
    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', () => {
    const forms = [
        {formId: 'businessInfoForm', route: "{{ route('setting.updateBusinessInfo') }}", updateImage: null},
        {formId: 'termsConditionsForm', route: "{{ route('setting.updateTermsConditions') }}", updateImage: null},
        {formId: 'remarksForm', route: "{{ route('setting.updateRemarks') }}", updateImage: null},
        {formId: 'logoForm', route: "{{ route('setting.updateLogo') }}", updateImage: 'logoImage'},
        {formId: 'profilePictureForm', route: "{{ route('setting.updateProfilePicture') }}", updateImage: 'profilePictureImage'},
        {formId: 'unitySignatureForm', route: "{{ route('setting.updateUnitySignature') }}", updateImage: 'unitySignatureImage'},
        {formId: 'securityForm', route: "{{ route('setting.updateSecurity') }}", updateImage: null}
    ];

    forms.forEach(({formId, route, updateImage}) => {
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

                    // If this form uploads image, update image src dynamically
                    if (updateImage && data.image_url) {
                        const imageEl = document.getElementById(updateImage);
                        if (imageEl) {
                            imageEl.src = data.image_url; // data.image_url should be sent from backend
                            imageEl.style.display = 'block';
                        }
                    }

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
