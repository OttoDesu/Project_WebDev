<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$stmt = $mysqli->prepare("SELECT id, title, category, event_date, venue, distance_km, fee, status FROM events ORDER BY event_date ASC");
$stmt->execute();
$events = $stmt->get_result();
?>
<h2>Upcoming Events</h2>
<div class="grid events-list">
<?php while ($row = $events->fetch_assoc()): ?>
    <div class="card event">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3><?php echo e($row['title']); ?></h3>
                <p class="badge"><?php echo e($row['category']); ?></p>
            </div>
            <div>
                <span class="badge" style="background:rgba(244,180,0,0.15); color:#f4b400;"><?php echo e($row['status']); ?></span>
            </div>
        </div>
        <p><?php echo date('M d, Y', strtotime($row['event_date'])); ?> &bull; <?php echo e($row['venue']); ?></p>
        <p><?php echo e($row['distance_km']); ?> km &bull; RM<?php echo e($row['fee']); ?></p>
        <div style="display:flex; gap:8px;">
            <a class="btn-secondary" href="<?php echo $appBase; ?>/event_detail.php?id=<?php echo $row['id']; ?>">View</a>
            <a class="btn-primary" href="<?php echo $appBase; ?>/register_event.php?id=<?php echo $row['id']; ?>">Register</a>
        </div>
    </div>
<?php endwhile; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
