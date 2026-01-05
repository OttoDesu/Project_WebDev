<?php require_once __DIR__ . '/includes/header.php'; ?>
<section class="hero">
    <div class="card">
        <p class="badge">Community Races</p>
        <h1>Find your next run with <span style="color: var(--accent)">We Run</span></h1>
        <p>Browse curated races, register instantly, and manage your profile. Administrators can control events, runners, and registrations from a secure dashboard.</p>
        <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:12px;">
            <a href="<?php echo $appBase; ?>/events.php" class="btn-primary">See Events</a>
            <a href="<?php echo $appBase; ?>/signup.php" class="btn-secondary">Join as Runner</a>
        </div>
        <div class="media-strip" aria-label="Running visuals">
            <img src="<?php echo $appBase; ?>/assets/hero.svg" alt="Runners" loading="lazy">
            <img src="<?php echo $appBase; ?>/assets/trail.svg" alt="Trail" loading="lazy">
            <img src="<?php echo $appBase; ?>/assets/pace.svg" alt="Pace chart" loading="lazy">
            <img src="<?php echo $appBase; ?>/assets/route.svg" alt="Route" loading="lazy">
            <img src="<?php echo $appBase; ?>/assets/go.svg" alt="Go" loading="lazy">
        </div>
    </div>
    <div class="card">
        <h3>Why We Run?</h3>
        <ul>
            <li>Real-time availability and capacity limits.</li>
            <li>Secure logins for admins and participants.</li>
            <li>Clear event details: date, venue, distance, fees.</li>
            <li>Responsive design that works on any device.</li>
        </ul>
        <h4>Admin superpowers</h4>
        <p>Manage events, categories, and participants, update statuses, and export records.</p>
        <h4>Runner experience</h4>
        <p>Save your profile, register for races, and get confirmations instantly.</p>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
