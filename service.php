<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

$requestedSlug = $_GET['slug'] ?? '';
if (!is_string($requestedSlug)) {
    $requestedSlug = '';
}

$selectedService = null;
foreach ($services as $service) {
    if ($service['slug'] === $requestedSlug) {
        $selectedService = $service;
        break;
    }
}

if ($selectedService === null) {
    http_response_code(404);
    $pageTitle = 'Service not found';
} else {
    $pageTitle = $selectedService['title'];
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($selectedService === null): ?>
    <section class="page-hero">
        <div class="container page-hero-inner">
            <p class="eyebrow">Page not found</p>
            <h1>We couldn’t find <span>that service.</span></h1>
            <p>It may have moved or the link may be incorrect. Browse our services to find what you need.</p>
            <a class="button button-primary" href="services.php">Browse services</a>
        </div>
    </section>
<?php else: ?>
    <section class="page-hero">
        <div class="container page-hero-inner">
            <p class="eyebrow">A little more time for you</p>
            <h1><?= e($selectedService['title']) ?></h1>
            <p><?= e($selectedService['description']) ?></p>
        </div>
    </section>

    <section class="section">
        <div class="container service-detail">
            <div class="service-icon service-detail-icon" aria-hidden="true"><?= e($selectedService['icon']) ?></div>
            <p class="eyebrow">Simple, thoughtful cleaning</p>
            <h2><?= e($selectedService['title']) ?></h2>
            <p><?= e($selectedService['description']) ?></p>
            <p class="service-detail-price">Starting from <strong><?= e(formatPrice($selectedService['price'])) ?></strong></p>
            <a class="button button-primary" href="contact.php">Ask us about this service <span aria-hidden="true">&rarr;</span></a>
            <a class="text-link service-back-link" href="services.php">Back to all services</a>
        </div>
    </section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
