<?php
require_once __DIR__ . '/auth.php';
// Derive base URL from filesystem paths so assets work even inside subfolders (/admin, etc.).
$docRoot = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));
$projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
$appBase = str_replace($docRoot, '', $projectRoot);
if ($appBase === '' || $appBase === false) { $appBase = ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We Run</title>
    <link rel="stylesheet" href="<?php echo $appBase; ?>/assets/style.css">
    <script defer src="<?php echo $appBase; ?>/assets/app.js"></script>
</head>
<body>
<header class="topbar">
    <div class="brand">We Run</div>
    <nav>
        <a href="<?php echo $appBase; ?>/index.php">Home</a>
        <a href="<?php echo $appBase; ?>/events.php">Events</a>
        <a href="<?php echo $appBase; ?>/my_registrations.php">My Runs</a>
        <?php if (is_logged_in()): ?>
            <a href="<?php echo $appBase; ?>/profile.php">Profile</a>
            <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
                <a href="<?php echo $appBase; ?>/admin/dashboard.php">Admin</a>
            <?php endif; ?>
            <a href="<?php echo $appBase; ?>/logout.php" class="btn">Logout</a>
        <?php else: ?>
            <a href="<?php echo $appBase; ?>/login.php" class="btn">Login</a>
            <a href="<?php echo $appBase; ?>/signup.php" class="btn ghost">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>
<main class="page">
