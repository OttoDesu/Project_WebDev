<?php
require_once __DIR__ . '/includes/auth.php';
$stmt = $pdo->prepare('SELECT * FROM jobs ORDER BY created_at DESC');
$stmt->execute();
$jobs = $stmt->fetchAll();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h1>Job Listings</h1>
    <p class="muted">Browse all available roles. Filter by location and closing dates to find your fit.</p>
    <div class="grid">
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
                        <p class="muted"><?php echo h($job['location']); ?> · Closes: <?php echo h($job['closing_date']); ?></p>
                    </div>
                </div>
                <p><?php echo nl2br(h(substr($job['description'], 0, 200))); ?><?php if (strlen($job['description']) > 200) echo '...'; ?></p>
                <div class="job-footer">
                    <span class="badge"><?php echo h($job['salary']); ?></span>
                    <a class="button small" href="<?php echo h(url_for('job.php?id=' . (int)$job['id'])); ?>">View Details</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
