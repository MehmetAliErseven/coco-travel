<?php
    $this->layout('admin/layouts/main');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Tours</h1>
    <a href="<?= \App\Helpers\url('admin/tours/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Tour
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
    <div class="card-body p-0">
        <?php if (empty($tours)): ?>
            <div class="p-4 text-center">
                <p class="text-muted mb-0"><?= $translator->trans('No tours found') ?></p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tours as $tour): ?>
                            <tr>
                                <td><?= $tour['id'] ?></td>
                                <td>
                                    <?php if (!empty($tour['image_url'])): ?>
                                        <img src="<?= \App\Helpers\asset('uploads/' . $tour['image_url']) ?>" 
                                             alt="<?= htmlspecialchars($tour['title']) ?>" 
                                             class="img-thumbnail" width="60">
                                    <?php else: ?>
                                        <div class="bg-light text-center p-2" style="width:60px">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($tour['title']) ?>
                                </td>
                                <td><?= htmlspecialchars($tour['category_name'] ?? 'Uncategorized') ?></td>
                                <td><?= !empty($tour['price']) ? '$' . number_format($tour['price'], 2) : 'N/A' ?></td>
                                <td>
                                    <?php if ($tour['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($tour['is_featured']): ?>
                                        <span class="badge bg-warning">Featured</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= \App\Helpers\url('tours/' . $tour['slug']) ?>" 
                                           target="_blank"
                                           class="btn btn-outline-primary" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= \App\Helpers\url('admin/tours/edit/' . $tour['id']) ?>" 
                                           class="btn btn-outline-secondary"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteTourModal<?= $tour['id'] ?>"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteTourModal<?= $tour['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Tour</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this tour?</p>
                                                    <p class="fw-bold"><?= htmlspecialchars($tour['title']) ?></p>
                                                    <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="<?= \App\Helpers\url('admin/tours/delete/' . $tour['id']) ?>" class="btn btn-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="d-flex justify-content-center p-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= \App\Helpers\url('admin/tours?page=' . ($currentPage - 1)) ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= \App\Helpers\url('admin/tours?page=' . $i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= \App\Helpers\url('admin/tours?page=' . ($currentPage + 1)) ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>