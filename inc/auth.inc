<?php
// Shared session and authentication helpers for DTShop.

const SESSION_TIMEOUT = 900; // 15 minutes of inactivity.

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    // Harden PHP's session handling before starting the session.
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && $_SERVER['HTTPS'] !== 'off';

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,       
        'httponly' => true,      
        'samesite' => 'Strict'
    ]);

    session_start();
}

function set_no_store_headers(): void
{
    if (!headers_sent()) {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['last_activity'])
        && (int) $_SESSION['user_id'] > 0;
}

function create_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token($submitted_token): bool
{
    return is_string($submitted_token)
        && isset($_SESSION['csrf_token'])
        && is_string($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $submitted_token);
}

function destroy_authenticated_session(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Strict'
        ]);
    }

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

// Call before output on every protected page.
function require_login(): void
{
    start_secure_session();
    set_no_store_headers();

    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }

    if (time() - (int) $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        destroy_authenticated_session();
        header('Location: login.php?timeout=1');
        exit();
    }

    $_SESSION['last_activity'] = time();
}
