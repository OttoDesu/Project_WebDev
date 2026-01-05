<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');
$eventCount = $mysqli->query("SELECT COUNT(*) c FROM events")->fetch_assoc()['c'];
$userCount = $mysqli->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$regCount = $mysqli->query("SELECT COUNT(*) c FROM registrations")->fetch_assoc()['c'];
?>
<h2>Admin Dashboard</h2>
<div class="grid">
    <div class="card"><h3>Events</h3><p><?php echo $eventCount; ?> total</p><a href="/admin/events.php" class="btn-primary">Manage</a></div>
    <div class="card"><h3>Registrations</h3><p><?php echo $regCount; ?> records</p><a href="/admin/registrations.php" class="btn-primary">View</a></div>
    <div class="card"><h3>Users</h3><p><?php echo $userCount; ?> accounts</p><a href="/admin/users.php" class="btn-primary">Manage</a></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
