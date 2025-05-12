<?php
require __DIR__ . '/../src/bootstrap.php';

// Get current request URI and method
$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

// Strip query string and decode URI
$uri = rawurldecode(parse_url($uri, PHP_URL_PATH));

// Check for cached version first
if ($cachedContent = getCachedContent($uri)) {
    echo $cachedContent;
    exit;
}

// Dispatch route
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header('HTTP/1.0 404 Not Found');
        require __DIR__ . '/404.php';
        break;
        
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        header('HTTP/1.0 405 Method Not Allowed');
        echo 'Method not allowed';
        break;
        
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        
        // Parse handler string to get controller and method
        list($controller, $method) = explode('@', $handler);
        
        // Initialize controller and call method
        $controller = new $controller();
        $response = $controller->$method($vars);
        
        // Generate static cache for GET requests
        if ($httpMethod === 'GET') {
            generateStaticCache($response, $uri);
        }
        
        echo $response;
        break;
}