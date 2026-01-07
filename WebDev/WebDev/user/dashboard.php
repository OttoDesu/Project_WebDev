<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('user');

$user = current_user();
$totals = $pdo->prepare('SELECT COUNT(*) AS total,
    SUM(status = "Pending") AS pending,
    SUM(status = "Accepted") AS accepted,
    SUM(status = "Rejected") AS rejected
    FROM applications WHERE user_id = ?');
$totals->execute([$user['id']]);
$counts = $totals->fetch();
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="layout-shell">
    <aside class="sidebar">
        <h3>Applicant</h3>
        <nav>
            <a href="<?php echo h(url_for('user/dashboard.php')); ?>">Dashboard</a>
            <a href="<?php echo h(url_for('user/applications.php')); ?>">My Applications</a>
            <a href="<?php echo h(url_for('profile.php')); ?>">Profile</a>
        </nav>
    </aside>
    <section class="main-panel">
        <h1>Dashboard</h1>
        <p class="muted">Overview of your applications.</p>
        <div class="grid">
            <div class="card">
                <h3>Total Applications</h3>
                <p class="pill"><?php echo (int)$counts['total']; ?></p>
            </div>
            <div class="card">
                <h3>Pending</h3>
                <p class="pill"><?php echo (int)$counts['pending']; ?></p>
            </div>
            <div class="card">
                <h3>Accepted</h3>
                <p class="pill"><?php echo (int)$counts['accepted']; ?></p>
            </div>
            <div class="card">
                <h3>Rejected</h3>
                <p class="pill"><?php echo (int)$counts['rejected']; ?></p>
            </div>
        </div>
    </section>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
