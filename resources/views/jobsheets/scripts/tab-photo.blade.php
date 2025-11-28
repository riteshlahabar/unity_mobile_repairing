<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo Upload with Preview (GALLERY)
    const devicePhotosInput = document.getElementById('device_photos');
    if (devicePhotosInput) {
        devicePhotosInput.addEventListener('change', handlePhotoPreview);
    }

    // Camera input (CAMERA)
    const deviceCameraInput = document.getElementById('device_camera');
    if (deviceCameraInput) {
        deviceCameraInput.addEventListener('change', handlePhotoPreview);
    }

    // Take Photo button
    const takePhotoBtn = document.getElementById('takePhotoBtn');
    if (takePhotoBtn) {
        takePhotoBtn.addEventListener('click', function() {
            if (deviceCameraInput) deviceCameraInput.click();
        });
    }
});

// ✅ SINGLE REUSABLE FUNCTION for both gallery & camera
function handlePhotoPreview(e) {
    const files = e.target.files;
    const previewBox = document.getElementById('preview-box');
    const emptyState = document.getElementById('emptyState');
    
    if (files.length === 0) return;
    
    if (files.length > 5) {
        alert('Maximum 5 photos allowed. Please select up to 5 images.');
        e.target.value = '';
        return;
    }
    
    if (emptyState) emptyState.style.display = 'none';
    
    // Clear existing previews
    const existingPreviews = previewBox.querySelectorAll('.photo-preview-item');
    existingPreviews.forEach(item => item.remove());
    
    Array.from(files).forEach((file) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const photoDiv = document.createElement('div');
                photoDiv.className = 'photo-preview-item';
                photoDiv.innerHTML = `
                    <div class="position-relative" style="width: 150px; height: 150px;">
                        <img src="${e.target.result}" class="img-thumbnail" 
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 2px solid #e3e6f0;">
                        <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute" 
                                style="top: -8px; right: -8px; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"
                                onclick="removePhotoPreview(this)" title="Remove photo">
                            <i class="iconoir-xmark" style="font-size: 16px;"></i>
                        </button>
                    </div>
                `;
                previewBox.appendChild(photoDiv);
            };
            reader.readAsDataURL(file);
        }
    });
}

// ✅ FIXED: Handles both gallery & camera inputs
function removePhotoPreview(button) {
    const previewBox = document.getElementById('preview-box');
    const emptyState = document.getElementById('emptyState');
    
    button.closest('.photo-preview-item').remove();
    
    const remainingPhotos = previewBox.querySelectorAll('.photo-preview-item');
    if (remainingPhotos.length === 0) {
        // Clear BOTH file inputs
        const galleryInput = document.getElementById('device_photos');
        const cameraInput = document.getElementById('device_camera');
        if (galleryInput) galleryInput.value = '';
        if (cameraInput) cameraInput.value = '';
        if (emptyState) emptyState.style.display = 'block';
    }
}
</script>
