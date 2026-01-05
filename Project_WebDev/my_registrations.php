<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
require_login();
$stmt = $mysqli->prepare("SELECT r.id, e.title, e.event_date, e.venue, e.status, r.registered_at FROM registrations r JOIN events e ON r.event_id = e.id WHERE r.user_id = ? ORDER BY r.registered_at DESC");
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$res = $stmt->get_result();
?>
<h2>My Registrations</h2>
<div class="grid">
<?php while ($row = $res->fetch_assoc()): ?>
    <div class="card">
        <h3><?php echo e($row['title']); ?></h3>
        <p><?php echo date('M d, Y', strtotime($row['event_date'])); ?> • <?php echo e($row['venue']); ?></p>
        <p>Status: <?php echo e($row['status']); ?></p>
        <p class="badge">Registered on <?php echo date('M d, Y', strtotime($row['registered_at'])); ?></p>
    </div>
<?php endwhile; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
