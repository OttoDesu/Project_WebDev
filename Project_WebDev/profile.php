<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
require_login();
$message = '';
$userId = $_SESSION['user']['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($name) {
        if ($password) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $mysqli->prepare("UPDATE users SET name = ?, password_hash = ? WHERE id = ?");
            $stmt->bind_param('ssi', $name, $hash, $userId);
        } else {
            $stmt = $mysqli->prepare("UPDATE users SET name = ? WHERE id = ?");
            $stmt->bind_param('si', $name, $userId);
        }
        if ($stmt->execute()) {
            $_SESSION['user']['name'] = $name;
            $message = '<div class="alert success">Profile updated.</div>';
        }
    }
}
?>
<div class="card" style="max-width:520px; margin:auto;">
    <h2>Profile</h2>
    <?php echo $message; ?>
    <form method="POST">
        <label>Name
            <input name="name" required value="<?php echo e($_SESSION['user']['name']); ?>" />
        </label>
        <label>New Password (optional)
            <input type="password" name="password" minlength="6" />
        </label>
        <button class="btn-primary" type="submit">Save</button>
    </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
