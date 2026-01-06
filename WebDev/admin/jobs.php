<?php
require_once __DIR__ . '/../includes/auth.php';
require_role('admin');

$errors = [];
$success = null;

if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $action = $_POST['action'] ?? '';
        $title = trim($_POST['title'] ?? '');
        $salary = trim($_POST['salary'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $closing = $_POST['closing_date'] ?? '';

        if ($action === 'create') {
            if (strlen($title) < 3) {
                $errors[] = 'Title is required.';
            }
            if (strlen($description) < 10) {
                $errors[] = 'Description is required.';
            }

            if (!$errors) {
                $stmt = $pdo->prepare('INSERT INTO jobs (title, description, salary, location, closing_date, created_by) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([
                    $title,
                    $description,
                    $salary,
                    $location,
                    $closing ?: null,
                    current_user()['id'],
                ]);
                $success = 'Job created.';
            }
        } elseif ($action === 'update') {
            $jobId = filter_input(INPUT_POST, 'job_id', FILTER_VALIDATE_INT);
            if (!$jobId) {
                $errors[] = 'Invalid job.';
            }
            if (strlen($title) < 3 || strlen($description) < 10) {
                $errors[] = 'Title and description are required.';
            }

            if (!$errors) {
                $stmt = $pdo->prepare('UPDATE jobs SET title = ?, description = ?, salary = ?, location = ?, closing_date = ? WHERE id = ?');
                $stmt->execute([$title, $description, $salary, $location, $closing ?: null, $jobId]);
                $success = 'Job updated.';
            }
        } elseif ($action === 'delete') {
            $jobId = filter_input(INPUT_POST, 'job_id', FILTER_VALIDATE_INT);
            if ($jobId) {
                $pdo->prepare('DELETE FROM jobs WHERE id = ?')->execute([$jobId]);
                $success = 'Job deleted.';
            } else {
                $errors[] = 'Invalid job id.';
            }
        }
    }
}

$jobs = $pdo->query('SELECT * FROM jobs ORDER BY created_at DESC')->fetchAll();
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="layout-shell">
    <aside class="sidebar">
        <h3>Admin</h3>
        <nav>
            <a href="<?php echo h(url_for('admin/index.php')); ?>">Dashboard</a>
            <a href="<?php echo h(url_for('admin/jobs.php')); ?>">Manage Jobs</a>
            <a href="<?php echo h(url_for('admin/applications.php')); ?>">Manage Applications</a>
            <a href="<?php echo h(url_for('logout.php')); ?>">Logout</a>
        </nav>
    </aside>
    <div class="stacked">
        <section class="card">
            <h1>Manage Jobs</h1>
            <?php if ($errors): ?>
                <div class="error"><?php echo implode('<br>', array_map('h', $errors)); ?></div>
            <?php elseif ($success): ?>
                <div class="success"><?php echo h($success); ?></div>
            <?php endif; ?>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
                <input type="hidden" name="action" value="create">
                <div class="grid">
                    <label>
                        Title
                        <input name="title" required>
                    </label>
                    <label>
                        Salary
                        <input name="salary" placeholder="$80k - $110k">
                    </label>
                    <label>
                        Location
                        <input name="location" placeholder="Remote / City">
                    </label>
                    <label>
                        Closing Date
                        <input type="date" name="closing_date">
                    </label>
                </div>
                <label>
                    Description
                    <textarea name="description" required placeholder="Role overview, responsibilities, requirements"></textarea>
                </label>
                <button class="button" type="submit">Create Job</button>
            </form>
        </section>

        <section class="card">
            <h2>Job Listings</h2>
            <?php if (!$jobs): ?>
                <p class="muted">No jobs available.</p>
            <?php else: ?>
                <table>
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Salary</th>
                        <th>Closing</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td data-label="Title"><?php echo h($job['title']); ?></td>
                            <td data-label="Location"><?php echo h($job['location']); ?></td>
                            <td data-label="Salary"><?php echo h($job['salary']); ?></td>
                            <td data-label="Closing"><?php echo h($job['closing_date']); ?></td>
                            <td data-label="Actions">
                                <details>
                                    <summary class="button small secondary">Edit</summary>
                                    <form class="stacked" method="post">
                                        <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="job_id" value="<?php echo (int)$job['id']; ?>">
                                        <label>
                                            Title
                                            <input name="title" required value="<?php echo h($job['title']); ?>">
                                        </label>
                                        <label>
                                            Salary
                                            <input name="salary" value="<?php echo h($job['salary']); ?>">
                                        </label>
                                        <label>
                                            Location
                                            <input name="location" value="<?php echo h($job['location']); ?>">
                                        </label>
                                        <label>
                                            Closing Date
                                            <input type="date" name="closing_date" value="<?php echo h($job['closing_date']); ?>">
                                        </label>
                                        <label>
                                            Description
                                            <textarea name="description" required><?php echo h($job['description']); ?></textarea>
                                        </label>
                                        <button class="button small" type="submit">Save</button>
                                    </form>
                                </details>
                                <form class="inline-form" method="post" onsubmit="return confirm('Delete this job?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="job_id" value="<?php echo (int)$job['id']; ?>">
                                    <button class="button small danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
