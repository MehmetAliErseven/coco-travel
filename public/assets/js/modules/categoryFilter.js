export default class CategoryFilter {
    constructor(options = {}) {
        this.filterLinks = document.querySelectorAll(options.filterSelector || '.category-filter');
        this.container = document.querySelector(options.containerSelector || '#toursContainer');
        this.activeClass = options.activeClass || 'active';
        this.BASE_URL = window.basePath || '';
        this.init();
    }

    init() {
        if (this.filterLinks) {
            this.filterLinks.forEach(link => {
                link.addEventListener('click', (e) => this.handleFilter(e));
            });
        }
    }

    async handleFilter(e) {
        e.preventDefault();
        const categoryId = e.target.dataset.categoryId;
        
        // Update active state
        this.filterLinks.forEach(link => link.classList.remove(this.activeClass));
        e.target.classList.add(this.activeClass);

        try {
            const endpoint = categoryId === 'all' ? 
                `${this.BASE_URL}/api/tours/category/all` : 
                `${this.BASE_URL}/api/tours/category/${categoryId}`;
                
            const response = await fetch(endpoint);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.updateDisplay(data.tours);
            }
        } catch (error) {
            console.error('Category filtering failed:', error);
        }
    }

    updateDisplay(tours) {
        if (!this.container) return;
        
        if (tours.length) {
            const tourCards = tours.map(tour => this.createTourCard(tour)).join('');
            this.container.innerHTML = tourCards;
        } else {
            this.container.innerHTML = `<div class="col-12 text-center">${window.translationService.translate('No tours found')}</div>`;
        }
    }

    createTourCard(tour) {
        const imageUrl = tour.image_url 
            ? `${this.BASE_URL}/assets/uploads/${tour.image_url}`
            : `${this.BASE_URL}/assets/images/placeholder.jpg`;

        return `
            <div class="col-md-6 col-lg-4 col-xl-3" data-tour-id="${tour.id}">
                <div class="card tour-card h-100">
                    <img src="${imageUrl}" class="card-img-top" alt="${tour.title}">
                    
                    ${tour.price ? 
                    `<div class="tour-price">
                        ฿${parseFloat(tour.price).toFixed(2)}
                    </div>` : ''}
                    
                    <div class="card-body">
                        <span class="category-badge mb-2">${tour.category_name || window.translationService.translate('Uncategorized')}</span>
                        <h5 class="card-title">${tour.title}</h5>
                        
                        ${tour.duration ? 
                        `<p class="tour-duration mb-2">
                            <i class="far fa-clock me-1"></i> ${tour.duration}
                        </p>` : ''}
                        
                        <p class="card-text">${tour.description ? tour.description.substring(0, 80) + '...' : ''}</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="${this.BASE_URL}/tours/view/${tour.slug}" class="btn btn-outline-primary w-100">
                            ${window.translationService.translate('View Details')}
                        </a>
                    </div>
                </div>
            </div>
        `;
    }
}
