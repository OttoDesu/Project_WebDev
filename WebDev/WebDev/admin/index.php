<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');

$jobCount = $pdo->query('SELECT COUNT(*) AS c FROM jobs')->fetch()['c'] ?? 0;
$appCount = $pdo->query('SELECT COUNT(*) AS c FROM applications')->fetch()['c'] ?? 0;
$userCount = $pdo->query('SELECT COUNT(*) AS c FROM users WHERE role = "user"')->fetch()['c'] ?? 0;
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
        </nav>
    </aside>
    <section class="main-panel">
        <h1>Admin Dashboard</h1>
        <div class="grid">
            <div class="card">
                <h3>Total Jobs</h3>
                <p class="pill"><?php echo (int)$jobCount; ?></p>
            </div>
            <div class="card">
                <h3>Total Applications</h3>
                <p class="pill"><?php echo (int)$appCount; ?></p>
            </div>
            <div class="card">
                <h3>Total Applicants</h3>
                <p class="pill"><?php echo (int)$userCount; ?></p>
            </div>
        </div>
    </section>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
