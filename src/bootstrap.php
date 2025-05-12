<?php
require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use Predis\Client;

// Initialize Redis client for caching
$redis = new Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

// Configure routes
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', 'App\Controllers\HomeController@index');
    $r->addRoute('GET', '/post/{slug}/{id}', 'App\Controllers\PostController@show');
    $r->addRoute('GET', '/category/{slug}', 'App\Controllers\CategoryController@show');
});

// Cache configuration
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 3600); // 1 hour

// Function to generate static HTML cache
function generateStaticCache($content, $path) {
    if (!CACHE_ENABLED) return;
    
    $cachePath = __DIR__ . '/../cache/' . md5($path) . '.html';
    file_put_contents($cachePath, $content);
    
    global $redis;
    $redis->set('page:' . $path, $content, 'EX', CACHE_DURATION);
}

// Function to get cached content
function getCachedContent($path) {
    if (!CACHE_ENABLED) return null;
    
    global $redis;
    return $redis->get('page:' . $path);
}