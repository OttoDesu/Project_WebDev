<?php
require_once __DIR__ . '/includes/auth.php';

$errors = [];
$success = null;

if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    }

    $name = trim($_POST['name'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message = trim($_POST['message'] ?? '');

    if (strlen($name) < 2) {
        $errors[] = 'Name is required.';
    }
    if (!$email) {
        $errors[] = 'Valid email is required.';
    }
    if (strlen($message) < 5) {
        $errors[] = 'Message is required.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $message]);
        $success = 'Message sent. We will get back to you soon.';
        $_POST = [];
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h1>Contact Us</h1>
    <p class="muted">We would love to hear from you. Reach out for support, partnerships, or feedback.</p>
    <?php if ($errors): ?>
        <div class="error"><?php echo implode('<br>', array_map('h', $errors)); ?></div>
    <?php elseif ($success): ?>
        <div class="success"><?php echo h($success); ?></div>
    <?php endif; ?>
    <div class="grid two-up">
        <div class="stacked">
            <strong>Email</strong>
            <span class="muted">support@erecruitment.test</span>
            <strong>Phone</strong>
            <span class="muted">+60 12-345 6789</span>
            <strong>Address</strong>
            <span class="muted">123 Talent Avenue, Kuala Lumpur</span>
        </div>
        <form class="card" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
            <label>
                Name
                <input name="name" placeholder="Your name" required value="<?php echo h($_POST['name'] ?? ''); ?>">
            </label>
            <label>
                Email
                <input type="email" name="email" placeholder="you@example.com" required value="<?php echo h($_POST['email'] ?? ''); ?>">
            </label>
            <label>
                Message
                <textarea name="message" placeholder="How can we help?" required><?php echo h($_POST['message'] ?? ''); ?></textarea>
            </label>
            <button class="button" type="submit">Send Message</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
