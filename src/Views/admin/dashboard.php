<?php
    $this->layout('admin/layouts/main');
?>

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                    <i class="fas fa-map-marked-alt text-primary fs-3"></i>
                </div>
                <div>
                    <h6 class="card-title mb-0">Total Tours</h6>
                    <h3 class="mt-2 mb-0"><?= $stats['totalTours'] ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                    <i class="fas fa-check-circle text-success fs-3"></i>
                </div>
                <div>
                    <h6 class="card-title mb-0">Active Tours</h6>
                    <h3 class="mt-2 mb-0"><?= $stats['activeTours'] ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                    <i class="fas fa-tags text-info fs-3"></i>
                </div>
                <div>
                    <h6 class="card-title mb-0">Categories</h6>
                    <h3 class="mt-2 mb-0"><?= $stats['totalCategories'] ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                    <i class="fas fa-envelope text-warning fs-3"></i>
                </div>
                <div>
                    <h6 class="card-title mb-0">Messages</h6>
                    <h3 class="mt-2 mb-0"><?= $stats['totalMessages'] ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Messages -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Messages</h5>
                <a href="<?= \App\Helpers\url('admin/messages') ?>" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentMessages)): ?>
                    <div class="p-4 text-center text-muted">
                        No messages found
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentMessages as $message): ?>
                            <a href="<?= \App\Helpers\url('admin/messages/view/' . $message['id']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <?= htmlspecialchars($message['name']) ?>
                                        <?php if ($message['is_read'] == 0): ?>
                                            <span class="badge bg-danger rounded-pill ms-2">New</span>
                                        <?php endif; ?>
                                    </h6>
                                    <small><?= date('M d, Y', strtotime($message['created_at'])) ?></small>
                                </div>
                                <p class="mb-1 text-truncate"><?= htmlspecialchars($message['subject']) ?></p>
                                <small class="text-muted"><?= htmlspecialchars($message['email']) ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="<?= \App\Helpers\url('admin/tours/create') ?>" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-plus-circle text-primary fs-1 mb-3"></i>
                                <h5 class="card-title mb-0">Add Tour</h5>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-6">
                        <a href="<?= \App\Helpers\url('admin/categories/create') ?>" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-folder-plus text-success fs-1 mb-3"></i>
                                <h5 class="card-title mb-0">Add Category</h5>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-6">
                        <a href="<?= \App\Helpers\url('admin/tours') ?>" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-list text-info fs-1 mb-3"></i>
                                <h5 class="card-title mb-0">Manage Tours</h5>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-6">
                        <a href="<?= \App\Helpers\url() ?>" target="_blank" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-eye text-warning fs-1 mb-3"></i>
                                <h5 class="card-title mb-0">View Website</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Info -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">System Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>PHP Version:</th>
                        <td><?= PHP_VERSION ?></td>
                    </tr>
                    <tr>
                        <th>Server:</th>
                        <td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Not available' ?></td>
                    </tr>
                    <tr>
                        <th>Database:</th>
                        <td>MySQL</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>Application Version:</th>
                        <td>1.0.0</td>
                    </tr>
                    <tr>
                        <th>Environment:</th>
                        <td><?= $_ENV['APP_ENV'] ?? 'development' ?></td>
                    </tr>
                    <tr>
                        <th>Current Time:</th>
                        <td><?= date('Y-m-d H:i:s') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>