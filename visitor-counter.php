<?php
/**
 * Lightweight Visitor Counter & Logger
 * 
 * Logs basic visitor info to a text file.
 * 
 * OPTIMIZED: Removed 3 synchronous external API calls per page load
 * (ipify.org, geoplugin.net, ip-api.com) that were adding 2-5 seconds
 * to every page load. Also removed ARP MAC address resolution
 * (security risk + doesn't work on web servers).
 * 
 * Now uses only locally available server variables — zero external calls.
 * 
 * @package SabkeNews
 * @since 1.0.0
 */

// Only log if enabled in config
if (!defined('VISITOR_LOG_ENABLED') || !VISITOR_LOG_ENABLED) {
    return;
}

// ─── Rate Limiting: Max 1 log per IP per 5 minutes ─────────────────
$visitorIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateLimitKey = 'visitor_' . md5($visitorIp);

// Use session-based rate limiting (no external dependency)
if (isset($_SESSION[$rateLimitKey]) && (time() - $_SESSION[$rateLimitKey]) < 300) {
    return; // Already logged within last 5 minutes
}
$_SESSION[$rateLimitKey] = time();

// ─── Log File Size Check ───────────────────────────────────────────
$logFile = defined('VISITOR_LOG_FILE') ? VISITOR_LOG_FILE : __DIR__ . '/visitor_logs.txt';
$maxSize = defined('VISITOR_LOG_MAX_SIZE') ? VISITOR_LOG_MAX_SIZE : 5 * 1024 * 1024;

if (file_exists($logFile) && filesize($logFile) > $maxSize) {
    // Rotate log: rename old, start fresh
    $backupFile = $logFile . '.' . date('Y-m-d-His') . '.bak';
    @rename($logFile, $backupFile);
}

// ─── Collect Visitor Data (local only — no external API calls) ─────
$logEntry = [
    'timestamp'  => date('Y-m-d H:i:s'),
    'ip'         => $visitorIp,
    'method'     => $_SERVER['REQUEST_METHOD'] ?? 'GET',
    'page'       => $_SERVER['REQUEST_URI'] ?? '/',
    'referrer'   => $_SERVER['HTTP_REFERER'] ?? 'Direct',
    'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 200), // Limit UA length
];

// ─── Write to Log File ─────────────────────────────────────────────
@file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);