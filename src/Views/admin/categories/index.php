<?php
    $this->layout('admin/layouts/main');
?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Categories</h5>
        <a href="<?= \App\Helpers\url('admin/categories/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New Category
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($categories)): ?>
            <div class="p-4 text-center text-muted">
                No categories found. <a href="<?= \App\Helpers\url('admin/categories/create') ?>">Create your first category</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">Name</th>
                            <th width="35%">Description</th>
                            <th width="10%">Tours</th>
                            <th width="10%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $index => $category): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <h6 class="mb-0"><?= htmlspecialchars($category['name']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($category['slug']) ?></small>
                                </td>
                                <td class="text-truncate" style="max-width: 300px;">
                                    <?= !empty($category['description']) ? htmlspecialchars($category['description']) : '<span class="text-muted">No description</span>' ?>
                                </td>
                                <td class="text-center"><?= $category['tour_count'] ?></td>
                                <td>
                                    <?php if ($category['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= \App\Helpers\url('admin/categories/edit/' . $category['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($category['tour_count'] == 0): ?>
                                            <a href="#" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-id="<?= $category['id'] ?>"
                                                data-name="<?= htmlspecialchars($category['name']) ?>"
                                                title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-danger" disabled title="Cannot delete - has tours">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the category "<span id="categoryName"></span>"? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete confirmation modal
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                // Button that triggered the modal
                const button = event.relatedTarget;
                
                // Extract info from data attributes
                const categoryId = button.getAttribute('data-id');
                const categoryName = button.getAttribute('data-name');
                
                // Update the modal content
                document.getElementById('categoryName').textContent = categoryName;
                document.getElementById('confirmDelete').href = '<?= \App\Helpers\url('admin/categories/delete/') ?>' + categoryId;
            });
        }
    });
</script>