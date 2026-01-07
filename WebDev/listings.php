<?php
require_once __DIR__ . '/includes/auth.php';

$keyword = trim($_GET['q'] ?? '');
$locationFilter = trim($_GET['loc'] ?? '');

$sql = 'SELECT * FROM jobs WHERE 1=1';
$params = [];

if ($keyword !== '') {
    $sql .= ' AND (title LIKE ? OR description LIKE ? OR company LIKE ?)';
    $like = '%' . $keyword . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($locationFilter !== '') {
    $sql .= ' AND location LIKE ?';
    $params[] = '%' . $locationFilter . '%';
}

$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h1>Job Listings</h1>
    <p class="muted">Browse all available roles. Filter by company, location, or keywords to find your fit.</p>
    <form method="get" class="grid-form filter-bar">
        <label>
            Keyword / Company
            <input type="search" name="q" placeholder="Search title, description, or company" value="<?php echo h($keyword); ?>">
        </label>
        <label>
            Location
            <input type="search" name="loc" placeholder="City / Remote" value="<?php echo h($locationFilter); ?>">
        </label>
        <div class="inline-form">
            <button class="button tiny" type="submit">Filter</button>
            <a class="button tiny secondary" href="<?php echo h(url_for('listings.php')); ?>">Reset</a>
        </div>
    </form>
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
                        <p class="muted"><?php echo h($job['location']); ?> | Closes: <?php echo h($job['closing_date']); ?></p>
                        <?php if (!empty($job['company'])): ?>
                            <p class="muted"><?php echo h($job['company']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <p><?php echo nl2br(h(substr($job['description'], 0, 200))); ?><?php if (strlen($job['description']) > 200) echo '...'; ?></p>
                <div class="job-footer column">
                    <span class="badge"><?php echo h($job['salary']); ?></span>
                    <div class="inline-form">
                        <a class="button tiny" href="<?php echo h(url_for('job.php?id=' . (int)$job['id'])); ?>">View Job</a>
                        <?php if ($user && $user['role'] === 'admin'): ?>
                            <a class="button tiny secondary" href="<?php echo h(url_for('admin/jobs.php#job-' . (int)$job['id'])); ?>">Edit Job</a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
