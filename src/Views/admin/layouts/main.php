<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Panel' ?> - <?= $_ENV['APP_NAME'] ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= \App\Helpers\asset('css/admin-style.css') ?>">
</head>
<body class="admin-layout">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <i class="fas fa-plane-departure me-2"></i>
                <span>Coco Travel</span>
            </div>
            <div class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
                <i class="fas fa-times" style="display: none;"></i>
            </div>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="<?= \App\Helpers\url('admin') ?>" class="<?= $_GET['url'] === 'admin' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="sidebar-menu-text">Dashboard</span>
                </a>
            </li>
            
            <li>
                <a href="<?= \App\Helpers\url('admin/tours') ?>" class="<?= strpos($_GET['url'] ?? '', 'admin/tours') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-map-marked-alt"></i>
                    <span class="sidebar-menu-text">Tours</span>
                </a>
            </li>
            
            <li>
                <a href="<?= \App\Helpers\url('admin/categories') ?>" class="<?= strpos($_GET['url'] ?? '', 'admin/categories') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span class="sidebar-menu-text">Categories</span>
                </a>
            </li>
            
            <li>
                <a href="<?= \App\Helpers\url('admin/messages') ?>" class="<?= strpos($_GET['url'] ?? '', 'admin/messages') === 0 ? 'active' : '' ?>">
                    <i class="fas fa-envelope"></i>
                    <span class="sidebar-menu-text">Messages</span>
                </a>
            </li>
            
            <div class="sidebar-divider"></div>
            
            <li>
                <a href="<?= \App\Helpers\url() ?>" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span class="sidebar-menu-text">View Site</span>
                </a>
            </li>
            
            <li>
                <a href="<?= \App\Helpers\url('admin/logout') ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-menu-text">Logout</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <div class="content-header">
            <h4 class="mb-0"><?= $pageTitle ?? 'Admin Panel' ?></h4>
            
            <div class="dropdown">
                <div class="d-flex align-items-center" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="me-2 d-none d-sm-inline"><?= $_SESSION['user']['username'] ?? 'Admin User' ?></span>
                    <div class="avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header">Admin User</h6></li>
                    <li><a class="dropdown-item" href="<?= \App\Helpers\url('admin/profile') ?>"><i class="fas fa-user-cog me-2"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= \App\Helpers\url('admin/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Alert messages -->
        <?php if (isset($_SESSION['admin_message'])): ?>
        <div class="alert alert-<?= $_SESSION['admin_message']['type'] ?> alert-dismissible fade show m-3" role="alert">
            <?= $_SESSION['admin_message']['text'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
            // Clear the message after displaying
            unset($_SESSION['admin_message']);
        endif; 
        ?>
        
        <!-- Main content body -->
        <div class="content-body">
            <?= $content ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const toggleIcons = sidebarToggle.querySelectorAll('i');
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                mainContent.classList.toggle('main-content-extended');
                toggleIcons.forEach(icon => {
                    icon.style.display = icon.style.display === 'none' ? 'inline-block' : 'none';
                });
            });
        });
    </script>
    
    <!-- Admin JS -->
    <script type="module" src="<?= \App\Helpers\asset('js/admin/admin.js') ?>"></script>
</body>
</html>