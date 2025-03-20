/**
 * Language switcher functionality
 * Handles language changes via AJAX without page reload
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get all language switcher links
    const languageLinks = document.querySelectorAll('.language-switcher');
    
    // Add click event listener to each language link
    languageLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the language code from the data attribute
            const langCode = this.getAttribute('data-lang');
            
            // Send AJAX request to change language
            fetch(`${window.basePath}/api/change-language?lang=${langCode}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to apply the language change
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error changing language:', error);
            });
        });
    });
});