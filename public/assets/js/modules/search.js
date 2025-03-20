export default class TourSearch {
    constructor(options = {}) {
        this.searchForm = document.querySelector('#tourSearchForm');
        if (!this.searchForm) return;
        
        this.searchInput = this.searchForm.querySelector('#searchInput');
        this.categorySelect = this.searchForm.querySelector('#category-filter');
        this.categoryButtons = document.querySelectorAll('.category-filter');
        this.resultsContainer = document.querySelector('#toursContainer') || document.querySelector('#live-search-results');
        this.countInfo = document.querySelector('.text-muted span');
        
        this.BASE_URL = window.basePath || '';
        this.originalContent = this.resultsContainer ? this.resultsContainer.innerHTML : '';
        this.originalCount = this.countInfo ? this.countInfo.textContent : '';
        this.selectedCategoryId = this.categorySelect ? this.categorySelect.value : 'all';
        
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

        // Handle empty search on homepage
        if (this.isHomePage && !query) {
            if (this.resultsContainer) {
                this.resultsContainer.style.display = 'none';
            }
            return;
        }

        // Handle empty search and all category on tours page
        if (!this.isHomePage && !query && this.selectedCategoryId === 'all') {
            if (this.resultsContainer && this.originalContent) {
                this.resultsContainer.innerHTML = this.originalContent;
            }
            if (this.countInfo && this.originalCount) {
                this.countInfo.textContent = this.originalCount;
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
                        <span class="tour-category">${tour.category_name || 'Uncategorized'}</span>
                    </div>
                    <div class="tour-price">
                        ${tour.price ? `$${parseFloat(tour.price).toFixed(2)}` : ''}
                    </div>
                    <a href="${this.BASE_URL}/tours/view/${tour.slug}" class="btn btn-sm btn-outline-primary ms-2">
                        View Details
                    </a>
                </div>
            `).join('');
        } else {
            this.resultsContainer.innerHTML = '<div class="no-results">No tours found</div>';
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
                            $${parseFloat(tour.price).toFixed(2)}
                        </div>` : ''}
                        
                        <div class="card-body">
                            <span class="category-badge mb-2">${tour.category_name || 'Uncategorized'}</span>
                            <h5 class="card-title">${tour.title}</h5>
                            
                            ${tour.duration ? 
                            `<p class="tour-duration mb-2">
                                <i class="far fa-clock me-1"></i> ${tour.duration}
                            </p>` : ''}
                            
                            <p class="card-text">${tour.description ? tour.description.substring(0, 80) + '...' : ''}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="${this.BASE_URL}/tours/view/${tour.slug}" class="btn btn-outline-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            this.resultsContainer.innerHTML = '<div class="col-12 text-center">No tours found</div>';
        }

        // Update tour count info
        if (this.countInfo) {
            this.countInfo.textContent = `Showing ${filteredCount} of ${totalCount} tours`;
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
