<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$values = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'service' => '',
    'message' => '',
];
$errors = [];
$sent = $_SERVER['REQUEST_METHOD'] !== 'POST'
    && isset($_GET['sent'])
    && $_GET['sent'] === '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($values) as $field) {
        $input = $_POST[$field] ?? '';
        if (!is_string($input)) {
            $errors[$field] = 'Please enter a valid value.';
            continue;
        }

        $values[$field] = trim($input);
        if (preg_match('//u', $values[$field]) !== 1) {
            $errors[$field] = 'Please use valid text characters.';
        }
    }

    $submittedToken = $_POST['csrf_token'] ?? '';
    if (
        !is_string($submittedToken)
        || !hash_equals($_SESSION['csrf_token'], $submittedToken)
    ) {
        $errors['_form'] = 'Your form session expired or could not be verified. Please try again.';
    }

    if ($values['name'] === '') {
        $errors['name'] = 'Please enter your name.';
    }

    if ($values['email'] === '') {
        $errors['email'] = 'Please enter your email address.';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($values['service'] === '') {
        $errors['service'] = 'Please choose a service.';
    } else {
        $serviceExists = false;
        foreach ($services as $service) {
            if ($service['slug'] === $values['service']) {
                $serviceExists = true;
                break;
            }
        }

        if (!$serviceExists) {
            $errors['service'] = 'Please choose one of the listed services.';
        }
    }

    if ($values['message'] === '') {
        $errors['message'] = 'Please enter a message.';
    } elseif (!isset($errors['message'])) {
        $messageLength = preg_match_all('/./us', $values['message']);
        if ($messageLength === false) {
            $errors['message'] = 'Please use valid text characters.';
        } elseif ($messageLength < 10 || $messageLength > 1000) {
            $errors['message'] = 'Your message must be between 10 and 1,000 characters.';
        }
    }

    if ($errors === []) {
        $submission = [
            'timestamp' => date(DATE_ATOM),
            'name' => $values['name'],
            'email' => $values['email'],
            'phone' => $values['phone'],
            'service' => $values['service'],
            'message' => $values['message'],
        ];

        try {
            $jsonLine = json_encode(
                $submission,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
            ) . PHP_EOL;
        } catch (JsonException $exception) {
            error_log('Contact form JSON encoding failed: ' . $exception->getMessage());
            $errors['_form'] = 'We could not save your message. Please try again later.';
            $jsonLine = '';
        }

        if ($jsonLine !== '') {
            $storageDirectory = __DIR__ . '/storage';
            if (
                !is_dir($storageDirectory)
                && !mkdir($storageDirectory, 0750, true)
                && !is_dir($storageDirectory)
            ) {
                error_log('Contact form storage directory could not be created.');
                $errors['_form'] = 'We could not save your message. Please try again later.';
            } else {
                $savedBytes = file_put_contents(
                    $storageDirectory . '/messages.jsonl',
                    $jsonLine,
                    FILE_APPEND | LOCK_EX
                );

                if ($savedBytes !== strlen($jsonLine)) {
                    error_log('Contact form submission could not be written to storage/messages.jsonl.');
                    $errors['_form'] = 'We could not save your message. Please try again later.';
                } else {
                    unset($_SESSION['csrf_token']);
                    header('Location: contact.php?sent=1', true, 303);
                    exit;
                }
            }
        }
    }
}

$pageTitle = 'Contact';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-inner">
        <p class="eyebrow">Let’s make it easy</p>
        <h1>Tell us what a fresh start <span>looks like for you.</span></h1>
        <p>Ask a question, share what you need, or get started with a free, no-pressure quote.</p>
    </div>
</section>

<section class="section">
    <div class="container contact-grid">
        <div class="contact-copy">
            <p class="eyebrow">We’re happy to help</p>
            <h2>Let’s find your <span>just-right clean.</span></h2>
            <p>Every space and schedule is different. Send us a note and we’ll get back to you to talk through the details.</p>
            <div class="contact-detail">
                <span class="contact-icon" aria-hidden="true">@</span>
                <div><span class="detail-label">Email us</span><a href="mailto:<?= e($contactEmail) ?>"><?= e($contactEmail) ?></a></div>
            </div>
            <div class="contact-detail">
                <span class="contact-icon icon-peach" aria-hidden="true">◷</span>
                <div><span class="detail-label">When we’re around</span><span>Monday–Friday, 9am–5pm</span></div>
            </div>
        </div>
        <div class="contact-card">
            <?php if ($sent): ?>
                <div class="form-alert form-alert-success" role="status">
                    Thanks for getting in touch. Your message has been received.
                </div>
            <?php endif; ?>

            <?php if (isset($errors['_form'])): ?>
                <div class="form-alert form-alert-error" role="alert"><?= e($errors['_form']) ?></div>
            <?php endif; ?>

            <h2>Send us a message.</h2>
            <p>Tell us a little about what you need and we’ll be in touch.</p>

            <form class="contact-form" method="post" action="contact.php">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="form-field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required
                        value="<?= e($values['name']) ?>"
                        <?= isset($errors['name']) ? 'aria-invalid="true" aria-describedby="name-error"' : '' ?>>
                    <?php if (isset($errors['name'])): ?>
                        <p class="field-error" id="name-error"><?= e($errors['name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        value="<?= e($values['email']) ?>"
                        <?= isset($errors['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>>
                    <?php if (isset($errors['email'])): ?>
                        <p class="field-error" id="email-error"><?= e($errors['email']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="phone">Phone <span class="optional-label">(optional)</span></label>
                    <input id="phone" name="phone" type="tel" autocomplete="tel"
                        value="<?= e($values['phone']) ?>"
                        <?= isset($errors['phone']) ? 'aria-invalid="true" aria-describedby="phone-error"' : '' ?>>
                    <?php if (isset($errors['phone'])): ?>
                        <p class="field-error" id="phone-error"><?= e($errors['phone']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="service">Service</label>
                    <select id="service" name="service" required
                        <?= isset($errors['service']) ? 'aria-invalid="true" aria-describedby="service-error"' : '' ?>>
                        <option value="">Choose a service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= e($service['slug']) ?>" <?= $values['service'] === $service['slug'] ? 'selected' : '' ?>>
                                <?= e($service['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['service'])): ?>
                        <p class="field-error" id="service-error"><?= e($errors['service']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required
                        <?= isset($errors['message']) ? 'aria-invalid="true" aria-describedby="message-error"' : '' ?>><?= e($values['message']) ?></textarea>
                    <?php if (isset($errors['message'])): ?>
                        <p class="field-error" id="message-error"><?= e($errors['message']) ?></p>
                    <?php endif; ?>
                </div>

                <button class="button button-primary" type="submit">Send message <span aria-hidden="true">&rarr;</span></button>
            </form>

            <p class="small-note">Your message is saved securely for the site owner to review.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
