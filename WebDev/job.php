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
$breadcrumb_current = $job ? $job['title'] : null;

function render_list_lines($text)
{
    $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string)$text)));
    if (!$lines) {
        return '';
    }

    $html = '<ol class="stacked-list">';
    foreach ($lines as $line) {
        $html .= '<li>' . h($line) . '</li>';
    }
    $html .= '</ol>';
    return $html;
}

if (!$job) {
    http_response_code(404);
    exit('Job not found.');
}

$user = current_user();
$errors = [];
$success = null;
$lastResume = null;

if ($user) {
    $lastResumeStmt = $pdo->prepare('SELECT resume FROM applications WHERE user_id = ? AND resume IS NOT NULL ORDER BY applied_at DESC, created_at DESC LIMIT 1');
    $lastResumeStmt->execute([$user['id']]);
    $lastResume = $lastResumeStmt->fetchColumn() ?: null;
}

if (is_post()) {
    if (!$user || $user['role'] !== 'user') {
        $errors[] = 'You must be logged in as an applicant to apply.';
    } elseif (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phone = trim($_POST['phone_number'] ?? '');
        $cover = trim($_POST['cover_letter'] ?? '');
        $resumeOption = $_POST['resume_option'] ?? 'upload';
        $resumePath = null;

        if (strlen($fullName) < 2) {
            $errors[] = 'Full name is required.';
        }
        if (!$email) {
            $errors[] = 'Valid email is required.';
        }
        if (strlen($phone) < 6) {
            $errors[] = 'Phone number is required.';
        }

        if ($resumeOption === 'existing') {
            if ($lastResume) {
                $resumePath = $lastResume;
            } else {
                $errors[] = 'No existing resume found. Please upload a resume.';
            }
        } else {
            if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Resume upload is required.';
            } else {
                $allowed = ['pdf', 'doc', 'docx'];
                $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed, true)) {
                    $errors[] = 'Resume must be a PDF, DOC, or DOCX.';
                }
                if ($_FILES['resume']['size'] > 5 * 1024 * 1024) {
                    $errors[] = 'Resume must be under 5MB.';
                }
                if (!$errors) {
                    $targetDir = __DIR__ . '/uploads/resumes/';
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0775, true);
                    }
                    $filename = 'resume_' . $user['id'] . '_' . time() . '.' . $ext;
                    $destPath = $targetDir . $filename;
                    if (!move_uploaded_file($_FILES['resume']['tmp_name'], $destPath)) {
                        $errors[] = 'Failed to save resume. Please try again.';
                    } else {
                        $resumePath = 'uploads/resumes/' . $filename;
                    }
                }
            }
        }

        if (!$errors) {
            $already = $pdo->prepare('SELECT id FROM applications WHERE user_id = ? AND job_id = ?');
            $already->execute([$user['id'], $job['id']]);
            if ($already->fetch()) {
                $errors[] = 'You already applied for this job.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO applications (user_id, job_id, full_name, email, phone_number, resume, cover_letter, status, applied_at) VALUES (?, ?, ?, ?, ?, ?, ?, "Pending", NOW())');
                $stmt->execute([$user['id'], $job['id'], $fullName, $email, $phone, $resumePath, $cover]);
                $success = 'Application submitted.';
                $lastResume = $resumePath ?: $lastResume;
            }
        }
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h1><?php echo h($job['title']); ?></h1>
    <p class="muted"><?php echo h($job['location']); ?> | Salary: <?php echo h($job['salary']); ?></p>
    <?php if (!empty($job['company'])): ?>
        <p class="muted"><?php echo h($job['company']); ?></p>
    <?php endif; ?>
    <?php if ($job['closing_date']): ?>
        <p class="badge">Closing Date: <?php echo h($job['closing_date']); ?></p>
    <?php endif; ?>
    <p><?php echo nl2br(h($job['description'])); ?></p>
    <?php if (!empty($job['requirements'])): ?>
        <h3>Requirements</h3>
        <?php echo render_list_lines($job['requirements']); ?>
    <?php endif; ?>
    <?php if (!empty($job['benefits'])): ?>
        <h3>Benefits</h3>
        <?php echo render_list_lines($job['benefits']); ?>
    <?php endif; ?>
</section><br>

<section class="card">
    <h2>Apply for this job</h2>
    <?php $showForm = is_post() && ($errors || $success || $user); ?>
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
        <button class="button" type="button" id="start-application">Start Application</button>
        <form method="post" enctype="multipart/form-data" id="application-form" style="display: <?php echo $showForm ? 'block' : 'none'; ?>; margin-top: 16px;">
            <input type="hidden" name="csrf_token" value="<?php echo h(get_csrf_token()); ?>">
            <label>
                Full Name
                <input name="full_name" required value="<?php echo h($_POST['full_name'] ?? ($user['name'] ?? '')); ?>">
            </label>
            <label>
                Email Address
                <input type="email" name="email" required value="<?php echo h($_POST['email'] ?? ($user['email'] ?? '')); ?>">
            </label>
            <label>
                Phone Number
                <input name="phone_number" required value="<?php echo h($_POST['phone_number'] ?? ''); ?>">
            </label>
            <fieldset class="card resume-options">
                <legend>Resume (PDF/DOC/DOCX)</legend>
                <div class="resume-items">
                    <?php if ($lastResume): ?>
                        <label class="resume-item">
                            <input type="radio" name="resume_option" value="existing" <?php echo ($_POST['resume_option'] ?? '') === 'existing' ? 'checked' : ''; ?>>
                            <div>
                                <strong>Use existing resume</strong>
                                <p class="muted">Existing: <a href="<?php echo h(url_for($lastResume)); ?>" target="_blank" rel="noopener">View current resume</a></p>
                            </div>
                        </label>
                        <label class="resume-item">
                            <input type="radio" name="resume_option" value="upload" <?php echo (!isset($_POST['resume_option']) || ($_POST['resume_option'] ?? '') === 'upload') ? 'checked' : ''; ?>>
                            <div>
                                <strong>Upload new resume</strong>
                                <input type="file" name="resume" accept=".pdf,.doc,.docx">
                            </div>
                        </label>
                    <?php else: ?>
                        <p class="muted">Upload your resume</p>
                        <input type="hidden" name="resume_option" value="upload">
                        <input type="file" name="resume" accept=".pdf,.doc,.docx">
                    <?php endif; ?>
                </div>
            </fieldset>
            <label>
                Cover Letter / Notes
                <textarea name="cover_letter" minlength="0" placeholder="Highlight your fit and availability..."><?php echo h($_POST['cover_letter'] ?? ''); ?></textarea>
            </label>
            <button class="button" type="submit">Submit Application</button>
        </form>
    <?php endif; ?>
</section>
<script>
    const startBtn = document.getElementById('start-application');
    const form = document.getElementById('application-form');
    if (startBtn && form) {
        startBtn.addEventListener('click', () => {
            form.style.display = 'block';
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>

