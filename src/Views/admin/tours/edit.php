<?php
    $this->layout('admin/layouts/main');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Tour</h1>
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

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form id="tourForm" action="<?= \App\Helpers\url('admin/tours/update/' . $tour['id']) ?>" method="POST" enctype="multipart/form-data" data-mode="edit">
            <input type="hidden" name="csrf_token" value="<?= \App\Helpers\generateCsrfToken() ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h5>Basic Information</h5>
                        <hr>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($tour['title']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="slug" name="slug" value="<?= htmlspecialchars($tour['slug']) ?>" required>
                            <div class="form-text">URL-friendly version of the title (e.g., "beach-vacation")</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="6" required><?= htmlspecialchars($tour['description']) ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Pricing and Duration -->
                    <div class="mb-4">
                        <h5>Pricing and Duration</h5>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price ($)</label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($tour['price'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration</label>
                                    <input type="text" class="form-control" id="duration" name="duration" placeholder="e.g., 3 days / 2 nights" value="<?= htmlspecialchars($tour['duration'] ?? '') ?>">
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
                            <textarea class="form-control" id="itinerary" name="itinerary" rows="6"><?= htmlspecialchars($tour['itinerary'] ?? '') ?></textarea>
                            <div class="form-text">Day-by-day breakdown of the tour activities</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="includes" class="form-label">What's Included</label>
                            <textarea class="form-control" id="includes" name="includes" rows="4"><?= htmlspecialchars($tour['includes'] ?? '') ?></textarea>
                            <div class="form-text">List of amenities, services, or items included in the package</div>
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
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= ($tour['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= $tour['is_active'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                <div class="form-text">Show this tour on the website</div>
                            </div>
                            
                            <div class="mb-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?= $tour['is_featured'] ? 'checked' : '' ?>>
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
                            <?php if (!empty($tour['image_url'])): ?>
                                <div class="current-image text-center p-2 mb-3 border rounded">
                                    <p class="mb-2"><small>Current image:</small></p>
                                    <img src="<?= \App\Helpers\asset('uploads/' . $tour['image_url']) ?>" 
                                         alt="<?= htmlspecialchars($tour['title']) ?>" 
                                         class="img-fluid">
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="tour_image" class="form-label">Upload New Image</label>
                                <input class="form-control" type="file" id="tour_image" name="tour_image" accept="image/*">
                                <div class="form-text">Leave empty to keep current image</div>
                            </div>
                            
                            <div class="image-preview text-center p-2 border rounded d-none" id="imagePreviewContainer">
                                <img id="imagePreview" src="#" alt="Image Preview" class="img-fluid">
                            </div>
                            
                            <?php if (!empty($tour['image_url'])): ?>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                    <label class="form-check-label text-danger" for="remove_image">
                                        Remove current image
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Tour
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
    let slugModified = false;
    
    slugInput.addEventListener('input', function() {
        slugModified = true;
    });
    
    titleInput.addEventListener('input', function() {
        if (!slugModified) {
            slugInput.value = titleInput.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
        }
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