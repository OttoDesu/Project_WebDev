<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');

$user = current_user();
$errors = [];
$success = null;

if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (strlen($name) < 2) {
            $errors[] = 'Name is required.';
        }
        if (!$email) {
            $errors[] = 'Valid email is required.';
        }

        if (!$errors) {
            $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?')->execute([$name, $email, $user['id']]);

            if ($password) {
                if (strlen($password) < 8) {
                    $errors[] = 'Password must be at least 8 characters.';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([$hash, $user['id']]);
                }
            }

            if (!$errors) {
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['email'] = $email;
                $success = 'Profile updated.';
            }
        }
    }
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="layout-shell">
    <aside class="sidebar">
        <h3>Admin</h3>
        <nav>
            <a href="<?php echo h(url_for('admin/index.php')); ?>">Dashboard</a>
            <a href="<?php echo h(url_for('admin/jobs.php')); ?>">Manage Jobs</a>
            <a href="<?php echo h(url_for('admin/applications.php')); ?>">Manage Applications</a>
            <a href="<?php echo h(url_for('admin/messages.php')); ?>">Contact Messages</a>
            <a href="<?php echo h(url_for('admin/profile.php')); ?>">Profile</a>
        </nav>
    </aside>
    <section class="main-panel">
        <h1>Admin Profile</h1>
        <?php if ($errors): ?>
            <div class="error"><?php echo implode('<br>', array_map('h', $errors)); ?></div>
        <?php elseif ($success): ?>
            <div class="success"><?php echo h($success); ?></div>
        <?php endif; ?>
        <form method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
            <label>
                Full Name
                <input name="name" required value="<?php echo h($_SESSION['user']['name']); ?>">
            </label>
            <label>
                Email
                <input type="email" name="email" required value="<?php echo h($_SESSION['user']['email']); ?>">
            </label>
            <label>
                New Password (optional)
                <input type="password" name="password" minlength="8" placeholder="Leave blank to keep current password">
            </label>
            <button class="button" type="submit">Update Profile</button>
        </form>
    </section>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
