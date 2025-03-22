<!-- Tours Listing -->
<section class="py-4">
    <div class="container">
        <!-- Search Form -->
        <form id="tourSearchForm" class="mb-4">
            <div class="input-group input-group-lg">
                <span class="input-group-text border-end-0 bg-white rounded-start-2">
                    <i class="fas fa-search text-primary"></i>
                </span>
                <input type="text" 
                       id="searchInput" 
                       class="form-control border-start-0 rounded-end-2 ps-0 shadow-none" 
                       placeholder="<?= $translator->trans('Search tours') ?>..."
                       aria-label="<?= $translator->trans('Search tours') ?>">
                <input type="hidden" id="category-filter" name="category_id" value="all">
            </div>
        </form>

        <!-- Category Filter & Tour Count -->
        <div class="mb-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2">
            <div id="categoryFilter" class="category-filters d-flex flex-wrap gap-1">
                <a href="#" class="btn btn-outline-primary btn-sm btn-md-normal category-filter active" data-category-id="all">
                    <?= $translator->trans('All Categories') ?>
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="#" class="btn btn-outline-primary btn-sm btn-md-normal category-filter" 
                       data-category-id="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a>
                <?php endforeach; ?>
            </div>
            <div class="text-muted small">
                <span class="fw-medium"><?= $translator->trans('Showing %filtered_count% of %total_count% tours', [
                    '%filtered_count%' => '<span id="filteredCount">' . count($tours) . '</span>',
                    '%total_count%' => $totalCount
                ]) ?></span>
            </div>
        </div>

        <!-- Tours Grid -->
        <?php if (empty($tours)): ?>
            <div class="alert alert-info">
                <p class="mb-0"><?= $translator->trans('No tours found') ?>. <?= $translator->trans('Try different search criteria') ?>.</p>
            </div>
        <?php else: ?>
            <div id="toursContainer" class="row g-4">
                <?php foreach ($tours as $tour): ?>
                <div class="col-md-6 col-lg-4 col-xl-3" data-tour-id="<?= $tour['id'] ?>">
                    <div class="card tour-card h-100">
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
                                <span class="category-badge me-1"><?= htmlspecialchars($tour['category_name'] ?? $translator->trans('Uncategorized')) ?></span>
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
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="<?= \App\Helpers\url('tours/view/' . $tour['slug']) ?>" class="btn btn-outline-primary w-100">
                                <?= $translator->trans('View Details') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav class="mt-5" aria-label="<?= $translator->trans('Tour navigation') ?>">
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= \App\Helpers\url('tours?page=' . ($currentPage - 1)) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php 
                    // Calculate range of pages to show
                    $range = 2;
                    $startPage = max(1, $currentPage - $range);
                    $endPage = min($totalPages, $currentPage + $range);
                    
                    // Always show first page
                    if ($startPage > 1): 
                    ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= \App\Helpers\url('tours?page=1') ?>">1</a>
                    </li>
                    <?php 
                        if ($startPage > 2): 
                            echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                        endif;
                    endif; 
                    
                    // Show page links in range
                    for ($i = $startPage; $i <= $endPage; $i++): 
                    ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="<?= \App\Helpers\url('tours?page=' . $i) ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; 
                    
                    // Always show last page
                    if ($endPage < $totalPages): 
                        if ($endPage < $totalPages - 1): 
                            echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                        endif;
                    ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= \App\Helpers\url('tours?page=' . $totalPages) ?>"><?= $totalPages ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= \App\Helpers\url('tours?page=' . ($currentPage + 1)) ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>