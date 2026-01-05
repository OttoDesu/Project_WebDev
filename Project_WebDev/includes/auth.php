<?php
session_start();

function is_logged_in(): bool {
    return isset($_SESSION['user']);
}

function require_login(string $role = null): void {
    if (!is_logged_in()) {
        header('Location: /login.php');
        exit;
    }
    if ($role && ($_SESSION['user']['role'] ?? '') !== $role) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
