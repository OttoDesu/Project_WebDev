<?php
require_once __DIR__ . '/includes/auth.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Invalid job.');
}

$stmt = $pdo->prepare('SELECT * FROM jobs WHERE id = ?');
$stmt->execute([$id]);
$job = $stmt->fetch();

if (!$job) {
    http_response_code(404);
    exit('Job not found.');
}

$user = current_user();
$errors = [];
$success = null;

if (is_post()) {
    if (!$user || $user['role'] !== 'user') {
        $errors[] = 'You must be logged in as an applicant to apply.';
    } elseif (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $cover = trim($_POST['cover_letter'] ?? '');
        if (strlen($cover) < 10) {
            $errors[] = 'Cover letter must be at least 10 characters.';
        } else {
            $already = $pdo->prepare('SELECT id FROM applications WHERE user_id = ? AND job_id = ?');
            $already->execute([$user['id'], $job['id']]);
            if ($already->fetch()) {
                $errors[] = 'You already applied for this job.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO applications (user_id, job_id, cover_letter, status) VALUES (?, ?, ?, "Pending")');
                $stmt->execute([$user['id'], $job['id'], $cover]);
                $success = 'Application submitted.';
            }
        }
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h1><?php echo h($job['title']); ?></h1>
    <p class="muted"><?php echo h($job['location']); ?> · Salary: <?php echo h($job['salary']); ?></p>
    <?php if ($job['closing_date']): ?>
        <p class="badge">Closing Date: <?php echo h($job['closing_date']); ?></p>
    <?php endif; ?>
    <p><?php echo nl2br(h($job['description'])); ?></p>
</section>

<section class="card">
    <h2>Apply for this job</h2>
    <?php if ($errors): ?>
        <div class="error"><?php echo implode('<br>', array_map('h', $errors)); ?></div>
    <?php elseif ($success): ?>
        <div class="success"><?php echo h($success); ?></div>
    <?php endif; ?>

    <?php if (!$user): ?>
        <p>Please <a href="<?php echo h(url_for('login.php')); ?>">login</a> or <a href="<?php echo h(url_for('register.php')); ?>">register</a> to apply.</p>
    <?php elseif ($user['role'] !== 'user'): ?>
        <p>Only applicant accounts can apply for jobs.</p>
    <?php else: ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
            <label>
                Cover Letter / Notes
                <textarea name="cover_letter" required minlength="10" placeholder="Highlight your fit and availability..."></textarea>
            </label>
            <button class="button" type="submit">Submit Application</button>
        </form>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
