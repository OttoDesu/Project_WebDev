<?php
require_once __DIR__ . '/includes/auth.php';
logout_user();
$home = url_for('index.php');
header("Location: {$home}");
exit;
