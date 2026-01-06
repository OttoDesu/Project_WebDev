<?php
require_once __DIR__ . '/includes/auth.php';
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="card">
    <h1>Contact Us</h1>
    <p class="muted">We would love to hear from you. Reach out for support, partnerships, or feedback.</p>
    <div class="grid two-up">
        <div class="stacked">
            <strong>Email</strong>
            <span class="muted">support@erecruitment.test</span>
            <strong>Phone</strong>
            <span class="muted">+60 12-345 6789</span>
            <strong>Address</strong>
            <span class="muted">123 Talent Avenue, Kuala Lumpur</span>
        </div>
        <form class="card" method="post">
            <label>
                Name
                <input name="name" placeholder="Your name">
            </label>
            <label>
                Email
                <input type="email" name="email" placeholder="you@example.com">
            </label>
            <label>
                Message
                <textarea name="message" placeholder="How can we help?"></textarea>
            </label>
            <button class="button" type="submit">Send Message</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
