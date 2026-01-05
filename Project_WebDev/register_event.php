<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';
require_login();

$eventId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$eventId) {
    echo '<div class="alert error">Invalid event.</div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$stmt = $mysqli->prepare("SELECT id, title, event_date, venue, fee, status FROM events WHERE id = ?");
$stmt->bind_param('i', $eventId);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();
if (!$event) {
    echo '<div class="alert error">Event not found.</div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notes = substr(trim($_POST['notes'] ?? ''), 0, 255);
    $check = $mysqli->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
    $check->bind_param('ii', $_SESSION['user']['id'], $eventId);
    $check->execute();
    if ($check->get_result()->fetch_assoc()) {
        $message = '<div class="alert error">You already registered for this event.</div>';
    } else {
        $ins = $mysqli->prepare("INSERT INTO registrations (user_id, event_id, notes) VALUES (?, ?, ?)");
        $ins->bind_param('iis', $_SESSION['user']['id'], $eventId, $notes);
        if ($ins->execute()) {
            $message = '<div class="alert success">Registration confirmed! See you at the start line.</div>';
        }
    }
}
?>
<div class="card" style="max-width:620px; margin:auto;">
    <h2>Register: <?php echo e($event['title']); ?></h2>
    <p><?php echo date('M d, Y', strtotime($event['event_date'])); ?> @ <?php echo e($event['venue']); ?> — RM<?php echo e($event['fee']); ?></p>
    <?php echo $message; ?>
    <form method="POST">
        <label>Notes to organizer (optional)
            <textarea name="notes" rows="3"></textarea>
        </label>
        <button class="btn-primary" type="submit">Confirm Registration</button>
    </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
