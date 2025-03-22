<?php
    $this->layout('admin/layouts/main');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Add New Tour</h1>
    <a href="<?= \App\Helpers\url('admin/tours') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Tours
    </a>
</div>

<?php if (isset($_SESSION['admin_message'])): ?>
    <div class="alert alert-<?= $_SESSION['admin_message']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['admin_message']['text'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['admin_message']); ?>
<?php endif; ?>

<?php if (isset($errors['general'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $errors['general'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form id="tourForm" action="<?= \App\Helpers\url('admin/tours/store') ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?= \App\Helpers\generateCsrfToken() ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h5>Basic Information</h5>
                        <hr>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" 
                                   id="title" 
                                   name="title" 
                                   value="<?= htmlspecialchars($tour['title'] ?? '') ?>"
                                   required>
                            <?php if (isset($errors['title'])): ?>
                                <div class="invalid-feedback"><?= $errors['title'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?= isset($errors['slug']) ? 'is-invalid' : '' ?>" 
                                   id="slug" 
                                   name="slug" 
                                   value="<?= htmlspecialchars($tour['slug'] ?? '') ?>"
                                   required>
                            <?php if (isset($errors['slug'])): ?>
                                <div class="invalid-feedback"><?= $errors['slug'] ?></div>
                            <?php else: ?>
                                <div class="form-text">URL-friendly version of the title (e.g., "beach-vacation")</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                                      id="description" 
                                      name="description" 
                                      rows="6" 
                                      required><?= htmlspecialchars($tour['description'] ?? '') ?></textarea>
                            <?php if (isset($errors['description'])): ?>
                                <div class="invalid-feedback"><?= $errors['description'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Pricing and Duration -->
                    <div class="mb-4">
                        <h5>Pricing and Duration</h5>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                                           id="price" 
                                           name="price" 
                                           step="0.01" 
                                           min="0"
                                           value="<?= htmlspecialchars($tour['price'] ?? '') ?>"
                                           required>
                                    <?php if (isset($errors['price'])): ?>
                                        <div class="invalid-feedback"><?= $errors['price'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?= isset($errors['duration']) ? 'is-invalid' : '' ?>" 
                                           id="duration" 
                                           name="duration" 
                                           value="<?= htmlspecialchars($tour['duration'] ?? '') ?>"
                                           placeholder="e.g., 3 days / 2 nights" 
                                           required>
                                    <?php if (isset($errors['duration'])): ?>
                                        <div class="invalid-feedback"><?= $errors['duration'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" 
                                           class="form-control <?= isset($errors['start_date']) ? 'is-invalid' : '' ?>" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="<?= htmlspecialchars($tour['start_date'] ?? '') ?>">
                                    <?php if (isset($errors['start_date'])): ?>
                                        <div class="invalid-feedback"><?= $errors['start_date'] ?></div>
                                    <?php else: ?>
                                        <div class="form-text">First date of the tour</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" 
                                           class="form-control <?= isset($errors['location']) ? 'is-invalid' : '' ?>" 
                                           id="location" 
                                           name="location" 
                                           value="<?= htmlspecialchars($tour['location'] ?? '') ?>"
                                           placeholder="e.g., Phuket, Thailand">
                                    <?php if (isset($errors['location'])): ?>
                                        <div class="invalid-feedback"><?= $errors['location'] ?></div>
                                    <?php else: ?>
                                        <div class="form-text">Main location or destination of the tour</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Itinerary and Includes -->
                    <div class="mb-4">
                        <h5>Details</h5>
                        <hr>
                        
                        <div class="mb-3">
                            <label for="itinerary" class="form-label">Itinerary</label>
                            <textarea class="form-control <?= isset($errors['itinerary']) ? 'is-invalid' : '' ?>" 
                                      id="itinerary" 
                                      name="itinerary" 
                                      rows="6"><?= htmlspecialchars($tour['itinerary'] ?? '') ?></textarea>
                            <?php if (isset($errors['itinerary'])): ?>
                                <div class="invalid-feedback"><?= $errors['itinerary'] ?></div>
                            <?php else: ?>
                                <div class="form-text">Day-by-day breakdown of the tour activities</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="includes" class="form-label">What's Included</label>
                            <textarea class="form-control <?= isset($errors['includes']) ? 'is-invalid' : '' ?>" 
                                      id="includes" 
                                      name="includes" 
                                      rows="4"><?= htmlspecialchars($tour['includes'] ?? '') ?></textarea>
                            <?php if (isset($errors['includes'])): ?>
                                <div class="invalid-feedback"><?= $errors['includes'] ?></div>
                            <?php else: ?>
                                <div class="form-text">List of amenities, services, or items included in the package</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Sidebar Settings -->
                    <div class="card border mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($errors['category_id']) ? 'is-invalid' : '' ?>" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">-- Select Category --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= ($tour['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['category_id'])): ?>
                                <div class="invalid-feedback"><?= $errors['category_id'] ?></div>
                            <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                <div class="form-text">Show this tour on the website</div>
                            </div>
                            
                            <div class="mb-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                    <label class="form-check-label" for="is_featured">Featured</label>
                                </div>
                                <div class="form-text">Show this tour in featured sections</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image Upload -->
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Featured Image</h5>
                        </div>
                        <div class="card-body">
                            <div id="dropZone" class="drop-zone">
                                <div class="drop-zone-text">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                    <p>Drag & drop image here or click to select</p>
                                    <small class="text-muted">Allowed formats: JPG, PNG, WebP (max 5MB)</small>
                                    <input type="file" name="tour_image" id="tour_image" class="drop-zone-input" accept="image/jpeg,image/png,image/webp">
                                </div>
                            </div>
                            
                            <div class="image-preview text-center p-2 border rounded mt-3 d-none" id="imagePreviewContainer">
                                <img id="imagePreview" src="#" alt="Image Preview" class="img-fluid">
                            </div>

                            <?php if (isset($errors['image'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['image'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <button type="reset" class="btn btn-light me-2">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Create Tour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        slugInput.value = titleInput.value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    });
    
    // Image preview
    const imageInput = document.getElementById('tour_image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    
    imageInput.addEventListener('change', function() {
        if (imageInput.files && imageInput.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.setAttribute('src', e.target.result);
                imagePreviewContainer.classList.remove('d-none');
            };
            
            reader.readAsDataURL(imageInput.files[0]);
        }
    });
});
</script>