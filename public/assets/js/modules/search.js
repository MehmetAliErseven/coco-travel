export default class TourSearch {
    constructor(options = {}) {
        this.searchForm = document.querySelector('#tourSearchForm');
        if (!this.searchForm) return;
        
        this.searchInput = this.searchForm.querySelector('#searchInput');
        this.dateInput = this.searchForm.querySelector('#search-date');
        this.categorySelect = this.searchForm.querySelector('#category-filter');
        this.categoryButtons = document.querySelectorAll('.category-filter');
        this.resultsContainer = document.querySelector('#toursContainer') || document.querySelector('#live-search-results');
        
        this.BASE_URL = window.basePath || '';
        this.originalContent = this.resultsContainer ? this.resultsContainer.innerHTML : '';
        this.selectedCategoryId = this.categorySelect ? this.categorySelect.value : 'all';
        this.selectedDate = this.dateInput ? this.dateInput.value : '';
        
        // Determine if we're on the home page
        this.isHomePage = window.location.pathname === '/' || window.location.pathname === this.BASE_URL;
        
        this.init();
    }

    init() {
        if (this.resultsContainer && this.resultsContainer.children.length > 0) {
            this.originalContent = this.resultsContainer.innerHTML;
        }
        
        if (this.searchInput) {
            this.searchInput.addEventListener('input', debounce((e) => this.handleSearch(e), 500));
        }

        if (this.dateInput) {
            this.dateInput.addEventListener('change', (e) => this.handleSearch(e));
        }

        if (this.searchForm) {
            this.searchForm.addEventListener('submit', (e) => {
                if (this.isHomePage) {
                    return true;
                }
                e.preventDefault();
                this.handleSearch(e);
            });
        }

        if (this.categoryButtons) {
            this.categoryButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.categoryButtons.forEach(btn => btn.classList.remove('active'));
                    e.target.classList.add('active');
                    this.selectedCategoryId = e.target.dataset.categoryId;
                    if (this.categorySelect) {
                        this.categorySelect.value = this.selectedCategoryId;
                    }
                    this.handleSearch(e);
                });
            });
        }

        if (this.categorySelect) {
            this.categorySelect.addEventListener('change', (e) => {
                this.selectedCategoryId = e.target.value;
                this.handleSearch(e);
            });
        }
    }

    async handleSearch(e) {
        e.preventDefault();
        const query = this.searchInput ? this.searchInput.value.trim() : '';
        this.selectedDate = this.dateInput ? this.dateInput.value : '';

        // Handle empty search on homepage
        if (this.isHomePage && !query && !this.selectedDate) {
            if (this.resultsContainer) {
                this.resultsContainer.style.display = 'none';
            }
            return;
        }

        try {
            let endpoint = `${this.BASE_URL}/api/tours/search`;
            let params = new URLSearchParams();
            
            if (query) {
                params.append('q', query);
            }
            
            if (this.selectedCategoryId !== 'all' && this.selectedCategoryId !== '0') {
                params.append('category_id', this.selectedCategoryId);
            }
            
            if (this.selectedDate) {
                params.append('start_date', this.selectedDate);
            }

            const paramString = params.toString();
            endpoint = `${endpoint}${paramString ? '?' + paramString : ''}`;
            
            const response = await fetch(endpoint);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            
            if (data.success) {
                if (this.isHomePage) {
                    this.displayLiveSearchResults(data.tours);
                } else {
                    this.displayToursPageResults(data.tours, data.totalCount, data.filteredCount);
                }
            }
        } catch (error) {
            console.error('Search failed:', error);
        }
    }

    displayLiveSearchResults(tours) {
        if (!this.resultsContainer) return;

        if (tours.length) {
            this.resultsContainer.innerHTML = tours.map(tour => `
                <div class="tour-card">
                    <img src="${tour.image_url ? 
                        `${this.BASE_URL}/assets/uploads/${tour.image_url}` : 
                        `${this.BASE_URL}/assets/images/placeholder.jpg`}" 
                        class="tour-image" alt="${tour.title}">
                    <div class="tour-info">
                        <h5 class="tour-title">${tour.title}</h5>
                        <div>
                            ${tour.start_date ? 
                            `<div class="tour-date"><i class="far fa-calendar-alt me-1"></i>${new Date(tour.start_date).toLocaleDateString()}</div>` : ''}
                            ${tour.location ? 
                            `<div class="tour-location"><i class="fas fa-map-marker-alt me-1"></i>${tour.location}</div>` : ''}
                        </div>
                    </div>
                    <div class="tour-price">
                        ${tour.price ? `฿${parseFloat(tour.price).toFixed(2)}` : ''}
                    </div>
                    <a href="${this.BASE_URL}/tours/view/${tour.slug}" class="btn btn-sm btn-outline-primary ms-2">
                        ${window.translationService.translate('View Details')}
                    </a>
                </div>
            `).join('');
        } else {
            this.resultsContainer.innerHTML = `<div class="no-results">${window.translationService.translate('No tours found')}</div>`;
        }
        
        this.resultsContainer.style.display = 'block';
    }

    displayToursPageResults(tours, totalCount, filteredCount) {
        if (!this.resultsContainer) return;

        if (tours.length) {
            this.resultsContainer.innerHTML = tours.map(tour => `
                <div class="col-md-6 col-lg-4 col-xl-3" data-tour-id="${tour.id}">
                    <div class="card tour-card h-100">
                        <img src="${tour.image_url ? 
                            `${this.BASE_URL}/assets/uploads/${tour.image_url}` : 
                            `${this.BASE_URL}/assets/images/placeholder.jpg`}" 
                            class="card-img-top" alt="${tour.title}">
                        
                        ${tour.price ? 
                        `<div class="tour-price">
                            ฿${parseFloat(tour.price).toFixed(2)}
                        </div>` : ''}
                        
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <span class="category-badge me-1">${tour.category_name || window.translationService.translate('Uncategorized')}</span>
                                ${tour.location ? 
                                `<span class="badge bg-info text-dark"><i class="fas fa-map-marker-alt me-1"></i>${tour.location}</span>` : ''}
                            </div>
                            <h5 class="card-title">${tour.title}</h5>
                            
                            <p class="tour-duration mb-2">
                                ${tour.start_date ? 
                                `<i class="far fa-calendar-alt me-1"></i>${new Date(tour.start_date).toLocaleDateString()}` : ''}
                                ${tour.start_date && tour.duration ? ' <i class="fas fa-circle mx-1" style="font-size: 5px; vertical-align: middle;"></i> ' : ''}
                                ${tour.duration ? 
                                `<i class="far fa-clock me-1"></i>${tour.duration}` : ''}
                            </p>
                            
                            <p class="card-text">${tour.description ? tour.description.substring(0, 80) + '...' : ''}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="${this.BASE_URL}/tours/view/${tour.slug}" class="btn btn-outline-primary w-100">
                                ${window.translationService.translate('View Details')}
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            this.resultsContainer.innerHTML = `<div class="col-12 text-center alert alert-info">${window.translationService.translate('No tours found')}</div>`;
        }

        // Update filtered count
        const filteredCountSpan = document.getElementById('filteredCount');
        if (filteredCountSpan) {
            filteredCountSpan.textContent = filteredCount;
        }
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
