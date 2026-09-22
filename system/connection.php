<?php
/**
 * Database Connection Handler
 * 
 * Establishes MySQLi connection using centralized config constants.
 * Sets proper charset, enables exception-based error reporting.
 * 
 * @package SabkeNews
 * @since 1.0.0
 */

// Ensure config is loaded
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/config.php';
}

// Enable MySQLi exception mode for proper error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Set charset to utf8mb4 for full Unicode support (emojis, special chars)
    $conn->set_charset(DB_CHARSET);
    
    // Set SQL mode for stricter data validation
    $conn->query("SET sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    
} catch (mysqli_sql_exception $e) {
    if (DEBUG_MODE) {
        die('Database Connection Error: ' . htmlspecialchars($e->getMessage()));
    } else {
        // Log error and show user-friendly page
        error_log('Database Connection Error: ' . $e->getMessage());
        http_response_code(503);
        die('<!DOCTYPE html><html><head><title>Service Unavailable</title></head><body><h1>Service Temporarily Unavailable</h1><p>Please try again later.</p></body></html>');
    }
}