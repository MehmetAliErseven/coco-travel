import DynamicLoader from './modules/dynamicLoader.js';
import TourSearch from './modules/search.js';
import './modules/language-switcher.js';
import TranslationService from './modules/translation.js';

// Create a global translation service instance
window.translationService = new TranslationService();

document.addEventListener('DOMContentLoaded', async () => {
    // Initialize all needed translations at once
    await window.translationService.loadTranslations([
        // Tour listing translations
        'View Details',
        'No tours found',
        'Uncategorized',
        
        // Tour detail page translations
        'Share on Facebook',
        'Share on Twitter',
        'Share on WhatsApp',
        'Share via Email',
        'Click to enlarge',
        
        // Contact form translations
        'Your message has been sent successfully!',
        'Error sending message. Please try again.',
        'An error occurred. Please try again later.',
        'Please enter your name',
        'Please enter a valid email address',
        'Please enter your message'
    ]);

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
            const response = await fetch('/api/contact/submit', {
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
    const nameField = document.querySelector('#name');
    const emailField = document.querySelector('#email');
    const messageField = document.querySelector('#message');
    
    let isValid = true;
    
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    if (!nameField.value.trim()) {
        nameField.classList.add('is-invalid');
        nameField.closest('.form-floating').querySelector('.invalid-feedback').textContent = window.translationService.translate('Please enter your name');
        isValid = false;
    }
    
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailField.value)) {
        emailField.classList.add('is-invalid');
        emailField.closest('.form-floating').querySelector('.invalid-feedback').textContent = window.translationService.translate('Please enter a valid email address');
        isValid = false;
    }
    
    if (!messageField.value.trim()) {
        messageField.classList.add('is-invalid');
        messageField.closest('.form-floating').querySelector('.invalid-feedback').textContent = window.translationService.translate('Please enter your message');
        isValid = false;
    }
    
    return isValid;
}

function showFormMessage(type, message) {
    const messageContainer = document.querySelector('#formMessage');
    if (!messageContainer) return;
    
    messageContainer.innerHTML = `<div class="alert alert-${type}">${window.translationService.translate(message)}</div>`;
    
    if (type === 'success') {
        setTimeout(() => {
            messageContainer.innerHTML = '';
        }, 5000);
    }
}
