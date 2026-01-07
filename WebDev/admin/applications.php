<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');

$errors = [];
$success = null;

if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $appId = filter_input(INPUT_POST, 'app_id', FILTER_VALIDATE_INT);
        $status = $_POST['status'] ?? '';
        $allowed = ['Pending', 'Accepted', 'Rejected'];

        if (!$appId || !in_array($status, $allowed, true)) {
            $errors[] = 'Invalid request.';
        } else {
            $pdo->prepare('UPDATE applications SET status = ? WHERE id = ?')->execute([$status, $appId]);
            $success = 'Status updated.';
        }
    }
}

$stmt = $pdo->query('
    SELECT a.*, u.name AS user_name, u.email AS user_email, j.title, COALESCE(a.applied_at, a.created_at) AS applied_at
    FROM applications a
    JOIN users u ON a.user_id = u.id
    JOIN jobs j ON a.job_id = j.id
    ORDER BY applied_at DESC
');
$applications = $stmt->fetchAll();
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
        <h1>Applications</h1>
        <?php if ($errors): ?>
            <div class="error"><?php echo implode('<br>', array_map('h', $errors)); ?></div>
        <?php elseif ($success): ?>
            <div class="success"><?php echo h($success); ?></div>
        <?php endif; ?>

        <?php if (!$applications): ?>
            <p class="muted">No applications yet.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Job</th>
                    <th>Cover Letter</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Update</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                    <td data-label="Applicant">
                        <div class="stacked">
                            <strong><?php echo h($app['full_name'] ?: $app['user_name']); ?></strong>
                            <span class="muted"><?php echo h($app['email'] ?: $app['user_email']); ?></span>
                            <?php if (!empty($app['phone_number'])): ?>
                                <span class="muted">Phone: <?php echo h($app['phone_number']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($app['resume'])): ?>
                                <a href="<?php echo h(url_for($app['resume'])); ?>" target="_blank" rel="noopener" class="button small secondary">View Resume</a>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td data-label="Job"><?php echo h($app['title']); ?></td>
                    <td data-label="Cover Letter"><?php echo nl2br(h($app['cover_letter'])); ?></td>
                    <td data-label="Status">
                        <span class="status <?php echo strtolower(h($app['status'])); ?>"><?php echo h($app['status']); ?></span>
                    </td>
                    <td data-label="Submitted"><?php echo h($app['applied_at']); ?></td>
                        <td data-label="Update">
                            <form class="inline-form" method="post">
                                <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
                                <input type="hidden" name="app_id" value="<?php echo (int)$app['id']; ?>">
                                <select name="status">
                                    <?php foreach (['Pending', 'Accepted', 'Rejected'] as $option): ?>
                                        <option value="<?php echo h($option); ?>" <?php if ($app['status'] === $option) echo 'selected'; ?>>
                                            <?php echo h($option); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="button small" type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
