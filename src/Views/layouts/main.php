<!DOCTYPE html>
<html lang="<?= isset($lang) ? $lang : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) && $pageTitle ? $pageTitle . ' - Coco Travel' : 'Coco Travel - Your Journey Begins Here' ?></title>
    
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="<?= \App\Helpers\asset('images/favicon/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= \App\Helpers\asset('images/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= \App\Helpers\asset('images/favicon/favicon-16x16.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= \App\Helpers\asset('images/favicon/apple-touch-icon.png') ?>">
    <link rel="manifest" href="<?= \App\Config\Config::getAppUrl() ?>/site.webmanifest">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= \App\Helpers\asset('css/style.css') ?>">
    
    <!-- JavaScript için basePath tanımı -->
    <script>
        window.basePath = '<?= \App\Config\Config::getAppUrl(); ?>';
    </script>

    <?php if (isset($extraCss)): ?>
        <?= $extraCss ?>
    <?php endif; ?>
</head>
<body>
    <?php if ($_ENV['APP_ENV'] === 'development'): ?>
        <?php $errors = \App\Helpers\getAndClearErrors(); ?>
        <?php if (!empty($errors)): ?>
            <div class="development-errors" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; max-width: 400px;">
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= \App\Config\Config::getAppUrl() ?>">
                <img src="<?= \App\Helpers\asset('images/coco-travel-logo.jpg') ?>" alt="Coco Travel Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= \App\Config\Config::getAppUrl() ?>"><?= $translator->trans('Home') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= \App\Config\Config::getAppUrl() ?>/tours"><?= $translator->trans('Tours') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= \App\Config\Config::getAppUrl() ?>/contact"><?= $translator->trans('Contact Us') ?></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-globe"></i> <?= $translator->trans('Language') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item language-switcher <?= ($lang == 'en') ? 'active' : '' ?>" href="#" data-lang="en"><?= ($lang == 'en') ? '✓ ' : '' ?>English</a></li>
                            <li><a class="dropdown-item language-switcher <?= ($lang == 'th') ? 'active' : '' ?>" href="#" data-lang="th"><?= ($lang == 'th') ? '✓ ' : '' ?>ไทย (Thai)</a></li>
                            <li><a class="dropdown-item language-switcher <?= ($lang == 'tr') ? 'active' : '' ?>" href="#" data-lang="tr"><?= ($lang == 'tr') ? '✓ ' : '' ?>Türkçe (Turkish)</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-4">
        <div class="container">
            <div class="row justify-content-center text-center text-md-start">
                <div class="col-md-4 mb-4">
                    <div class="mb-4 text-center text-md-start">
                        <img src="<?= \App\Helpers\asset('images/coco-travel-logo.jpg') ?>" alt="Coco Travel Logo" height="40">
                    </div>
                    <p><?= $translator->trans('Your premier destination for extraordinary travel experiences. We specialize in curated adventures that create memories to last a lifetime.') ?></p>
                    <div class="social-links mt-4">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h3 class="mb-4"><?= $translator->trans('Quick Links') ?></h3>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= \App\Config\Config::getAppUrl() ?>" class="text-white"><?= $translator->trans('Home') ?></a></li>
                        <li class="mb-2"><a href="<?= \App\Config\Config::getAppUrl() ?>/tours" class="text-white"><?= $translator->trans('Tours') ?></a></li>
                        <li class="mb-2"><a href="<?= \App\Config\Config::getAppUrl() ?>/contact" class="text-white"><?= $translator->trans('Contact Us') ?></a></li>
                        <li class="mb-2"><a href="<?= \App\Config\Config::getAppUrl() ?>/admin/login" class="text-white"><?= $translator->trans('Admin Login') ?></a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h3 class="mb-4"><?= $translator->trans('Contact Info') ?></h3>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> <?= $translator->trans('123 Travel Street, City, Country') ?></li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@cocotravel.com</li>
                        <li class="mb-2"><i class="fab fa-whatsapp me-2"></i> +1 (555) 987-6543</li>
                        <li class="mb-2"><i class="fab fa-line me-2"></i> @cocotravel</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0"><?= $translator->trans('© %year% Coco Travel. All rights reserved.', ['%year%' => date('Y')]) ?></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script type="module" src="<?= \App\Helpers\asset('js/app.js') ?>"></script>
    
    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
</body>
</html>