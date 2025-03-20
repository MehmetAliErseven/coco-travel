import ImageUploader from './imageUpload.js';

document.addEventListener('DOMContentLoaded', () => {
    // Initialize tour form functionality
    initTourForm();
    
    // Initialize delete confirmations
    initDeleteConfirmations();
    
    // Initialize image uploader if drop zone exists
    if (document.getElementById('dropZone')) {
        new ImageUploader();
    }
});

// Tour form handling with image preview and validation
function initTourForm() {
    const tourForm = document.querySelector('#tourForm');
    if (!tourForm) return;
    
    // Image preview functionality (basic version without drag-drop)
    const imageInput = document.querySelector('#tourImage');
    const imagePreview = document.querySelector('#imagePreview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Form validation
    tourForm.addEventListener('submit', function(e) {
        const nameField = document.querySelector('#tourName');
        const priceField = document.querySelector('#tourPrice');
        const categoryField = document.querySelector('#tourCategory');
        const descriptionField = document.querySelector('#tourDescription');
        
        let isValid = true;
        
        // Reset validation states
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        
        // Validate required fields
        if (!nameField.value.trim()) {
            nameField.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!priceField.value || isNaN(parseFloat(priceField.value)) || parseFloat(priceField.value) <= 0) {
            priceField.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!categoryField.value) {
            categoryField.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!descriptionField.value.trim() || descriptionField.value.length < 50) {
            descriptionField.classList.add('is-invalid');
            isValid = false;
        }
        
        const isCreateMode = tourForm.dataset.mode === 'create';
        if (isCreateMode && imageInput && (!imageInput.files || !imageInput.files[0])) {
            imageInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            document.querySelector('#validationMessage').innerHTML = 
                '<div class="alert alert-danger">Please correct the errors in the form.</div>';
        }
    });
}

// Confirmation for delete operations
function initDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const itemType = this.dataset.type || 'item';
            if (!confirm(`Are you sure you want to delete this ${itemType}? This action cannot be undone.`)) {
                e.preventDefault();
            }
        });
    });
}
