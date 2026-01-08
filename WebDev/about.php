<?php
require_once __DIR__ . '/includes/auth.php';
$user = current_user();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h1>About Us</h1>
    <div class="banner">
        <div>
            <p class="muted">Job Board connects employers and applicants with a streamlined hiring flow, secure access, and a responsive experience across devices.</p>
            <h3>Our Vision</h3>
            <p>Transparent, efficient hiring that empowers teams to find the right talent faster.</p>
            <h3>Our Mission</h3>
            <p>Provide a secure, intuitive platform for posting roles, applying with confidence, and collaborating on hiring decisions.</p>
        </div>
        <div>
            <div class="image-placeholder">
                <span>
                    <img src="AboutUs" onerror="this.src='assets/team&office.jpg';" />
                </span>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
