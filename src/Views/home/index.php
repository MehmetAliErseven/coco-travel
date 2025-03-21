<!-- Hero Section with Search -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title"><?= $translator->trans('Journey Beyond Dreams') ?></h1>
        <p class="hero-subtitle"><?= $translator->trans('Make your dream vacation come true with Coco Travel') ?></p>
        
        <div class="search-box">
            <form id="tourSearchForm" action="<?= \App\Helpers\url('home/search') ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="searchInput" name="query" value="">
                            <label for="searchInput"><?= $translator->trans('Where do you want to go?') ?></label>
                        </div>
                        <div id="live-search-results" class="position-absolute bg-white shadow rounded w-100 mt-1 z-3" style="display: none; max-height: 300px; overflow-y: auto;"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" id="category-filter" name="category_id">
                                <option value="0" selected><?= $translator->trans('All Categories') ?></option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="category-filter"><?= $translator->trans('Category') ?></label>
                        </div>
                    </div>
                </div>
            </form>
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
                        <span class="category-badge mb-2"><?= htmlspecialchars($tour['category_name']) ?></span>
                        <h5 class="card-title"><?= htmlspecialchars($tour['title']) ?></h5>
                        
                        <?php if ($tour['duration']): ?>
                        <p class="tour-duration mb-2">
                            <i class="far fa-clock me-1"></i> <?= htmlspecialchars($tour['duration']) ?>
                        </p>
                        <?php endif; ?>
                        
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
                        <span class="category-badge mb-2"><?= htmlspecialchars($tour['category_name']) ?></span>
                        <h5 class="card-title"><?= htmlspecialchars($tour['title']) ?></h5>
                        
                        <?php if ($tour['duration']): ?>
                        <p class="tour-duration mb-2">
                            <i class="far fa-clock me-1"></i> <?= htmlspecialchars($tour['duration']) ?>
                        </p>
                        <?php endif; ?>
                        
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