export default class DynamicLoader {
    constructor(options = {}) {
        this.button = document.querySelector(options.buttonSelector || '#loadMoreTours');
        this.container = document.querySelector(options.containerSelector || '#toursContainer');
        this.currentPage = 1;
        this.loading = false;
        this.init();
    }

    init() {
        if (this.button) {
            this.button.addEventListener('click', () => this.loadMore());
            // Optional: Infinite scroll
            if (window.IntersectionObserver) {
                this.observeScroll();
            }
        }
    }

    async loadMore() {
        if (this.loading) return;
        this.loading = true;
        this.currentPage++;

        try {
            const response = await fetch(`/api/tours/page/${this.currentPage}`);
            const data = await response.json();
            
            if (data.success) {
                this.appendTours(data.tours);
                if (!data.hasMore) {
                    this.button.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Loading more tours failed:', error);
        } finally {
            this.loading = false;
        }
    }

    appendTours(tours) {
        if (!this.container) return;
        
        const html = tours.map(tour => this.createTourCard(tour)).join('');
        this.container.insertAdjacentHTML('beforeend', html);
    }

    observeScroll() {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !this.loading) {
                this.loadMore();
            }
        }, { threshold: 0.5 });

        if (this.button) {
            observer.observe(this.button);
        }
    }

    createTourCard(tour) {
        return `
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="${tour.image_url}" class="card-img-top" alt="${tour.title}">
                    <div class="card-body">
                        <h5 class="card-title">${tour.title}</h5>
                        <p class="card-text">${tour.description.substring(0, 100)}...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price">$${tour.price}</span>
                            <a href="/tour/${tour.slug}" class="btn btn-primary">${window.translationService.translate('View Details')}</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}
