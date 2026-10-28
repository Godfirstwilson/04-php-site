<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($tagline) ?>">
    <title><?= e($pageTitle) ?> | <?= e($siteName) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="index.php" aria-label="<?= e($siteName) ?> home">
                <span class="brand-mark" aria-hidden="true"><?= e(substr($siteName, 0, 1)) ?></span>
                <span class="brand-copy">
                    <span class="brand-name"><?= e($siteName) ?></span>
                    <span class="brand-tagline"><?= e($tagline) ?></span>
                </span>
            </a>
            <nav class="site-nav" aria-label="Main navigation">
                <?php foreach ($navItems as $item): ?>
                    <?php
                    $currentPage = basename($_SERVER['PHP_SELF']);
                    $isCurrentPage = $currentPage === $item['file']
                        || ($currentPage === 'service.php' && $item['file'] === 'services.php');
                    ?>
                    <a
                        href="<?= e($item['file']) ?>"
                        class="nav-link<?= $isCurrentPage ? ' active' : '' ?>"
                        <?= $isCurrentPage ? 'aria-current="page"' : '' ?>
                    ><?= e($item['label']) ?></a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>
    <main id="main-content">
