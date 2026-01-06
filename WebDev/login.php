<?php
require_once __DIR__ . '/includes/auth.php';

$errors = [];

if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $errors[] = 'Email and password are required.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            login_user($user);
            $target = $user['role'] === 'admin'
                ? url_for('admin/index.php')
                : url_for('profile.php');
            header("Location: {$target}");
            exit;
        } else {
            $errors[] = 'Invalid credentials.';
        }
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h1>Login</h1>
    <?php if ($errors): ?>
        <div class="error"><?php echo implode('<br>', array_map('h', $errors)); ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
        <label>
            Email
            <input type="email" name="email" required value="<?php echo h($_POST['email'] ?? ''); ?>">
        </label>
        <label>
            Password
            <input type="password" name="password" required>
        </label>
        <button class="button" type="submit">Login</button>
        <p class="muted">Employer? Use the <a href="<?php echo h(url_for('admin/login.php')); ?>">Admin login</a></p>
        <p class="muted">Need an account? <a href="<?php echo h(url_for('register.php')); ?>">Register</a></p>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
