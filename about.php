<?php
declare(strict_types=1);

$pageTitle = 'About';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-inner">
        <p class="eyebrow">A little about us</p>
        <h1>We believe a clean space can make <span>room for more.</span></h1>
        <p>More calm in your morning. More time with your people. More space to simply feel at home.</p>
    </div>
</section>

<section class="section">
    <div class="container story-grid">
        <div class="story-art" role="img" aria-label="A green plant in a bright, tidy home">
            <div class="story-sun"></div>
            <div class="story-vase"><span></span><i></i><b></b></div>
            <div class="story-books"></div>
            <div class="story-sparkle">✦</div>
        </div>
        <div class="story-copy">
            <p class="eyebrow">Thoughtful by nature</p>
            <h2>Cleaning that feels <span>like a fresh start.</span></h2>
            <p><?= e($siteName) ?> began with a simple idea: getting help at home should feel easy, reassuring, and genuinely worthwhile. We’re a local team that cares about the little things—arriving when we say we will, listening to what matters to you, and leaving your space feeling just right.</p>
            <p>Every home is different. That’s why we take the time to understand yours and create a clean that suits your routine, your priorities, and your definition of comfortable.</p>
            <a class="text-link" href="contact.php">Meet your cleaning team <span aria-hidden="true">&rarr;</span></a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">The people behind the clean</p>
            <h2>Meet our <span>team.</span></h2>
            <p>Friendly people who care about helping your space feel its best.</p>
        </div>
        <div class="team-grid">
            <?php foreach ($team as $member): ?>
                <article class="team-card">
                    <div class="team-avatar" aria-hidden="true"><?= e(substr($member['name'], 0, 1)) ?></div>
                    <h3><?= e($member['name']) ?></h3>
                    <p class="team-role"><?= e($member['role']) ?></p>
                    <p><?= e($member['bio']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container values-grid">
        <div class="section-heading align-left">
            <p class="eyebrow">What matters to us</p>
            <h2>Care is in the <span>details.</span></h2>
        </div>
        <div class="value-list">
            <article><span>01</span><div><h3>Respect</h3><p>Your home, your preferences, your privacy—we treat all three with care.</p></div></article>
            <article><span>02</span><div><h3>Consistency</h3><p>A reliable routine and thoughtful checklist help make every visit count.</p></div></article>
            <article><span>03</span><div><h3>Consideration</h3><p>We’re mindful of the products and practices we bring into your space.</p></div></article>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
