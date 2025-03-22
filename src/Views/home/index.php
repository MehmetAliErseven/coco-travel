<!-- Hero Section with Search -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title"><?= $translator->trans('Journey Beyond Dreams') ?></h1>
        <p class="hero-subtitle"><?= $translator->trans('Make your dream vacation come true with Coco Travel') ?></p>
        
        <div class="search-box rounded shadow-lg p-3">
            <form id="tourSearchForm" action="<?= \App\Helpers\url('home/search') ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control border-0 bg-white" id="searchInput" name="query" value="">
                            <label for="searchInput"><i class="fas fa-search text-primary me-2"></i><?= $translator->trans('Where do you want to go?') ?></label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control border-0 bg-white" id="search-date" name="start_date" placeholder="<?= $translator->trans('When') ?>">
                            <label for="search-date"><i class="far fa-calendar-alt text-primary me-2"></i><?= $translator->trans('When') ?></label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select border-0 bg-white" id="category-filter" name="category_id">
                                <option value="0" selected><?= $translator->trans('All Categories') ?></option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="category-filter"><i class="fas fa-tag text-primary me-2"></i><?= $translator->trans('Category') ?></label>
                        </div>
                    </div>
                </div>
                <div id="live-search-results" class="position-absolute bg-white shadow rounded w-100 mt-2 z-3" style="display: none; max-height: 300px; overflow-y: auto;"></div>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="<?= \App\Helpers\url('tours') ?>" class="fw-bold text-white text-decoration-none">
                <i class="fas fa-arrow-right text-primary me-2"></i><?= $translator->trans('View All Tours') ?>
            </a>
        </div>
    </div>
</section>

<!-- Featured Tours Section -->
<?php if (!empty($featuredTours)): ?>
<section class="py-5">
    <div class="container">
        <h2 class="section-title"><?= $translator->trans('Popular Destinations') ?></h2>
        
        <div class="row g-4">
            <?php foreach ($featuredTours as $tour): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card tour-card">
                    <?php if (!empty($tour['image_url'])): ?>
                    <img src="<?= \App\Helpers\asset('uploads/' . $tour['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($tour['title']) ?>">
                    <?php else: ?>
                    <img src="<?= \App\Helpers\asset('images/placeholder.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($tour['title']) ?>">
                    <?php endif; ?>
                    
                    <?php if ($tour['price']): ?>
                    <div class="tour-price">
                        <?= \App\Helpers\formatPrice($tour['price']) ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <span class="category-badge me-1"><?= htmlspecialchars($tour['category_name']) ?></span>
                            <?php if (!empty($tour['location'])): ?>
                            <span class="badge bg-info text-dark"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($tour['location']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h5 class="card-title"><?= htmlspecialchars($tour['title']) ?></h5>
                        
                        <p class="tour-duration mb-2">
                            <?php if (!empty($tour['start_date'])): ?>
                            <i class="far fa-calendar-alt me-1"></i><?= date('M d, Y', strtotime($tour['start_date'])) ?>
                            <?php if (!empty($tour['duration'])): ?>
                            <i class="fas fa-circle mx-1" style="font-size: 5px; vertical-align: middle;"></i>
                            <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php if (!empty($tour['duration'])): ?>
                            <i class="far fa-clock me-1"></i><?= htmlspecialchars($tour['duration']) ?>
                            <?php endif; ?>
                        </p>
                        
                        <p class="card-text"><?= \App\Helpers\truncate(htmlspecialchars($tour['description']), 100) ?></p>
                        <a href="<?= \App\Helpers\url('tours/view/' . $tour['slug']) ?>" class="btn btn-outline-primary"><?= $translator->trans('View Details') ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- All Tours Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title"><?= $translator->trans('Explore Our Popular Tours') ?></h2>
        
        <div class="row g-4">
            <?php if (empty($recentTours)): ?>
            <div class="col-12 text-center">
                <p><?= $translator->trans('No tours found') ?></p>
            </div>
            <?php else: ?>
            <?php foreach ($recentTours as $tour): ?>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card tour-card">
                    <?php if (!empty($tour['image_url'])): ?>
                    <img src="<?= \App\Helpers\asset('uploads/' . $tour['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($tour['title']) ?>">
                    <?php else: ?>
                    <img src="<?= \App\Helpers\asset('images/placeholder.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($tour['title']) ?>">
                    <?php endif; ?>
                    
                    <?php if ($tour['price']): ?>
                    <div class="tour-price">
                        <?= \App\Helpers\formatPrice($tour['price']) ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <span class="category-badge me-1"><?= htmlspecialchars($tour['category_name']) ?></span>
                            <?php if (!empty($tour['location'])): ?>
                            <span class="badge bg-info text-dark"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($tour['location']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h5 class="card-title"><?= htmlspecialchars($tour['title']) ?></h5>
                        
                        <p class="tour-duration mb-2">
                            <?php if (!empty($tour['start_date'])): ?>
                            <i class="far fa-calendar-alt me-1"></i><?= date('M d, Y', strtotime($tour['start_date'])) ?>
                            <?php if (!empty($tour['duration'])): ?>
                            <i class="fas fa-circle mx-1" style="font-size: 5px; vertical-align: middle;"></i>
                            <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php if (!empty($tour['duration'])): ?>
                            <i class="far fa-clock me-1"></i><?= htmlspecialchars($tour['duration']) ?>
                            <?php endif; ?>
                        </p>
                        
                        <p class="card-text"><?= \App\Helpers\truncate(htmlspecialchars($tour['description']), 80) ?></p>
                        <a href="<?= \App\Helpers\url('tours/view/' . $tour['slug']) ?>" class="btn btn-sm btn-outline-primary"><?= $translator->trans('View Details') ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?= \App\Helpers\url('tours') ?>" class="btn btn-primary"><?= $translator->trans('View All Tours') ?></a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title"><?= $translator->trans('Why Choose Us') ?></h2>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-crown fa-3x text-primary"></i>
                    </div>
                    <h4><?= $translator->trans('Exclusive Experiences') ?></h4>
                    <p><?= $translator->trans('We offer exclusive access to destinations and experiences not available elsewhere.') ?></p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-user-tie fa-3x text-primary"></i>
                    </div>
                    <h4><?= $translator->trans('Expert Guides') ?></h4>
                    <p><?= $translator->trans('Our knowledgeable local guides ensure authentic cultural immersion.') ?></p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-hand-holding-heart fa-3x text-primary"></i>
                    </div>
                    <h4><?= $translator->trans('Personalized Service') ?></h4>
                    <p><?= $translator->trans('We tailor each journey to your preferences and interests.') ?></p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="feature-box text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-leaf fa-3x text-primary"></i>
                    </div>
                    <h4><?= $translator->trans('Sustainable Travel') ?></h4>
                    <p><?= $translator->trans('We\'re committed to environmentally responsible and sustainable tourism.') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call-to-Action Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4"><?= $translator->trans('Find Your Perfect Tour') ?></h2>
                <p class="lead mb-4"><?= $translator->trans('Contact us today to plan your dream vacation with Coco Travel. Our expert team is ready to help you create unforgettable memories.') ?></p>
                <a href="<?= \App\Helpers\url('contact') ?>" class="btn btn-lg btn-primary"><?= $translator->trans('Contact Us') ?></a>
            </div>
        </div>
    </div>
</section>