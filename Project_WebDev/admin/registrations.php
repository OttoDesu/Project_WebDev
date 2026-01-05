<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');
$regs = $mysqli->query("SELECT r.id, r.registered_at, u.name as user_name, u.email, e.title as event_title FROM registrations r JOIN users u ON r.user_id = u.id JOIN events e ON r.event_id = e.id ORDER BY r.registered_at DESC");
?>
<h2>Registrations</h2>
<div class="card">
<table class="table">
    <tr><th>Runner</th><th>Email</th><th>Event</th><th>Registered At</th></tr>
    <?php while ($row = $regs->fetch_assoc()): ?>
        <tr>
            <td><?php echo e($row['user_name']); ?></td>
            <td><?php echo e($row['email']); ?></td>
            <td><?php echo e($row['event_title']); ?></td>
            <td><?php echo e($row['registered_at']); ?></td>
        </tr>
    <?php endwhile; ?>
</table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
