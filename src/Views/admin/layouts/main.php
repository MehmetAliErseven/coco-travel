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
        <div class="sidebar-header" id="sidebarHeader">
            <div class="sidebar-brand">
                <i class="fas fa-plane-departure me-2"></i>
                <span>Coco Travel</span>
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
                    <div class="avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header">Admin User</h6></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i> Profile</a></li>
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
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarHeader = document.getElementById('sidebarHeader');
            const isMobile = window.innerWidth <= 768;
            
            // Toggle sidebar function
            function toggleSidebar() {
                sidebar.classList.toggle('sidebar-collapsed');
                mainContent.classList.toggle('main-content-extended');
                
                // Store sidebar state in localStorage (only for desktop)
                if (!isMobile) {
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('sidebar-collapsed'));
                }
            }
            
            // Initialize sidebar state from localStorage (only for desktop)
            if (!isMobile) {
                const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (sidebarCollapsed) {
                    sidebar.classList.add('sidebar-collapsed');
                    mainContent.classList.add('main-content-extended');
                }
            }
            
            // Add click event listener to header
            sidebarHeader.addEventListener('click', toggleSidebar);
            
            // Handle window resize
            window.addEventListener('resize', function() {
                const newIsMobile = window.innerWidth <= 768;
                
                if (newIsMobile !== isMobile) {
                    // Remove all classes and reset to default state for the new view
                    sidebar.classList.remove('sidebar-collapsed');
                    mainContent.classList.remove('main-content-extended');
                    
                    if (newIsMobile) {
                        localStorage.removeItem('sidebarCollapsed');
                    } else {
                        // Restore desktop state from localStorage
                        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                        if (sidebarCollapsed) {
                            sidebar.classList.add('sidebar-collapsed');
                            mainContent.classList.add('main-content-extended');
                        }
                    }
                }
            });
            
            // Close sidebar when clicking outside on mobile
            if (isMobile) {
                document.addEventListener('click', function(event) {
                    const isClickInside = sidebar.contains(event.target);
                    if (!isClickInside && sidebar.classList.contains('sidebar-collapsed')) {
                        toggleSidebar();
                    }
                });
            }
        });
    </script>
    
    <!-- Admin JS -->
    <script type="module" src="<?= \App\Helpers\asset('js/admin/admin.js') ?>"></script>
</body>
</html>