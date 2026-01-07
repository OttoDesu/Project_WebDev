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
    $password = $_POST['password'] ?? '';

    if (strlen($name) < 2) {
        $errors[] = 'Name is required.';
    }
    if (!$email) {
        $errors[] = 'Valid email is required.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, "user")');
            $insert->execute([$name, $email, $hash]);
            $success = 'Account created. You can now log in.';
        }
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h1>Create an account</h1>
    <?php if ($errors): ?>
        <div class="error"><?php echo implode('<br>', array_map('h', $errors)); ?></div>
    <?php elseif ($success): ?>
        <div class="success"><?php echo h($success); ?></div>
    <?php endif; ?>
    <form method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
        <label>
            Full Name
            <input name="name" required value="<?php echo h($_POST['name'] ?? ''); ?>">
        </label>
        <label>
            Email
            <input type="email" name="email" required value="<?php echo h($_POST['email'] ?? ''); ?>">
        </label>
        <label>
            Password
            <input type="password" name="password" required minlength="8" placeholder="At least 8 characters">
        </label>
        <button class="button" type="submit">Register</button>
        <p class="muted">Already have an account? <a href="<?php echo h(url_for('login.php')); ?>">Login</a></p>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
