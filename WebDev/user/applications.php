<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('user');

$user = current_user();
$apps = $pdo->prepare('SELECT a.*, j.title FROM applications a JOIN jobs j ON a.job_id = j.id WHERE a.user_id = ? ORDER BY a.created_at DESC');
$apps->execute([$user['id']]);
$applications = $apps->fetchAll();
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="layout-shell">
    <aside class="sidebar">
        <h3>Applicant</h3>
        <nav>
            <a href="<?php echo h(url_for('user/dashboard.php')); ?>">Dashboard</a>
            <a href="<?php echo h(url_for('user/applications.php')); ?>">My Applications</a>
            <a href="<?php echo h(url_for('profile.php')); ?>">Profile</a>
            <a href="<?php echo h(url_for('logout.php')); ?>">Logout</a>
        </nav>
    </aside>
    <section class="main-panel">
        <h1>My Applications</h1>
        <?php if (!$applications): ?>
            <p class="muted">No applications yet.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Job</th>
                    <th>Status</th>
                    <th>Submitted</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td data-label="Job"><?php echo h($app['title']); ?></td>
                        <td data-label="Status"><span class="status <?php echo strtolower(h($app['status'])); ?>"><?php echo h($app['status']); ?></span></td>
                        <td data-label="Submitted"><?php echo h($app['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
