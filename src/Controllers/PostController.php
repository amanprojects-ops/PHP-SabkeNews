<?php
namespace App\Controllers;

class PostController extends BaseController {
    public function show($slug, $id) {
        // Try to get cached post from Redis
        $cacheKey = "post:{$id}";
        $post = $this->redis->get($cacheKey);
        
        if (!$post) {
            // Fetch post from database
            $query = "SELECT p.*, c.name as category_name, c.url as category_url 
                      FROM `post` p 
                      LEFT JOIN `category` c ON p.category_id = c.category_id 
                      WHERE p.post_id = ? AND p.postStatus = 'Y'";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $post = mysqli_fetch_assoc($result);
            
            if (!$post) {
                $this->redirect('/');
            }
            
            // Cache post for 1 hour
            $this->redis->set($cacheKey, json_encode($post), 'EX', 3600);
        } else {
            $post = json_decode($post, true);
        }
        
        // Get website settings
        $settings = mysqli_fetch_assoc(mysqli_query($this->conn, "SELECT * FROM settings"));
        
        // Prepare SEO metadata
        $seoData = [
            'title' => $post['title'] . ' - ' . $settings['websitename'],
            'description' => substr(strip_tags($post['content']), 0, 160),
            'og_type' => 'article',
            'og_image' => $post['featured_image'] ? 'https://' . $_SERVER['HTTP_HOST'] . '/assets/postImage/' . $post['featured_image'] : '',
            'canonical' => 'https://' . $_SERVER['HTTP_HOST'] . '/post/' . $slug . '/' . $id,
            'schema' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $post['title'],
                'description' => substr(strip_tags($post['content']), 0, 160),
                'image' => $post['featured_image'] ? 'https://' . $_SERVER['HTTP_HOST'] . '/assets/postImage/' . $post['featured_image'] : '',
                'datePublished' => $post['last_date'],
                'dateModified' => $post['last_date'],
                'author' => [
                    '@type' => 'Organization',
                    'name' => $settings['websitename']
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => $settings['websitename'],
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => $settings['logo'] ? 'https://' . $_SERVER['HTTP_HOST'] . '/assets/images/' . $settings['logo'] : ''
                    ]
                ]
            ]
        ];
        
        // Get related posts
        $relatedPosts = $this->getRelatedPosts($post['category_id'], $id);
        
        return $this->view('post-details', [
            'post' => $post,
            'settings' => $settings,
            'seo' => $seoData,
            'related_posts' => $relatedPosts
        ]);
    }
    
    protected function getRelatedPosts($categoryId, $currentPostId) {
        $cacheKey = "related_posts:{$categoryId}:{$currentPostId}";
        $relatedPosts = $this->redis->get($cacheKey);
        
        if (!$relatedPosts) {
            $query = "SELECT post_id, title, featured_image, last_date 
                      FROM `post` 
                      WHERE category_id = ? 
                      AND post_id != ? 
                      AND postStatus = 'Y' 
                      ORDER BY last_date DESC 
                      LIMIT 4";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, 'ii', $categoryId, $currentPostId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $relatedPosts = [];
            while ($post = mysqli_fetch_assoc($result)) {
                $relatedPosts[] = [
                    'id' => $post['post_id'],
                    'title' => $post['title'],
                    'url' => $this->generateUrl($post['title']),
                    'image' => $post['featured_image'],
                    'date' => $post['last_date']
                ];
            }
            
            // Cache related posts for 1 hour
            $this->redis->set($cacheKey, json_encode($relatedPosts), 'EX', 3600);
        } else {
            $relatedPosts = json_decode($relatedPosts, true);
        }
        
        return $relatedPosts;
    }
}