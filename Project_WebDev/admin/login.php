<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $mysqli->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = $user;
            header('Location: /admin/dashboard.php');
            exit;
        }
    }
    $message = '<div class="alert error">Invalid admin login.</div>';
}
?>
<div class="card" style="max-width:480px; margin:auto;">
    <h2>Admin Login</h2>
    <?php echo $message; ?>
    <form method="POST">
        <label>Email
            <input required type="email" name="email" />
        </label>
        <label>Password
            <input required type="password" name="password" />
        </label>
        <button class="btn-primary" type="submit">Login</button>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
