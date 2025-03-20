<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Panel' ?></title>
    <link href="<?= \App\Helpers\asset('css/admin-style.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?= $this->section('content') ?>
    </div>
    <script type="module" src="<?= \App\Helpers\asset('js/admin/imageUpload.js') ?>"></script>
</body>
</html>