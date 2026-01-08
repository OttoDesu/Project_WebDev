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
        $company = trim($_POST['company'] ?? '');
        $salary = trim($_POST['salary'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $requirements = trim($_POST['requirements'] ?? '');
        $benefits = trim($_POST['benefits'] ?? '');
        $closing = $_POST['closing_date'] ?? '';

        if ($action === 'create') {
            if (strlen($title) < 3) {
                $errors[] = 'Title is required.';
            }
            if (strlen($description) < 10) {
                $errors[] = 'Description is required.';
            }
            if (strlen($company) < 2) {
                $errors[] = 'Company is required.';
            }

            if (!$errors) {
                $stmt = $pdo->prepare('INSERT INTO jobs (title, description, company, salary, location, closing_date, requirements, benefits, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([
                    $title,
                    $description,
                    $company,
                    $salary,
                    $location,
                    $closing ?: null,
                    $requirements,
                    $benefits,
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
            if (strlen($company) < 2) {
                $errors[] = 'Company is required.';
            }

            if (!$errors) {
                $stmt = $pdo->prepare('UPDATE jobs SET title = ?, description = ?, company = ?, salary = ?, location = ?, closing_date = ?, requirements = ?, benefits = ? WHERE id = ?');
                $stmt->execute([$title, $description, $company, $salary, $location, $closing ?: null, $requirements, $benefits, $jobId]);
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
            <a href="<?php echo h(url_for('admin/messages.php')); ?>">Contact Messages</a>
            <a href="<?php echo h(url_for('admin/profile.php')); ?>">Profile</a>
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
            <form class="stacked-lg" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
                <input type="hidden" name="action" value="create">
                <div class="grid-form">
                    <label>
                        Title
                        <input name="title" required>
                    </label>
                    <label>
                        Company
                        <input name="company" required placeholder="Company name">
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
                <div class="grid-form">
                    <label>
                        Requirements
                        <textarea name="requirements" placeholder="One requirement per line; numbering added automatically"></textarea>
                    </label>
                    <label>
                        Benefits
                        <textarea name="benefits" placeholder="One benefit per line; numbering added automatically"></textarea>
                    </label>
                </div>
                <button class="button" type="submit">Create Job</button>
            </form>
        </section><br>

        <section class="card">
            <h2>Job Listings</h2>
            <?php if (!$jobs): ?>
                <p class="muted">No jobs available.</p>
            <?php else: ?>
                <table class="jobs-table">
                    <thead>
                    <tr>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Closing</th>
                    <th>Actions</th>
                </tr>
                    </thead>
                    <tbody>
            <?php foreach ($jobs as $job): ?>
                <tr id="job-<?php echo (int)$job['id']; ?>">
                    <td data-label="Title"><?php echo h($job['title']); ?></td>
                    <td data-label="Company"><?php echo h($job['company']); ?></td>
                    <td data-label="Location"><?php echo h($job['location']); ?></td>
                    <td data-label="Salary"><?php echo h($job['salary']); ?></td>
                    <td data-label="Closing"><?php echo h($job['closing_date']); ?></td>
                    <td data-label="Actions">
                        <button type="button" class="button small secondary" onclick="showEditForm(<?php echo (int)$job['id']; ?>)">Edit</button>
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

<template id="edit-form-template">
    <form class="card stacked-lg edit-form" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="job_id" value="">
        <div class="grid-form">
            <label>
                Title
                <input name="title" required>
            </label>
            <label>
                Company
                <input name="company" required>
            </label>
            <label>
                Salary
                <input name="salary">
            </label>
            <label>
                Location
                <input name="location">
            </label>
            <label>
                Closing Date
                <input type="date" name="closing_date">
            </label>
        </div>
        <label>
            Description
            <textarea name="description" required></textarea>
        </label>
        <div class="grid-form">
            <label>
                Requirements
                <textarea name="requirements" placeholder="One requirement per line; numbering added automatically"></textarea>
            </label>
            <label>
                Benefits
                <textarea name="benefits" placeholder="One benefit per line; numbering added automatically"></textarea>
            </label>
        </div>
        <div class="inline-form">
            <button class="button" type="submit">Save</button>
            <button class="button secondary" type="button" onclick="closeEditForm()">Cancel</button>
        </div>
    </form>
</template>

<script>
    const jobsData = <?php echo json_encode($jobs); ?>;
    const container = document.querySelector('.stacked');
    let editFormEl = null;

    function closeEditForm() {
        if (editFormEl) {
            editFormEl.remove();
            editFormEl = null;
        }
    }

    function showEditForm(id) {
        const job = jobsData.find(j => Number(j.id) === Number(id));
        if (!job) return;

        closeEditForm();
        const tmpl = document.getElementById('edit-form-template');
        editFormEl = tmpl.content.firstElementChild.cloneNode(true);

        editFormEl.querySelector('input[name="job_id"]').value = job.id;
        editFormEl.querySelector('input[name="title"]').value = job.title || '';
        editFormEl.querySelector('input[name="company"]').value = job.company || '';
        editFormEl.querySelector('input[name="salary"]').value = job.salary || '';
        editFormEl.querySelector('input[name="location"]').value = job.location || '';
        editFormEl.querySelector('input[name="closing_date"]').value = job.closing_date || '';
        editFormEl.querySelector('textarea[name="description"]').value = job.description || '';
        editFormEl.querySelector('textarea[name="requirements"]').value = job.requirements || '';
        editFormEl.querySelector('textarea[name="benefits"]').value = job.benefits || '';

        container.insertBefore(editFormEl, container.querySelector('.card:nth-of-type(2)'));
        editFormEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
