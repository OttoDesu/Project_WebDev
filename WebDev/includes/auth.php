<?php
require_once __DIR__ . '/../config.php';

function h($string)
{
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

function is_post()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function get_csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

function base_path()
{
    static $base;
    if ($base !== null) {
        return $base;
    }

    // First try to infer from the current script path (works when accessed via /WebDev/... URLs).
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $base = rtrim(preg_replace('#/(admin|user)(/.*)?$#', '', $scriptDir), '/');

    // If app is served from a subfolder but user accessed via root (e.g., http://localhost/login.php),
    // fall back to deriving base relative to the document root.
    if ($base === '' && !empty($_SERVER['DOCUMENT_ROOT'])) {
        $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
        $appRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
        if ($docRoot && $appRoot && strpos($appRoot, $docRoot) === 0) {
            $derived = substr($appRoot, strlen($docRoot));
            $base = rtrim($derived, '/');
        }
    }

    return $base;
}

function url_for($path)
{
    $root = base_path();
    $trimmed = '/' . ltrim($path, '/');
    return ($root === '') ? $trimmed : $root . $trimmed;
}

function current_path()
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $base = base_path();
    if ($base && str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base));
    }
    $uri = ltrim($uri, '/');
    if ($uri === '' || $uri === false) {
        $uri = 'index.php';
    }
    return $uri;
}

function breadcrumb_trail($currentLabel = null)
{
    $path = current_path();
    $trail = [
        ['label' => 'Home', 'url' => url_for('index.php')],
    ];

    $admin = str_starts_with($path, 'admin/');
    $user = str_starts_with($path, 'user/');

    if ($admin) {
        $trail[] = ['label' => 'Admin Dashboard', 'url' => url_for('admin/index.php')];
        if ($path === 'admin/jobs.php') {
            $trail[] = ['label' => 'Manage Jobs'];
        } elseif ($path === 'admin/applications.php') {
            $trail[] = ['label' => 'Manage Applications'];
        } elseif ($path === 'admin/messages.php') {
            $trail[] = ['label' => 'Contact Messages'];
        } elseif ($path === 'admin/profile.php') {
            $trail[] = ['label' => 'Admin Profile'];
        }
    } elseif ($user) {
        $trail[] = ['label' => 'Applicant Dashboard', 'url' => url_for('user/dashboard.php')];
        if ($path === 'user/applications.php') {
            $trail[] = ['label' => 'My Applications'];
        } elseif ($path === 'profile.php' || $path === 'user/profile.php') {
            $trail[] = ['label' => 'Profile'];
        }
    } else {
        if ($path === 'listings.php') {
            $trail[] = ['label' => 'Job Listings'];
        } elseif ($path === 'job.php') {
            $trail[] = ['label' => 'Job Listings', 'url' => url_for('listings.php')];
        } elseif ($path === 'about.php') {
            $trail[] = ['label' => 'About Us'];
        } elseif ($path === 'contact.php') {
            $trail[] = ['label' => 'Contact'];
        } elseif ($path === 'login.php') {
            $trail[] = ['label' => 'Login'];
        } elseif ($path === 'register.php') {
            $trail[] = ['label' => 'Register'];
        }
    }

    if ($currentLabel) {
        $trail[] = ['label' => $currentLabel];
    }

    // Deduplicate sequential same labels
    $deduped = [];
    foreach ($trail as $crumb) {
        if ($deduped && $deduped[count($deduped) - 1]['label'] === $crumb['label']) {
            continue;
        }
        $deduped[] = $crumb;
    }

    return $deduped;
}

function current_user()
{
    if (!empty($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    return null;
}

function require_login()
{
    if (!current_user()) {
        header('Location: /login.php');
        exit;
    }
}

function require_role($role)
{
    require_login();

    $user = current_user();
    if ($user['role'] !== $role) {
        http_response_code(403);
        exit('Access denied.');
    }
}

function login_user($user)
{
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];
}

function logout_user()
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
