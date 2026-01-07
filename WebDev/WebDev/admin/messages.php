<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');

$stmt = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC');
$messages = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="layout-shell">
    <aside class="sidebar">
        <h3>Admin</h3>
        <nav>
            <a href="<?php echo h(url_for('admin/index.php')); ?>">Dashboard</a>
            <a href="<?php echo h(url_for('admin/jobs.php')); ?>">Manage Jobs</a>
            <a href="<?php echo h(url_for('admin/applications.php')); ?>">Manage Applications</a>
            <a href="<?php echo h(url_for('admin/messages.php')); ?>">Contact Messages</a>
            <a href="<?php echo h(url_for('admin/profile.php')); ?>">Profile</a>
        </nav>
    </aside>
    <section class="main-panel">
        <h1>Contact Messages</h1>
        <?php if (!$messages): ?>
            <p class="muted">No messages yet.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Received</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr>
                        <td data-label="Name"><?php echo h($msg['name']); ?></td>
                        <td data-label="Email"><?php echo h($msg['email']); ?></td>
                        <td data-label="Message"><?php echo nl2br(h($msg['message'])); ?></td>
                        <td data-label="Received"><?php echo h($msg['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
