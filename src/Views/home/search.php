<!-- Search Results Header -->
<div class="bg-light py-5">
    <div class="container">
        <h1 class="mb-3"><?= $translator->trans('Search Results') ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= \App\Helpers\url() ?>"><?= $translator->trans('Home') ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $translator->trans('Search Results') ?></li>
            </ol>
        </nav>
        
        <!-- Search Form -->
        <div class="card mt-4">
            <div class="card-body">
                <form id="tourSearchForm" action="<?= \App\Helpers\url('home/search') ?>" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="searchInput" name="query" 
                                   value="<?= htmlspecialchars($query) ?>">
                            <label for="searchInput"><?= $translator->trans('Search Tours') ?></label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <select class="form-select" id="category-filter" name="category_id">
                                <option value="0"><?= $translator->trans('All Categories') ?></option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= $categoryId == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="category-filter"><?= $translator->trans('Category') ?></label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary h-100 w-100">
                            <i class="fas fa-search me-2"></i> <?= $translator->trans('Search') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Search Results -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><?= $translator->trans('Found') ?> <?= count($searchResults) ?> <?= count($searchResults) !== 1 ? $translator->trans('Results') : $translator->trans('Result') ?></h2>
        </div>

        <?php if (empty($searchResults)): ?>
            <div class="alert alert-info">
                <p class="mb-0"><?= $translator->trans('No tours found matching your search') ?> "<?= htmlspecialchars($query) ?>". <?= $translator->trans('Please try different keywords or browse our') ?> <a href="<?= \App\Helpers\url('tours') ?>"><?= $translator->trans('tour catalog') ?></a>.</p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($searchResults as $tour): ?>
                <div class="col-md-6 col-lg-4">
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
                            
                            <p class="card-text"><?= \App\Helpers\truncate(htmlspecialchars($tour['description']), 120) ?></p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="<?= \App\Helpers\url('tours/view/' . $tour['slug']) ?>" class="btn btn-outline-primary w-100"><?= $translator->trans('View Details') ?></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>