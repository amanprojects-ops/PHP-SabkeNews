<?php
/**
 * 404 Not Found Error Page
 * 
 * @package SabkeNews
 * @since 1.0.0
 */

// Load config for SITE_URL
require_once __DIR__ . '/system/config.php';
$siteUrl = SITE_URL;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | SabkeNews</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #1e1549 0%, #2e67d9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            text-shadow: 4px 4px 0 rgba(0,0,0,0.2);
            margin-bottom: 1rem;
            animation: pulse 2s ease-in-out infinite;
        }
        .error-title {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            opacity: 0.95;
        }
        .error-message {
            font-size: 1.1rem;
            opacity: 0.8;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .home-btn {
            display: inline-block;
            padding: 0.9rem 2.5rem;
            background: #fff;
            color: #1e1549;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .home-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            background: #f0f0f0;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1) }
            50% { transform: scale(1.05) }
        }
        @media (max-width: 480px) {
            .error-code { font-size: 5rem }
            .error-title { font-size: 1.3rem }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">
            The page you're looking for doesn't exist or has been moved. 
            Don't worry, let's get you back on track!
        </p>
        <a href="<?= $siteUrl ?>" class="home-btn">← Back to Home</a>
    </div>
</body>
</html>
