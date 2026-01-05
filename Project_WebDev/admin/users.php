<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');
$message = '';
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $mysqli->prepare("DELETE FROM users WHERE id = ? AND id <> ?");
        $stmt->bind_param('ii', $id, $_SESSION['user']['id']);
        $stmt->execute();
        $message = '<div class="alert success">User deleted.</div>';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $role = $_POST['role'] === 'admin' ? 'admin' : 'participant';
    $stmt = $mysqli->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param('si', $role, $id);
    $stmt->execute();
    $message = '<div class="alert success">Role updated.</div>';
}
$users = $mysqli->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
?>
<h2>Manage Users</h2>
<?php echo $message; ?>
<div class="card">
<table class="table">
    <tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr>
    <?php while ($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo e($row['name']); ?></td>
            <td><?php echo e($row['email']); ?></td>
            <td>
                <form method="POST" style="display:flex; gap:6px; align-items:center;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                    <select name="role" onchange="this.form.submit()">
                        <option value="participant" <?php echo $row['role']==='participant'?'selected':''; ?>>Participant</option>
                        <option value="admin" <?php echo $row['role']==='admin'?'selected':''; ?>>Admin</option>
                    </select>
                </form>
            </td>
            <td><?php echo e($row['created_at']); ?></td>
            <td>
                <?php if ($row['id'] !== $_SESSION['user']['id']): ?>
                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete user?');">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
