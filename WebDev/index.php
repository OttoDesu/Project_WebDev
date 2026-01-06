<?php
require_once __DIR__ . '/includes/auth.php';

$stmt = $pdo->prepare('SELECT * FROM jobs WHERE closing_date >= CURDATE() OR closing_date IS NULL ORDER BY created_at DESC');
$stmt->execute();
$jobs = $stmt->fetchAll();
$user = current_user();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="hero">
    <div class="hero-text">
        <p class="pill">Job Board - Secure - Responsive</p>
        <h1>Hire and get hired with confidence.</h1>
        <p class="muted">Job Board streamlines hiring with clear vacancy management for employers and a frictionless application experience for candidates.</p>
        <?php if (!$user): ?>
            <div class="hero-actions">
                <a class="button" href="<?php echo h(url_for('register.php')); ?>">Find a role</a>
                <a class="button secondary" href="<?php echo h(url_for('login.php')); ?>">Sign in to apply</a>
            </div>
        <?php endif; ?>
        <div class="hero-stats">
            <div><strong><?php echo count($jobs); ?></strong><span>Open roles</span></div>
            <div><strong>Fast</strong><span>Secure sessions</span></div>
            <div><strong>Ready</strong><span>Responsive UI</span></div>
        </div>
    </div>
    <div class="hero-card">
        <h3>For Candidates</h3>
        <p class="muted">Browse openings, apply with one click, and track status in your profile.</p>
        <ul class="feature-list">
            <li>Searchable, detailed job posts</li>
            <li>Secure login and profile updates</li>
            <li>Status tracking across devices</li>
        </ul>
        <?php if (!$user): ?>
            <a class="button small" href="<?php echo h(url_for('login.php')); ?>">Sign in to apply</a>
        <?php endif; ?>
    </div>
</section>

<section class="grid">
    <?php if (!$jobs): ?>
        <div class="card">
            <p>No jobs are available yet. Please check back soon.</p>
        </div>
    <?php endif; ?>
    <?php foreach ($jobs as $job): ?>
        <article class="card job-card">
            <div class="job-header">
                <div>
                    <h3><?php echo h($job['title']); ?></h3>
                    <p class="muted"><?php echo h($job['location']); ?> | Salary: <?php echo h($job['salary']); ?></p>
                </div>
                <?php if ($job['closing_date']): ?>
                    <span class="badge">Closes: <?php echo h($job['closing_date']); ?></span>
                <?php endif; ?>
            </div>
            <p><?php echo nl2br(h(substr($job['description'], 0, 180))); ?><?php if (strlen($job['description']) > 180) echo '...'; ?></p>
            <div class="job-footer">
                <a class="button small" href="<?php echo h(url_for('job.php?id=' . (int)$job['id'])); ?>">View & Apply</a>
            </div>
        </article>
    <?php endforeach; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
