import DynamicLoader from './modules/dynamicLoader.js';
import TourSearch from './modules/search.js';
import './modules/language-switcher.js'; // Dil değiştirici modülünü ekledik

document.addEventListener('DOMContentLoaded', () => {
    // Initialize search module if search input or category filters exist
    if (document.querySelector('#searchInput') || document.querySelector('.category-filter')) {
        new TourSearch();
    }
    
    if (document.querySelector('#loadMoreTours')) {
        new DynamicLoader();
    }
    
    // Initialize Bootstrap components
    initBootstrapComponents();
    
    // Initialize contact form validation if present
    if (document.querySelector('#contactForm')) {
        initContactForm();
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            // Check if href is just "#" or if the target element exists
            if (href === "#") {
                e.preventDefault(); // Just prevent default for empty anchors
            } else if (document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Initialize Bootstrap tooltips and popovers
function initBootstrapComponents() {
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.forEach(popoverTriggerEl => {
        new bootstrap.Popover(popoverTriggerEl);
    });
}

// Contact form validation and submission
function initContactForm() {
    const contactForm = document.querySelector('#contactForm');
    if (!contactForm) return;
    
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Validate form
        if (!validateContactForm()) return;
        
        // Submit form via fetch API
        try {
            const formData = new FormData(contactForm);
            const response = await fetch('/api/contact', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.success) {
                showFormMessage('success', 'Your message has been sent successfully!');
                contactForm.reset();
            } else {
                showFormMessage('danger', 'Error sending message. Please try again.');
            }
        } catch (error) {
            showFormMessage('danger', 'An error occurred. Please try again later.');
        }
    });
}

function validateContactForm() {
    // Get form fields
    const nameField = document.querySelector('#contactName');
    const emailField = document.querySelector('#contactEmail');
    const messageField = document.querySelector('#contactMessage');
    
    let isValid = true;
    
    // Reset previous validation states
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // Validate name
    if (!nameField.value.trim()) {
        nameField.classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailField.value)) {
        emailField.classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate message
    if (!messageField.value.trim() || messageField.value.length < 10) {
        messageField.classList.add('is-invalid');
        isValid = false;
    }
    
    return isValid;
}

function showFormMessage(type, message) {
    const messageContainer = document.querySelector('#formMessage');
    if (!messageContainer) return;
    
    messageContainer.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            messageContainer.innerHTML = '';
        }, 5000);
    }
}
