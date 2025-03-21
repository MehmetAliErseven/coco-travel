<?php
    $this->layout('admin/layouts/main');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">View Message</h1>
    <a href="<?= \App\Helpers\url('admin/messages') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Messages
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-4">
                    <h5>Message Details</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">From</label>
                            <div class="fw-medium"><?= htmlspecialchars($message['name'] ?? '') ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email</label>
                            <div class="fw-medium"><?= htmlspecialchars($message['email'] ?? '') ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <div class="fw-medium"><?= !empty($message['phone']) ? htmlspecialchars($message['phone']) : '<span class="text-muted">Not provided</span>' ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Date</label>
                            <div class="fw-medium"><?= date('M d, Y H:i', strtotime($message['created_at'])) ?></div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Subject</h5>
                    <hr>
                    <p class="mb-0"><?= !empty($message['subject']) ? htmlspecialchars($message['subject']) : '<span class="text-muted">No subject</span>' ?></p>
                </div>

                <div>
                    <h5>Message</h5>
                    <hr>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($message['message'] ?? '')) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Quick Actions</h5>
                <a href="mailto:<?= htmlspecialchars($message['email'] ?? '') ?>" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-reply me-2"></i>Reply via Email
                </a>
                <?php if (!empty($message['phone'])): ?>
                <a href="tel:<?= htmlspecialchars($message['phone']) ?>" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-phone me-2"></i>Call
                </a>
                <?php endif; ?>
                <button type="button" 
                        class="btn btn-outline-danger w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteMessageModal">
                    <i class="fas fa-trash-alt me-2"></i>Delete Message
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteMessageModal" tabindex="-1" aria-hidden="true">
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