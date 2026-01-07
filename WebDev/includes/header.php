<?php
require_once __DIR__ . '/auth.php';
$user = current_user();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Board</title>
    <link rel="stylesheet" href="<?php echo h(url_for('assets/styles.css?v=2')); ?>">
</head>
<body>
<header class="site-header">
    <div class="logo">Job Board</div>
    <nav class="nav-links">
        <?php if (!$user): ?>
            <a href="<?php echo h(url_for('index.php')); ?>">Home</a>
        <?php endif; ?>

        <?php if ($user && $user['role'] === 'user'): ?>
            <a href="<?php echo h(url_for('user/dashboard.php')); ?>">Dashboard</a>
            <a href="<?php echo h(url_for('listings.php')); ?>">Job Listings</a>
            <a href="<?php echo h(url_for('about.php')); ?>">About Us</a>
            <a href="<?php echo h(url_for('contact.php')); ?>">Contact</a>
            <a href="<?php echo h(url_for('logout.php')); ?>" class="button secondary">Logout</a>
        <?php elseif ($user && $user['role'] === 'admin'): ?>
            <a href="<?php echo h(url_for('admin/index.php')); ?>">Dashboard</a>
            <a href="<?php echo h(url_for('listings.php')); ?>">Job Listings</a>
            <a href="<?php echo h(url_for('about.php')); ?>">About Us</a>
            <a href="<?php echo h(url_for('contact.php')); ?>">Contact</a>
            <a href="<?php echo h(url_for('logout.php')); ?>" class="button secondary">Logout</a>
        <?php else: ?>
            <a href="<?php echo h(url_for('listings.php')); ?>">Job Listings</a>
            <a href="<?php echo h(url_for('about.php')); ?>">About Us</a>
            <a href="<?php echo h(url_for('contact.php')); ?>">Contact</a>
            <a href="<?php echo h(url_for('login.php')); ?>" class="button">Login</a>
        <?php endif; ?>
    </nav>
</header>
<?php
$crumbs = breadcrumb_trail($breadcrumb_current ?? null);
if ($crumbs && count($crumbs) > 1): ?>
    <div class="breadcrumbs">
        <?php foreach ($crumbs as $i => $crumb): ?>
            <?php if (!empty($crumb['url']) && $i !== count($crumbs) - 1): ?>
                <a href="<?php echo h($crumb['url']); ?>"><?php echo h($crumb['label']); ?></a>
            <?php else: ?>
                <span class="crumb-current"><?php echo h($crumb['label']); ?></span>
            <?php endif; ?>
            <?php if ($i !== count($crumbs) - 1): ?>
                <span class="crumb-sep">/</span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<main class="page">
