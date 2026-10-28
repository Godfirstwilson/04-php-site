<?php
declare(strict_types=1);

$pageTitle = 'Services';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-inner">
        <p class="eyebrow">How can we help?</p>
        <h1>A clean that fits <span>your kind of busy.</span></h1>
        <p>From a regular refresh to a one-off reset, we’ll help find the right kind of clean for your space.</p>
    </div>
</section>

<section class="section">
    <div class="container services-grid">
        <?php foreach ($services as $service): ?>
            <?= renderServiceCard($service) ?>
        <?php endforeach; ?>
    </div>
    <div class="container service-note">
        <p>Not sure what you need? Tell us a little about your space and we’ll help you figure it out.</p>
        <a class="button button-primary" href="contact.php">Get a free quote</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
