<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - <?= $_ENV['APP_NAME'] ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= \App\Helpers\asset('css/style.css') ?>">
    
    <style>
        body {
            padding-top: 0;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .error-page {
            text-align: center;
            padding: 3rem 0;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: #0d6efd;
            line-height: 1;
        }
        
        .error-image {
            max-width: 100%;
            height: auto;
            margin: 2rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-page">
            <div class="error-code">404</div>
            <h1 class="display-4">Page Not Found</h1>
            <p class="lead mb-4">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
            
            <img src="<?= \App\Helpers\asset('images/error-404.svg') ?>" alt="404 Error" class="error-image" width="300">
            
            <div class="mt-4">
                <a href="<?= \App\Helpers\url() ?>" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-home me-2"></i> Go to Homepage
                </a>
                <a href="<?= \App\Helpers\url('contact') ?>" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-envelope me-2"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</body>
</html>