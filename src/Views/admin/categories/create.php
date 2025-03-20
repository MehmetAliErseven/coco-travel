<?php
    $this->layout('admin/layouts/main');
?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create New Category</h5>
        <a href="<?= \App\Helpers\url('admin/categories') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Categories
        </a>
    </div>
    <div class="card-body">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger">
                <?= $errors['general'] ?>
            </div>
        <?php endif; ?>
        
        <form id="categoryForm" action="<?= \App\Helpers\url('admin/categories/store') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \App\Helpers\generateCsrfToken() ?>">
            
            <div class="mb-3">
                <label for="name" class="form-label">Category Name *</label>
                <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                       id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['name'] ?>
                    </div>
                <?php else: ?>
                    <div class="form-text">
                        The name of the category as it will appear on the site.
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" 
                          rows="4"><?= htmlspecialchars($category['description']) ?></textarea>
                <div class="form-text">
                    A brief description of what this category represents (optional).
                </div>
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                           <?= $category['is_active'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_active">
                        Active
                    </label>
                </div>
                <div class="form-text">
                    Inactive categories won't be displayed on the website.
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-light me-2">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Create Category
                </button>
            </div>
        </form>
    </div>
</div>