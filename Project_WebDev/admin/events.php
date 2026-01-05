<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');
$message = '';
$editing = null;
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $mysqli->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $message = '<div class="alert success">Event deleted.</div>';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $date = $_POST['event_date'] ?? '';
    $venue = trim($_POST['venue'] ?? '');
    $distance = floatval($_POST['distance_km'] ?? 0);
    $fee = floatval($_POST['fee'] ?? 0);
    $status = $_POST['status'] ?? 'available';
    $capacity = intval($_POST['capacity'] ?? 0);
    if (isset($_POST['id']) && $_POST['id']) {
        $id = intval($_POST['id']);
        $stmt = $mysqli->prepare("UPDATE events SET title=?, category=?, description=?, event_date=?, venue=?, distance_km=?, fee=?, status=?, capacity=? WHERE id = ?");
        $stmt->bind_param('sssssddsii', $title, $category, $description, $date, $venue, $distance, $fee, $status, $capacity, $id);
        $stmt->execute();
        $message = '<div class="alert success">Event updated.</div>';
    } else {
        $stmt = $mysqli->prepare("INSERT INTO events (title, category, description, event_date, venue, distance_km, fee, status, capacity) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sssssddsi', $title, $category, $description, $date, $venue, $distance, $fee, $status, $capacity);
        $stmt->execute();
        $message = '<div class="alert success">Event created.</div>';
    }
}
if (isset($_GET['edit'])) {
    $id = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $mysqli->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $editing = $stmt->get_result()->fetch_assoc();
    }
}
$events = $mysqli->query("SELECT * FROM events ORDER BY event_date DESC");
?>
<h2>Manage Events</h2>
<?php echo $message; ?>
<div class="grid">
    <div class="card">
        <h3><?php echo $editing ? 'Edit Event' : 'Create Event'; ?></h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $editing['id'] ?? ''; ?>" />
            <label>Title<input required name="title" value="<?php echo e($editing['title'] ?? ''); ?>" /></label>
            <label>Category<input required name="category" value="<?php echo e($editing['category'] ?? ''); ?>" /></label>
            <label>Date<input required type="date" name="event_date" value="<?php echo e($editing['event_date'] ?? ''); ?>" /></label>
            <label>Venue<input required name="venue" value="<?php echo e($editing['venue'] ?? ''); ?>" /></label>
            <label>Distance (km)<input required type="number" step="0.1" name="distance_km" value="<?php echo e($editing['distance_km'] ?? ''); ?>" /></label>
            <label>Fee (RM)<input required type="number" step="0.01" name="fee" value="<?php echo e($editing['fee'] ?? ''); ?>" /></label>
            <label>Capacity<input required type="number" name="capacity" value="<?php echo e($editing['capacity'] ?? ''); ?>" /></label>
            <label>Status
                <select name="status">
                    <option value="available" <?php echo (($editing['status'] ?? '')==='available')?'selected':''; ?>>Available</option>
                    <option value="not available" <?php echo (($editing['status'] ?? '')==='not available')?'selected':''; ?>>Not available</option>
                </select>
            </label>
            <label>Description<textarea name="description" rows="4"><?php echo e($editing['description'] ?? ''); ?></textarea></label>
            <button class="btn-primary" type="submit">Save</button>
        </form>
    </div>
    <div class="card">
        <h3>Events</h3>
        <table class="table">
            <tr><th>Title</th><th>Date</th><th>Status</th><th>Actions</th></tr>
            <?php while ($row = $events->fetch_assoc()): ?>
                <tr>
                    <td><?php echo e($row['title']); ?></td>
                    <td><?php echo e($row['event_date']); ?></td>
                    <td><?php echo e($row['status']); ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
                        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete event?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
