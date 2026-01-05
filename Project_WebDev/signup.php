<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    if (!$name || !$email || strlen($password) < 6) {
        $message = '<div class="alert error">Please enter a name, valid email, and password (6+ chars).</div>';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $mysqli->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'participant')");
        $stmt->bind_param('sss', $name, $email, $hash);
        if ($stmt->execute()) {
            $message = '<div class="alert success">Account created. You can log in now.</div>';
        } else {
            $message = '<div class="alert error">Email already registered.</div>';
        }
    }
}
?>
<div class="card" style="max-width:520px; margin:auto;">
    <h2>Create your runner account</h2>
    <?php echo $message; ?>
    <form method="POST">
        <label>Name
            <input required name="name" />
        </label>
        <label>Email
            <input required type="email" name="email" />
        </label>
        <label>Password
            <input required type="password" name="password" minlength="6" />
        </label>
        <button class="btn-primary" type="submit">Sign Up</button>
    </form>
    <p>Have an account? <a href="/login.php">Log in</a></p>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
