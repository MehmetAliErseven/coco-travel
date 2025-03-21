<?php
    $this->layout('admin/layouts/main');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Messages</h1>
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
        <?php if (empty($messages)): ?>
            <div class="p-4 text-center">
                <p class="text-muted mb-0">No messages found</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr>
                                <td>
                                    <?php if ($message['is_read'] == 0): ?>
                                        <span class="badge bg-danger">New</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Read</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($message['name']) ?></td>
                                <td><?= htmlspecialchars($message['email']) ?></td>
                                <td><?= htmlspecialchars($message['subject']) ?></td>
                                <td><?= date('M d, Y', strtotime($message['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= \App\Helpers\url('admin/messages/view/' . $message['id']) ?>" 
                                           class="btn btn-outline-primary"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteMessageModal<?= $message['id'] ?>"
                                                title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteMessageModal<?= $message['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Message</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this message?</p>
                                                    <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="<?= \App\Helpers\url('admin/messages/delete/' . $message['id']) ?>" class="btn btn-danger">Delete</a>
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
                                    <a class="page-link" href="<?= \App\Helpers\url('admin/messages?page=' . ($currentPage - 1)) ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= \App\Helpers\url('admin/messages?page=' . $i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= \App\Helpers\url('admin/messages?page=' . ($currentPage + 1)) ?>" aria-label="Next">
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