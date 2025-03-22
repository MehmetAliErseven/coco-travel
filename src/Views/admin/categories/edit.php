<?php
    $this->layout('admin/layouts/main');
?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Category</h5>
        <a href="<?= \App\Helpers\url('admin/categories') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Categories
        </a>
    </div>
    <div class="card-body">
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
        
        <form id="categoryForm" action="<?= \App\Helpers\url('admin/categories/update/' . $category['id']) ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \App\Helpers\generateCsrfToken() ?>">
            
            <div class="mb-3">
                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                       id="name" 
                       name="name" 
                       value="<?= htmlspecialchars($category['name'] ?? '') ?>" 
                       required>
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
                <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                          id="description" 
                          name="description" 
                          rows="4"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                <?php if (isset($errors['description'])): ?>
                    <div class="invalid-feedback">
                        <?= $errors['description'] ?>
                    </div>
                <?php else: ?>
                    <div class="form-text">
                        A brief description of what this category represents (optional).
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           <?= ($category['is_active'] ?? true) ? 'checked' : '' ?>>
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
                    <i class="fas fa-save me-2"></i> Update Category
                </button>
            </div>
        </form>
    </div>
</div>