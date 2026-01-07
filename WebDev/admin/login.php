<?php
require_once __DIR__ . '/../includes/auth.php';

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

        if ($user && password_verify($password, $user['password_hash']) && $user['role'] === 'admin') {
            login_user($user);
            $target = url_for('admin/index.php');
            header("Location: {$target}");
            exit;
        } else {
            $errors[] = 'Invalid admin credentials.';
        }
    }
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<section class="card">
    <h1>Employer / Admin Login</h1>
    <p class="muted">Access the admin dashboard to manage vacancies and applicants.</p>
    <?php if ($errors): ?>
        <div class="error"><?php echo implode('<br>', array_map('h', $errors)); ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
        <label>
            Admin Email
            <input type="email" name="email" required value="<?php echo h($_POST['email'] ?? ''); ?>">
        </label>
        <label>
            Password
            <input type="password" name="password" required>
        </label>
        <button class="button" type="submit">Login</button>
        <p class="muted">Need a user account instead? <a href="<?php echo h(url_for('login.php')); ?>">User login</a></p>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
