<?php
/**
 * Central Configuration File for PHP-SabkeNews
 * All site-wide settings and constants are defined here.
 * 
 * @package SabkeNews
 * @since 1.0.0
 */

// ─── Error Reporting ───────────────────────────────────────────────
// Set to true in development, false in production
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ─── Database Configuration ────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog');
define('DB_CHARSET', 'utf8mb4');

// ─── Site Configuration ────────────────────────────────────────────
// Auto-detect base URL or use environment variable
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('SITE_URL', getenv('SITE_URL') ?: $protocol . '://' . $host . '/PHP-SabkeNews');

// ─── Directory Paths ───────────────────────────────────────────────
define('ROOT_DIR', dirname(__DIR__));
define('SYSTEM_DIR', __DIR__);
define('ASSETS_DIR', ROOT_DIR . '/assets');
define('CACHE_DIR', ROOT_DIR . '/cache');
define('IMG_DIR', ROOT_DIR . '/img');

// ─── Cache Settings ────────────────────────────────────────────────
define('CACHE_ENABLED', true);
define('CACHE_TTL', 3600); // 1 hour in seconds

// ─── Timezone ──────────────────────────────────────────────────────
define('APP_TIMEZONE', 'Asia/Kolkata');
date_default_timezone_set(APP_TIMEZONE);

// ─── Session Configuration ─────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// ─── Visitor Logging ───────────────────────────────────────────────
define('VISITOR_LOG_ENABLED', true);
define('VISITOR_LOG_FILE', ROOT_DIR . '/visitor_logs.txt');
define('VISITOR_LOG_MAX_SIZE', 5 * 1024 * 1024); // 5 MB max log file size
