/**
 * Tour Detail Page Module
 */
export default class TourDetail {
    constructor(options = {}) {
        // Initialize properties
        this.shareButtons = document.querySelectorAll('.social-share-btn, [data-share]');
        this.galleryContainer = document.querySelector('.tour-images-gallery');
        this.galleryImages = document.querySelectorAll('.tour-gallery-image');
        this.mainImage = document.querySelector('.tour-main-image');
        
        // Initialize functionality
        this.init();
    }
    
    init() {
        this.initSocialSharing();
        if (this.galleryContainer) {
            this.initImageGallery();
        }
    }
    
    /**
     * Initialize social sharing functionality
     */
    initSocialSharing() {
        if (!this.shareButtons.length) return;
        
        this.shareButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // Don't prevent default for email links
                if (!button.getAttribute('href')?.startsWith('mailto:')) {
                    e.preventDefault();
                    
                    // Open share dialogs in popup window
                    const url = button.getAttribute('href') || 
                                button.dataset.shareUrl;
                                
                    if (url) {
                        window.open(url, 'share-dialog', 'width=626,height=436');
                    }
                }
            });
        });
    }
    
    /**
     * Initialize image gallery/lightbox if multiple images are present
     */
    initImageGallery() {
        if (!this.galleryImages.length || !this.mainImage) return;
        
        this.galleryImages.forEach(image => {
            image.addEventListener('click', () => {
                // Update main image when thumbnail is clicked
                const fullSrc = image.dataset.fullImage;
                if (this.mainImage && fullSrc) {
                    this.mainImage.src = fullSrc;
                    
                    // If main image is in a lightbox link, update that too
                    const lightboxLink = this.mainImage.closest('a[data-lightbox]');
                    if (lightboxLink) {
                        lightboxLink.href = fullSrc;
                    }
                }
                
                // Update active state
                this.galleryImages.forEach(img => img.classList.remove('active'));
                image.classList.add('active');
            });
        });
    }
}
