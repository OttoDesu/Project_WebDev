<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo '<div class="alert error">Invalid event.</div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$stmt = $mysqli->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
if (!$event) {
    echo '<div class="alert error">Event not found.</div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
?>
<article class="card">
    <h2><?php echo e($event['title']); ?></h2>
    <p class="badge"><?php echo e($event['category']); ?></p>
    <p><?php echo date('l, M d, Y', strtotime($event['event_date'])); ?> at <?php echo e($event['venue']); ?></p>
    <p>Distance: <?php echo e($event['distance_km']); ?> km</p>
    <p>Fee: RM<?php echo e($event['fee']); ?></p>
    <p>Status: <strong><?php echo e($event['status']); ?></strong></p>
    <p><?php echo nl2br(e($event['description'])); ?></p>
    <div style="display:flex; gap:10px;">
        <a class="btn-primary" href="<?php echo $appBase; ?>/register_event.php?id=<?php echo $event['id']; ?>">Register now</a>
        <a class="btn-secondary" href="<?php echo $appBase; ?>/events.php">Back to list</a>
    </div>
</article>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
