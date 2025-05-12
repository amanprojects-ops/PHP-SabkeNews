<?php
namespace App\Controllers;

class BaseController {
    protected $conn;
    protected $redis;
    
    public function __construct() {
        global $conn, $redis;
        $this->conn = $conn;
        $this->redis = $redis;
    }
    
    protected function view($template, $data = []) {
        // Extract data to make variables available in template
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include header
        require __DIR__ . '/../../header.php';
        
        // Include main template
        require __DIR__ . '/../../views/' . $template . '.php';
        
        // Include footer
        require __DIR__ . '/../../footer.php';
        
        // Get buffered content and clean buffer
        return ob_get_clean();
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        return json_encode($data);
    }
    
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    
    protected function generateUrl($string) {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);
        $string = trim($string, '-');
        return $string;
    }
}