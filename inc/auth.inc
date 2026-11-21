<?php
// inc/auth.inc - shared session and login protection helpers.

const SESSION_TIMEOUT = 900; // 15 minutes of inactivity

function start_secure_session() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $https = !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off";
    session_set_cookie_params([
        "lifetime" => 0,          // cookie dies when the browser closes
        "path"     => "/",
        "secure"   => $https,     // only sent over HTTPS when the site uses it
        "httponly" => true,       // JavaScript cannot read the session cookie
        "samesite" => "Strict"    // cookie is not sent on cross-site requests
    ]);
    session_start();
}

function is_logged_in() {
    return isset($_SESSION["user_id"]);
}

// Call at the very top of a protected page, before any HTML is sent.
function require_login() {
    start_secure_session();

    // Stop browsers caching the protected page (back button after logout).
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Pragma: no-cache");

    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }

    if (isset($_SESSION["last_activity"]) &&
        time() - $_SESSION["last_activity"] > SESSION_TIMEOUT) {
        $_SESSION = [];
        session_destroy();
        header("Location: login.php?timeout=1");
        exit();
    }
    $_SESSION["last_activity"] = time();
}
