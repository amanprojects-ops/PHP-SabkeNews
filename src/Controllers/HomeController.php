<?php
namespace App\Controllers;

class HomeController extends BaseController {
    public function index() {
        // Try to get cached posts from Redis
        $cacheKey = 'home:latest_posts';
        $posts = $this->redis->get($cacheKey);
        
        if (!$posts) {
            // Fetch latest posts from database
            $query = "SELECT * FROM `post` WHERE postStatus ='Y' ORDER BY last_date DESC LIMIT 8";
            $result = mysqli_query($this->conn, $query);
            $posts = [];
            
            while ($post = mysqli_fetch_assoc($result)) {
                $posts[] = [
                    'id' => $post['post_id'],
                    'title' => $post['title'],
                    'url' => $this->generateUrl($post['title']),
                    'last_date' => $post['last_date'],
                    'description' => substr(strip_tags($post['content']), 0, 160),
                    'image' => $post['featured_image'] ?? null
                ];
            }
            
            // Cache posts for 1 hour
            $this->redis->set($cacheKey, json_encode($posts), 'EX', 3600);
        } else {
            $posts = json_decode($posts, true);
        }
        
        // Get website settings
        $settings = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM settings"));
        
        // Prepare SEO metadata
        $seoData = [
            'title' => $settings['websitename'] . ' - Latest Updates',
            'description' => substr($settings['description'] ?? '', 0, 160),
            'og_type' => 'website',
            'og_image' => $settings['logo'] ?? '',
            'canonical' => 'https://' . $_SERVER['HTTP_HOST'] . '/',
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => $settings['websitename'],
                'url' => 'https://' . $_SERVER['HTTP_HOST'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => 'https://' . $_SERVER['HTTP_HOST'] . '/search?q={search_term_string}',
                    'query-input' => 'required name=search_term_string'
                ]
            ]
        ];
        
        return $this->view('home', [
            'posts' => $posts,
            'settings' => $settings,
            'seo' => $seoData
        ]);
    }
}