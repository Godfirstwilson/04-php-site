<?php
declare(strict_types=1);

$pageTitle = 'Home';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">A clean you can feel good about</p>
            <h1>Come home to <span>fresh.</span></h1>
            <p class="hero-intro">More breathing room, less to-do list. We bring thoughtful, dependable cleaning to homes and small businesses.</p>
            <div class="hero-actions">
                <a class="button button-primary" href="contact.php">Get a free quote</a>
                <a class="text-link" href="services.php">Explore our services <span aria-hidden="true">&rarr;</span></a>
            </div>
            <div class="trust-note">
                <span class="trust-stars" aria-label="Five out of five stars">★★★★★</span>
                <span>Friendly people. A lovely clean. Every time.</span>
            </div>
        </div>
        <div class="hero-art" role="img" aria-label="Sunlit, freshly cleaned living room">
            <div class="art-sun"></div>
            <div class="art-window"><span></span><span></span><span></span><span></span></div>
            <div class="art-plant"><i></i><i></i><i></i><i></i></div>
            <div class="art-sofa"><span></span><span></span></div>
            <div class="art-table"></div>
            <div class="art-sparkle sparkle-one">✦</div>
            <div class="art-sparkle sparkle-two">✦</div>
            <div class="art-caption"><span class="caption-dot"></span> Your fresh start, right at home</div>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">The Prime difference</p>
            <h2>Good cleaning. <span>Good people.</span></h2>
            <p>We make it simple to enjoy a cleaner space, with care in every detail and people you can trust.</p>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <span class="feature-icon" aria-hidden="true">✦</span>
                <h3>Care in every corner</h3>
                <p>We take the time to do things properly, with a checklist tailored to your space.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon icon-peach" aria-hidden="true">♡</span>
                <h3>People you can trust</h3>
                <p>Friendly, respectful cleaners who treat your home like it’s their own.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon icon-blue" aria-hidden="true">↗</span>
                <h3>Easy to make it yours</h3>
                <p>Choose the service and schedule that fit your life. We’ll take care of the rest.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container callout">
        <div>
            <p class="eyebrow">A little more time for you</p>
            <h2>Ready to make room for what matters?</h2>
        </div>
        <a class="button button-light" href="contact.php">Let’s talk <span aria-hidden="true">&rarr;</span></a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
