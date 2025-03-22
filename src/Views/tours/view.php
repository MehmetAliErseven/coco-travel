<?php
// Set page title
$pageTitle = htmlspecialchars($tour['title']);

// Add page-specific JavaScript that uses the module
$extraJs = '<script type="module">
    import TourDetail from "' . \App\Helpers\asset('js/modules/tourDetail.js') . '";
    document.addEventListener("DOMContentLoaded", () => new TourDetail());
</script>';
?>

<!-- Tour Header Section -->
<div class="bg-light py-3">
    <div class="container">
        <h1><?= htmlspecialchars($tour['title']) ?></h1>
        <div class="d-flex flex-wrap align-items-center gap-3 mt-2">
            <span class="badge bg-black me-2"><?= htmlspecialchars($tour['category_name']) ?></span>

            <?php if (!empty($tour['start_date'])): ?>
            <div class="d-flex align-items-center me-2">
                <i class="far fa-calendar-alt me-2"></i>
                <span><?= date('M d, Y', strtotime($tour['start_date'])) ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($tour['duration'])): ?>
            <div class="d-flex align-items-center me-2">
                <i class="far fa-clock me-2"></i>
                <span><?= htmlspecialchars($tour['duration']) ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($tour['location'])): ?>
            <div class="d-flex align-items-center me-2">
                <i class="fas fa-map-marker-alt me-2"></i>
                <span><?= htmlspecialchars($tour['location']) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($tour['price']): ?>
            <div class="fs-5 text-primary fw-bold">
                <?= \App\Helpers\formatPrice($tour['price']) ?> <span class="text-black fw-light"> (<?= $translator->trans('per person') ?>)</span>
            </div>
            <?php else: ?>
            <div class="fs-5 text-primary fw-bold">
                <?= $translator->trans('Price on request') ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Tour Content Section -->
<section class="py-3">
    <div class="container">
        <div class="row g-5">
            <!-- Tour Main Content -->
            <div class="col-lg-8">
                <!-- Tour Image -->
                <div class="tour-image-container mb-4">
                    <?php if (!empty($tour['image_url'])): ?>
                    <img src="<?= \App\Helpers\asset('uploads/' . $tour['image_url']) ?>" 
                         class="img-fluid rounded tour-main-image"
                         alt="<?= htmlspecialchars($tour['title']) ?>">
                    <?php else: ?>
                    <img src="<?= \App\Helpers\asset('images/placeholder.jpg') ?>" 
                         class="img-fluid rounded tour-main-image"
                         alt="<?= htmlspecialchars($tour['title']) ?>">
                    <?php endif; ?>
                </div>

                <!-- Tour Description -->
                <div class="tour-description mb-5">
                    <h3 class="mb-3"><?= $translator->trans('Tour Description') ?></h3>
                    <div class="tour-description-content">
                        <?= nl2br(htmlspecialchars($tour['description'])) ?>
                    </div>
                </div>
                
                <!-- Tour Itinerary -->
                <?php if (!empty($tour['itinerary'])): ?>
                <div class="tour-itinerary mb-5">
                    <h3 class="mb-3"><?= $translator->trans('Itinerary') ?></h3>
                    <div class="tour-itinerary-content">
                        <?= nl2br(htmlspecialchars($tour['itinerary'])) ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- What's Included -->
                <?php if (!empty($tour['includes'])): ?>
                <div class="tour-includes mb-5">
                    <h3 class="mb-3"><?= $translator->trans('What\'s Included') ?></h3>
                    <div class="tour-includes-content">
                        <?php 
                        $includes = explode("\n", $tour['includes']);
                        if (count($includes) > 1): 
                        ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($includes as $include): ?>
                                <?php if (trim($include)): ?>
                                <li class="list-group-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <?= htmlspecialchars(trim($include)) ?>
                                </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                            <?= nl2br(htmlspecialchars($tour['includes'])) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Book Now Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title"><?= $translator->trans('Interested in this tour?') ?></h4>
                        <p class="card-text"><?= $translator->trans('Contact us to book this tour or request more information.') ?></p>
                        <a href="<?= \App\Helpers\url('contact?tour=' . urlencode($tour['title'])) ?>" class="btn btn-primary w-100">
                            <i class="fas fa-envelope me-2"></i> <?= $translator->trans('Contact Us') ?>
                        </a>
                    </div>
                </div>
                
                <!-- Share Tour -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title"><?= $translator->trans('Share This Tour') ?></h4>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(\App\Helpers\url('tours/view/' . $tour['slug'])) ?>" 
                               class="btn btn-outline-primary social-share-btn" title="<?= $translator->trans('Share on Facebook') ?>">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(\App\Helpers\url('tours/view/' . $tour['slug'])) ?>&text=<?= urlencode($translator->trans('Check out this amazing tour: ') . $tour['title']) ?>" 
                               class="btn btn-outline-info social-share-btn" title="<?= $translator->trans('Share on Twitter') ?>">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?= urlencode($translator->trans('Check out this amazing tour: ') . $tour['title'] . ' - ' . \App\Helpers\url('tours/view/' . $tour['slug'])) ?>" 
                               class="btn btn-outline-success social-share-btn" title="<?= $translator->trans('Share on WhatsApp') ?>">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="mailto:?subject=<?= urlencode($translator->trans('Check out this amazing tour: ') . $tour['title']) ?>&body=<?= urlencode($translator->trans('I thought you might be interested in this tour: ') . \App\Helpers\url('tours/view/' . $tour['slug'])) ?>" 
                               class="btn btn-outline-secondary" title="<?= $translator->trans('Share via Email') ?>">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Tours Section -->
<?php if (!empty($relatedTours)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title"><?= $translator->trans('Related Tours') ?></h2>
        
        <div class="row g-4">
            <?php foreach ($relatedTours as $relatedTour): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card tour-card h-100">
                    <?php if (!empty($relatedTour['image_url'])): ?>
                    <img src="<?= \App\Helpers\asset('uploads/' . $relatedTour['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($relatedTour['title']) ?>">
                    <?php else: ?>
                    <img src="<?= \App\Helpers\asset('images/placeholder.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($relatedTour['title']) ?>">
                    <?php endif; ?>
                    
                    <?php if ($relatedTour['price']): ?>
                    <div class="tour-price">
                        <?= \App\Helpers\formatPrice($relatedTour['price']) ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <span class="category-badge mb-2"><?= htmlspecialchars($relatedTour['category_name']) ?></span>
                        <h5 class="card-title"><?= htmlspecialchars($relatedTour['title']) ?></h5>
                        
                        <?php if ($relatedTour['duration']): ?>
                        <p class="tour-duration mb-2">
                            <i class="far fa-clock me-1"></i> <?= htmlspecialchars($relatedTour['duration']) ?>
                        </p>
                        <?php endif; ?>
                        
                        <p class="card-text"><?= \App\Helpers\truncate(htmlspecialchars($relatedTour['description']), 100) ?></p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="<?= \App\Helpers\url('tours/view/' . $relatedTour['slug']) ?>" class="btn btn-outline-primary w-100">
                            <?= $translator->trans('View Details') ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>