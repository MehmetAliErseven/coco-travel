<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?= $_ENV['APP_NAME'] ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= \App\Helpers\asset('css/admin-style.css') ?>">
</head>
<body class="login-page">
    <div class="container login-container">
        <div class="login-logo">
            <h2>Coco Travel</h2>
            <p class="text-muted">Admin Panel</p>
        </div>
        
        <div class="card login-card">
            <div class="card-body p-4">
                <h4 class="card-title text-center mb-4">Sign In</h4>
                
                <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
                <?php endif; ?>
                
                <form id="loginForm" action="<?= \App\Helpers\url('admin/authenticate') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \App\Helpers\generateCsrfToken() ?>">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="username" name="username" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary login-btn">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 text-center">
                    <a href="<?= \App\Helpers\url() ?>" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i> Back to Website
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center text-muted small">
            &copy; <?= date('Y') ?> <?= $_ENV['APP_NAME'] ?>. All rights reserved.
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>