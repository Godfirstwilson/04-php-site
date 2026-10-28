<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if (
    !isset($_SESSION['messages_csrf_token'])
    || !is_string($_SESSION['messages_csrf_token'])
) {
    $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
}

$loginError = '';
$storageError = '';
$messages = [];
$isAuthenticated = ($_SESSION['messages_authenticated'] ?? false) === true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    $validToken = is_string($submittedToken)
        && hash_equals($_SESSION['messages_csrf_token'], $submittedToken);

    if (!$validToken) {
        $loginError = 'Your form session expired or could not be verified. Please reload and try again.';
    } elseif (($_POST['action'] ?? '') === 'logout') {
        unset($_SESSION['messages_authenticated']);
        header('Location: messages.php', true, 303);
        exit;
    } elseif (($_POST['action'] ?? '') === 'login') {
        $submittedPassword = $_POST['password'] ?? '';

        if (
            !is_string($submittedPassword)
            || !hash_equals($messagesPassword, $submittedPassword)
        ) {
            $loginError = 'That password was not correct. Please try again.';
        } else {
            session_regenerate_id(true);
            $_SESSION['messages_authenticated'] = true;
            header('Location: messages.php', true, 303);
            exit;
        }
    } else {
        $loginError = 'That request could not be processed. Please try again.';
    }

    $isAuthenticated = ($_SESSION['messages_authenticated'] ?? false) === true;
}

if ($isAuthenticated) {
    $messagesPath = __DIR__ . '/storage/messages.jsonl';

    if (is_file($messagesPath)) {
        $contents = file_get_contents($messagesPath);
        if ($contents === false) {
            error_log('Messages page could not read storage/messages.jsonl.');
            $storageError = 'Messages could not be loaded. Check the storage file permissions.';
        } else {
            $lines = preg_split('/\R/', trim($contents));
            if ($lines !== false) {
                foreach ($lines as $lineIndex => $line) {
                    if ($line === '') {
                        continue;
                    }

                    try {
                        $message = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
                    } catch (JsonException $exception) {
                        error_log('Messages page skipped an invalid JSONL record: ' . $exception->getMessage());
                        continue;
                    }

                    if (!is_array($message)) {
                        error_log('Messages page skipped a JSONL record that was not an object.');
                        continue;
                    }

                    $messages[] = [
                        'data' => $message,
                        'line_index' => $lineIndex,
                    ];
                }
            }
        }
    }

    usort(
        $messages,
        static function (array $first, array $second): int {
            $firstData = $first['data'];
            $secondData = $second['data'];
            $firstTimestamp = strtotime(is_string($firstData['timestamp'] ?? null) ? $firstData['timestamp'] : '') ?: 0;
            $secondTimestamp = strtotime(is_string($secondData['timestamp'] ?? null) ? $secondData['timestamp'] : '') ?: 0;

            $timestampOrder = $secondTimestamp <=> $firstTimestamp;
            if ($timestampOrder !== 0) {
                return $timestampOrder;
            }

            return $second['line_index'] <=> $first['line_index'];
        }
    );
}

$pageTitle = 'Messages';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container page-hero-inner">
        <p class="eyebrow">Site owner area</p>
        <h1>Contact <span>messages.</span></h1>
        <p>Review messages submitted through the contact form.</p>
    </div>
</section>

<section class="section">
    <div class="container messages-container">
        <?php if (!$isAuthenticated): ?>
            <div class="contact-card messages-login">
                <h2>Enter the local password</h2>
                <p>This page is for local learning and demonstration purposes.</p>
                <?php if ($loginError !== ''): ?>
                    <div class="form-alert form-alert-error" role="alert"><?= e($loginError) ?></div>
                <?php endif; ?>
                <form class="contact-form" method="post" action="messages.php">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['messages_csrf_token']) ?>">
                    <input type="hidden" name="action" value="login">
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    <button class="button button-primary" type="submit">View messages</button>
                </form>
            </div>
        <?php else: ?>
            <?php if ($storageError !== ''): ?>
                <div class="form-alert form-alert-error" role="alert"><?= e($storageError) ?></div>
            <?php endif; ?>
            <div class="messages-toolbar">
                <p class="messages-count"><span><?= count($messages) ?></span> <?= count($messages) === 1 ? 'message' : 'messages' ?></p>
                <form method="post" action="messages.php">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['messages_csrf_token']) ?>">
                    <input type="hidden" name="action" value="logout">
                    <button class="button button-primary" type="submit">Log out</button>
                </form>
            </div>

            <?php if ($messages === []): ?>
                <div class="contact-card messages-empty">
                    <h2>No messages yet</h2>
                    <p>Contact form submissions will appear here, newest first.</p>
                </div>
            <?php else: ?>
                <div class="messages-table-wrap">
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th scope="col">Received</th>
                                <th scope="col">Name &amp; email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Service</th>
                                <th scope="col">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $entry): ?>
                                <?php
                                $message = $entry['data'];
                                $timestamp = is_string($message['timestamp'] ?? null) ? $message['timestamp'] : '';
                                $parsedTimestamp = $timestamp !== '' ? strtotime($timestamp) : false;
                                $received = $parsedTimestamp === false ? 'Unknown' : date('M j, Y g:i a', $parsedTimestamp);
                                $name = is_string($message['name'] ?? null) ? $message['name'] : '';
                                $email = is_string($message['email'] ?? null) ? $message['email'] : '';
                                $phone = is_string($message['phone'] ?? null) && $message['phone'] !== ''
                                    ? $message['phone']
                                    : '—';
                                $service = is_string($message['service'] ?? null) ? $message['service'] : '';
                                $content = is_string($message['message'] ?? null) ? $message['message'] : '';
                                ?>
                                <tr>
                                    <td><?= e($received) ?></td>
                                    <td><strong><?= e($name) ?></strong><br><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></td>
                                    <td><?= e($phone) ?></td>
                                    <td><?= e($service) ?></td>
                                    <td class="message-content"><?= e($content) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
