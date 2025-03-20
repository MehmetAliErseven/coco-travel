export default class ImageUploader {
    constructor(options = {}) {
        this.dropZone = document.getElementById(options.dropZoneId || 'dropZone');
        this.fileInput = document.getElementById(options.fileInputId || 'tourImage');
        this.imagePreview = document.getElementById(options.previewId || 'imagePreview');
        this.maxSize = options.maxSize || 5 * 1024 * 1024; // 5MB
        this.allowedTypes = options.allowedTypes || ['image/jpeg', 'image/png', 'image/webp'];

        if (this.dropZone && this.fileInput) {
            this.bindEvents();
        }
    }

    bindEvents() {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
            this.dropZone.addEventListener(event, this.preventDefaults);
        });

        ['dragenter', 'dragover'].forEach(event => {
            this.dropZone.addEventListener(event, () => this.highlight());
        });

        ['dragleave', 'drop'].forEach(event => {
            this.dropZone.addEventListener(event, () => this.unhighlight());
        });

        this.dropZone.addEventListener('drop', e => this.handleDrop(e));
        this.fileInput.addEventListener('change', e => this.handleFileSelect(e));
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    highlight() {
        this.dropZone.classList.add('drag-over');
    }

    unhighlight() {
        this.dropZone.classList.remove('drag-over');
    }

    handleDrop(e) {
        const file = e.dataTransfer.files[0];
        if (file) {
            this.validateAndPreview(file);
        }
    }

    handleFileSelect(e) {
        const file = e.target.files[0];
        if (file) {
            this.validateAndPreview(file);
        }
    }

    validateAndPreview(file) {
        if (!this.allowedTypes.includes(file.type)) {
            alert('Please upload only JPG, PNG or WebP images.');
            return false;
        }

        if (file.size > this.maxSize) {
            alert('File size should not exceed 5MB.');
            return false;
        }

        const reader = new FileReader();
        reader.onload = e => {
            if (this.imagePreview) {
                this.imagePreview.src = e.target.result;
                this.imagePreview.style.display = 'block';
                this.dropZone.querySelector('.drop-zone-text').style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
        return true;
    }
}
