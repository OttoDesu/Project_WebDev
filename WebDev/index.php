<?php
require_once __DIR__ . '/includes/auth.php';

$jobsCount = (int)$pdo->query('SELECT COUNT(*) FROM jobs')->fetchColumn();
$employersCount = (int)$pdo->query('SELECT COUNT(*) FROM users WHERE role = "admin"')->fetchColumn();
$seekersCount = (int)$pdo->query('SELECT COUNT(*) FROM users WHERE role = "user"')->fetchColumn();
$successRate = 95;
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="hero">
    <div class="hero-text">
        <p class="pill">Job Board · Discover Talent · Grow Careers</p>
        <h1>Discover Your Next Career Opportunity</h1>
        <p class="muted">Connect with top employers and explore a wide range of job openings across industries.</p>
        <div class="hero-actions">
            <a class="button" href="<?php echo h(url_for('listings.php')); ?>">Explore Opportunities</a>
        </div>
        <div class="hero-stats">
            <div><strong><?php echo number_format($jobsCount); ?>+</strong><span>Active Jobs</span></div>
            <div><strong><?php echo number_format($employersCount); ?>+</strong><span>Registered Employers</span></div>
            <div><strong><?php echo number_format($seekersCount); ?>+</strong><span>Job Seekers</span></div>
            <div><strong><?php echo $successRate; ?>%</strong><span>Success Rate</span></div>
        </div>
    </div>
    <div class="hero-card">
        <h3>Why Job Board</h3>
        <ul class="feature-list">
            <li>For Job Seekers: Track applications and manage searches in one place.</li>
            <li>For Employers: Find the right talent quickly and manage recruitment with ease.</li>
            <li>User-Friendly: Seamless matching and efficient application flows.</li>
        </ul>
         <div class="hero-actions">
            <a class="button" href="<?php echo h(url_for('register.php')); ?>">Register Now!</a>
        </div>
    </div>
</section><br>

<section class="card explore-card">
    <div class="explore-header">
        <div>
            <h2>Explore the Platform</h2>
            <p class="muted">A quick look at the core experiences for candidates and employers.</p>
        </div>
    </div>
    <div class="circle-row" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
        <div class="image-block">
            <div class="image-circle" style="width:220px;height:180px;border-radius:20%;overflow:hidden;display:inline-flex;align-items:center;justify-content:center;">
                <img src="assets/jobsearch.jpg" alt="Job search preview" style="width:100%;height:100%;object-fit:cover;border-radius:20%;display:block;">
            </div>
            <p class="muted">Job Search</p>
        </div>
        <div class="image-block">
            <div class="image-circle" style="width:220px;height:180px;border-radius:20%;overflow:hidden;display:inline-flex;align-items:center;justify-content:center;">
                <img src="assets/EmployerDashboard.png" alt="Employer dashboard" style="width:100%;height:100%;object-fit:cover;border-radius:20%;display:block;">
            </div>
            <p class="muted">Employer Dashboard</p>
        </div>
        <div class="image-block">
            <div class="image-circle" style="width:220px;height:180px;border-radius:20%;overflow:hidden;display:inline-flex;align-items:center;justify-content:center;">
                <img src="assets/CandidateProfile.jpg" alt="Candidate profile" style="width:100%;height:100%;object-fit:cover;border-radius:20%;display:block;">
            </div>
            <p class="muted">Candidate Profile</p>
        </div>
    </div>
</section><br>

<section class="card">
    <h2>How It Works</h2>
    <div class="grid two-up">
        <div class="stacked">
            <div class="card tone">
                <h3>Step 1</h3>
                <p class="muted">Sign up and create your profile (Job Seeker or Employer).</p>
            </div>
            <div class="card tone-alt">
                <h3>Step 2</h3>
                <p class="muted">Complete your profile (upload resume, company details, or job listings).</p>
            </div>
            <div class="card">
                <h3>Step 3</h3>
                <p class="muted">Start connecting and applying (explore jobs or receive applications).</p>
            </div>
        </div>
        <div class="hero-card">
            <h3>See it in action</h3>
            <div style="position:relative;padding-top:56.25%;border-radius:12px;overflow:hidden;border:1px solid var(--border);">
                <iframe src="https://www.youtube.com/embed/guXxy8LH2QM?si=qaSQOsZIYVAfTgPY" title="Platform overview"
                        style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;"
                        allowfullscreen></iframe>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>

